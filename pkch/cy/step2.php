<?PHP
session_start();
/*
http://www.myopenapi.com/get_oauth_verifier.php/get_oauth_verifier.php?oauth_token=5a3377a10ad1f2c937e7bd8c83e57bec&oauth_verifier=505fe4a298974a9579e7bac78db2a199
���� ���� Nate OAuth�� ó���� ������ callback url�� oauth_token�� oauth_verifier �� ���� �մϴ�.
���⼭ oauth_token�� Authorize URL�� ȣ���Ҷ� ������ request_token���Դϴ�.
���� �������� �̷��� Ȯ���� request_token���� �̿��Ͽ� �̸� �����ص� request_token_secret�� Ȯ�� ���� �մϴ�.
*/
/*
$strMysqlHost = "127.0.0.1:3306";
$strMysqlID = "mysql_use";
$strMysqlPassword = "mysql_password";
*/


$oauth_consumer_key = "fa76356e1f7f9dfa550d5f090cd153be04f065f6d"; // Your consumer key
$oauth_consumer_key_secret = "db566fcb7a01f0b5e0bbf8e205ea5f2d"; // Your consumer key secret

$callBackToken = $_REQUEST["oauth_token"];
$oauth_verifier= $_REQUEST["oauth_verifier"];

$request_token = $callBackToken;
$request_token_secret = "";

/*
$mysql_connect=mysql_connect($strMysqlHost,$strMysqlID,$strMysqlPassword);
$sql = "select TOKEN,TOKEN_SC from tbRequestToken where TOKEN='".$request_token."';";
echo($sql."<br />");
$result = mysql_query($sql);
if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
$rowcnt = mysql_num_rows($result);
if ($rowcnt == 0) {
    echo "No rows found, nothing to print so am exiting";
    exit;
}
else if ($rowcnt > 1 ) {
	echo "ERROR";
  exit;
}
else
{
	while ($row = mysql_fetch_assoc($result)) {    
    $request_token_secret = $row["TOKEN_SC"];
	}
}

mysql_free_result($result);
mysql_close($mysql_connect);
*/

$request_token=$_SESSION['rt'];
$request_token_secret=$_SESSION['rts'];
//request_token, request_token_secret ���
//echo ("request_token = ".$request_token."<br />");
//echo ("request_token_secret = ".$request_token_secret."<br />");

//�̸� request_token,request_token_secret�� ���� �� �ξ����� ��ġ�Ǵ� request_token_secret�� Ȯ��
$oauth_signature_method = "HMAC-SHA1";
$oauth_timestamp = time();
$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
$oauth_version = "1.0";

$get_access_token_url = "https://oauth.nate.com/OAuth/GetAccessToken/V1a";
$access_token = "";
$access_token_secret = "";
//Get Access Token

//Generate Base String For Get Access Token
//!!�Ķ���� �̸� ������ �����ؾ� �Ѵ�.
//!!�Ķ������ �̸��� ���� rfc3986 ���� encode
//[Name=Valeu&Name=Value��] �������� ����

$Query_String  = urlencode_rfc3986("oauth_consumer_key")."=".urlencode_rfc3986($oauth_consumer_key);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_nonce")."=".urlencode_rfc3986($oauth_nonce);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_signature_method")."=".urlencode_rfc3986($oauth_signature_method);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_timestamp")."=".urlencode_rfc3986($oauth_timestamp);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_token")."=".urlencode_rfc3986($request_token);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_verifier")."=".urlencode_rfc3986($oauth_verifier);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_version")."=".urlencode_rfc3986($oauth_version);
//echo "<pre>";var_dump($Query_String);echo "</pre>";

//Base String ��ҵ��� rfc3986 ���� encode
$Base_String = urlencode_rfc3986("POST")."&".urlencode_rfc3986($get_access_token_url)."&".urlencode_rfc3986($Query_String);
//echo("Base_String=".$Base_String."<br />");

//���� �ܰ迡���� $oauth_token_secret�� request_token_secret�� ���
$Key_For_Signing = urlencode_rfc3986($oauth_consumer_key_secret)."&".urlencode_rfc3986($request_token_secret);
//echo("Key_For_Signing=".$Key_For_Signing."<br />");

//oauth_signature ����
$oauth_signature=base64_encode(hash_hmac('sha1', $Base_String, $Key_For_Signing, true));
//echo("oauth_signature=".$oauth_signature."<br />");

//Authorization Header ����
$Authorization_Header  = "Authorization: OAuth ";
$Authorization_Header .= urlencode_rfc3986("oauth_version")."=\"".urlencode_rfc3986($oauth_version)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_nonce")."=\"".urlencode_rfc3986($oauth_nonce)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_timestamp")."=\"".urlencode_rfc3986($oauth_timestamp)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_consumer_key")."=\"".urlencode_rfc3986($oauth_consumer_key)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_token")."=\"".urlencode_rfc3986($request_token)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_verifier")."=\"".urlencode_rfc3986($oauth_verifier)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature_method")."=\"".urlencode_rfc3986($oauth_signature_method)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature")."=\"".urlencode_rfc3986($oauth_signature)."\"";
//echo("Authorization_Header=".$Authorization_Header."<br />");

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
//echo("Request=".$out."<br />");

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
	echo("response=".$response."<br />");
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
	
	//access_token, access_token_secret ���
	echo ("access_token = ".$access_token."<br />");
	echo ("access_token_secret = ".$access_token_secret."<br />");
	
}
$_SESSION['at']=$access_token;
$_SESSION['ats']=$access_token_secret;
echo "
<a href='./step3.php' > Go! test!</a>
<br/>
<a href='./test4.php?rtn=step1.php'>Clear Session</a>";
echo "<pre>";var_dump($_SESSION);echo "</pre>";
exit;


/*==============================================================================================================
==============================================================================================================*/
//Ȯ���� access_token ���� �̴�Ȩ��  ���� ��� �ޱ�� �ٷ� ����
//access_token�� ������ ���� �������� ���� 24�ð� �Դϴ�.
//���� �� �ξ��ٰ� 24�ð� �̳��̸� �ش� access_token���� �ٷ� API�� ȣ�� �ص� �˴ϴ�.
$oauth_timestamp = time();
$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;

$get_minihp_folder_list_url = "http://openapi.nate.com/OApi/RestApi/CY/200110/xml_RetrieveFolderList/v1";

$menuType="1";
//��ȸ��� �̴�Ȩ�� ���̵�� ���� API�� Ȯ�� �����մϴ�.
$targetId="";//�������� �θ� Access Token �������� �̴� Ȩ�Ǹ� ��ȸ�մϴ�.

//Generate Base String For Get Access Token
//!!�Ķ���� �̸� ������ �����ؾ� �Ѵ�.
//!!�Ķ������ �̸��� ���� rfc3986 ���� encode
//[Name=Valeu&Name=Value��] �������� ����

$Query_String  = urlencode_rfc3986("menuType")."=".urlencode_rfc3986($menuType);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_consumer_key")."=".urlencode_rfc3986($oauth_consumer_key);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_nonce")."=".urlencode_rfc3986($oauth_nonce);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_signature_method")."=".urlencode_rfc3986($oauth_signature_method);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_timestamp")."=".urlencode_rfc3986($oauth_timestamp);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_token")."=".urlencode_rfc3986($access_token);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("oauth_version")."=".urlencode_rfc3986($oauth_version);
$Query_String .= "&";
$Query_String .= urlencode_rfc3986("targetId")."=".urlencode_rfc3986($targetId);

echo("Query_String=".$Query_String."<br />");

//Base String ���� ��Ҹ� &�� ����
$Base_String = urlencode_rfc3986("POST")."&".urlencode_rfc3986($get_minihp_folder_list_url)."&".urlencode_rfc3986($Query_String);

echo("Base_String=".$Base_String."<br />");

//���� �ܰ迡���� $oauth_token_secret�� request_token_secret�� ���
$Key_For_Signing = urlencode_rfc3986($oauth_consumer_key_secret)."&".urlencode_rfc3986($access_token_secret);

echo("Key_For_Signing=".$Key_For_Signing."<br />");

//oauth_signature ����
$oauth_signature=base64_encode(hash_hmac('sha1', $Base_String, $Key_For_Signing, true));

echo("oauth_signature=".$oauth_signature."<br />");

//Authorization Header ����
$Authorization_Header  = "Authorization: OAuth ";
$Authorization_Header .= urlencode_rfc3986("oauth_version")."=\"".urlencode_rfc3986($oauth_version)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_nonce")."=\"".urlencode_rfc3986($oauth_nonce)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_timestamp")."=\"".urlencode_rfc3986($oauth_timestamp)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_consumer_key")."=\"".urlencode_rfc3986($oauth_consumer_key)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_token")."=\"".urlencode_rfc3986($access_token)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature_method")."=\"".urlencode_rfc3986($oauth_signature_method)."\",";
$Authorization_Header .= urlencode_rfc3986("oauth_signature")."=\"".urlencode_rfc3986($oauth_signature)."\"";

echo("Authorization_Header=".$Authorization_Header."<br />");

$parsed = parse_url($get_minihp_folder_list_url);
$scheme = $parsed["scheme"];
$path = $parsed["path"];
$ip = $parsed["host"];
$port = @$parsed["port"];

$queryStr="menuType=".$menuType."&targetId=".$targetId;
$queryLength = (strlen($queryStr));

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
$out .= "Content-Length: " . $queryLength . "\r\n\r\n";
$out .= $queryStr;

echo("Request=".$out."<br />");
//exit;
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
	//echo("response=".$response."<br />");
	//exit;
	//Response Header�� Body �и�
	$bi = strpos($response, "\r\n\r\n");
	$body = substr($response, $bi+4);
	/*
	�������� ��� �̴�Ȩ��  ���� ��� ��� $body����
	<ArrayOfFolder xmlns="http://schemas.datacontract.org/2004/07/Cy.Service.OpenCy.Entity" xmlns:i="http://www.w3.org/2001/XMLSchema-instance"><Folder><folderName>�����Խ���</folderName><folderNo>2</folderNo><folderOpen/><folderOpenType>allOpen</folderOpenType><id>64617408</id><itemCount>0</itemCount></Folder></ArrayOfFolder>
	�� XML ���·� �����ϴ�.
	*/
	echo ("miniHP Folder XML = <br /><textarea rows='50' cols='120' style='border:1px solid; overflow:auto'>".$body."</textarea><br />");
}


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