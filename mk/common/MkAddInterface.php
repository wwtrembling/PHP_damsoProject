<?php
interface MkAddInterface{
	//������ �Լ�
	public function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null );

	//����Ȯ��
	public function checkAuth();

	//���� �޾ƿ��� for request token
	public function goAuth();

	//���� �޾ƿ���2 for access token
	public function goAuth2($a=null, $b=null, $c=null);

	//����� ���� ������ ����
	public function getUserInfo();
	
	//�۵��
	public function mkWrite($str);

	//�ۻ���
	public function mkRemove($hometown, $etc_uid);

	//�α��� URL �޾ƿ���
	public function getLoginUrl();

	//�α׾ƿ� URL �޾ƿ���
	public function getLogoutUrl();
}


?>