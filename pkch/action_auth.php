<?
/*
공통 인증 action 페이지 : action_auth.php
*/
session_start();
$type=isset($_POST['type'])?$_POST['type']:$_GET['type'];

switch($type){
	case "facebook":
		include("./facebook/mkFacebook.class.php");
		break;
	case "yozm":
		include("./yozm/mkYozm.class.php");
		break;
	default :
		$type='yozm';
		include("./yozm/mkYozm.class.php");
		break;
}

//############################################# 인증 request token, access token 만들기 action
/*인증방식은 모두 OAuth을 사용하지만 정확하게 루틴을 따르지 않는 경우가 있으므로 분리해서 처리하도록 함*/
$cls= new MkAddClass();
$_sess=$_SESSION[$_COOKIE['PHPSESSID']][$type];	// 사용자 세션가지고 오기
if($type=='yozm'){
	//Request Token 생성시
	if(empty($_sess['request_token']) && empty($_sess['request_token_secret'])){
		$result=$cls->goAuth();
		$_SESSION[$_COOKIE['PHPSESSID']][$type]['request_token']=$result['request_token']['oauth_token'];
		$_SESSION[$_COOKIE['PHPSESSID']][$type]['request_token_secret']=$result['request_token']['oauth_token_secret'];
		echo "<script type='text/javascript'>location.href='".$result['user_auth_url']."';</script>";
		exit;
	}
	//Access Toekn 생성시
	if(empty($_sess['access_token']) && empty($_sess['access_token_secret'])){
		$o_token = @$_REQUEST['oauth_token'];
		$o_verifier = @$_REQUEST['oauth_verifier'];
		$result=$cls->goAuth2($o_token, $o_verifier,$_sess['request_token_secret']);
		$_SESSION[$_COOKIE['PHPSESSID']][$type]['access_token']=$result['oauth_token'];
		$_SESSION[$_COOKIE['PHPSESSID']][$type]['access_token_secret']=$result['oauth_token_secret'];
		echo "<script type='text/javascript'>alert('인증완료');opener.location.reload();window.close();</script>";
		exit;
	}
}
else if($type=='facebook'){
	//http://110.13.170.154/tempProject/pkch/action_auth.php?type=facebook&state=45809fe15519970b5c1ad00fb53fc060&code=AQBwf57TogTEuxlUybdBokIxptaNBuGZoNC9SIZdJCiPpnzCmds_aOD-UP-xlnjy2exbTdIjChNlanYVLBkjxIydJGW2Ed-GupNCg2aZKSLqRAqTQsaWi0myrVbiDiCw2-D9UuhW-hDf_kAn3XKZBmyc1JJaKrxtnYvaUb5D2KwIGF1u7Rg67sE2tddU0htZdQWUQT8VwwzF7YhX9-QLZETs#_=_

	$result=$cls->goAuth();
	echo "<script type='text/javascript'>location.href='".$result['user_auth_url']."';</script>";
}

?>
