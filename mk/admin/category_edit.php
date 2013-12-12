<?
/*
카테고리관리 > 카테고리등록/수정
*/
//-------------------------------------------------------- 기본 변수 체크
if(!defined("checksum")) exit;
$page=isset($_GET['page'])?intval($_GET['page']):0;
$_id=isset($_GET['_id'])?trim($_GET['_id']):null;

//-------------------------------------------------------- 데이타 불러오기
$cls_cat= new MkAddCateDB();
$info=array();
if(isset($_id)){
	$_id=$cls_cat->getMongoId($_id);	// id로 검색하려면 반드시 사용해야 함
	if($admin_auth=='S'){
		$info=$cls_cat->lists(null, array("_id"=>$_id), 0, 1);
	}
	else{
		$info=$cls_cat->lists(null, array("app_id"=>$app_id, "_id"=>$_id), 0, 1);
	}
	$info=$info[0];
}
?>

<script type='text/javascript'>
function doRemove(){
	if(confirm('삭제하시겠습니까?')){
		document.getElementsByName('cmd')[0].value='action_category_remove';
		thisform.submit();
	}
}
function addSubtitle(){
	var el_id='addSubtitle';
	var i=1;
	while(document.getElementById(el_id+i)!=null){
		i++;
	}
	var prev_idx=el_id+(i-1);
	var next_idx=el_id+i;
	var html="<br/><input type='text' name='subtitle[]' id='"+next_idx+"' style='width:100px;' value='' />";
	var a=$("#addSubtitleDiv").append(html);
}
function doRegist(){
	var category_name=document.getElementsByName('category_name')[0];
	if(category_name.value!=''){
		document.getElementsByName('cmd')[0].value='action_category_regist';
		document.getElementsByName('thisform')[0].submit();
	}
	else{
		category_name.focus();
		alert('카테고리 이름을 입력해 주세요.');
		return false;
	}
}
</script>

<?
echo "
<form name='thisform' method='post' action='./action_admin.php'>
<input type='hidden' name='cmd' value='' />
<input type='hidden' name='_id' value='".$_id."' />
<br/><br/>
<table border='0' width='600' align='center'>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702'><b>| 카테고리 기본정보</b></font></td>
	</tr>
	<tr>
		<td>
			<!--인적사항-->
			<table border='1' width='100%' bordercolor='#b4b4b4'> 
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >카테고리고유번호</td>
					<td style='padding:10px;' colspan='3'>".(isset($info['category_no'])?$info['category_no']:"")."</td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >카테고리 이름</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:100%;' name='category_name' value=\"".(isset($info['category_name'])?$info['category_name']:"")."\" /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >서브카테고리</td>
					<td style='padding:10px;' colspan='3'>
						<div id='addSubtitleDiv'>";
$i=0;
if(!empty($info['category_sub'])){
	$max=count($info['category_sub']);
	for($i=0;$i<$max;$i++){
		echo "<input type='text' name='subtitle[]' id='addSubtitle".$i."' value='".$info['category_sub'][$i]['sub_category_name']."'  style='width:100px;'/><br/>";
	}
}
echo "
						<input type='text' name='subtitle[]' id='addSubtitle".($i)."' value=''  style='width:100px;'/>&nbsp;<input type='button' value='서브카테고리 추가' id='addSubtitleBtn' onclick='addSubtitle()' class='btn_style1' />
						</div>
					</td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >작성일</td>
					<td style='padding:10px;' colspan='3'>".(isset($info['category_regdate'])?date('Y-m-d H:i:s',$info['category_regdate']):"")."</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width='100%'>
				<tr>
					<td align='left' style='padding:10px;'><input type='button' value='목록' onclick=\"location.href='./index_admin.php?mn1=category';\"/></td>
					<td align='right' style='padding:10px;'>
						<input type='button' value='확인' onclick='doRegist()'/>&nbsp;&nbsp;&nbsp;
						<input type='button' value='삭제' onclick='doRemove()'/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>";
?>