<?
/*
Yozm Class
-����Ȯ��
-�����޾ƿ��� for request token
-�����޾ƿ��� for access token
-�ۿø���(db����, ���� ������)
-�ۻ���(db����, ���� ����)
*/

require_once("daumOAuth.class.php"); // DaumOAuth �޾ƿ���
class MkAddClass{
	private static $consumer_key = '7b286ec7-41fc-41e8-92e9-21d764d73c2e';
	private static $consumer_secret = 'WD8ZyPncjZ5iM1yWeC9EwnWwjk25P.mhaZUOnJMARE.3xvLxxO6Tlg00';
	private static $callback_url = 'http://110.13.170.154/tempProject/pkch/action.php';

	//������ �Լ�
	function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null ){
		$this->request_token=$request_token;
		$this->request_token_secret=$request_token_secret;
		$this->access_token=$access_token;
		$this->access_token_secret=$access_token_secret;
	}

	//����Ȯ��
	function checkAuth(){
		if(empty($this->request_token) || empty($this->request_token) || empty($this->request_token) || empty($this->request_token) ){
			return false;
		}
		else{
			return true;
		}
	}

	//���� �޾ƿ��� for request token
	function goAuth(){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url);
		$rTok = $to->getRequestToken();
		$url = $to->getAuthorizeURL($rTok);
		$result=array();
		$result['request_token']=$rTok;
		$result['user_auth_url']=$url;
		return $result;
	}

	//���� �޾ƿ���2 for access token
	function goAuth2($o_token, $o_verifier, $request_token_secret){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $o_token,$request_token_secret);
		$aTok = @$to->getAccessToken($o_verifier);
		return $aTok;
	}
	//�� ������ ���� >> �Ŀ� ����� ������
	function mkLoad($access_token, $access_token_secret){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $access_token, $access_token_secret);
		$url = 'https://apis.daum.net/yozm/v1_0/timeline/home.json';
		$args = array('count'=>20);
		$response = json_decode($to->OAuthRequest($url, $args));
		return $response;
	}

	//�ۿø��� >> �Ŀ� ����� ������
	function mkWrite($access_token, $access_token_secret, $str){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $access_token, $access_token_secret);
		$url = 'https://apis.daum.net/yozm/v1_0/message/add.json';
		$args = array('message'=>$str);
		$response = $to->OAuthRequest($url, $args);
		return $response;
	}

	//�ۻ���
	function mkRemove($access_token, $access_token_secret, $msg_id){
		$to = new DaumOAuth(self::$consumer_key, self::$consumer_secret, self::$callback_url, $access_token, $access_token_secret);
		$url = 'https://apis.daum.net/yozm/v1_0/message/delete.json';
		$args = array('msg_id'=>$msg_id);
		$response = $to->OAuthRequest($url, $args);
		return $response;
	}
}
?>