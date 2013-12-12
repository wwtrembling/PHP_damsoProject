<?
/*
MkFaceBook Class
-인증확인
-인증받아오기 for request token
-인증받아오기 for access token
-글올리기(db삽입, 파일 떨구기)
-글삭제(db수정, 파일 수정)
*/
include("base_facebook.class.php");

class MkAddClass extends BaseFacebook{
	private static $consumer_key = '268567429874202';					// App Id
	private static $consumer_secret = '509096dc5b91682e04d8e8b7a0634fcb';	// App Secret
	private static $callback_url = 'http://110.13.170.154/tempProject/pkch/facebook/facebook_action.php';

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
		if(empty($this->request_token) || empty($this->request_token) || empty($this->request_token) || empty($this->request_token) ){
			return false;
		}
		else{
			return true;
		}
	}

	//인증 받아오기 for request token
	function goAuth(){
		parent::__construct(array("appId"=>self::$consumer_key, "apiSecret"=>self::$consumer_secret));
		$loginUrl = $this->getLoginUrl(array('scope'=>'read_stream, publish_stream'));
		$result=array("user_auth_url"=>$loginUrl);
		return $result;
	}

	//인증 받아오기2 for access token
	function goAuth2($o_token, $o_verifier, $request_token_secret){
	}
	//글 가지고 오기 >> 후에 변경될 예정임
	function mkLoad($access_token, $access_token_secret){
	}

	//글올리기 >> 후에 변경될 예정임
	function mkWrite($access_token, $access_token_secret, $str){
	}

	//글삭제
	function mkRemove($access_token, $access_token_secret, $msg_id){
	}


	//###################################################이하는 Default Method를 정의한 것임
	protected static $kSupportedKeys =array('state', 'code', 'access_token', 'user_id');
	protected function setPersistentData($key, $value) {
	if (!in_array($key, self::$kSupportedKeys)) {
		self::errorLog('Unsupported key passed to setPersistentData.');
		return;
	}

	$session_var_name = $this->constructSessionVariableName($key);
	$_SESSION[$session_var_name] = $value;
	}

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
		return implode('_', array('fb', $this->getAppId(), $key));
	}
}
?>