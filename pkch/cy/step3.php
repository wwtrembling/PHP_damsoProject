<?
session_start();
$oauth_consumer_key = "fa76356e1f7f9dfa550d5f090cd153be04f065f6d"; // Your consumer key
$oauth_consumer_key_secret = "db566fcb7a01f0b5e0bbf8e205ea5f2d"; // Your consumer key secret
$request_token=$_SESSION['rt'];
$request_token_secret=$_SESSION['rts'];
$access_token=$_SESSION['at'];
$access_token_secret=$_SESSION['ats'];
$oauth_signature_method = "HMAC-SHA1";
$oauth_version = "1.0";

/*==============================================================================================================
==============================================================================================================*/
//확보된 access_token 으로 미니홈피  폴더 목록 받기로 바로 진행
//access_token의 수명은 생성 시점으로 부터 24시간 입니다.
//저장 해 두었다가 24시간 이내이면 해당 access_token으로 바로 API를 호출 해도 됩니다.
$oauth_timestamp = time();
$oauth_nonce = md5(microtime().mt_rand()); // md5s look nicer than numbers;
$get_minihp_folder_list_url = "http://openapi.nate.com/OApi/RestApi/CY/200110/xml_RetrieveFolderList/v1";

$menuType="1";
//조회대상 미니홈피 아이디는 일촌 API로 확보 가능합니다.
$targetId="";//공백으로 두면 Access Token 소유자의 미니 홈피를 조회합니다.

//Generate Base String For Get Access Token
//!!파라메터 이름 순서로 조합해야 한다.
//!!파라메터의 이름과 값은 rfc3986 으로 encode
//[Name=Valeu&Name=Value…] 형식으로 연결

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

//Base String 구성 요소를 &로 연결
$Base_String = urlencode_rfc3986("POST")."&".urlencode_rfc3986($get_minihp_folder_list_url)."&".urlencode_rfc3986($Query_String);

echo("Base_String=".$Base_String."<br />");

//지금 단계에서는 $oauth_token_secret에 request_token_secret을 사용
$Key_For_Signing = urlencode_rfc3986($oauth_consumer_key_secret)."&".urlencode_rfc3986($access_token_secret);

echo("Key_For_Signing=".$Key_For_Signing."<br />");

//oauth_signature 생성
$oauth_signature=base64_encode(hash_hmac('sha1', $Base_String, $Key_For_Signing, true));

echo("oauth_signature=".$oauth_signature."<br />");

//Authorization Header 조합
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

//Request 만들기
$out  = "POST " . $path . " HTTP/1.1\r\n";
$out .= "Host: ". $ip . "\r\n";
$out .= $Authorization_Header . "\r\n";
$out .= "Accept-Language: ko\r\n";
$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
$out .= "Content-Length: " . $queryLength . "\r\n\r\n";
$out .= $queryStr;

echo("Request=".$out."<br />");
//exit;
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
	//echo("response=".$response."<br />");
	//exit;
	//Response Header와 Body 분리
	$bi = strpos($response, "\r\n\r\n");
	$body = substr($response, $bi+4);
	/*
	정상적인 경우 미니홈피  폴더 목록 결과 $body값은
	<ArrayOfFolder xmlns="http://schemas.datacontract.org/2004/07/Cy.Service.OpenCy.Entity" xmlns:i="http://www.w3.org/2001/XMLSchema-instance"><Folder><folderName>자유게시판</folderName><folderNo>2</folderNo><folderOpen/><folderOpenType>allOpen</folderOpenType><id>64617408</id><itemCount>0</itemCount></Folder></ArrayOfFolder>
	의 XML 형태로 떨집니다.
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