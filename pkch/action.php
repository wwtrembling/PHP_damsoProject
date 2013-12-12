<?
/*
공통 등록/삭제 action 페이지 : action.php
*/
session_start();
$cmd=isset($_POST['cmd'])?$_POST['cmd']:$_GET['cmd'];
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

$_sess=$_SESSION[$_COOKIE['PHPSESSID']][$type];	// 사용자 세션가지고 오기

//############################################# 등록/삭제 action
//Default Setting
$cls= new MkAddClass($_sess['request_token'], $_sess['request_token_secret'], $_sess['access_token'], $_sess['access_token_secret']);
$chk=$cls->checkAuth();
//------------------------------------ 로그인 여부 확인
if($cmd=='Loginchk'){
	$chkLogin=null;
	if($chk==true){
		$chkLogin=0;
		$rtnUrl="action.php?type=".$type."&cmd=Logout";
	}
	else{
		$chkLogin=-1;
		$rtnUrl="action_auth.php?type=".$type;
	}
	$arr = array("chkLogin"=>$chkLogin,"rtnUrl"=>$rtnUrl);
	$jsonData = json_encode($arr);
	echo $_GET["callback"] . "(" . $jsonData . ");";
	exit;
}
//---------------------------------- 로그인이 된 후에야 가능한 작업들
if($chk==true){
	//------------------------------------ 로그아웃
	if($cmd=='Logout'){
		unset($_SESSION[$_COOKIE['PHPSESSID']]);
		echo "<script type='text/javascript'>opener.location.reload();window.close();</script>";
		exit;
	}
	//------------------------------------ 글가지고 오기	 >> 이후에는 변경될 예정임
	else if($cmd=='Load'){
		$result=$cls->mkLoad($_sess['access_token'], $_sess['access_token_secret']);
		$jsonData= json_encode($result);
		echo $_GET["callback"] . "(" . $jsonData . ");";
		exit;
	}
	//------------------------------------ 글등록
	else if($cmd=='Regist'){
		$str=$_GET['str'];
		$result=$cls->mkWrite($_sess['access_token'], $_sess['access_token_secret'], $str);
		$jsonData= json_encode($result);
		echo $_GET["callback"] . "(" . $jsonData . ");";
		exit;
	}
	//------------------------------------ 글삭제
	else if($cmd=='Remove'){
		$msg_id=intval($_GET['id']);
		$result=$cls->mkRemove($_sess['access_token'], $_sess['access_token_secret'], $msg_id);
		$jsonData= json_encode($result);
		echo $_GET["callback"] . "(" . $jsonData . ");";
		exit;
	}
}

?>
