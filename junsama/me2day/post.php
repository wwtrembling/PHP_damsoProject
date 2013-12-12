<?php 
require_once("config.php");

$body = urlencode($_GET["body"]);
//$callback = $_GET["callback"];

session_start();
$user_id = $_SESSION["user_id"];
$user_key = $_SESSION["user_key"];

$authKey = "12345678" . md5("12345678" . $user_key);

if ( $_GET['flag'] == 'insert' ) {
	$result = json_decode(file_get_contents("http://me2day.net/api/create_post/{$user_id}.json?uid={$user_id}&ukey={$authKey}&akey=" . A_KEY . "&post[body]={$body}"));
	
	//내용			: $_GET["body"]
	//이미지		: $result->icon
	//user아이디	: $result->author->id
	//닉네임		: $result->author->nickname
	//글아이디		: $result->post_id
	//me2day링크	: $result->author->me2dayHome
	//글 me2day링크	: $result->plink
	
	$fileName = date( 'Ymd' );
	$str_logs = $result;
	$filepath = "./$fileName";
	$fp = fopen( "$filepath", "a" );
	fwrite( $fp, "$str_logs\n" );
	fclose( $fp );
} else if ( $_GET['flag'] == 'delete' ) {
	$result = file_get_contents("http://me2day.net/api/delete_post.xml?uid={$user_id}&ukey={$authKey}&akey=" . A_KEY . "&post_id=py2swrw-6jjkv");
} else if ( $_GET['flag'] == 'logout' ) {
	$_SESSION["token"] = $_GET["token"];
	$_SESSION["user_id"] = $_GET["user_id"];
	$_SESSION["user_key"] = $_GET["user_key"];
	$_SESSION["result"] = $_GET["result"];
}
?>