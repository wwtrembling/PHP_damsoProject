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
require($mkadd_dir."twitter/Mktwitter.class.php");
$type='twitter';

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

if (empty($_SESSION[$type.'_request_token']) || empty($_SESSION[$type.'_request_token_secret'])) {
	//Request Token 저장
	$cls= new Mktwitter($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret']);
	$result=$cls->goAuth();
	$_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];
	$_SESSION[$type.'_request_token']=$result['request_token']['oauth_token'];
	$_SESSION[$type.'_request_token_secret']=$result['request_token']['oauth_token_secret'];
	echo "<script type='text/javascript'>location.href='".$result['user_auth_url']."';</script>";
	exit;
}
else if (isset($_REQUEST['oauth_token'])==true && $_SESSION[$type.'_request_token'] == $_REQUEST['oauth_token']) {
	//Access Token 저장
	$cls= new Mktwitter($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret']);
	$result=$cls->goAuth2($_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
	$_SESSION[$type.'_access_token']=$result['oauth_token'];
	$_SESSION[$type.'_access_token_secret']=$result['oauth_token_secret'];

	//사용자 정보 받아오기
	$cls= new Mktwitter($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'],$_SESSION[$type.'_access_token'] ,$_SESSION[$type.'_access_token_secret']);
	$result=$cls->getUserInfo();
	$_SESSION[$type.'_userid']=$result['userid'];
	$_SESSION[$type.'_usernick']=$result['usernick'];
	$_SESSION[$type.'_userpic']=$result['userpic'];
	$_SESSION[$type.'_usage']="Y";	/*전송상태*/
	$http_referrer=$_SESSION['HTTP_REFERER'];
	unset($_SESSION['HTTP_REFERER']);
	echo "<script type='text/javascript'>location.href='".$http_referrer."';</script>";
	exit;
}
exit;

?>