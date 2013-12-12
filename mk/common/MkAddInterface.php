<?php
interface MkAddInterface{
	//생성자 함수
	public function __construct( $request_token=null, $request_token_secret=null, $access_token=null, $access_token_secret=null );

	//인증확인
	public function checkAuth();

	//인증 받아오기 for request token
	public function goAuth();

	//인증 받아오기2 for access token
	public function goAuth2($a=null, $b=null, $c=null);

	//사용자 정보 가지고 오기
	public function getUserInfo();
	
	//글등록
	public function mkWrite($str);

	//글삭제
	public function mkRemove($hometown, $etc_uid);

	//로그인 URL 받아오기
	public function getLoginUrl();

	//로그아웃 URL 받아오기
	public function getLogoutUrl();
}


?>