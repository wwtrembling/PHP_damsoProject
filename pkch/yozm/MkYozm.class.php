<?
/*
Yozm Class
-인증확인
-인증받아오기 for request token
-인증받아오기 for access token
-글올리기(db삽입, 파일 떨구기)
-글삭제(db수정, 파일 수정)
*/

require_once("daumOAuth.class.php"); // DaumOAuth 받아오기
class MkAddClass{
	private static $consumer_key = '7b286ec7-41fc-41e8-92e9-21d764d73c2e';
	private static $consumer_secret = 'WD8ZyPncjZ5iM1yWeC9EwnWwjk25P.mhaZUOnJMARE.3xvLxxO6Tlg00';
	private static $callback_url = 'http://110.13.170.154/tempProject/pkch/action.php';

	//생성자 함수
	function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null ){
		$this->request_token=$request_token;
		$this->request_token_secret=$request_token_secret;
		$this->access_token=$access_token;
		$this->access_token_secret=$access_token_secret;
	}

	//인증확인
	function checkAuth(){
		if(empty($this->request_token) || empty($this->request_token) || empty($this->request_token) || empty($this->request_token) ){
			return false;
		}
		else{
			return true;
		}
	}

	//인증 받아오기 for request token
	function goAuth(){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url);
		$rTok = $to->getRequestToken();
		$url = $to->getAuthorizeURL($rTok);
		$result=array();
		$result['request_token']=$rTok;
		$result['user_auth_url']=$url;
		return $result;
	}

	//인증 받아오기2 for access token
	function goAuth2($o_token, $o_verifier, $request_token_secret){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $o_token,$request_token_secret);
		$aTok = @$to->getAccessToken($o_verifier);
		return $aTok;
	}
	//글 가지고 오기 >> 후에 변경될 예정임
	function mkLoad($access_token, $access_token_secret){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $access_token, $access_token_secret);
		$url = 'https://apis.daum.net/yozm/v1_0/timeline/home.json';
		$args = array('count'=>20);
		$response = json_decode($to->OAuthRequest($url, $args));
		return $response;
	}

	//글올리기 >> 후에 변경될 예정임
	function mkWrite($access_token, $access_token_secret, $str){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $access_token, $access_token_secret);
		$url = 'https://apis.daum.net/yozm/v1_0/message/add.json';
		$args = array('message'=>$str);
		$response = $to->OAuthRequest($url, $args);
		return $response;
	}

	//글삭제
	function mkRemove($access_token, $access_token_secret, $msg_id){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $access_token, $access_token_secret);
		$url = 'https://apis.daum.net/yozm/v1_0/message/delete.json';
		$args = array('msg_id'=>$msg_id);
		$response = $to->OAuthRequest($url, $args);
		return $response;
	}
}
?>