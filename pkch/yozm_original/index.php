<?php
require_once('./daumOAuth.php');

session_start();//for save request token.

$consumer_key = '7b286ec7-41fc-41e8-92e9-21d764d73c2e';
$consumer_secret = 'WD8ZyPncjZ5iM1yWeC9EwnWwjk25P.mhaZUOnJMARE.3xvLxxO6Tlg00';
$callback_url = 'http://110.13.170.154/tempProject/pkch/yozm_original/index.php';

$session_var_names = array('request_token', 'user_auth_url', 'access_token', 'blog_info');

$o_mode = @$_REQUEST['o_mode'];
switch ($o_mode) {
	default:
		//처음 접속시 or 2번후 콜백으로 들어와서 3번 진행.
		$aTok = @$_SESSION['access_token'];
		if( $aTok['oauth_token'] && $aTok['oauth_token_secret']) continue;	// 추가함 by pkch
		$o_token = @$_REQUEST['oauth_token'];
		$o_verifier = @$_REQUEST['oauth_verifier'];
		if (isset($o_token) && isset($o_verifier)) {
			//2번에서 인증 후에 콜백 URL로 다시 돌아오는 곳.
			$rTok = $_SESSION['request_token'];
			$to = new DaumOAuth($consumer_key, $consumer_secret, $callback_url, $o_token, $rTok['oauth_token_secret']);
			$aTok = @$to->getAccessToken($o_verifier);
			$_SESSION['access_token'] = $aTok;
		}
		else {
			//처음 접속시.
			foreach ($session_var_names as $var_name) {
				if (isset($_SESSION[$var_name])) {
					unset($_SESSION[$var_name]);
				}
			}
		}
		break;
	case 'request_token':
		//1번의 버튼을 눌러서 진행됨.
		$to = new DaumOAuth($consumer_key, $consumer_secret, $callback_url);
		$rTok = $to->getRequestToken();
		$url = $to->getAuthorizeURL($rTok);
		$_SESSION['request_token'] = $rTok;
		$_SESSION['user_auth_url'] = $url;
		break;
	case 'blog_info':
		//3번의 버튼을 눌러서 진행됨.
		$aTok = @$_SESSION['access_token'];
		$blog_id = $_REQUEST['blog_id'];
		if (isset($aTok)) {
			$to = new DaumOAuth($consumer_key, $consumer_secret, $callback_url, $aTok['oauth_token'], $aTok['oauth_token_secret']);
			$url = 'http://apis.daum.net/blog/info/blog.do';
			$args = array('blogName'=>$blog_id);
			$response = $to->OAuthRequest($url, $args);
			$_SESSION['blog_info'] = $response;
		}
		break;
	case 'msg_insert':
		$str=$_POST['str'];
		$aTok = @$_SESSION['access_token'];
		$to = new DaumOAuth($consumer_key, $consumer_secret, $callback_url, $aTok['oauth_token'], $aTok['oauth_token_secret']);
		$url = 'https://apis.daum.net/yozm/v1_0/message/add.json';
		$args = array('message'=>$str);
		$response = $to->OAuthRequest($url, $args);
		echo "registed!";
		break;
	case 'msg_remove':
		$aTok = @$_SESSION['access_token'];
		$to = new DaumOAuth($consumer_key, $consumer_secret, $callback_url, $aTok['oauth_token'], $aTok['oauth_token_secret']);
		$url = 'https://apis.daum.net/yozm/v1_0/message/delete.json';
		$args = array('msg_id'=>$_POST['msg_id']);
		$response = $to->OAuthRequest($url, $args);
		echo "deleted!";
		break;
}

$dump_data = array();
foreach ($session_var_names as $var_name) {
	if (isset($_SESSION[$var_name])) {
		$dump_data[$var_name] = $_SESSION[$var_name];
	}
}
echo "<pre>";var_dump($_REQUEST);echo "</pre>";
echo "<pre>";var_dump($_SESSION);echo "</pre>";
?><!DOCTYPE html> 
<html> 
<head>
<meta charset=utf-8><!-- simplified version; works on legacy browsers -->
<title>Daum OAuth Example.</title>
</head>
<body>
	<h1>Daum OAuth Example.</h1>
	<textarea rows="10" cols="120"><?php print_r($dump_data); ?></textarea>
	<hr/>
	<p>
		<h3>0. 정보 초기화 하기.</h3>
		<form>
			<input name="o_mode" value="reset" type="hidden" />
			<input type="submit" />
		</form>
	</p>
	<p>
		<h3>1. 인증되지 않은 Request 토큰 획득 및 서비스 프로바이더의 사용자 인증 URL 구하기.</h3>
		<form>
			<input name="o_mode" value="request_token" type="hidden" />
			<input type="submit" />
		</form>
	</p>
	<p>
		<h3>2. 사용자 인증 획득 및 Access 토큰 획득</h3>
		1번에서 받은 user_auth_url 로 이동하여 인증하십시요.
		<div>
			<?php 
if (isset($_SESSION['user_auth_url'])) {
	$url = $_SESSION['user_auth_url'];
	print '<a href="' . $url . '">' . $url . '</a>';
}
			?>
		</div>
	</p>
	<p>
		<h3>3. 보호된 리소스에 접근</h3>
		다음 오픈 API 중 "블로그 정보 가져오기"의 예제(http://apis.daum.net/blog/info/blog.do).
		<form>
			<input name="o_mode" value="blog_info" type="hidden" />
			블로그 아이디: <input name="blog_id" value="<?php
print isset($blog_id) ? $blog_id : 'ahahblog';		
			?>" type="text" />
			<br/><input type="submit" />
		</form>
		<div>
			<pre><?php
if (isset($_SESSION['blog_info'])) {
	$info = $_SESSION['blog_info'];
	//print htmlspecialchars($info);
}
			?></pre>
		</div>
	</p>

	<p>
	<script type='text/javascript'>
	<!--
	function removeArticle(msg_id){
		document.getElementsByName('msg_id')[0].value=msg_id;
		document.getElementsByName('thisform')[0].submit();
	}
	//-->
	</script>
	<h3>4.글보기</h3>
<?
$aTok = @$_SESSION['access_token'];
$to = new DaumOAuth($consumer_key, $consumer_secret, $callback_url, $aTok['oauth_token'], $aTok['oauth_token_secret']);
$url = 'https://apis.daum.net/yozm/v1_0/timeline/home.json';
$args = array('count'=>20);
$response = json_decode($to->OAuthRequest($url, $args));
$list=$response->{"msg_list"};
$max=count($list);
$str=null;
for($i=0;$i<$max;$i++){
	$tmp=$list[$i];
	$str.="<tr><td><img src='".$tmp->{'user'}->{'profile_big_img_url'}."' style='width:100px;'/></td><td>".$tmp->{'text'}."</td><td><input type='button' value='삭제' onclick=\"removeArticle('".$tmp->{'msg_id'}."')\" /></td></tr>";
}
echo "
	<form method='post' name='thisform' >
	<input type='hidden' name='o_mode' value='msg_remove'/>
	<input type='hidden' name='msg_id' value='' />
	</form>
	<table>".$str."</table>";
?>
	</p>


	<p>
	<h3>5.Yozm에 등록하기</h3>
	<form method='post' >
	<input name="o_mode" value="msg_insert" type="hidden" />
	<input type='hidden' name='msg_id' value=''/>
	<input type='text' name='str' value='매경인'/>
	<input type='submit' value='등록하기' />
	</form>
	</p>
</body>
</html>