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
�Ű��� ��쿡�� maekyung_request_token �� ����� ���̵� ��Ī�ϰԲ� �Ѵ�
�Ű��� ��쿡�� maekyung_request_token_secret �� ����� �̸��� ��Ī�ϰԲ� �Ѵ�
--------------------------------------------------------------------------------
*/
//############################################# �α��� ���� (���� request token, access token ����� action)
session_start();
require('./common/MKAdd.conf.php');
require($mkadd_dir."maekyung/Mkmaekyung.class.php");
$type="maekyung";

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

//���� 1�ܰ�
if(empty($_REQUEST[$type.'_request_token']) && empty($_REQUEST[$type.'_request_token_secret'])) {
	$cls= new Mkmaekyung(null,null, null, null);
	$_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];
	$result=$cls->goAuth();
	$result['user_auth_url']=$result['user_auth_url']."http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	echo "<script type='text/javascript'>location.href='".$result['user_auth_url']."';</script>";
	exit;
}
//���� 2�ܰ�
else if($_REQUEST[$type.'_request_token'] && $_REQUEST[$type.'_request_token_secret']){
	$_SESSION[$type.'_request_token']=$_REQUEST[$type.'_request_token'];
	$_SESSION[$type.'_request_token_secret']=$_REQUEST[$type.'_request_token_secret'];

	//����� ���̵� �޾ƿ���
	$cls= new Mkmaekyung($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'], null,null);
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