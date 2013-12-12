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
require($mkadd_dir."twitter/Mktwitter.class.php");
$type='twitter';

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

if (empty($_SESSION[$type.'_request_token']) || empty($_SESSION[$type.'_request_token_secret'])) {
	//Request Token ����
	$cls= new Mktwitter($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret']);
	$result=$cls->goAuth();
	$_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];
	$_SESSION[$type.'_request_token']=$result['request_token']['oauth_token'];
	$_SESSION[$type.'_request_token_secret']=$result['request_token']['oauth_token_secret'];
	echo "<script type='text/javascript'>location.href='".$result['user_auth_url']."';</script>";
	exit;
}
else if (isset($_REQUEST['oauth_token'])==true && $_SESSION[$type.'_request_token'] == $_REQUEST['oauth_token']) {
	//Access Token ����
	$cls= new Mktwitter($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret']);
	$result=$cls->goAuth2($_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
	$_SESSION[$type.'_access_token']=$result['oauth_token'];
	$_SESSION[$type.'_access_token_secret']=$result['oauth_token_secret'];

	//����� ���� �޾ƿ���
	$cls= new Mktwitter($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'],$_SESSION[$type.'_access_token'] ,$_SESSION[$type.'_access_token_secret']);
	$result=$cls->getUserInfo();
	$_SESSION[$type.'_userid']=$result['userid'];
	$_SESSION[$type.'_usernick']=$result['usernick'];
	$_SESSION[$type.'_userpic']=$result['userpic'];
	$_SESSION[$type.'_usage']="Y";	/*���ۻ���*/
	$http_referrer=$_SESSION['HTTP_REFERER'];
	unset($_SESSION['HTTP_REFERER']);
	echo "<script type='text/javascript'>location.href='".$http_referrer."';</script>";
	exit;
}
exit;

?>