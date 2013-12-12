<?
session_start();

//---------------------------------------------------사용자 정보 얻어오기
function urlencode_rfc3986($input) {if (is_scalar($input)) {return str_replace('+',' ', str_replace('%7E', '~', rawurlencode($input)));} else {	return '';}}

function getInfo(){
	//Default Setting
	$oauth_consumer_key="fa76356e1f7f9dfa550d5f090cd153be04f065f6d";
	$oauth_consumer_key_secret="db566fcb7a01f0b5e0bbf8e205ea5f2d";
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
	$query_string.=urlencode_rfc3986("oauth_consumer_key")."=".urlencode_rfc3986($oauth_consumer_key)."&";
	$query_string.=urlencode_rfc3986("oauth_nonce")."=".urlencode_rfc3986($oauth_nonce)."&";
	$query_string.=urlencode_rfc3986("oauth_signature_method")."=".urlencode_rfc3986($oauth_signature_method)."&";
	$query_string.=urlencode_rfc3986("oauth_timestamp")."=".urlencode_rfc3986($oauth_timestamp)."&";
	$query_string.=urlencode_rfc3986("oauth_token")."=".urlencode_rfc3986($_SESSION['cylog_access_token'])."&";	 // access toekn
	$query_string.=urlencode_rfc3986("oauth_version")."=".urlencode_rfc3986($oauth_version)."&";
	$query_string.= urlencode_rfc3986("output")."=".urlencode_rfc3986($output)."&";
	$query_string.= urlencode_rfc3986("profileThumb")."=".urlencode_rfc3986($profileThumb)."&";
	$query_string.= urlencode_rfc3986("targetId")."=".urlencode_rfc3986($targetId);

	//Basebase_string String 요소를 rfc3986 로 encode 하고 & 로 연결 하여 Base string 완성
	$base_string = urlencode_rfc3986("POST")."&".urlencode_rfc3986($get_api_url)."&".urlencode_rfc3986($query_string);
	//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
	$key_for_signing = urlencode_rfc3986($oauth_consumer_key_secret)."&".urlencode_rfc3986($_SESSION['cylog_access_token_secret']);
	//oauth_signature 생성
	$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));
	//Autorization Header 조합
	$Authorization_Header  = "Authorization: OAuth ";
	$Authorization_Header .= urlencode_rfc3986("oauth_version")."=\"".urlencode_rfc3986($oauth_version)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_nonce")."=\"".urlencode_rfc3986($oauth_nonce)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_timestamp")."=\"".urlencode_rfc3986($oauth_timestamp)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_consumer_key")."=\"".urlencode_rfc3986($oauth_consumer_key)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_token")."=\"".urlencode_rfc3986($_SESSION['cylog_access_token'])."\","; /*Access 토큰*/
	$Authorization_Header .= urlencode_rfc3986("oauth_signature_method")."=\"".urlencode_rfc3986($oauth_signature_method)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_signature")."=\"".urlencode_rfc3986($oauth_signature)."\"";
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
		echo("ERROR!!");
	} else {
		fwrite($fp, $out);
		$response = "";
		while ($s = fread($fp, 4096)){$response .= $s;}
		return $response;
	}
}


function regArticle($str){
	//Default Setting
	$oauth_consumer_key="fa76356e1f7f9dfa550d5f090cd153be04f065f6d";
	$oauth_consumer_key_secret="db566fcb7a01f0b5e0bbf8e205ea5f2d";
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
	$query_string.=urlencode_rfc3986("attachId")."=".urlencode_rfc3986($attachId)."&";
	$query_string.=urlencode_rfc3986("attachType")."=".urlencode_rfc3986($attachType)."&";
	$query_string.=urlencode_rfc3986("contents")."=".urlencode_rfc3986($contents)."&";
	$query_string.=urlencode_rfc3986("deviceType")."=".urlencode_rfc3986($deviceType)."&";
	$query_string.=urlencode_rfc3986("oauth_consumer_key")."=".urlencode_rfc3986($oauth_consumer_key)."&";
	$query_string.=urlencode_rfc3986("oauth_nonce")."=".urlencode_rfc3986($oauth_nonce)."&";
	$query_string.=urlencode_rfc3986("oauth_signature_method")."=".urlencode_rfc3986($oauth_signature_method)."&";
	$query_string.=urlencode_rfc3986("oauth_timestamp")."=".urlencode_rfc3986($oauth_timestamp)."&";
	$query_string.=urlencode_rfc3986("oauth_token")."=".urlencode_rfc3986($_SESSION['cylog_access_token'])."&";	 // access toekn
	$query_string.=urlencode_rfc3986("oauth_version")."=".urlencode_rfc3986($oauth_version)."&";
	$query_string.=urlencode_rfc3986("openType")."=".urlencode_rfc3986($openType)."&";
	$query_string.=urlencode_rfc3986("output")."=".urlencode_rfc3986($output)."&";
	$query_string.=urlencode_rfc3986("sendto")."=".urlencode_rfc3986($sendto)."&";
	$query_string.=urlencode_rfc3986("targetId")."=".urlencode_rfc3986($targetId);
	echo str_replace("&","<br/>",$query_string);
	//------------------------------------------------------------------------------------------------------ 수정1 끝

	//Basebase_string String 요소를 rfc3986 로 encode 하고 & 로 연결 하여 Base string 완성
	$base_string = urlencode_rfc3986("POST")."&".urlencode_rfc3986($get_api_url)."&".urlencode_rfc3986($query_string);

	//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
	$key_for_signing = urlencode_rfc3986($oauth_consumer_key_secret)."&".urlencode_rfc3986($_SESSION['cylog_access_token_secret']);

	//oauth_signature 생성
	$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));

	//Autorization Header 조합
	$Authorization_Header  = "Authorization: OAuth ";
	$Authorization_Header .= urlencode_rfc3986("oauth_version")."=\"".urlencode_rfc3986($oauth_version)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_nonce")."=\"".urlencode_rfc3986($oauth_nonce)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_timestamp")."=\"".urlencode_rfc3986($oauth_timestamp)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_consumer_key")."=\"".urlencode_rfc3986($oauth_consumer_key)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_token")."=\"".urlencode_rfc3986($_SESSION['cylog_access_token'])."\","; /*Access 토큰*/
	$Authorization_Header .= urlencode_rfc3986("oauth_signature_method")."=\"".urlencode_rfc3986($oauth_signature_method)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_signature")."=\"".urlencode_rfc3986($oauth_signature)."\"";
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
		echo("ERROR!!");
	} else {
		fwrite($fp, $out);
		$response = "";
		while ($s = fread($fp, 4096)){$response .= $s;}
		return $response;
	}
}

function getArticle(){
	//Default Setting
	$oauth_consumer_key="fa76356e1f7f9dfa550d5f090cd153be04f065f6d";
	$oauth_consumer_key_secret="db566fcb7a01f0b5e0bbf8e205ea5f2d";
	$oauth_signature_method = "HMAC-SHA1";
	$oauth_timestamp = time();
	$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
	$oauth_version = "1.0";

	//------------------------------------------------------------------------------------------------------ 수정1 시작
	$get_api_url = "https://openapi.nate.com/OApi/RestApiSSL/CY/200800/getnotelist/v1";
	// Access Token 요청/획득에서 확보한 access_token,access_token_secret
	$abstract=0;
	$cPage="1";
	$contentThumb=3;
	$filter=0;
	$output="json";
	$pPage="10";
	$profileThumb=4;
	$targetId = "";//공백으로 두면 accesstoken소유자를 조회합니다.

	//Generate Base String For Get Access Token(파라메터 이름 순서로 조합해야 한다.)
	$query_string=null;
	$query_string.=urlencode_rfc3986("abstract")."=".urlencode_rfc3986($abstract)."&";
	$query_string.=urlencode_rfc3986("cPage")."=".urlencode_rfc3986($cPage)."&";
	$query_string.=urlencode_rfc3986("contentThumb")."=".urlencode_rfc3986($contentThumb)."&";
	$query_string.=urlencode_rfc3986("filter")."=".urlencode_rfc3986($filter)."&";
	$query_string.=urlencode_rfc3986("oauth_consumer_key")."=".urlencode_rfc3986($oauth_consumer_key)."&";
	$query_string.=urlencode_rfc3986("oauth_nonce")."=".urlencode_rfc3986($oauth_nonce)."&";
	$query_string.=urlencode_rfc3986("oauth_signature_method")."=".urlencode_rfc3986($oauth_signature_method)."&";
	$query_string.=urlencode_rfc3986("oauth_timestamp")."=".urlencode_rfc3986($oauth_timestamp)."&";
	$query_string.=urlencode_rfc3986("oauth_token")."=".urlencode_rfc3986($_SESSION['cylog_access_token'])."&";	 // access toekn
	$query_string.=urlencode_rfc3986("oauth_version")."=".urlencode_rfc3986($oauth_version)."&";
	$query_string.=urlencode_rfc3986("output")."=".urlencode_rfc3986($output)."&";
	$query_string.=urlencode_rfc3986("pPage")."=".urlencode_rfc3986($pPage)."&";
	$query_string.=urlencode_rfc3986("profileThumb")."=".urlencode_rfc3986($profileThumb)."&";
	$query_string.=urlencode_rfc3986("targetId")."=".urlencode_rfc3986($targetId);
	//echo str_replace("&","<br/>",$query_string);
	//------------------------------------------------------------------------------------------------------ 수정1 끝

	//Basebase_string String 요소를 rfc3986 로 encode 하고 & 로 연결 하여 Base string 완성
	$base_string = urlencode_rfc3986("POST")."&".urlencode_rfc3986($get_api_url)."&".urlencode_rfc3986($query_string);

	//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
	$key_for_signing = urlencode_rfc3986($oauth_consumer_key_secret)."&".urlencode_rfc3986($_SESSION['cylog_access_token_secret']);

	//oauth_signature 생성
	$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));

	//Autorization Header 조합
	$Authorization_Header  = "Authorization: OAuth ";
	$Authorization_Header .= urlencode_rfc3986("oauth_version")."=\"".urlencode_rfc3986($oauth_version)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_nonce")."=\"".urlencode_rfc3986($oauth_nonce)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_timestamp")."=\"".urlencode_rfc3986($oauth_timestamp)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_consumer_key")."=\"".urlencode_rfc3986($oauth_consumer_key)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_token")."=\"".urlencode_rfc3986($_SESSION['cylog_access_token'])."\","; /*Access 토큰*/
	$Authorization_Header .= urlencode_rfc3986("oauth_signature_method")."=\"".urlencode_rfc3986($oauth_signature_method)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_signature")."=\"".urlencode_rfc3986($oauth_signature)."\"";

	//------------------------------------------------------------------------------------------------------ 수정2 시작
	$queryStr="abstract=".$abstract."&cPage=".$cPage."&contentThumb=".$contentThumb."&filter=".$filter."&output=".$output."&pPage=".$pPage."&profileThumb=".$profileThumb."&targetId=".$targetId;
	$queryLength = (strlen($queryStr));
	//------------------------------------------------------------------------------------------------------ 수정2 끝

	$parsed = parse_url($get_api_url);
	$scheme = $parsed["scheme"];
	$path = $parsed["path"];
	$ip = $parsed["host"];
	$port = @$parsed["port"];
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
		echo("ERROR!!");
	} else {
		fwrite($fp, $out);
		$response = "";
		while ($s = fread($fp, 4096)){$response .= $s;}
		$bi = strpos($response, "\r\n\r\n");
		$body = substr($response, $bi+4);
		$response=json_decode($body);
		return $response;
	}
}
function removeArticle($noteSeq){
	//Default Setting
	$oauth_consumer_key="fa76356e1f7f9dfa550d5f090cd153be04f065f6d";
	$oauth_consumer_key_secret="db566fcb7a01f0b5e0bbf8e205ea5f2d";
	$oauth_signature_method = "HMAC-SHA1";
	$oauth_timestamp = time();
	$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
	$oauth_version = "1.0";

	//------------------------------------------------------------------------------------------------------ 수정1 시작
	$get_api_url = "https://openapi.nate.com/OApi/RestApiSSL/CY/200800/deletenote/v1";
	// Access Token 요청/획득에서 확보한 access_token,access_token_secret
	$noteSeq=$noteSeq;
	$output="json";
	$targetId = "";//공백으로 두면 accesstoken소유자를 조회합니다.

	//Generate Base String For Get Access Token(파라메터 이름 순서로 조합해야 한다.)
	$query_string=null;
	$query_string.=urlencode_rfc3986("noteSeq")."=".urlencode_rfc3986($noteSeq)."&";
	$query_string.=urlencode_rfc3986("oauth_consumer_key")."=".urlencode_rfc3986($oauth_consumer_key)."&";
	$query_string.=urlencode_rfc3986("oauth_nonce")."=".urlencode_rfc3986($oauth_nonce)."&";
	$query_string.=urlencode_rfc3986("oauth_signature_method")."=".urlencode_rfc3986($oauth_signature_method)."&";
	$query_string.=urlencode_rfc3986("oauth_timestamp")."=".urlencode_rfc3986($oauth_timestamp)."&";
	$query_string.=urlencode_rfc3986("oauth_token")."=".urlencode_rfc3986($_SESSION['cylog_access_token'])."&";	 // access toekn
	$query_string.=urlencode_rfc3986("oauth_version")."=".urlencode_rfc3986($oauth_version)."&";
	$query_string.=urlencode_rfc3986("output")."=".urlencode_rfc3986($output)."&";
	$query_string.=urlencode_rfc3986("targetId")."=".urlencode_rfc3986($targetId);
	//echo str_replace("&","<br/>",$query_string);
	//------------------------------------------------------------------------------------------------------ 수정1 끝

	//Basebase_string String 요소를 rfc3986 로 encode 하고 & 로 연결 하여 Base string 완성
	$base_string = urlencode_rfc3986("POST")."&".urlencode_rfc3986($get_api_url)."&".urlencode_rfc3986($query_string);

	//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
	$key_for_signing = urlencode_rfc3986($oauth_consumer_key_secret)."&".urlencode_rfc3986($_SESSION['cylog_access_token_secret']);

	//oauth_signature 생성
	$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));

	//Autorization Header 조합
	$Authorization_Header  = "Authorization: OAuth ";
	$Authorization_Header .= urlencode_rfc3986("oauth_version")."=\"".urlencode_rfc3986($oauth_version)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_nonce")."=\"".urlencode_rfc3986($oauth_nonce)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_timestamp")."=\"".urlencode_rfc3986($oauth_timestamp)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_consumer_key")."=\"".urlencode_rfc3986($oauth_consumer_key)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_token")."=\"".urlencode_rfc3986($_SESSION['cylog_access_token'])."\","; /*Access 토큰*/
	$Authorization_Header .= urlencode_rfc3986("oauth_signature_method")."=\"".urlencode_rfc3986($oauth_signature_method)."\",";
	$Authorization_Header .= urlencode_rfc3986("oauth_signature")."=\"".urlencode_rfc3986($oauth_signature)."\"";
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
		echo("ERROR!!");
	} else {
		fwrite($fp, $out);
		$response = "";
		while ($s = fread($fp, 4096)){$response .= $s;}
		return $response;
	}
}
//사용자 정보 받아오기
$response=getInfo();
echo "<pre>";var_dump($response);echo "</pre>";

//사용자 글 올리기
$str="허허허";
$response=regArticle($str);
echo "<pre>";var_dump($response);echo "</pre>";

//사용가 글 받아오기
$response=getArticle();
echo "<pre>";var_dump($response);echo "</pre>";

//사용자 글 삭제하기("noteSeq":251911)
$noteSeq="251918";
$response=removeArticle($noteSeq);
echo "<pre>";var_dump($response);echo "</pre>";
exit;
?>