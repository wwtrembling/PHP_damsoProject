<?
/*
function microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

$a=microtime_float();
require('./common/MKAdd.conf.php');
if(!defined("checksum")) exit;
require($mkadd_common_dir."MkAddDB.class.php");	// ����DB ��� Ŭ����
require($mkadd_common_dir."MkClickedDB.class.php");	// ����DB ��� ��õ/�ݴ�/�Ű� Ŭ����
require($mkadd_common_dir."MkFilterDB.class.php");	// ����DB �������̵� Ŭ����
require(admin_dir."lib/MkAddMemDB.class.php");	// ����DB ȸ�� ���� Ŭ����
require($mkadd_common_dir."MkAdd.func.php");		//�Ű��� ���� ���� php �Լ�
require_once($mkadd_common_dir."MkAddDB.class.php");	 // �⺻ INTERFACE

//$cond=array("unique_id"=>"index2","usage"=>"Y");
$cond=array("usage"=>"Y");
$maxlist=20;
$page=(isset($_GET['page']))?intval($_GET['page']):0;
$fields=array("user_name"=>1,"user_pic"=>1,"text"=>1,"regdate"=>1,"good"=>1,"bad"=>1,"hometown"=>1);
$db= new MkAddDB();
$list=$db->lists($fields, $cond,$page, $maxlist, array("regdate"=>-1));
$total_no=$db->listsCount($cond);
foreach($list as $key=>$val) {
	//echo "<pre>";var_dump($val);echo "</pre>";
}
$b=microtime_float();
$c=$b-$a;
echo "�� ����Ÿ ���� : ".number_format($total_no)." , ".number_format($c,2)."�� �ҿ�<br/><br/>";

include($mkadd_common_dir.'pager.class.php');
$pager= new pager($page, $total_no, $maxlist);
$pager->stroke();
*/
?>
<br/><br/><br/><br/>
<script type='text/javascript' src='http://110.13.170.154/tempProject/mk/common/js/jquery_min.js' charset='utf-8'></script>
<script type='text/javascript' src='http://110.13.170.154/tempProject/mk/common/js/MkAdd_mysql.js' charset='utf-8'></script>
<script type='text/javascript' charset='euc-kr'>
var title="�����Դϴ�!!!!";			/*����*/
var uniqueID="index";	/*������������ȣ*/
var appID="CZYMAL";		/*�Ҵ�Ⱦ� ���̵�*/
var cat_01='����';
var cat_02='��Ÿ';
var fromurl=document.location.href;
MkAdd.init(title, uniqueID, appID, cat_01, cat_02 ,fromurl);
</script>

