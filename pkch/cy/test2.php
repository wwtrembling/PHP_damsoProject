<?
session_start();
function urlencode_rfc3986($input) {if (is_scalar($input)) {return str_replace('+',' ', str_replace('%7E', '~', rawurlencode($input)));} else {	return '';}}
/*
oauth_token=badd809deb0f528511f6941b60f6ae00&oauth_token_secret=f796fc029675c7d3eadee84808853806&oauth_callback_confirmed=true
위와 같이 Nate OAuth는 처음에 설정한 callback url에 oauth_token과 oauth_verifier 를 전달 합니다.
여기서 oauth_token은 Authorize URL을 호출할때 설정한 request_token값입니다.
따라서 여러분은 이렇게 확보한 request_token값을 이용하여 미리 저장해둔 request_token_secret을 확보 해햐 합니다.
*/

//Deafult Value
$oauth_consumer_key="fa76356e1f7f9dfa550d5f090cd153be04f065f6d";
$oauth_consumer_key_secret="db566fcb7a01f0b5e0bbf8e205ea5f2d";
$callBackToken = $_REQUEST["oauth_token"];
$oauth_verifier= $_REQUEST["oauth_verifier"];
$request_token = $callBackToken;
$request_token_secret = "";
$request_token=$_SESSION['cylog_request_token'];					//request
$request_token_secret=$_SESSION['cylog_request_token_secret'];	//request secret

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
$query_string.=urlencode_rfc3986("oauth_consumer_key")."=".urlencode_rfc3986($oauth_consumer_key)."&";
$query_string.=urlencode_rfc3986("oauth_nonce")."=".urlencode_rfc3986($oauth_nonce)."&";
$query_string.=urlencode_rfc3986("oauth_signature_method")."=".urlencode_rfc3986($oauth_signature_method)."&";
$query_string.=urlencode_rfc3986("oauth_timestamp")."=".urlencode_rfc3986($oauth_timestamp)."&";
$query_string.=urlencode_rfc3986("oauth_token")."=".urlencode_rfc3986($request_token)."&";
$query_string.=urlencode_rfc3986("oauth_verifier")."=".urlencode_rfc3986($oauth_verifier)."&";
$query_string.=urlencode_rfc3986("oauth_version")."=".urlencode_rfc3986($oauth_version);


//Base String 요소들을 rfc3986 으로 encode
$base_string = urlencode_rfc3986("POST")."&".urlencode_rfc3986($get_access_token_url)."&".urlencode_rfc3986($query_string);

//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
$key_for_signing = urlencode_rfc3986($oauth_consumer_key_secret)."&".urlencode_rfc3986($request_token_secret);

//oauth_signature 생성
$oauth_signature=base64_encode(hash_hmac('sha1', $base_string, $key_for_signing, true));


/***********************************************Autorization Header 조합*/
$Authorization_Header  = "Authorization: OAuth ";
$Authorization_Header .= urlencode_rfc3986("oauth_version")."=\"".urlencode_rfc3986($oauth_version)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_nonce")."=\"".urlencode_rfc3986($oauth_nonce)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_timestamp")."=\"".urlencode_rfc3986($oauth_timestamp)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_consumer_key")."=\"".urlencode_rfc3986($oauth_consumer_key)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_token")."=\"".urlencode_rfc3986($request_token)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_verifier")."=\"".urlencode_rfc3986($oauth_verifier)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature_method")."=\"".urlencode_rfc3986($oauth_signature_method)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature")."=\"".urlencode_rfc3986($oauth_signature)."\"";


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
	echo("ERROR!!");
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
	
	//access_token, access_token_secret 출력
	$_SESSION['cylog_access_token']=$access_token;
	$_SESSION['cylog_access_token_secret']=$access_token_secret;
	echo "<pre>";var_dump($_SESSION);echo "</pre>";
	echo "
	<a href='./test3.php' > Go! Test!</a>
	<br/>
	<a href='./test4.php?rtn=test.php'>Clear Session</a>";
}

?>