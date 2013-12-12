<?
/*
댓글관리 > 댓글수정 form
*/
//-------------------------------------------------------- 기본 변수 체크
if(!defined("checksum")) exit;
$page=isset($_GET['page'])?intval($_GET['page']):0;
$_id=htmlspecialchars(trim($_GET['_id']));
$rtnmn1=isset($_GET['rtnmn1'])?trim($_GET['rtnmn1']):null;
$rtnmn2=isset($_GET['rtnmn2'])?trim($_GET['rtnmn2']):null;

//-------------------------------------------------------- 데이터 불러오기
$cls= new MkAddDB();
$info=array();
$_id=$cls->getMongoId($_id);	// id로 검색하려면 반드시 사용해야 함
if($admin_auth=='S'){
	$info=$cls->lists(null, array("_id"=>$_id), 0, 1);
}
else{
	$info=$cls->lists(null, array("app_id"=>$app_id, "_id"=>$_id), 0, 1);
}
$info=$info[0];
if(empty($info)){showAlert('No Auth');goUrl('back');}

echo "
<script type='text/javascript'>
function doRemoveForever(){
	if(confirm('해당 댓글을 영구삭제하시겠습니까?(복구 불가)')){
		document.getElementsByName('cmd')[0].value='action_remove';
		thisform.submit();
	}
}
function doRemove(){
	if(confirm('삭제하시겠습니까?')){
		document.getElementsByName('cmd')[0].value='action_remove_fake';
		thisform.submit();
	}
}
</script>
<form name='thisform' method='post' action='./action_admin.php'>
<input type='hidden' name='cmd' value='' />
<input type='hidden' name='_id' value='".$_id."' />
</form>
<br/><br/>
<table border='0' width='800' align='center'>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702'><b>| 댓글 기본정보</b></font></td>
	</tr>
	<tr>
		<td>
			<!--인적사항-->
			<table border='1' width='100%' bordercolor='#b4b4b4'>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >뉴스제목</td>
					<td style='padding:10px;' colspan='3'>".(isset($info['title'])?$info['title']:"")."</td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >구분</td>
					<td style='padding:10px;' colspan='3'>";
$gubun=null;
if(isset($info['unique_maekyung']) && !empty($info['unique_maekyung'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_mk.png' />&nbsp;&nbsp;&nbsp;&nbsp;";}
if(isset($info['unique_twitter']) && !empty($info['unique_twitter'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_twitter.png' />&nbsp;&nbsp;&nbsp;&nbsp;";}
if(isset($info['unique_facebook']) && !empty($info['unique_facebook'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_face_book.png' />&nbsp;&nbsp;&nbsp;&nbsp;";}
if(isset($info['unique_metoday']) && !empty($info['unique_metoday'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_metoday.png' />&nbsp;&nbsp;&nbsp;&nbsp;";}
if(isset($info['unique_yozm']) && !empty($info['unique_yozm'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_thesedays.png' />&nbsp;&nbsp;&nbsp;&nbsp;";}
if(isset($info['unique_cyworld']) && !empty($info['unique_cyworld'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_cyworld.png' />&nbsp;&nbsp;&nbsp;&nbsp;";}
echo $gubun;
echo "
					</td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >작성자</td>
					<td style='padding:10px;' colspan='3'>".(isset($info['user_name'])?$info['user_id']."(".$info['user_name'].")":"")."</td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >작성일</td>
					<td style='padding:10px;' colspan='3'>".(isset($info['regdate'])?date('Y-m-d H:i:s',$info['regdate']):"")."</td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >카테고리</td>
					<td style='padding:10px;'>".(isset($info['cat_01'])?$info['cat_01']:"")."</td>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >서브 카테고리</td>
					<td style='padding:10px;'>".(isset($info['cat_02'])?$info['cat_02']:"")."</td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >추천/반대</td>
					<td style='padding:10px;'>".(isset($info['good'])?$info['good']:"")." / ".(isset($info['bad'])?$info['bad']:"")."</td>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >신고</td>
					<td style='padding:10px;'><font color='red'>".(isset($info['bad2'])?$info['bad2']:"")."</font></td>
				</tr>
				<tr>
					<td style='padding:20px;' colspan='4'>".(isset($info['text'])?str_replace("\n","<br/>",$info['text']):"")."</td>
				</tr>
			</table>
		</td>
	</tr>";
//삭제 후 보여지는 파트
if(isset($info['usage']) && $info['usage']=='N'){
	echo "
	<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702'><b>| 댓글 삭제정보</b></font></td>
	</tr>
	<tr>
		<td>
			<table border='1' width='100%' bordercolor='#b4b4b4'>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >삭제일</td>
					<td style='padding:10px;' colspan='3'>".(isset($info['removedate'])?date('Y-m-d',$info['removedate']):"")."</td>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >삭제유형</td>
					<td style='padding:10px;' colspan='3'>".(isset($info['remove_type'])?$info['remove_type']:"")."</td>
				</tr>
			</table>
		</td>
	</tr>";
}
echo "
	<tr>
		<td>
			<table width='100%'>
				<tr>
					<td align='left' style='padding:10px;'><input type='button' value='목록' onclick=\"location.href='./index_admin.php?page=".$page."&".((!empty($rtnmn1))?"&mn1=".$rtnmn1:"").((!empty($rtnmn2))?"&mn2=".$rtnmn2:"")."';\"/></td>";
if(isset($info['usage']) && $info['usage']=='Y'){
	echo "
					<td align='right' style='padding:10px;'><input type='button' value='삭제' onclick='doRemove()'/></td>";
}
echo "
					<td align='right' style='padding:10px;'><input type='button' value='영구삭제' onclick='doRemoveForever()'/></td>
				</tr>
			</table>
		</td>
	</tr>
</table>";
?>