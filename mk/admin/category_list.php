<?
/*
카테고리관리 > 카테고리목록
*/

//-------------------------------------------------------- 기본 변수 체크
if(!defined("checksum")) exit;
$page=isset($_GET['page'])?intval($_GET['page']):0;

//-------------------------------------------------------- 데이타 불러오기
$cls_cat= new MkAddCateDB();
$lists=array();
$total_no=0;
$maxlist=5;
if($admin_auth=='S'){
	$lists=$cls_cat->lists(null, null, $page, $maxlist);
	$total_no=$cls_cat->listsCount(null);
}
else{
	$lists=$cls_cat->lists(null, array("app_id"=>$app_id), $page, $maxlist);
	$total_no=$cls_cat->listsCount(array("app_id"=>$app_id));
}

//-------------------------------------------------------- 페이징
$pager= new Pager($page, $total_no, $maxlist);
$pager->tags_prev = '<span class="ic_gray1"><span class="icon_g"><</span>이전</span>'; //이전
$pager->tags_next = '<span class="ic_gray1">다음<span class="icon_g">></span></span></a>'; //다음
$pager->tags_begin = '<span class="ic_gray1"><span class="icon_g"><</span>처음</span></a>'; //처음
$pager->tags_end = '<span class="ic_gray1">끝<span class="icon_g">></span></span>'; //마지막
$pager->tags_split2 = "&nbsp;"; // begin과 prev , next와 end 분리자
$pager->tags_split = "&nbsp;"; //페이지 분리자
$pager->tags_current_index='<span class="ic_blue">{index}</span>'; //현재 페이지
$pager->tags_index='<span class="ic_gray">{index}</span>'; //현재 페이지를 제외한 나머지 페이지
$pager->addQuery('mn1',"category");

echo "
<br/><br/>
<script type='text/javascript'>
function doRegist(){
	location.href='./index_admin.php?mn1=category&mn2=category_edit';
}
function doRemove(){
	var thiform=document.getElementsByName('thisform')[0];
	var chkflag=false;
	for(i=0;i<thisform.length;i++){
		var tmp=thisform.elements[i];
		if(tmp.type=='checkbox'){
			if(tmp.checked==true){
				chkflag=true;
			}
		}
	}
	if(chkflag==false){
		alert('댓글을 선택해 주세요.');
		return;
	}
	else{
		if(confirm('해당 댓글을 삭제하시겠습니까?')){
			document.getElementsByName('cmd')[0].value='action_category_remove_multi';
			thisform.submit();
		}
	}
}

function doCheckedAll(){
	var all_checked= document.getElementsByName('all_checked')[0];
	var thiform=document.getElementsByName('thisform')[0];
	var chkflag=all_checked.checked;
	for(i=0;i<thisform.length;i++){
		var tmp= thiform.elements[i];
		tmp.checked=chkflag;
	}
}
</script>
<form name='thisform' method='post' action='./action_admin.php'>
<input type='hidden' name='cmd' value='' />
<table border='0' width='90%' bordercolor='#b4b4b4' align='center'>
	<tr>
		<td><font color='#ff8702' size='3px'><b>| 카테고리 목록</b></font></td>
	</tr>
	<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr>
	<tr>
		<td>
			<table border='0' width='100%'>
				<tr>
					<td width='250'>Total : ".$total_no."&nbsp;&nbsp; Page : ".(($total_no>0)?($page+1)."/".(ceil($total_no/$maxlist)):0)."</td>
				</tr>
				<tr>
					<td height='10px'>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border='1' width='100%' bordercolor='#b4b4b4' align='center'>
				<tr>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' width='3%'><input type='checkbox' name='all_checked' onclick='doCheckedAll()'/></td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>NO</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>카테고리</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>서브카테고리</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>등록일</td>
				</tr>";
$no=$total_no-$page*$maxlist;
foreach($lists as $key=>$val){
	$subcategory="<table width='100%'>";
	if(!empty($val['category_sub']) && is_array($val['category_sub'])){
		foreach($val['category_sub'] as $key2=>$val2){
			$subcategory.="<tr><td>".$val2['sub_category_name']."</td></tr>";
		}
	}
	$subcategory.="</table>";
	$alink="./index_admin.php?mn1=category&mn2=category_edit&_id=".$val['_id']."&page=".$page;
	echo "
				<tr>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'><input type='checkbox' name='removeid__".$val['_id']."' /></td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".($no--)."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'><a href='".$alink."'>".$val['category_name']."</a></td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:15px; text-align:left;'>".$subcategory."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".date('Y-m-d H:i',$val['category_regdate'])."</td>
				</tr>";
}
echo "
			</table>
		</td>
	</tr>
	<tr>
		<td >
			<table border='0' width='100%' bordercolor='#777777'>
				<tr>
					<td style='padding:10px; text-align:right;'>
						<input type='button' value='카테고리등록' onclick='doRegist()' class='button2'/>
						<input type='button' value='삭제' onclick='doRemove()' class='button1'/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div style='text-align:center;height:30px;'>";
if($total_no>0){$pager->stroke();}
echo "
			</div>
		</td>
	</tr>
</table>
</form>";

?>