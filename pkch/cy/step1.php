<?
session_start();
$oauth_consumer_key = "fa76356e1f7f9dfa550d5f090cd153be04f065f6d"; // Your consumer key
$oauth_consumer_key_secret = "db566fcb7a01f0b5e0bbf8e205ea5f2d"; // Your consumer key secret
$oauth_signature_method = "HMAC-SHA1";
$oauth_timestamp = time();
$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
$oauth_version = "1.0";
$oauth_callback = "http://110.13.170.154/tempProject/pkch/cy/step2.php";// Your Call Back Page URL
$get_request_token_url = "https://oauth.nate.com/OAuth/GetRequestToken/V1a";
$oauth_token = "";
$oauth_token_secret = "";
$oauth_signature = "";
$request_token = "";
$request_token_secret = "";

//Get Request Token
//Generate Base String For Get Request Token
//!!�Ķ���� �̸� ������ �����ؾ� �Ѵ�.
//!!�Ķ������ �̸��� ���� rfc3986 ���� encode
//[Name=Valeu&Name=Value��] �������� ����
$Query_String  = urlencode_rfc3986("oauth_callback")."=".urlencode_rfc3986($oauth_callback);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_consumer_key")."=".urlencode_rfc3986($oauth_consumer_key);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_nonce")."=".urlencode_rfc3986($oauth_nonce);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_signature_method")."=".urlencode_rfc3986($oauth_signature_method);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_timestamp")."=".urlencode_rfc3986($oauth_timestamp);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_version")."=".urlencode_rfc3986($oauth_version);
//echo("Query_String=".$Query_String."<br /><br />");exit;

//Base String ��ҵ��� rfc3986 ���� encode
$Base_String = urlencode_rfc3986("POST")."&".urlencode_rfc3986($get_request_token_url)."&".urlencode_rfc3986($Query_String);
//echo("Base_String=".$Base_String."<br /><br />");exit;

//���� �ܰ迡���� $oauth_token_secret�� ""
$Key_For_Signing = urlencode_rfc3986($oauth_consumer_key_secret)."&".urlencode_rfc3986($oauth_token_secret);
//echo("Key_For_Signing=".$Key_For_Signing."<br /><br />");

//oauth_signature ����
$oauth_signature=base64_encode(hash_hmac('sha1', $Base_String, $Key_For_Signing, true));
//echo("oauth_signature=".$oauth_signature."<br /><br />");

//Authorization Header ����
$Authorization_Header  = "Authorization: OAuth ";
$Authorization_Header .= urlencode_rfc3986("oauth_version")."=\"".urlencode_rfc3986($oauth_version)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_nonce")."=\"".urlencode_rfc3986($oauth_nonce)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_timestamp")."=\"".urlencode_rfc3986($oauth_timestamp)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_consumer_key")."=\"".urlencode_rfc3986($oauth_consumer_key)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_callback")."=\"".urlencode_rfc3986($oauth_callback)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature_method")."=\"".urlencode_rfc3986($oauth_signature_method)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature")."=\"".urlencode_rfc3986($oauth_signature)."\"";
//echo("Authorization_Header=".$Authorization_Header."<br /><br />");

$parsed = parse_url($get_request_token_url);
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
//echo("Request=".$out."<br /><br />");

//Request ������
$fp = fsockopen($tip, $port, $errno, $errstr, $timeout);

//Reponse �ޱ�
if (!$fp) {
	echo("ERROR!!");
} else {
	fwrite($fp, $out);
	$response = "";
	while ($s = fread($fp, 4096)) {
		$response .= $s;
	}
	//echo("response=".$response."<br /><br />");
	
	//Response Header�� Body �и�
	$bi = strpos($response, "\r\n\r\n");
	$body = substr($response, $bi+4);
	
	//�������� ��� $body����
	//oauth_token=5a3377a10ad1f2c937e7bd8c83e57bec&oauth_token_secret=5be6580cc3e8ea2c71a1c56106c19c1f&oauth_callback_confirmed=true	
	//�� �������� ������.
	$tmpArray = explode("&",$body);
	$TokenArray = 	explode("=",$tmpArray[0]);
	$TokenSCArray = 	explode("=",$tmpArray[1]);
	$request_token = $TokenArray[1];
	$request_token_secret = $TokenSCArray[1];
	
	//request_token, request_token_secret ���
	/*
	echo ("request_token = ".$request_token."<br /><br />");
	echo ("request_token_secret = ".$request_token_secret."<br /><br />");
	//request_token, request_token_secret DB����	
	*/
	$_SESSION['rt']=$request_token;
	$_SESSION['rts']=$request_token_secret;
	/*
	$mysql_connect=mysql_connect($strMysqlHost,$strMysqlID,$strMysqlPassword);
	$sql = "insert into tbRequestToken (TOKEN,TOKEN_SC) values ('".$request_token."', '".$request_token_secret."');";
	$result = mysql_query($sql);
	mysql_close($mysql_connect);
	*/
}
//Redirect to Authorize URL 
//���� �ܰ��� Nate Login�� ���� ������ �̵�
$Authorize_URL = "https://oauth.nate.com/OAuth/Authorize/V1a?oauth_token=".$request_token;
//Header("Location: $Authorize_URL");
echo "
<a href='".$Authorize_URL."' > Go! Authentication!</a>
<br/>
<a href='./test4.php?rtn=step1.php'>Clear Session</a>";
echo "<pre>";var_dump($_SESSION);echo "</pre>";

function urlencode_rfc3986($input) {
	if (is_scalar($input)) {
	    return str_replace(
	      '+',
	      ' ',
	      str_replace('%7E', '~', rawurlencode($input))
	    );
	  } else {
	    return '';
	  }
}
?>