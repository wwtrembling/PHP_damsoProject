<?php
if ( $_POST['hName'] == 'trans' ) {
	/**
	 * @file
	 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
	 */
	
	/* Load required lib files. */
	session_start();
	require_once('twitteroauth/twitteroauth.php');
	require_once('config.php');

	/* If access tokens are not available redirect to connect page. */
	if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
	    header('Location: ./clearsessions.php');
	}
	
	/* Get user access tokens out of the session. */
	$access_token = $_SESSION['access_token'];
	
	/* Create a TwitterOauth object with consumer/user tokens. */
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	
	/* If method is set change API call made. Test is called by default. */
	$content = $connection->get('account/verify_credentials');
	
	/* twit 인코딩 urt-8 */
	$twitMsg = iconv('euc-kr', 'utf-8', $_POST['reply']);
	
	/* Some example calls */
	//$connection->get('users/show', array('screen_name' => 'abraham'));
	
	/* 글쓰기  시작 */
	$return_value = $connection->post('statuses/update', array('status' => $twitMsg));
	//$twit_id = $return_value->{'id'};
	echo "<pre>";
	var_dump($return_value);
	echo "</pre>";
	/* 글쓰기 끝 */

	/* 글삭제 */
	//$return_value = $connection->post('statuses/destroy', array('id' => '154416830079967232' ));
	
	//$connection->post('friendships/create', array('id' => 9436992));
	//$connection->post('friendships/destroy', array('id' => 9436992));
}
?>

<HTML>
 <HEAD>
  <TITLE> New Document </TITLE>
 <BODY>
 	<form name='thisform' method='post'>
	 	<input type='text' name='hName' value='trans'>
		<input type='text' name='reply' /> <input type='submit' value=' submit ' />
		<a href='reply_delete.php' target='_blank'> 삭제 </a>
	</form>
 </BODY>
</HTML>