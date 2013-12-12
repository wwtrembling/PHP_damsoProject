<?
/*
매경사이트 클래서
request_token 에 사용자 아이디 가 저장됨
request_token_secret에 사용자 이름이 저장됨
*/
if(!defined("checksum")) exit;

class Mkmaekyung implements MkAddInterface{
	private static $consumer_key ="";
	private static $consumer_secret ="";
	private static $callback_url ="";
	//생성자 함수
	public function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null ){
		$this->request_token=$request_token;
		$this->request_token_secret=$request_token_secret;
		$this->access_token=$access_token;
		$this->access_token_secret=$access_token_secret;
	}

	//인증확인
	public function checkAuth(){
		//매경은 cUserID 가 저장되어 있는 request_token 하나만 확인한다
		if(empty($this->request_token) || empty($this->request_token)) {
			return -1;
		}
		else{
			return 1;
		}
	}

	//인증 받아오기 for request token
	public function goAuth(){
		$result['request_token']=null;
		$result['user_auth_url']="https://member.mk.co.kr/etc/chk_mkadd_service.php?firstchk=1&surl=";
		return $result;
	}

	//인증 받아오기2 for access token
	public function goAuth2($a=null, $b=null, $c=null){
		return null;
	}

	//사용자 정보 가지고 오기
	public function getUserInfo(){
		$result['user_id']=$this->request_token;
		$result['user_nick']=$this->request_token_secret;
		$result['user_pic']="http://img.mk.co.kr/main/april/l_main_mklogo1.gif";
		return $result;
	}
	
	//글등록
	public function mkWrite($str){
		return 200;
	}

	//글삭제
	public function mkRemove($hometown, $etc_uid){
	}

	//로그인 URL 받아오기
	public function getLoginUrl(){
		return null;
	}

	//로그아웃 URL 받아오기
	public function getLogoutUrl(){
		$url_logout="http://member.mk.co.kr/member_logoff.php?nextUrl=http://mk.co.kr/";
		return $url_logout;
	}
}
?>