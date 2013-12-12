<?
if(!defined("checksum")) exit;
require_once($mkadd_dir."twitter/twitteroauth.class.php");

class Mktwitter implements MkAddInterface{
	private static $consumer_key = 'AfPAbfkqonqTaihSXtCZA';
	private static $consumer_secret = 'K9B0kPJO6XRVITd8eeSwXdHW6f0C03hTq57E4eARo';
	private static $callback_url = 'http://220.73.139.94/mkadd/action_auth_twitter.php';

	//������ �Լ�
	public function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null ){
		$this->request_token=$request_token;
		$this->request_token_secret=$request_token_secret;
		$this->access_token=$access_token;
		$this->access_token_secret=$access_token_secret;
	}

	//����Ȯ��
	public function checkAuth(){
		if(empty($this->request_token) || empty($this->request_token_secret) || empty($this->access_token) || empty($this->access_token_secret) ){
			return -1;
		}
		else{
			return 1;
		}
	}

	//���� �޾ƿ��� for request token
	public function goAuth(){
		/* consumer key, consumer secret �̿��Ͽ� twitterOauth ��ü ���� */
		$connection = new TwitterOAuth(self::$consumer_key, self::$consumer_secret);
		/* request token �޾ƿ� */
		$request_token = $connection->getRequestToken(self::$callback_url);
		if($connection->http_code==200){
			$result=array();
			$result['request_token']['oauth_token']=$request_token['oauth_token'];
			$result['request_token']['oauth_token_secret']=$request_token['oauth_token_secret'];
			$result['user_auth_url']=$connection->getAuthorizeURL($request_token['oauth_token']);
			return $result;
		}
		else{
			return false;
		}
	}

	//���� �޾ƿ���2 for access token
	public function goAuth2($oauth_token=null, $oauth_verifier=null, $c=null){
		$connection = new TwitterOAuth(self::$consumer_key, self::$consumer_secret,$this->request_token, $this->request_token_secret);
		$result = $connection->getAccessToken($oauth_verifier);
		return $result;
	}

	//����� ���� ������ ����
	public function getUserInfo(){
		$connection = new TwitterOAuth(self::$consumer_key, self::$consumer_secret,$this->access_token, $this->access_token_secret);
		$content = $connection->get('account/verify_credentials');
		$result=array();
		$result['userid']=$content->id_str;
		$result['usernick']=$content->name;
		$result['userpic']=$content->profile_image_url;
		return $result;
	}


	//�۵��
	public function mkWrite($str){
		$connection = new TwitterOAuth(self::$consumer_key, self::$consumer_secret,$this->access_token, $this->access_token_secret);
		$response=$connection->post('statuses/update', array('status' => $str));
		if($response->id_str>0){
			return $response->id_str; // ok
		}
		else{
			return -1;	// error
		}
	}

	//�ۻ���
	public function mkRemove($hometown, $unique_etc){
		$connection = new TwitterOAuth(self::$consumer_key, self::$consumer_secret,$this->access_token, $this->access_token_secret);
		$response=$connection->post('statuses/destroy', array('id' => $unique_etc));
		$a=null;
		if(isset($response->error)){$a=$response->error;}
		if(strlen($a)==0){
			return 1;		// ok
		}
		else {
			return -1;		// error
		}
	}

	//�α��� URL �޾ƿ���
	public function getLoginUrl(){
	}

	//�α׾ƿ� URL �޾ƿ���
	public function getLogoutUrl(){
	}
}

?>