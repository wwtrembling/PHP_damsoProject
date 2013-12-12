<?
session_start();

include('/Data/web/mkadd/common/MKAdd.conf.php');


$admin_id	= isset($_SESSION['admin_id']) ? trim($_SESSION['admin_id']) : "" ;
$admin_name	= isset($_SESSION['admin_name']) ? trim($_SESSION['admin_name']) : "" ;
$admin_auth	= isset($_SESSION['admin_auth']) ? trim($_SESSION['admin_auth']) : "" ;
$app_id		= isset($_SESSION['app_id']) ? trim($_SESSION['app_id']) : "" ;
$chksum		= "mkadd_chk";

function showAlert($str){
	echo "<script type='text/javascript'>alert('".$str."');</script>";
}

function goUrl($url){
	if($url=='back'){
		echo "<script type='text/javascript'>history.back();</script>";
	}
	else{
		echo "<script type='text/javascript'>location.href='".$url."';</script>";
	}
}

function __autoload($className) {
	if(is_file(admin_dir."lib/".$className.'.class.php')) {include_once(admin_dir."lib/".$className.'.class.php');}
	else if(is_file(common_dir.$className.'.class.php')){include_once(common_dir.$className.'.class.php');}
}

?>