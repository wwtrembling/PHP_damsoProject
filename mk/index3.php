<?
/*
require('./common/MKAdd.conf.php');
if(!defined("checksum")) exit;
require($mkadd_common_dir."MkAddDB.class.php");	// 몽고DB 댓글 클래스
require($mkadd_common_dir."MkClickedDB.class.php");	// 몽고DB 댓글 추천/반대/신고 클래스
require($mkadd_common_dir."MkFilterDB.class.php");	// 몽고DB 금지아이디 클래스
require(admin_dir."lib/MkAddMemDB.class.php");	// 몽고DB 회원 관리 클래스
require($mkadd_common_dir."MkAdd.func.php");		//매경댓글 서비스 공통 php 함수
require_once($mkadd_common_dir."MkAddDB.class.php");	 // 기본 INTERFACE
$db= new MkAddDB();
$list=$db->lists(array("unique_id"=>"index2"),null, 20, array("bad"=>-1));
foreach($list as $key=>$val) {
	echo "등록일 : ".$val['regdate'].", good : ".$val['good'].", bad : ".$val['bad']." <br/>";
}
*/
?>
<br/><br/><br/><br/>
<script type='text/javascript' src='http://220.73.139.94/mkadd/common/js/jquery_min.js' charset='utf-8'></script>
<script type='text/javascript' src='http://220.73.139.94/mkadd/common/js/MkAdd.js' charset='utf-8'></script>
<script type='text/javascript' charset='euc-kr'>
var title="제목입니다!!!!";			/*제목*/
var uniqueID="index3";	/*페이지고유번호*/
var appID="CZYMAL";		/*할당된앱 아이디*/
var cat_01='뉴스';
var cat_02='기타';
var fromurl=document.location.href;
MkAdd.init(title, uniqueID, appID, cat_01, cat_02 ,fromurl);
</script>

