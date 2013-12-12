<?
/*
필터링관리 > 아이디 목록관리
*/
if(!defined("checksum")) exit;
$cls_filter= new MkFilterDB();
$total_no=0;
$lists=$cls_filter->lists(null, array("app_id"=>$_SESSION['app_id']), null, null, $total_no, array('filter_uid_hometown'=>'-1'));
$options=null;
foreach($lists as $key=>$val){
	$options.="<option value='".$val['_id']."'>".$val['filter_uid_hometown']." : ".$val['filter_uid']."</option>";
}
?>
<script type='text/javascript'>
function doRemove(){
	if($("#filter_id_lists option:selected").val()==null || $("#filter_id_lists option:selected").val()==''){
		alert('삭제할 아이디를 선택해 주세요.');
		$("#filter_id_lists").focus();
	}
	else{
		if(confirm('해당 아이디를 삭제하시겠습니까?')){
			$("input[name='cmd']").val("action_remove_filtering_id");
			$("form[name='thisform']").submit();
		}
	}
}
function doRegist(){
	if($("input[name='choice_hometown']:checked").val()=='' || $("input[name='choice_hometown']:checked").val()==null){
		alert('대표 계정를 선택해 주세요.');
		$("input[name='choice_hometown']").focus();
		return;
	}
	else if($("input[name='id_regist']").val()=='' || $("input[name='id_regist']").val()==null){
		alert('등록할 아이디를 입력해 주세요.');
		$("input[name='id_regist']").focus();
		return;
	}
	else{
		$("input[name='cmd']").val("action_add_filtering_id");
		$("#filter_id_lists").hide();
		$("#filter_id_lists option").each(function(){$(this).attr("selected",true);});
		$("form[name='thisform']").submit();
	}
}
</script>
<br/><br/>
<link href='../common/css/calendar.css' rel='stylesheet' type='text/css' />
<form name='thisform' method='post' action='./action_admin.php'>
<input type='hidden' name='cmd' value=''/>
<table border='0' width='90%' bordercolor='#b4b4b4' align='center'>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702' size='3'><b>| 금지 아이디</b></font></td>
	</tr>
	<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr>
		<td>
			<table border='0' width='100%' >
				<tr valign='top'>
					<td style='padding:20px;' width='20%'>
						<table width='100%' border='0'>
							<tr><td><input type='radio' name='choice_hometown' value='<?=$mkadd_company[0]?>'/>&nbsp;<img src='http://img.mk.co.kr/sns/b_mk.png' /></td></tr>
							<tr><td><input type='radio' name='choice_hometown' value='<?=$mkadd_company[1]?>'/>&nbsp;<img src='http://img.mk.co.kr/sns/b_twitter.png' /></td></tr>
							<tr><td><input type='radio' name='choice_hometown' value='<?=$mkadd_company[2]?>'/>&nbsp;<img src='http://img.mk.co.kr/sns/b_face_book.png' /></td></tr>
							<tr><td><input type='radio' name='choice_hometown' value='<?=$mkadd_company[3]?>'/>&nbsp;<img src='http://img.mk.co.kr/sns/b_thesedays.png' /></td></tr>
							<tr><td><input type='radio' name='choice_hometown' value='<?=$mkadd_company[4]?>'/>&nbsp;<img src='http://img.mk.co.kr/sns/b_cyworld.png' /></td></tr>
							<tr><td><input type='radio' name='choice_hometown' value='<?=$mkadd_company[5]?>'/>&nbsp;<img src='http://img.mk.co.kr/sns/b_metoday.png' /></td></tr>
						</table>
						<br/><br/>
						<input type='text' name='id_regist' style='width:150px;' value=''/><br/><br/>
						<input type='button' value='아이디등록'  style='width:150px;height:30px;border:1px solid #000000;' onclick='doRegist()'/>
					</td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td>
						<select name='filter_id_lists[]' id='filter_id_lists' style='width:400px;' size='35' multiple='true'>
						<?=$options?>
						</select>
						<br/><br/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='삭제' style='width:200px;height:30px;border:1px solid #000000;' onclick='doRemove()'/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>