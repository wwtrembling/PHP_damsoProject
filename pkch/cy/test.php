<?
session_start();

function urlencode_rfc3986($input) {if (is_scalar($input)) {return str_replace('+',' ', str_replace('%7E', '~', rawurlencode($input)));} else {	return '';}}

//Deafult Value
$get_request_token_url = "https://oauth.nate.com/OAuth/GetRequestToken/V1a";
$oauth_consumer_key="fa76356e1f7f9dfa550d5f090cd153be04f065f6d";
$oauth_consumer_key_secret="db566fcb7a01f0b5e0bbf8e205ea5f2d";
$oauth_callback="http://110.13.170.154/tempProject/pkch/cy/test2.php";
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
$query_string.=urlencode_rfc3986("oauth_callback")."=".urlencode_rfc3986($oauth_callback)."&";
$query_string.=urlencode_rfc3986("oauth_consumer_key")."=".urlencode_rfc3986($oauth_consumer_key)."&";
$query_string.=urlencode_rfc3986("oauth_nonce")."=".urlencode_rfc3986($oauth_nonce)."&";
$query_string.=urlencode_rfc3986("oauth_signature_method")."=".urlencode_rfc3986($oauth_signature_method)."&";
$query_string.=urlencode_rfc3986("oauth_timestamp")."=".urlencode_rfc3986($oauth_timestamp)."&";
$query_string.=urlencode_rfc3986("oauth_version")."=".urlencode_rfc3986($oauth_version);
//echo("Query_String=".$query_string."<br /><br />");exit;

//BaseString 완성
$base_string=null;
$base_string.=urlencode_rfc3986("POST")."&";
$base_string.=urlencode_rfc3986($get_request_token_url)."&";
$base_string.=urlencode_rfc3986($query_string);
//echo("Base_String=".$base_string."<br /><br />");exit;

//현재는 request token Secret이 존재하지 않음
$key_for_signing=null;
$key_for_signing.=urlencode_rfc3986($oauth_consumer_key_secret)."&";
$key_for_signing.=urlencode_rfc3986("");
//echo("Key_For_Signing=".$key_for_signing."<br /><br />");

//OAuth Signature
$oauth_signature=base64_encode(hash_hmac('sha1',$base_string,$key_for_signing, true));
//echo("oauth_signature=".$oauth_signature."<br /><br />");

/***********************************************Autorization Header 조합*/
$Authorization_Header  = "Authorization: OAuth ";
$Authorization_Header .= urlencode_rfc3986("oauth_version")."=\"".urlencode_rfc3986($oauth_version)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_nonce")."=\"".urlencode_rfc3986($oauth_nonce)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_timestamp")."=\"".urlencode_rfc3986($oauth_timestamp)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_consumer_key")."=\"".urlencode_rfc3986($oauth_consumer_key)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_callback")."=\"".urlencode_rfc3986($oauth_callback)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature_method")."=\"".urlencode_rfc3986($oauth_signature_method)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature")."=\"".urlencode_rfc3986($oauth_signature)."\"";
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
	$_SESSION['cylog_request_token']=$request_token;
	$_SESSION['cylog_request_token_secret']=$request_token_secret;
	echo "<pre>";var_dump($_SESSION);echo "</pre>";

	//인증받는 URL
	echo "
	<a href='https://oauth.nate.com/OAuth/Authorize/V1a?oauth_token=".$request_token."' > Go! Authentication!</a>
	<br/>
	<a href='./test4.php?rtn=test.php'>Clear Session</a>";
}
?>