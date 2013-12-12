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
매경의 경우에는 maekyung_request_token 이 사용자 아이디를 지칭하게끔 한다
매경의 경우에는 maekyung_request_token_secret 이 사용자 이름을 지칭하게끔 한다
--------------------------------------------------------------------------------
*/
//############################################# 로그인 수행 (인증 request token, access token 만들기 action)
session_start();
require('./common/MKAdd.conf.php');
require($mkadd_dir."maekyung/Mkmaekyung.class.php");
$type="maekyung";

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

//인증 1단계
if(empty($_REQUEST[$type.'_request_token']) && empty($_REQUEST[$type.'_request_token_secret'])) {
	$cls= new Mkmaekyung(null,null, null, null);
	$_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];
	$result=$cls->goAuth();
	$result['user_auth_url']=$result['user_auth_url']."http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	echo "<script type='text/javascript'>location.href='".$result['user_auth_url']."';</script>";
	exit;
}
//인증 2단계
else if($_REQUEST[$type.'_request_token'] && $_REQUEST[$type.'_request_token_secret']){
	$_SESSION[$type.'_request_token']=$_REQUEST[$type.'_request_token'];
	$_SESSION[$type.'_request_token_secret']=$_REQUEST[$type.'_request_token_secret'];

	//사용자 아이디 받아오기
	$cls= new Mkmaekyung($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'], null,null);
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