<?
/*
ADMIN Login 페이지
*/
include('./inc/conf_admin.inc.php');
if(!defined("checksum")) exit;
//$_SESSION['admin_id']='sullung2';
//$_SESSION['admin_name']='MK관리자';
//$_SESSION['admin_auth']="S";
//$_SESSION['app_id']="qwerpoiu01";

if(!empty($admin_id) && !empty($admin_name) && !empty($admin_auth)){goUrl('./index_admin.php');}
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='ko' lang='ko'> 
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8;' />
<meta http-equiv='cache-control' content='no-cache' />
<meta http-equiv='pragma' content='no-cache' />
<title>MK Marshal</title>
<meta name='description' content='SP, 관리자 로그인' />
<meta name='keywords' content='SP, 관리자 로그인' />
<link href='http://mas.mk.co.kr:7878/css/default.css' rel='stylesheet' type='text/css' />
<script language='javascript'>
window.onload=function(){
	document.getElementById('id').focus();
}
function checkForm()
{
	var fn = document.loginForm;
	
	if(!fn.id.value)
	{
		alert('아이디를 입력해 주세요.');
		fn.id.focus();
		return false;
	}
	if(!fn.password.value)
	{
		alert('비밀번호를 입력해 주세요.');
		fn.password.focus();
		return false;
	}
	fn.action = 'action_admin.php';
	fn.submit();
}
</script>
</head>
<body>
<div id='main_wrap'>
	<div id='main_header'>
		<h1><n:img><img src='http://mas.mk.co.kr:7878/img/im_mklogo.gif' alt='smartu' /></n:img></h1>
	</div>
	<hr />
	<div id='main_container'>
		<form name='loginForm' method='post' >
		<input type='hidden' name='cmd' value='chk_login'>
		<div id='login'>
			<fieldset>
				<legend>관리자 로그인</legend>
				<dl class='cfx'>
					<dt>&nbsp;</dt>
					<dd>&nbsp;</dd>
				</dl>
				<dl class='cfx'>
					<dt><n:img><img src='http://mas.mk.co.kr:7878/img/txt_login_id.gif' alt='아이디' /></n:img></dt>
					<dd><input type='text' id='id' name='id' class='text' tabindex='1'/></dd>
				</dl>
				<dl class='cfx'>
					<dt><n:img><img src='http://mas.mk.co.kr:7878/img/txt_login_pw.gif' alt='비밀번호' /></n:img></dt>
					<dd><input type='password' id='password' name='password' class='text'  tabindex='2' onkeypress="if(event.keyCode==13) checkForm();"/></dd>
				</dl>
				<a href='#' onclick='checkForm();'><n:img><img src='http://mas.mk.co.kr:7878/img/btn_login.gif' alt='Login' /></n:img></a>
			</fieldset>
		</div>
		</form>
	</div>
	<hr />
	<div id='main_footer'>
		<address>Copyright (c) <?=date('Y')?> 매경닷컴. All rights reserved.</address>
	</div>
</div>
</body>
</html>