<?
//----------------------필수사항 시작
require '../src/facebook.php';
// 페이스북 객체 생성
$facebook = new Facebook(array('appId'  => '268567429874202','secret' => '509096dc5b91682e04d8e8b7a0634fcb'));
// Get User ID
$user = $facebook->getUser();
//----------------------필수사항 끝

$cmd=$_POST['cmd'];
//----------------사용자 글 삭제 : 이 페이지를 통해 생성된 트위터에 등록된 글(news feed)만 삭제가 가능하다. 다른 것들은 삭제가 불가함.페이스북 정책(http://forum.developers.facebook.net/viewtopic.php?id=94145)
if($cmd=='remove'){
	$id=$_POST['id'];
	//$tmp=explode("_",$id);	echo "<pre>";var_dump($tmp);echo "</pre>";
	echo "<pre>";var_dump($id);echo "</pre>";
	$result=$facebook->api("/".$id,"DELETE");
	echo "<pre>";var_dump($result);echo "</pre>";
	exit;
}
//----------------사용자 글 등록
else if($cmd=='regist'){
	try {
		$a=$facebook->api('/me/feed','POST',
			array( 
				'message' =>$_POST['str']
			)
		);
	} catch(FacebookApiException $e) {
		$result = $e->getResult();
		error_log(json_encode($result));
	}
	echo "<pre>";var_dump($a);echo "</pre>";
	exit;
}


//로그인/로그아웃 URL
if ($user) {
	$logoutUrl = $facebook->getLogoutUrl();
} else {
	$loginUrl = $facebook->getLoginUrl(
			array('scope'=>'read_stream, publish_stream')
		);
}


//사용자 프로필
if ($user) {
	try {
		// Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	}
}

//사용자 글가지고 오기

if ($user) {
	try {
		// Proceed knowing you have a logged in user who's authenticated.
		$temp = $facebook->api('/me/home');
	} catch (FacebookApiException $e) {
		error_log($e);
		$temp = null;
	}
	$articles=$temp['data'];
	$max=count($articles);
	$str=null;
	for($i=0;$i<$max;$i++){
		$temp=$articles[$i];
		$str.="
		<tr>
			<td>".$temp['id']."</td>
			<td>".$temp['from']['name']."</td>
			<td>".$temp['message']."</td>
			<td><input type='button' value='삭제' onclick=\"removeArticle('".$temp['id']."')\" /></td>
		</tr>";
	}
}



echo "
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<script>
function removeArticle(id){
	document.getElementsByName('id')[0].value=id;
	document.getElementsByName('cmd')[0].value='remove';
	document.getElementsByName('thisform')[0].submit();
}
</script>
<form name='thisform' action='./example.php' method='post' >
<input type='hidden' name='cmd' value='' />
<input type='hidden' name='id' value='' />
<table>".$str."</table>
</form>";
//사용자 글 등록
echo "
<script>
function registArticle(){
	var thisform2=document.getElementsByName('thisform2')[0];
	thisform2.cmd.value='regist';
	thisform2.submit();
}
</script>
<form name='thisform2' action='./example.php' method='post' >
<input type='hidden' name='cmd' value='' />
<input type='text' name='str' value='나는 매경' />
<input type='button' value='확인' onclick='registArticle()'/>
</form>";


// This call will always work since we are fetching public data.
//$naitik = $facebook->api('/naitik');
//echo "<pre>";var_dump($naitik);echo "</pre>";
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title>php-sdk</title>
<style>
body {
font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
}
h1 a {
text-decoration: none;
color: #3b5998;
}
h1 a:hover {
text-decoration: underline;
}
</style>
</head>
<body>
<h1>php-sdk</h1>
<?php if ($user): ?>
<a href="<?php echo $logoutUrl; ?>">Logout</a>
<?php else: ?>
<div>Login using OAuth 2.0 handled by the PHP SDK:<a href="<?php echo $loginUrl; ?>">Login with Facebook</a></div>
<?php endif ?>
<h3>PHP Session</h3>
<pre><?php print_r($_SESSION); ?></pre>
<?php if ($user): ?>
	<h3>You</h3>
	<img src="https://graph.facebook.com/<?php echo $user; ?>/picture">
	<h3>Your User Object (/me)</h3>
	<pre><?php print_r($user_profile); ?></pre>
<?php else: ?>
	<strong><em>You are not Connected.</em></strong>
<?php endif ?>
<h3>Public profile of Naitik</h3>
<img src="https://graph.facebook.com/naitik/picture">
<?php echo $naitik['name']; ?>
</body>
</html>
