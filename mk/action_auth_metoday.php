<?
/*
�������� ���� ������
*/
//############################################# �α��� ���� (���� request token, access token ����� action)
session_start();
require('./common/MKAdd.conf.php');
require($mkadd_dir."metoday/Mkmetoday.class.php");
$type='metoday';

//���� 1�ܰ踦 ��ġ�� ���ؼ��� _SESSION['HTTP_REFERER']���� Ȯ���Ͽ� ������ Ŭ�����Ѵ�.
if(empty($_SESSION['HTTP_REFERER'])==true){
	if(!empty($_SESSION[$type.'_request_token']))	unset($_SESSION[$type.'_request_token']);
	if(!empty($_SESSION[$type.'_request_token_secret'])) 	unset($_SESSION[$type.'_request_token_secret']);
	if(!empty($_SESSION[$type.'_access_token'])) 	unset($_SESSION[$type.'_access_token']);
	if(!empty($_SESSION[$type.'_access_token_secret'])) 	unset($_SESSION[$type.'_access_token_secret']);
	if(!empty($_SESSION[$type.'_userid'])) 	unset($_SESSION[$type.'_userid']);
	if(!empty($_SESSION[$type.'_usernick'])) 	unset($_SESSION[$type.'_usernick']);
	if(!empty($_SESSION[$type.'_userpic'])) 	unset($_SESSION[$type.'_userpic']);
	if(!empty($_SESSION[$type.'_usage'])) 	unset($_SESSION[$type.'_usage']);
}

$cls= new Mkmetoday($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'], $_SESSION[$type.'_access_token'], $_SESSION[$type.'_access_token_secret']);

//���� 1�ܰ� >> Request Token ������
if(empty($_SESSION[$type.'_request_token']) && empty($_SESSION[$type.'_request_token_secret'])) {
	$result=$cls->goAuth();
	$_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];
	$_SESSION[$type.'_request_token']=$result['request_token']['oauth_token'];
	$_SESSION[$type.'_request_token_secret']=$result['request_token']['oauth_token_secret'];
	echo "<script type='text/javascript'>location.href='".$result['user_auth_url']."';</script>";
	exit;
}
//���� 2�ܰ� >> Access Toekn ������
if(empty($_SESSION[$type.'_access_token']) && empty($_SESSION[$type.'_access_token_secret'])){
	$user_id = $_GET["user_id"];
	$user_key = $_GET["user_key"];
	$result = $_GET["result"];
	$authKey = "12345678" . md5("12345678" . $user_key);
	$result=$cls->goAuth2($user_id, $user_key,$authKey);


	$_SESSION[$type.'_access_token']=$user_id;
	$_SESSION[$type.'_access_token_secret']=$authKey;
	$cls= new Mkmetoday($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'], $_SESSION[$type.'_access_token'], $_SESSION[$type.'_access_token_secret']);
	$result=$cls->getUserInfo();

	$_SESSION[$type.'_userid']=$result['user_id'];
	$_SESSION[$type.'_usernick']=$result['user_nick'];
	$_SESSION[$type.'_userpic']=$result['user_pic'];
	$_SESSION[$type.'_usage']="Y";	/*���ۻ���*/
	$http_referrer=$_SESSION['HTTP_REFERER'];
	unset($_SESSION['HTTP_REFERER']);
	echo "<script type='text/javascript'>location.href='".$http_referrer."';</script>";
	exit;
}


?>