<?
/*
싸이로그 클래스
*/
if(!defined("checksum")) exit;

class Mkcyworld implements MkAddInterface{
	private static $consumer_key ="2bdf1cf669afbf2f6f95ceefde6a603c04f8f571b";	 // nate.com // mkinternetco@nate.com // 비밀번호는 기획팀에 문의
	private static $consumer_secret ="0bc6699bc35624e53b1b7868fd3826da";
	private static $callback_url ="http://220.73.139.94/mkadd/action_auth_cyworld.php";
	//################################################### MK 기본 common 함수 모음
	//생성자 함수
	public function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null ){
		$this->request_token=$request_token;
		$this->request_token_secret=$request_token_secret;
		$this->access_token=$access_token;
		$this->access_token_secret=$access_token_secret;
	}

	//인증확인
	public function checkAuth(){
		if(empty($this->request_token) || empty($this->request_token_secret) || empty($this->access_token) || empty($this->access_token_secret) ){
			return -1;
		}
		else{
			return 1;
		}
	}

	//인증 받아오기 for request token
	public function goAuth(){
		//Deafult Value
		$get_request_token_url = "https://oauth.nate.com/OAuth/GetRequestToken/V1a";
		$oauth_signature_method ="HMAC-SHA1";
		$oauth_timestamp=time();
		$oauth_nonce =md5(microtime().mt_rand());
		$oauth_version = "1.0";
		$oauth_token = "";
		$oauth_token_secret = "";
		$oauth_signature = "";
		$request_token = "";
		$request_token_secret = "";

		/***********************************************Request Token 획득을 위한 HTTP Request 만들기*/
		$query_string=null;
		$query_string.=$this->urlencode_rfc3986("oauth_callback")."=".$this->urlencode_rfc3986(self::$callback_url)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_consumer_key")."=".$this->urlencode_rfc3986(self::$consumer_key)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_nonce")."=".$this->urlencode_rfc3986($oauth_nonce)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_signature_method")."=".$this->urlencode_rfc3986($oauth_signature_method)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_timestamp")."=".$this->urlencode_rfc3986($oauth_timestamp)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_version")."=".$this->urlencode_rfc3986($oauth_version);
		//echo("Query_String=".$query_string."<br /><br />");exit;

		//BaseString 완성
		$base_string=null;
		$base_string.=$this->urlencode_rfc3986("POST")."&";
		$base_string.=$this->urlencode_rfc3986($get_request_token_url)."&";
		$base_string.=$this->urlencode_rfc3986($query_string);
		//echo("Base_String=".$base_string."<br /><br />");exit;

		//현재는 request token Secret이 존재하지 않음
		$key_for_signing=null;
		$key_for_signing.=$this->urlencode_rfc3986(self::$consumer_secret)."&";
		$key_for_signing.=$this->urlencode_rfc3986("");
		//echo("Key_For_Signing=".$key_for_signing."<br /><br />");

		//OAuth Signature
		$oauth_signature=base64_encode(hash_hmac('sha1',$base_string,$key_for_signing, true));
		//echo("oauth_signature=".$oauth_signature."<br /><br />");

		/***********************************************Autorization Header 조합*/
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_callback")."=\"".$this->urlencode_rfc3986(self::$callback_url)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";
		//echo("Authorization_Header=".$Authorization_Header."<br /><br />");

		/***********************************************Request Token 획득하기*/
		$parsed = parse_url($get_request_token_url);
		$scheme = $parsed["scheme"];
		$path = $parsed["path"];
		$ip = $parsed["host"];
		$port = @$parsed["port"];
		$timeout = 5;
		$error = null;
		$errstr = null;

		if ($scheme == "http"){
			if(!isset($parsed["port"])){$port="80";}else{$port=$parsed["port"];};
			$tip = $ip;
		} else if ($scheme == "https"){
			if(!isset($parsed["port"])){$port = "443";}else{$port=$parsed["port"];};
			$tip =  "ssl://" . $ip;
		}

		//Request 만들기
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: 0\r\n\r\n";
		//echo("Request=".$out."<br /><br />");

		//Request 보내기
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse 받기
		if (!$fp) {
			//echo("ERROR!!");
			return -1;
		} else {
			fwrite($fp, $out);
			$response = "";
			while ($s = fread($fp, 4096)) {$response .= $s;}
			//Response Header와Body 분리
			$bi = strpos($response, "\r\n\r\n");
			$body = substr($response, $bi+4);
			/*정상적인 경우 $body값은
			oauth_token=5a3377a10ad1f2c937e7bd8c83e57bec&oauth_token_secret=5be6580cc3e8ea2c71a1c56106c19c1f&oauth_callback_confirmed=true 의 형식으로 떨어짐.*/
			$tmpArray = explode("&",$body);
			$TokenArray =   explode("=",$tmpArray[0]);
			$TokenSCArray =     explode("=",$tmpArray[1]);

			$request_token = $TokenArray[1];
			$request_token_secret = $TokenSCArray[1];
			$rTok['oauth_token']=$request_token;
			$rTok['oauth_token_secret']=$request_token_secret;
			$result['request_token']=$rTok;
			$result['user_auth_url']="https://oauth.nate.com/OAuth/Authorize/V1a?oauth_token=".$request_token;
			return $result;
		}
	}

	//인증 받아오기2 for access token( auth_token, oauth_verifier, null )
	public function goAuth2($a=null, $b=null, $c=null){
		//Deafult Value
		$callBackToken = $a;
		$oauth_verifier= $b;
		$request_token = $callBackToken;
		$request_token_secret = "";
		$request_token=$this->request_token;					//request
		$request_token_secret=$this->request_token_secret;	//request secret

		//미리 request_token,request_token_secret를 저장 해 두었으로 매치되는 request_token_secret를 확보
		$oauth_signature_method = "HMAC-SHA1";
		$oauth_timestamp = time();
		$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
		$oauth_version = "1.0";

		$get_access_token_url = "https://oauth.nate.com/OAuth/GetAccessToken/V1a";
		$access_token = "";
		$access_token_secret = "";

		/***********************************************Access Token 획득을 위한 HTTP Request 만들기*/
		//Generate Base String For Get Access Token
		//!!파라메터 이름 순서로 조합해야 한다.
		//!!파라메터의 이름과 값은 rfc3986 으로 encode
		//[Name=Valeu&Name=Value…] 형식으로 연결
		$query_string=null;
		$query_string.=$this->urlencode_rfc3986("oauth_consumer_key")."=".$this->urlencode_rfc3986(self::$consumer_key)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_nonce")."=".$this->urlencode_rfc3986($oauth_nonce)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_signature_method")."=".$this->urlencode_rfc3986($oauth_signature_method)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_timestamp")."=".$this->urlencode_rfc3986($oauth_timestamp)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_token")."=".$this->urlencode_rfc3986($request_token)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_verifier")."=".$this->urlencode_rfc3986($oauth_verifier)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_version")."=".$this->urlencode_rfc3986($oauth_version);


		//Base String 요소들을 rfc3986 으로 encode
		$base_string = $this->urlencode_rfc3986("POST")."&".$this->urlencode_rfc3986($get_access_token_url)."&".$this->urlencode_rfc3986($query_string);

		//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
		$key_for_signing = $this->urlencode_rfc3986(self::$consumer_secret)."&".$this->urlencode_rfc3986($request_token_secret);

		//oauth_signature 생성
		$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));


		/***********************************************Autorization Header 조합*/
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_token")."=\"".$this->urlencode_rfc3986($request_token)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_verifier")."=\"".$this->urlencode_rfc3986($oauth_verifier)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";


		/***********************************************Access Token 획득하기*/
		$parsed = parse_url($get_access_token_url);
		$scheme = $parsed["scheme"];
		$path = $parsed["path"];
		$ip = $parsed["host"];
		$port = @$parsed["port"];

		if ($scheme == "http")
		{
			if(!isset($parsed["port"])) { $port = "80"; } else { $port = $parsed["port"]; };
			$tip = $ip;
		} else if ($scheme == "https")
		{
			if(!isset($parsed["port"])) { $port = "443"; } else { $port = $parsed["port"]; };
			$tip =  "ssl://" . $ip;
		} 
		$timeout = 5;
		$error = null;
		$errstr = null;

		//Request 만들기
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: 0\r\n\r\n";//Request Token 받기에서는 post body에 들어가는 파라메터가 없어서 0

		//Request 보내기
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse 받기
		if (!$fp) {
			//echo("ERROR!!");
			return -1;
		} else {
			fwrite($fp, $out);
			$response = "";
			while ($s = fread($fp, 4096)) {
				$response .= $s;
			}
			//Response Header와 Body 분리
			$bi = strpos($response, "\r\n\r\n");
			$body = substr($response, $bi+4);
			//정상적인 경우 $body값은
			//oauth_token=d934cf945ee6f6bdfb65865f1c1d116a&oauth_token_secret=9a024ddded7a188790796bb9be32f4e5

			//의 형식으로 떨어짐.
			$tmpArray = explode("&",$body);
			$TokenArray = 	explode("=",$tmpArray[0]);
			$TokenSCArray = 	explode("=",$tmpArray[1]);
			$access_token = $TokenArray[1];
			$access_token_secret = $TokenSCArray[1];
			$result['oauth_token']=$access_token;
			$result['oauth_token_secret']=$access_token_secret;
			return $result;
		}
	}

	//사용자 정보 가지고 오기
	public function getUserInfo(){
		//Default Setting
		$oauth_signature_method = "HMAC-SHA1";
		$oauth_timestamp = time();
		$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
		$oauth_version = "1.0";

		// Access Token 요청/획득에서 확보한 access_token,access_token_secret
		$get_api_url = "https://openapi.nate.com/OApi/RestApiSSL/CY/200800/gethomeinfo/v1";
		$targetId = "";//공백으로 두면 accesstoken소유자를 조회합니다.
		$profileThumb=4;
		$output="json";
		$query_string=null;

		//Generate Base String For Get Access Token(파라메터 이름 순서로 조합해야 한다.)
		$query_string.=$this->urlencode_rfc3986("oauth_consumer_key")."=".$this->urlencode_rfc3986(self::$consumer_key)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_nonce")."=".$this->urlencode_rfc3986($oauth_nonce)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_signature_method")."=".$this->urlencode_rfc3986($oauth_signature_method)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_timestamp")."=".$this->urlencode_rfc3986($oauth_timestamp)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_token")."=".$this->urlencode_rfc3986($this->access_token)."&";	 // access toekn
		$query_string.=$this->urlencode_rfc3986("oauth_version")."=".$this->urlencode_rfc3986($oauth_version)."&";
		$query_string.= $this->urlencode_rfc3986("output")."=".$this->urlencode_rfc3986($output)."&";
		$query_string.= $this->urlencode_rfc3986("profileThumb")."=".$this->urlencode_rfc3986($profileThumb)."&";
		$query_string.= $this->urlencode_rfc3986("targetId")."=".$this->urlencode_rfc3986($targetId);

		//Basebase_string String 요소를 rfc3986 로 encode 하고 & 로 연결 하여 Base string 완성
		$base_string = $this->urlencode_rfc3986("POST")."&".$this->urlencode_rfc3986($get_api_url)."&".$this->urlencode_rfc3986($query_string);
		//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
		$key_for_signing = $this->urlencode_rfc3986(self::$consumer_secret)."&".$this->urlencode_rfc3986($this->access_token_secret);
		//oauth_signature 생성
		$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));
		//Autorization Header 조합
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_token")."=\"".$this->urlencode_rfc3986($this->access_token)."\","; /*Access 토큰*/
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";
		$parsed = parse_url($get_api_url);
		$scheme = $parsed["scheme"];
		$path = $parsed["path"];
		$ip = $parsed["host"];
		$port = @$parsed["port"];
		//미니 홈피 폴더 정보 획득을 위해서는 Request Body에 Post 파라메터가 셋팅됩니다.
		$queryStr="output=".$output."&profileThumb=".$profileThumb."&targetId=".$targetId;
		$queryLength = (strlen($queryStr));
		if ($scheme == "http"){
			if(!isset($parsed["port"])) { $port = "80"; } else { $port = $parsed["port"]; };
			$tip = $ip;
		} else if ($scheme == "https"){
			if(!isset($parsed["port"])) { $port = "443"; } else { $port = $parsed["port"]; };
			$tip =  "ssl://" . $ip;
		}
		$timeout = 5;
		$error = null;
		$errstr = null;

		//Request 만들기
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . $queryLength . "\r\n\r\n";
		$out .= $queryStr;

		//Request 보내기
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse 받기
		if (!$fp) {
			//echo("ERROR!!");
			return -1;
		} else {
			fwrite($fp, $out);
			$response = "";
			while ($s = fread($fp, 4096)){$response .= $s;}
			$bi = strpos($response, "\r\n\r\n");
			$body = substr($response, $bi+4);
			$response=json_decode($body);
			$result['user_id']=$response->id;
			$result['user_nick']=$response->name;
			$result['user_pic']=$response->profileUrl;
			return $result;
		}
	}
	
	//글등록
	public function mkWrite($str){
		//Default Setting
		$oauth_signature_method = "HMAC-SHA1";
		$oauth_timestamp = time();
		$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
		$oauth_version = "1.0";

		//------------------------------------------------------------------------------------------------------ 수정1 시작
		$get_api_url = "https://openapi.nate.com/OApi/RestApiSSL/CY/200800/addnote/v1";
		// Access Token 요청/획득에서 확보한 access_token,access_token_secret
		$attachType="0";
		$attachId="";
		$contents=$str;
		$deviceType="0";
		$openType="4";
		$output="json";
		$sendto="0";
		$targetId = "";//공백으로 두면 accesstoken소유자를 조회합니다.

		//Generate Base String For Get Access Token(파라메터 이름 순서로 조합해야 한다.)
		$query_string=null;
		$query_string.=$this->urlencode_rfc3986("attachId")."=".$this->urlencode_rfc3986($attachId)."&";
		$query_string.=$this->urlencode_rfc3986("attachType")."=".$this->urlencode_rfc3986($attachType)."&";
		$query_string.=$this->urlencode_rfc3986("contents")."=".$this->urlencode_rfc3986($contents)."&";
		$query_string.=$this->urlencode_rfc3986("deviceType")."=".$this->urlencode_rfc3986($deviceType)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_consumer_key")."=".$this->urlencode_rfc3986(self::$consumer_key)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_nonce")."=".$this->urlencode_rfc3986($oauth_nonce)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_signature_method")."=".$this->urlencode_rfc3986($oauth_signature_method)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_timestamp")."=".$this->urlencode_rfc3986($oauth_timestamp)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_token")."=".$this->urlencode_rfc3986($this->access_token)."&";	 // access toekn
		$query_string.=$this->urlencode_rfc3986("oauth_version")."=".$this->urlencode_rfc3986($oauth_version)."&";
		$query_string.=$this->urlencode_rfc3986("openType")."=".$this->urlencode_rfc3986($openType)."&";
		$query_string.=$this->urlencode_rfc3986("output")."=".$this->urlencode_rfc3986($output)."&";
		$query_string.=$this->urlencode_rfc3986("sendto")."=".$this->urlencode_rfc3986($sendto)."&";
		$query_string.=$this->urlencode_rfc3986("targetId")."=".$this->urlencode_rfc3986($targetId);
		//------------------------------------------------------------------------------------------------------ 수정1 끝

		//Basebase_string String 요소를 rfc3986 로 encode 하고 & 로 연결 하여 Base string 완성
		$base_string = $this->urlencode_rfc3986("POST")."&".$this->urlencode_rfc3986($get_api_url)."&".$this->urlencode_rfc3986($query_string);

		//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
		$key_for_signing = $this->urlencode_rfc3986(self::$consumer_secret)."&".$this->urlencode_rfc3986($this->access_token_secret);

		//oauth_signature 생성
		$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));

		//Autorization Header 조합
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_token")."=\"".$this->urlencode_rfc3986($this->access_token)."\","; /*Access 토큰*/
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";
		$parsed = parse_url($get_api_url);
		$scheme = $parsed["scheme"];
		$path = $parsed["path"];
		$ip = $parsed["host"];
		$port = @$parsed["port"];

		//------------------------------------------------------------------------------------------------------ 수정2 시작
		//미니 홈피 폴더 정보 획득을 위해서는 Request Body에 Post 파라메터가 셋팅됩니다.
		$queryStr="attachType=".$attachType."&attachId=".$attachId."&contents=".$contents."&deviceType=".$deviceType."&openType=".$openType."&output=".$output."&sendto=".$sendto."&targetId=".$targetId;
		$queryLength = (strlen($queryStr));
		//------------------------------------------------------------------------------------------------------ 수정2 끝

		if ($scheme == "http"){
			if(!isset($parsed["port"])) { $port = "80"; } else { $port = $parsed["port"]; };
			$tip = $ip;
		} else if ($scheme == "https"){
			if(!isset($parsed["port"])) { $port = "443"; } else { $port = $parsed["port"]; };
			$tip =  "ssl://" . $ip;
		}
		$timeout = 5;
		$error = null;
		$errstr = null;

		//Request 만들기
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . $queryLength . "\r\n\r\n";
		$out .= $queryStr;

		//Request 보내기
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse 받기
		if (!$fp) {
			//echo("ERROR!!");
			return -1;
		} else {
			fwrite($fp, $out);
			$response = "";
			while ($s = fread($fp, 4096)){$response .= $s;}
			$bi = strpos($response, "\r\n\r\n");
			$body = substr($response, $bi+4);
			$response=json_decode($body);
			if($response->noteSeq){
				return $response->noteSeq;
			}
			else{
				return -1;
			}
		}
	}

	//글삭제
	public function mkRemove($hometown, $etc_uid){
		//Default Setting
		$oauth_signature_method = "HMAC-SHA1";
		$oauth_timestamp = time();
		$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
		$oauth_version = "1.0";

		//------------------------------------------------------------------------------------------------------ 수정1 시작
		$get_api_url = "https://openapi.nate.com/OApi/RestApiSSL/CY/200800/deletenote/v1";
		// Access Token 요청/획득에서 확보한 access_token,access_token_secret
		$noteSeq=$etc_uid;
		$output="json";
		$targetId = "";//공백으로 두면 accesstoken소유자를 조회합니다.

		//Generate Base String For Get Access Token(파라메터 이름 순서로 조합해야 한다.)
		$query_string=null;
		$query_string.=$this->urlencode_rfc3986("noteSeq")."=".$this->urlencode_rfc3986($noteSeq)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_consumer_key")."=".$this->urlencode_rfc3986(self::$consumer_key)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_nonce")."=".$this->urlencode_rfc3986($oauth_nonce)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_signature_method")."=".$this->urlencode_rfc3986($oauth_signature_method)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_timestamp")."=".$this->urlencode_rfc3986($oauth_timestamp)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_token")."=".$this->urlencode_rfc3986($this->access_token)."&";	 // access toekn
		$query_string.=$this->urlencode_rfc3986("oauth_version")."=".$this->urlencode_rfc3986($oauth_version)."&";
		$query_string.=$this->urlencode_rfc3986("output")."=".$this->urlencode_rfc3986($output)."&";
		$query_string.=$this->urlencode_rfc3986("targetId")."=".$this->urlencode_rfc3986($targetId);
		//echo str_replace("&","<br/>",$query_string);
		//------------------------------------------------------------------------------------------------------ 수정1 끝

		//Basebase_string String 요소를 rfc3986 로 encode 하고 & 로 연결 하여 Base string 완성
		$base_string = $this->urlencode_rfc3986("POST")."&".$this->urlencode_rfc3986($get_api_url)."&".$this->urlencode_rfc3986($query_string);

		//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
		$key_for_signing = $this->urlencode_rfc3986(self::$consumer_secret)."&".$this->urlencode_rfc3986($this->access_token_secret);

		//oauth_signature 생성
		$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));

		//Autorization Header 조합
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_token")."=\"".$this->urlencode_rfc3986($this->access_token)."\","; /*Access 토큰*/
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";
		$parsed = parse_url($get_api_url);
		$scheme = $parsed["scheme"];
		$path = $parsed["path"];
		$ip = $parsed["host"];
		$port = @$parsed["port"];

		//------------------------------------------------------------------------------------------------------ 수정2 시작
		$queryStr="noteSeq=".$noteSeq."&output=".$output."&targetId=".$targetId;
		$queryLength = (strlen($queryStr));
		//------------------------------------------------------------------------------------------------------ 수정2 끝

		if ($scheme == "http"){
			if(!isset($parsed["port"])) { $port = "80"; } else { $port = $parsed["port"]; };
			$tip = $ip;
		} else if ($scheme == "https"){
			if(!isset($parsed["port"])) { $port = "443"; } else { $port = $parsed["port"]; };
			$tip =  "ssl://" . $ip;
		}
		$timeout = 5;
		$error = null;
		$errstr = null;

		//Request 만들기
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . $queryLength . "\r\n\r\n";
		$out .= $queryStr;

		//Request 보내기
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse 받기
		if (!$fp) {
			//echo("ERROR!!");
			return -1;
		} else {
			fwrite($fp, $out);
			$response = "";
			while ($s = fread($fp, 4096)){$response .= $s;}
			$bi = strpos($response, "\r\n\r\n");
			$body = substr($response, $bi+4);
			$response=json_decode($body);
			if($response->rmsg=='success'){
				return 1;
			}
			else{
				return -1;
			}
		}
	}

	//로그인 URL 받아오기
	public function getLoginUrl(){
		return null;
	}

	//로그아웃 URL 받아오기
	public function getLogoutUrl(){
		return null;
	}

	//################################################### MK 기본  기타 함수 모음
	function urlencode_rfc3986($input) {
		if (is_scalar($input)) {return str_replace('+',' ', str_replace('%7E', '~', rawurlencode($input)));} else {	return '';}
	}
}
?>