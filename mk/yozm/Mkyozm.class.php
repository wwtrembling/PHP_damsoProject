<?
if(!defined("checksum")) exit;
require_once($mkadd_dir."yozm/daumOAuth.class.php"); // DaumOAuth 받아오기

class Mkyozm implements MkAddInterface{
	private static $consumer_key = 'd16770ae-fef7-4fc1-9686-f54674f3319f';	// daum.net // mkdev // 비밀번호는 기획팀에게 문의
	private static $consumer_secret = 'gW_dh_Er2gUdIPlE-h7ueaY-JBe1l6kuAfAlLw6iiUyBqv15AGOSWw00';
	private static $callback_url = 'http://220.73.139.94/mkadd/action_auth_yozm.php';

	//################################################### MK 기본 common 함수 모음
	//생성자 함수
	public function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null ){
		$this->request_token=$request_token;
		$this->request_token_secret=$request_token_secret;
		$this->access_token=$access_token;
		$this->access_token_secret=$access_token_secret;
	}

	//인증확인
	public function checkAuth(){
		if(empty($this->request_token) || empty($this->request_token_secret) || empty($this->access_token) || empty($this->access_token_secret) ){
			return -1;
		}
		else{
			return 1;
		}
	}

	//인증 받아오기 for request token
	public function goAuth(){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url);
		$rTok = $to->getRequestToken();
		$url = $to->getAuthorizeURL($rTok);
		$result=array();
		$result['request_token']=$rTok;
		$result['user_auth_url']=$url;
		return $result;
	}

	//인증 받아오기2 for access token
	public function goAuth2($o_token=null, $o_verifier=null, $request_token_secret=null){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $o_token,$request_token_secret);
		$aTok = @$to->getAccessToken($o_verifier);
		return $aTok;
	}

	//사용자 정보 받아오기
	public function getUserInfo(){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $this->access_token, $this->access_token_secret);
		$url = 'http://apis.daum.net/yozm/v1_0/user/show.json';
		$args = array();
		$response = json_decode($to->OAuthRequest($url, $args));
		$result=array();
		$result['user_id']=$response->user->url_name;
		$result['user_nick']=$response->user->nickname;
		$result['user_pic']=$response->user->profile_big_img_url;
		return $result;
	}
	
	//글올리기
	public function mkWrite($str){
		//요즘에 올리기
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $this->access_token, $this->access_token_secret);
		$url = 'https://apis.daum.net/yozm/v1_0/message/add.json';
		$args = array('message'=>$str);
		$response = $to->OAuthRequest($url, $args);
		$jsonData=json_decode($response);
		if($jsonData->status==200){
			return $jsonData->message->msg_id;	//ok
		}
		else{
			return -1;// error
		}
	}

	//글삭제
	public function mkRemove($hometown, $unique_etc){
		//댓글 지우기
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $this->access_token, $this->access_token_secret);
		$url = 'https://apis.daum.net/yozm/v1_0/message/delete.json';
		$args = array('msg_id'=>$unique_etc);
		$response = $to->OAuthRequest($url, $args);
		$jsonData=json_decode($response);
		if($jsonData->status==200){
			return 1;
		}
		else{
			return -1;
		}
	}

	//로그인 URL 받아오기
	public function getLoginUrl(){
	}

	//로그아웃 URL 받아오기
	public function getLogoutUrl(){
	}
	//################################################### 별도 Method 필요하지 않음
}
?>