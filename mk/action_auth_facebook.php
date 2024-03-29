<?
/*
--------------------------------------------------------------------------------
인증 받을 때마다 session내에 아래와 같이 저장함 (공통)
$_SESSION['소셜서비스명_request_token']
$_SESSION['소셜서비스명_request_secret']
$_SESSION['소셜서비스명_access_token']
$_SESSION['소셜서비스명_access_secret']
$_SESSION['소셜서비스명_userid']
$_SESSION['소셜서비스명_usernick']
$_SESSION['소셜서비스명_userpic']
--------------------------------------------------------------------------------
referer 체크하여 원래있던 경로로 돌아가서 스크립트가 동작하게끔 수정함 at 2012.2.10
--------------------------------------------------------------------------------
*/
//############################################# 로그인 수행 (인증 request token, access token 만들기 action)
session_start();
require('./common/MKAdd.conf.php');
include($mkadd_dir."facebook/Mkfacebook.class.php");
$type='facebook';

//인증 1단계를 거치기 위해서는 _SESSION['HTTP_REFERER']값을 확인하여 세션을 클리어한다.
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

//페북은 인증받는 방식이 조금 다르다
$cls= new Mkfacebook($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'], $_SESSION[$type.'_access_token'], $_SESSION[$type.'_access_token_secret']);
if(!isset($_GET['state'])|| !isset($_GET['code'])) {
	//------------------------------------- 1번째 인증
	$cls->goAuth();	//Default Setting
	$_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];
	$loginUrl=$cls->getLoginUrl();
	echo "<script type='text/javascript'>location.href='".$loginUrl."';</script>";
	exit;
}
else{
	//------------------------------------- 2번째 인증
	$cls->goAuth();	//Default Setting
	$user = $cls->getUser();
	/*
	페이스북은 MkFacebook.class.php 에서 세션을 생성하게 된다.
	*/
	$_SESSION[$type.'_request_token']=null;
	//$_SESSION[$type.'_request_token_secret']=null;
	//$_SESSION[$type.'_access_token']=null;
	//$_SESSION[$type.'_access_token_secret']=null;

		//사용자 아이디 받아오기
	$result=$cls->getUserInfo();
	$_SESSION[$type.'_userid']=$result['user_id'];
	$_SESSION[$type.'_usernick']=$result['user_nick'];
	$_SESSION[$type.'_userpic']=$result['user_pic'];
	$_SESSION[$type.'_usage']="Y";	/*전송상태*/
	$http_referrer=$_SESSION['HTTP_REFERER'];
	unset($_SESSION['HTTP_REFERER']);
	echo "<script type='text/javascript'>location.href='".$http_referrer."';</script>";
	exit;
}
?>