<?
/*
--------------------------------------------------------------------------------
���� ���� ������ session���� �Ʒ��� ���� ������ (����)
$_SESSION['�Ҽȼ��񽺸�_request_token']
$_SESSION['�Ҽȼ��񽺸�_request_secret']
$_SESSION['�Ҽȼ��񽺸�_access_token']
$_SESSION['�Ҽȼ��񽺸�_access_secret']
$_SESSION['�Ҽȼ��񽺸�_userid']
$_SESSION['�Ҽȼ��񽺸�_usernick']
$_SESSION['�Ҽȼ��񽺸�_userpic']
--------------------------------------------------------------------------------
referer üũ�Ͽ� �����ִ� ��η� ���ư��� ��ũ��Ʈ�� �����ϰԲ� ������ at 2012.2.10
--------------------------------------------------------------------------------
*/
//############################################# �α��� ���� (���� request token, access token ����� action)
session_start();
require('./common/MKAdd.conf.php');
include($mkadd_dir."facebook/Mkfacebook.class.php");
$type='facebook';

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

//����� �����޴� ����� ���� �ٸ���
$cls= new Mkfacebook($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'], $_SESSION[$type.'_access_token'], $_SESSION[$type.'_access_token_secret']);
if(!isset($_GET['state'])|| !isset($_GET['code'])) {
	//------------------------------------- 1��° ����
	$cls->goAuth();	//Default Setting
	$_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];
	$loginUrl=$cls->getLoginUrl();
	echo "<script type='text/javascript'>location.href='".$loginUrl."';</script>";
	exit;
}
else{
	//------------------------------------- 2��° ����
	$cls->goAuth();	//Default Setting
	$user = $cls->getUser();
	/*
	���̽����� MkFacebook.class.php ���� ������ �����ϰ� �ȴ�.
	*/
	$_SESSION[$type.'_request_token']=null;
	//$_SESSION[$type.'_request_token_secret']=null;
	//$_SESSION[$type.'_access_token']=null;
	//$_SESSION[$type.'_access_token_secret']=null;

		//����� ���̵� �޾ƿ���
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