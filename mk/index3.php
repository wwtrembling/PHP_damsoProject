<?
/*
require('./common/MKAdd.conf.php');
if(!defined("checksum")) exit;
require($mkadd_common_dir."MkAddDB.class.php");	// ����DB ��� Ŭ����
require($mkadd_common_dir."MkClickedDB.class.php");	// ����DB ��� ��õ/�ݴ�/�Ű� Ŭ����
require($mkadd_common_dir."MkFilterDB.class.php");	// ����DB �������̵� Ŭ����
require(admin_dir."lib/MkAddMemDB.class.php");	// ����DB ȸ�� ���� Ŭ����
require($mkadd_common_dir."MkAdd.func.php");		//�Ű��� ���� ���� php �Լ�
require_once($mkadd_common_dir."MkAddDB.class.php");	 // �⺻ INTERFACE
$db= new MkAddDB();
$list=$db->lists(array("unique_id"=>"index2"),null, 20, array("bad"=>-1));
foreach($list as $key=>$val) {
	echo "����� : ".$val['regdate'].", good : ".$val['good'].", bad : ".$val['bad']." <br/>";
}
*/
?>
<br/><br/><br/><br/>
<script type='text/javascript' src='http://220.73.139.94/mkadd/common/js/jquery_min.js' charset='utf-8'></script>
<script type='text/javascript' src='http://220.73.139.94/mkadd/common/js/MkAdd.js' charset='utf-8'></script>
<script type='text/javascript' charset='euc-kr'>
var title="�����Դϴ�!!!!";			/*����*/
var uniqueID="index3";	/*������������ȣ*/
var appID="CZYMAL";		/*�Ҵ�Ⱦ� ���̵�*/
var cat_01='����';
var cat_02='��Ÿ';
var fromurl=document.location.href;
MkAdd.init(title, uniqueID, appID, cat_01, cat_02 ,fromurl);
</script>

