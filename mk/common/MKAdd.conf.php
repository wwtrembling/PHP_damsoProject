<?
/*
***�ʼ�!!! ��θ� ������ ��쿡�� �Ʒ� ���������� URL ��θ� �����ؾ� ��
1./mk/common/MkAdd.js
2./Data/web/mkadd/twitter/Mktwitter.class.php
3./Data/web/mkadd/facebook/Mkfacebook.class.php
4.���̽��� ������ ������ >> �� >> ���� >> ������Ʈ ��� : parksol@mkinternet.com // �Ű����
5./Data/web/mkadd/cyworld/Mkcyworld.class.php
6.���̹� ������ ������ >> ��� ����
7.���� ����API >> OAuth ���� >> CallBack ��� ����
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
$mkadd_company=array("maekyung","twitter","facebook","yozm","cyworld","metoday");	 // �α��� üũ�ؾ� �� ��� ���� ����

//�⺻ class
require($mkadd_common_dir.'MkMongoDB.class.php');
require($mkadd_common_dir."MkAddInterface.php");
?>