<?
/*
페이스북은 MkFacebook.class.php 에서 세션을 생성하게 된다.
array('state', 'code', 'access_token', 'user_id') 는 아래 배열과 매치된다.
array('request_token', 'request_token_secret', 'access_token', 'access_token_secret');
*/

if(!defined("checksum")) exit;
require_once($mkadd_dir."/facebook/base_facebook.class.php");

class Mkfacebook extends BaseFacebook implements MkAddInterface{
	private static $consumer_key = '431908396835542';					// App Id >> facebook.com // parksol@mkinternet.com // 비밀번호는 기획팀에 문의
	private static $consumer_secret = '364cc836b59d564cea400ec47d168de6';	// App Secret
	private static $callback_url = 'http://220.73.139.94/mkadd/action_auth_facebook.php';

	//################################################### MK 기본 common 함수 모음
	//생성자 함수
	function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null ){
		$this->request_token=$request_token;
		$this->request_token_secret=$request_token_secret;
		$this->access_token=$access_token;
		$this->access_token_secret=$access_token_secret;
	}

	//인증확인
	function checkAuth(){
		//if(empty($this->request_token) || empty($this->request_token_secret) || empty($this->access_token) || empty($this->access_token_secret) ){} // 페북은 교체함
		if(empty($this->request_token_secret) || empty($this->access_token) || empty($this->access_token_secret) ){
			return -1;
		}
		else{
			return 1;
		}
	}

	//인증 받아오기 for request token
	function goAuth(){
		parent::__construct(array("appId"=>self::$consumer_key, "secret"=>self::$consumer_secret));
	}

	//인증 받아오기2 for access token
	function goAuth2($a=null, $b=null, $c=null){
	}

	//사용자 정보 받아오기
	function getUserInfo(){
		$user_profile = $this->api('/me');
		$result=array();
		$result['user_id']=$user_profile['id'];
		$result['user_nick']=$user_profile['name'];
		$result['user_pic']="http://graph.facebook.com/".$user_profile['id']."/picture";
		return $result;
	}

	//글올리
	function mkWrite($str){
		try {
			$rtn_id=$this->api('/me/feed','POST',
				array( 
					'message' =>$str
				)
			);
		} catch(FacebookApiException $e) {
			$result = $e->getResult();
			//error_log(json_encode($result));
		}
		//mk DB에 저장
		$rtn_id=$rtn_id['id'];
		if($rtn_id>0){
			return $rtn_id;	// ok
		}
		else{
			return -1;	// error
		}
	}

	//글삭제
	function mkRemove($hometown, $etc_uid){
		//facebook 글 삭제
		$result=$this->api("/".$etc_uid,"DELETE");
		if($result==true){
			return 1;
		}
		else{
			return -1;
		}
	}

	//로그인 URL 받아오기
	function getLoginUrl(){
		$loginUrl = parent::getLoginUrl(array('scope'=>'read_stream, publish_stream'));
		return $loginUrl;
	}

	//로그아웃 URL 받아오기
	function getLogoutUrl(){
		$user = $this->getUser();
		if($user){
			$logoutUrl = $facebook->getLogoutUrl();
		}
	}

	//###################################################별도 Method 를 정의한 것임
		
	function getConsumer_key(){
		return self::$consumer_key;
	}
	
	//protected static $kSupportedKeys =array('state', 'code', 'access_token', 'user_id');
	protected static $kSupportedKeys = array('request_token', 'request_token_secret', 'access_token', 'access_token_secret');
	protected function setPersistentData($key, $value) {
		if (!in_array($key, self::$kSupportedKeys)) {
			self::errorLog('Unsupported key passed to setPersistentData.');
			return;
		}

		$session_var_name = $this->constructSessionVariableName($key);
		$_SESSION[$session_var_name] = $value;
	}

	//세션에서 값을 찾아서 리턴한다
	protected function getPersistentData($key, $default = false) {
		if (!in_array($key, self::$kSupportedKeys)) {
			self::errorLog('Unsupported key passed to getPersistentData.');
			return $default;
		}
		$session_var_name = $this->constructSessionVariableName($key);
		return isset($_SESSION[$session_var_name]) ? $_SESSION[$session_var_name] : $default;
	}

	protected function clearPersistentData($key) {
		if (!in_array($key, self::$kSupportedKeys)) {
			self::errorLog('Unsupported key passed to clearPersistentData.');
			return;
		}
		$session_var_name = $this->constructSessionVariableName($key);
		unset($_SESSION[$session_var_name]);
	}

	protected function clearAllPersistentData() {
		foreach (self::$kSupportedKeys as $key) {
		  $this->clearPersistentData($key);
		}
	}

	protected function constructSessionVariableName($key) {
		//여기부터는 새롭게 추가함 by pkch at 2012.2.6
		//return implode('_', array('fb', $this->getAppId(), $key));
		$rtn="facebook_".$key;
		return $rtn;
	}
}
?>