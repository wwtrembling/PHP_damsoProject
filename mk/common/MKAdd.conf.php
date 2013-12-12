<?
/*
***필수!!! 경로를 수정할 경우에는 아래 페이지에서 URL 경로를 수정해야 함
1./mk/common/MkAdd.js
2./Data/web/mkadd/twitter/Mktwitter.class.php
3./Data/web/mkadd/facebook/Mkfacebook.class.php
4.페이스북 개발자 페이지 >> 앱 >> 설정 >> 웹사이트 경로 : parksol@mkinternet.com // 매경닷컴
5./Data/web/mkadd/cyworld/Mkcyworld.class.php
6.네이버 개발자 페이지 >> 경로 수정
7.다음 오픈API >> OAuth 관리 >> CallBack 경로 수정
*/

define('admin_url',"http://110.13.170.154/tempProject/mk/admin/");
define('admin_dir',"D:\\svc\\web\\tempProject\\mk\\admin/");
define('common_url',"http://110.13.170.154/tempProject/mk/common/");
define('common_dir',"D:\\svc\\web\\tempProject\\mk\\common/");
define('js_url',"http://110.13.170.154/tempProject/mk/admin/js/");
define('upload_dir',"/Data/upload/");
define("checksum","mkadd");

$mkadd_dir="D:\\svc\\web\\tempProject\\mk\\";
$mkadd_common_dir="D:\\svc\\web\\tempProject\\mk\\common\\";
$mkadd_url="http://110.13.170.154/tempProject/mk/";
$mkadd_company=array("maekyung","twitter","facebook","yozm","cyworld","metoday");	 // 로그인 체크해야 할 댓글 서비스 종류

//기본 class
require($mkadd_common_dir.'MkMongoDB.class.php');
require($mkadd_common_dir."MkAddInterface.php");
?>