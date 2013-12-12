<?
/*
���̷α� Ŭ����
*/
if(!defined("checksum")) exit;

class Mkcyworld implements MkAddInterface{
	private static $consumer_key ="2bdf1cf669afbf2f6f95ceefde6a603c04f8f571b";	 // nate.com // mkinternetco@nate.com // ��й�ȣ�� ��ȹ���� ����
	private static $consumer_secret ="0bc6699bc35624e53b1b7868fd3826da";
	private static $callback_url ="http://220.73.139.94/mkadd/action_auth_cyworld.php";
	//################################################### MK �⺻ common �Լ� ����
	//������ �Լ�
	public function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null ){
		$this->request_token=$request_token;
		$this->request_token_secret=$request_token_secret;
		$this->access_token=$access_token;
		$this->access_token_secret=$access_token_secret;
	}

	//����Ȯ��
	public function checkAuth(){
		if(empty($this->request_token) || empty($this->request_token_secret) || empty($this->access_token) || empty($this->access_token_secret) ){
			return -1;
		}
		else{
			return 1;
		}
	}

	//���� �޾ƿ��� for request token
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

		/***********************************************Request Token ȹ���� ���� HTTP Request �����*/
		$query_string=null;
		$query_string.=$this->urlencode_rfc3986("oauth_callback")."=".$this->urlencode_rfc3986(self::$callback_url)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_consumer_key")."=".$this->urlencode_rfc3986(self::$consumer_key)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_nonce")."=".$this->urlencode_rfc3986($oauth_nonce)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_signature_method")."=".$this->urlencode_rfc3986($oauth_signature_method)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_timestamp")."=".$this->urlencode_rfc3986($oauth_timestamp)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_version")."=".$this->urlencode_rfc3986($oauth_version);
		//echo("Query_String=".$query_string."<br /><br />");exit;

		//BaseString �ϼ�
		$base_string=null;
		$base_string.=$this->urlencode_rfc3986("POST")."&";
		$base_string.=$this->urlencode_rfc3986($get_request_token_url)."&";
		$base_string.=$this->urlencode_rfc3986($query_string);
		//echo("Base_String=".$base_string."<br /><br />");exit;

		//����� request token Secret�� �������� ����
		$key_for_signing=null;
		$key_for_signing.=$this->urlencode_rfc3986(self::$consumer_secret)."&";
		$key_for_signing.=$this->urlencode_rfc3986("");
		//echo("Key_For_Signing=".$key_for_signing."<br /><br />");

		//OAuth Signature
		$oauth_signature=base64_encode(hash_hmac('sha1',$base_string,$key_for_signing, true));
		//echo("oauth_signature=".$oauth_signature."<br /><br />");

		/***********************************************Autorization Header ����*/
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_callback")."=\"".$this->urlencode_rfc3986(self::$callback_url)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";
		//echo("Authorization_Header=".$Authorization_Header."<br /><br />");

		/***********************************************Request Token ȹ���ϱ�*/
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

		//Request �����
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: 0\r\n\r\n";
		//echo("Request=".$out."<br /><br />");

		//Request ������
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse �ޱ�
		if (!$fp) {
			//echo("ERROR!!");
			return -1;
		} else {
			fwrite($fp, $out);
			$response = "";
			while ($s = fread($fp, 4096)) {$response .= $s;}
			//Response Header��Body �и�
			$bi = strpos($response, "\r\n\r\n");
			$body = substr($response, $bi+4);
			/*�������� ��� $body����
			oauth_token=5a3377a10ad1f2c937e7bd8c83e57bec&oauth_token_secret=5be6580cc3e8ea2c71a1c56106c19c1f&oauth_callback_confirmed=true �� �������� ������.*/
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

	//���� �޾ƿ���2 for access token( auth_token, oauth_verifier, null )
	public function goAuth2($a=null, $b=null, $c=null){
		//Deafult Value
		$callBackToken = $a;
		$oauth_verifier= $b;
		$request_token = $callBackToken;
		$request_token_secret = "";
		$request_token=$this->request_token;					//request
		$request_token_secret=$this->request_token_secret;	//request secret

		//�̸� request_token,request_token_secret�� ���� �� �ξ����� ��ġ�Ǵ� request_token_secret�� Ȯ��
		$oauth_signature_method = "HMAC-SHA1";
		$oauth_timestamp = time();
		$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
		$oauth_version = "1.0";

		$get_access_token_url = "https://oauth.nate.com/OAuth/GetAccessToken/V1a";
		$access_token = "";
		$access_token_secret = "";

		/***********************************************Access Token ȹ���� ���� HTTP Request �����*/
		//Generate Base String For Get Access Token
		//!!�Ķ���� �̸� ������ �����ؾ� �Ѵ�.
		//!!�Ķ������ �̸��� ���� rfc3986 ���� encode
		//[Name=Valeu&Name=Value��] �������� ����
		$query_string=null;
		$query_string.=$this->urlencode_rfc3986("oauth_consumer_key")."=".$this->urlencode_rfc3986(self::$consumer_key)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_nonce")."=".$this->urlencode_rfc3986($oauth_nonce)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_signature_method")."=".$this->urlencode_rfc3986($oauth_signature_method)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_timestamp")."=".$this->urlencode_rfc3986($oauth_timestamp)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_token")."=".$this->urlencode_rfc3986($request_token)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_verifier")."=".$this->urlencode_rfc3986($oauth_verifier)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_version")."=".$this->urlencode_rfc3986($oauth_version);


		//Base String ��ҵ��� rfc3986 ���� encode
		$base_string = $this->urlencode_rfc3986("POST")."&".$this->urlencode_rfc3986($get_access_token_url)."&".$this->urlencode_rfc3986($query_string);

		//���� �ܰ迡���� $oauth_token_secret�� request_token_secret�� ���
		$key_for_signing = $this->urlencode_rfc3986(self::$consumer_secret)."&".$this->urlencode_rfc3986($request_token_secret);

		//oauth_signature ����
		$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));


		/***********************************************Autorization Header ����*/
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_token")."=\"".$this->urlencode_rfc3986($request_token)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_verifier")."=\"".$this->urlencode_rfc3986($oauth_verifier)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";


		/***********************************************Access Token ȹ���ϱ�*/
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

		//Request �����
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: 0\r\n\r\n";//Request Token �ޱ⿡���� post body�� ���� �Ķ���Ͱ� ��� 0

		//Request ������
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse �ޱ�
		if (!$fp) {
			//echo("ERROR!!");
			return -1;
		} else {
			fwrite($fp, $out);
			$response = "";
			while ($s = fread($fp, 4096)) {
				$response .= $s;
			}
			//Response Header�� Body �и�
			$bi = strpos($response, "\r\n\r\n");
			$body = substr($response, $bi+4);
			//�������� ��� $body����
			//oauth_token=d934cf945ee6f6bdfb65865f1c1d116a&oauth_token_secret=9a024ddded7a188790796bb9be32f4e5

			//�� �������� ������.
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

	//����� ���� ������ ����
	public function getUserInfo(){
		//Default Setting
		$oauth_signature_method = "HMAC-SHA1";
		$oauth_timestamp = time();
		$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
		$oauth_version = "1.0";

		// Access Token ��û/ȹ�濡�� Ȯ���� access_token,access_token_secret
		$get_api_url = "https://openapi.nate.com/OApi/RestApiSSL/CY/200800/gethomeinfo/v1";
		$targetId = "";//�������� �θ� accesstoken�����ڸ� ��ȸ�մϴ�.
		$profileThumb=4;
		$output="json";
		$query_string=null;

		//Generate Base String For Get Access Token(�Ķ���� �̸� ������ �����ؾ� �Ѵ�.)
		$query_string.=$this->urlencode_rfc3986("oauth_consumer_key")."=".$this->urlencode_rfc3986(self::$consumer_key)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_nonce")."=".$this->urlencode_rfc3986($oauth_nonce)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_signature_method")."=".$this->urlencode_rfc3986($oauth_signature_method)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_timestamp")."=".$this->urlencode_rfc3986($oauth_timestamp)."&";
		$query_string.=$this->urlencode_rfc3986("oauth_token")."=".$this->urlencode_rfc3986($this->access_token)."&";	 // access toekn
		$query_string.=$this->urlencode_rfc3986("oauth_version")."=".$this->urlencode_rfc3986($oauth_version)."&";
		$query_string.= $this->urlencode_rfc3986("output")."=".$this->urlencode_rfc3986($output)."&";
		$query_string.= $this->urlencode_rfc3986("profileThumb")."=".$this->urlencode_rfc3986($profileThumb)."&";
		$query_string.= $this->urlencode_rfc3986("targetId")."=".$this->urlencode_rfc3986($targetId);

		//Basebase_string String ��Ҹ� rfc3986 �� encode �ϰ� & �� ���� �Ͽ� Base string �ϼ�
		$base_string = $this->urlencode_rfc3986("POST")."&".$this->urlencode_rfc3986($get_api_url)."&".$this->urlencode_rfc3986($query_string);
		//���� �ܰ迡���� $oauth_token_secret�� request_token_secret�� ���
		$key_for_signing = $this->urlencode_rfc3986(self::$consumer_secret)."&".$this->urlencode_rfc3986($this->access_token_secret);
		//oauth_signature ����
		$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));
		//Autorization Header ����
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_token")."=\"".$this->urlencode_rfc3986($this->access_token)."\","; /*Access ��ū*/
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";
		$parsed = parse_url($get_api_url);
		$scheme = $parsed["scheme"];
		$path = $parsed["path"];
		$ip = $parsed["host"];
		$port = @$parsed["port"];
		//�̴� Ȩ�� ���� ���� ȹ���� ���ؼ��� Request Body�� Post �Ķ���Ͱ� ���õ˴ϴ�.
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

		//Request �����
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . $queryLength . "\r\n\r\n";
		$out .= $queryStr;

		//Request ������
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse �ޱ�
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
	
	//�۵��
	public function mkWrite($str){
		//Default Setting
		$oauth_signature_method = "HMAC-SHA1";
		$oauth_timestamp = time();
		$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
		$oauth_version = "1.0";

		//------------------------------------------------------------------------------------------------------ ����1 ����
		$get_api_url = "https://openapi.nate.com/OApi/RestApiSSL/CY/200800/addnote/v1";
		// Access Token ��û/ȹ�濡�� Ȯ���� access_token,access_token_secret
		$attachType="0";
		$attachId="";
		$contents=$str;
		$deviceType="0";
		$openType="4";
		$output="json";
		$sendto="0";
		$targetId = "";//�������� �θ� accesstoken�����ڸ� ��ȸ�մϴ�.

		//Generate Base String For Get Access Token(�Ķ���� �̸� ������ �����ؾ� �Ѵ�.)
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
		//------------------------------------------------------------------------------------------------------ ����1 ��

		//Basebase_string String ��Ҹ� rfc3986 �� encode �ϰ� & �� ���� �Ͽ� Base string �ϼ�
		$base_string = $this->urlencode_rfc3986("POST")."&".$this->urlencode_rfc3986($get_api_url)."&".$this->urlencode_rfc3986($query_string);

		//���� �ܰ迡���� $oauth_token_secret�� request_token_secret�� ���
		$key_for_signing = $this->urlencode_rfc3986(self::$consumer_secret)."&".$this->urlencode_rfc3986($this->access_token_secret);

		//oauth_signature ����
		$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));

		//Autorization Header ����
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_token")."=\"".$this->urlencode_rfc3986($this->access_token)."\","; /*Access ��ū*/
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";
		$parsed = parse_url($get_api_url);
		$scheme = $parsed["scheme"];
		$path = $parsed["path"];
		$ip = $parsed["host"];
		$port = @$parsed["port"];

		//------------------------------------------------------------------------------------------------------ ����2 ����
		//�̴� Ȩ�� ���� ���� ȹ���� ���ؼ��� Request Body�� Post �Ķ���Ͱ� ���õ˴ϴ�.
		$queryStr="attachType=".$attachType."&attachId=".$attachId."&contents=".$contents."&deviceType=".$deviceType."&openType=".$openType."&output=".$output."&sendto=".$sendto."&targetId=".$targetId;
		$queryLength = (strlen($queryStr));
		//------------------------------------------------------------------------------------------------------ ����2 ��

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

		//Request �����
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . $queryLength . "\r\n\r\n";
		$out .= $queryStr;

		//Request ������
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse �ޱ�
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

	//�ۻ���
	public function mkRemove($hometown, $etc_uid){
		//Default Setting
		$oauth_signature_method = "HMAC-SHA1";
		$oauth_timestamp = time();
		$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
		$oauth_version = "1.0";

		//------------------------------------------------------------------------------------------------------ ����1 ����
		$get_api_url = "https://openapi.nate.com/OApi/RestApiSSL/CY/200800/deletenote/v1";
		// Access Token ��û/ȹ�濡�� Ȯ���� access_token,access_token_secret
		$noteSeq=$etc_uid;
		$output="json";
		$targetId = "";//�������� �θ� accesstoken�����ڸ� ��ȸ�մϴ�.

		//Generate Base String For Get Access Token(�Ķ���� �̸� ������ �����ؾ� �Ѵ�.)
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
		//------------------------------------------------------------------------------------------------------ ����1 ��

		//Basebase_string String ��Ҹ� rfc3986 �� encode �ϰ� & �� ���� �Ͽ� Base string �ϼ�
		$base_string = $this->urlencode_rfc3986("POST")."&".$this->urlencode_rfc3986($get_api_url)."&".$this->urlencode_rfc3986($query_string);

		//���� �ܰ迡���� $oauth_token_secret�� request_token_secret�� ���
		$key_for_signing = $this->urlencode_rfc3986(self::$consumer_secret)."&".$this->urlencode_rfc3986($this->access_token_secret);

		//oauth_signature ����
		$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));

		//Autorization Header ����
		$Authorization_Header  = "Authorization: OAuth ";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_version")."=\"".$this->urlencode_rfc3986($oauth_version)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_nonce")."=\"".$this->urlencode_rfc3986($oauth_nonce)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_timestamp")."=\"".$this->urlencode_rfc3986($oauth_timestamp)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_consumer_key")."=\"".$this->urlencode_rfc3986(self::$consumer_key)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_token")."=\"".$this->urlencode_rfc3986($this->access_token)."\","; /*Access ��ū*/
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature_method")."=\"".$this->urlencode_rfc3986($oauth_signature_method)."\",";
		$Authorization_Header .= $this->urlencode_rfc3986("oauth_signature")."=\"".$this->urlencode_rfc3986($oauth_signature)."\"";
		$parsed = parse_url($get_api_url);
		$scheme = $parsed["scheme"];
		$path = $parsed["path"];
		$ip = $parsed["host"];
		$port = @$parsed["port"];

		//------------------------------------------------------------------------------------------------------ ����2 ����
		$queryStr="noteSeq=".$noteSeq."&output=".$output."&targetId=".$targetId;
		$queryLength = (strlen($queryStr));
		//------------------------------------------------------------------------------------------------------ ����2 ��

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

		//Request �����
		$out  = "POST " . $path . " HTTP/1.1\r\n";
		$out .= "Host: ". $ip . "\r\n";
		$out .= $Authorization_Header . "\r\n";
		$out .= "Accept-Language: ko\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . $queryLength . "\r\n\r\n";
		$out .= $queryStr;

		//Request ������
		$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);
		//Reponse �ޱ�
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

	//�α��� URL �޾ƿ���
	public function getLoginUrl(){
		return null;
	}

	//�α׾ƿ� URL �޾ƿ���
	public function getLogoutUrl(){
		return null;
	}

	//################################################### MK �⺻  ��Ÿ �Լ� ����
	function urlencode_rfc3986($input) {
		if (is_scalar($input)) {return str_replace('+',' ', str_replace('%7E', '~', rawurlencode($input)));} else {	return '';}
	}
}
?>