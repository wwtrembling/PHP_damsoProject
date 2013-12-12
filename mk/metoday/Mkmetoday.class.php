<?
if(!defined("checksum")) exit;

class Mkmetoday implements MkAddInterface{
	private static $consumer_key = '384b0c0b6d3182e4ab619042c4e6fde9';//미투데이의 A_KEY , naver.com //  mkdev.개발자아이디  // 개발자비밀번호 >> 요청은 기획팀에 할것
	private static $consumer_secret = '';
	private static $callback_url = '';

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
		$get_url="http://me2day.net/api/get_auth_url.json?akey=".self::$consumer_key;
		$tmp=@file_get_contents($get_url);
		$output = json_decode($tmp);
		$result['request_token']['oauth_token']='request_token';
		$result['request_token']['oauth_token_secret']='request_token_secret';
		$result['user_auth_url']=$output->url;
		return $result;
	}

	//인증 받아오기2 for access token
	public function goAuth2($user_id=null, $user_key=null, $authKey=null){
		$get_url="http://me2day.net/api/noop?uid=".$user_id."&ukey=".$authKey."&akey=".self::$consumer_key;
		$result_xml = @file_get_contents($get_url);
		$result=array();
		if($result_xml==false){
			return false;
		}
		else{
			$result['oauth_token']=$user_id;	// 사용자 아이디
			$result['oauth_token_secret']=$authKey;	// 사용자 고유키
			return $result;
		}
	}

	//사용자 정보 받아오기
	public function getUserInfo(){
		//http://me2day.net/api/get_person/codian.xml
		$get_url="http://me2day.net/api/get_person/".$this->access_token.".json";
		$result_json = @file_get_contents($get_url);
		$result_arr=json_decode($result_json);
		$result=array();
		$result['user_id']=$result_arr->id;
		$result['user_nick']=$result_arr->nickname;	// NICK NAME
		$result['user_pic']=$result_arr->face;	// Png
		return $result;
	}
	
	//글올리기
	public function mkWrite($str){
		$put_url="http://me2day.net/api/create_post/".$this->access_token.".json?uid=".$this->access_token."&ukey=".$this->access_token_secret."&akey=".self::$consumer_key."&post[body]=".trim($str);
		$result_json = @file_get_contents($put_url);
		$result=json_decode($result_json);
		$post_id=isset($result->post_id)?$result->post_id:null;
		if(!empty($post_id) && isset($post_id)){
			return $post_id;	//ok
		}
		else{
			return -1;// error
		}
	}

	//글삭제
	public function mkRemove($hometown, $unique_etc){
		$put_url="http://me2day.net/api/delete_post.json?uid=".$this->access_token."&ukey=".$this->access_token_secret."&akey=".self::$consumer_key."&post_id=".$unique_etc;
		$result_json = @file_get_contents($put_url);
		$result=json_decode($result_json);
		$status=$result->code;
		if($status==0){
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