<?
/*
�Ű����Ʈ Ŭ����
request_token �� ����� ���̵� �� �����
request_token_secret�� ����� �̸��� �����
*/
if(!defined("checksum")) exit;

class Mkmaekyung implements MkAddInterface{
	private static $consumer_key ="";
	private static $consumer_secret ="";
	private static $callback_url ="";
	//������ �Լ�
	public function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null ){
		$this->request_token=$request_token;
		$this->request_token_secret=$request_token_secret;
		$this->access_token=$access_token;
		$this->access_token_secret=$access_token_secret;
	}

	//����Ȯ��
	public function checkAuth(){
		//�Ű��� cUserID �� ����Ǿ� �ִ� request_token �ϳ��� Ȯ���Ѵ�
		if(empty($this->request_token) || empty($this->request_token)) {
			return -1;
		}
		else{
			return 1;
		}
	}

	//���� �޾ƿ��� for request token
	public function goAuth(){
		$result['request_token']=null;
		$result['user_auth_url']="https://member.mk.co.kr/etc/chk_mkadd_service.php?firstchk=1&surl=";
		return $result;
	}

	//���� �޾ƿ���2 for access token
	public function goAuth2($a=null, $b=null, $c=null){
		return null;
	}

	//����� ���� ������ ����
	public function getUserInfo(){
		$result['user_id']=$this->request_token;
		$result['user_nick']=$this->request_token_secret;
		$result['user_pic']="http://img.mk.co.kr/main/april/l_main_mklogo1.gif";
		return $result;
	}
	
	//�۵��
	public function mkWrite($str){
		return 200;
	}

	//�ۻ���
	public function mkRemove($hometown, $etc_uid){
	}

	//�α��� URL �޾ƿ���
	public function getLoginUrl(){
		return null;
	}

	//�α׾ƿ� URL �޾ƿ���
	public function getLogoutUrl(){
		$url_logout="http://member.mk.co.kr/member_logoff.php?nextUrl=http://mk.co.kr/";
		return $url_logout;
	}
}
?>