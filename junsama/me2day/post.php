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
	
	//����			: $_GET["body"]
	//�̹���		: $result->icon
	//user���̵�	: $result->author->id
	//�г���		: $result->author->nickname
	//�۾��̵�		: $result->post_id
	//me2day��ũ	: $result->author->me2dayHome
	//�� me2day��ũ	: $result->plink
	
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