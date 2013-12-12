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
require($mkadd_common_dir."MkAddDB.class.php");	// 몽고DB 댓글 클래스
require($mkadd_common_dir."MkClickedDB.class.php");	// 몽고DB 댓글 추천/반대/신고 클래스
require($mkadd_common_dir."MkFilterDB.class.php");	// 몽고DB 금지아이디 클래스
require(admin_dir."lib/MkAddMemDB.class.php");	// 몽고DB 회원 관리 클래스
require($mkadd_common_dir."MkAdd.func.php");		//매경댓글 서비스 공통 php 함수
require_once($mkadd_common_dir."MkAddDB.class.php");	 // 기본 INTERFACE

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
echo "총 데이타 갯수 : ".number_format($total_no)." , ".number_format($c,2)."초 소요<br/><br/>";

include($mkadd_common_dir.'pager.class.php');
$pager= new pager($page, $total_no, $maxlist);
$pager->stroke();
*/
?>
<br/><br/><br/><br/>
<script type='text/javascript' src='http://110.13.170.154/tempProject/mk/common/js/jquery_min.js' charset='utf-8'></script>
<script type='text/javascript' src='http://110.13.170.154/tempProject/mk/common/js/MkAdd_mysql.js' charset='utf-8'></script>
<script type='text/javascript' charset='euc-kr'>
var title="제목입니다!!!!";			/*제목*/
var uniqueID="index";	/*페이지고유번호*/
var appID="CZYMAL";		/*할당된앱 아이디*/
var cat_01='뉴스';
var cat_02='기타';
var fromurl=document.location.href;
MkAdd.init(title, uniqueID, appID, cat_01, cat_02 ,fromurl);
</script>

