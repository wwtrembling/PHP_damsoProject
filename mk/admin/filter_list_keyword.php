<?
/*
필터링관리 > 키워드 목록관리
*/
if(!defined("checksum")) exit;
$keyword_file=upload_dir."forbidden_keyword_".$_SESSION['app_id'].".dat";
$options=null;
$a=is_file($keyword_file);
if(is_file($keyword_file)==true){
	$fcontent=file_get_contents($keyword_file);
	$filter_content=explode("^",$fcontent);
	foreach($filter_content as $key=>$val) {
		if(isset($val)  && !empty( $val) ) {$options.="<option value='".trim($val)."'>".trim($val)."</option>";}
	}
}
?>
<script type='text/javascript'>
function doRemove(){
	if($("#filter_lists option:selected").val()==null || $("#filter_lists option:selected").val()==''){
		alert('삭제할 키워드를 선택해 주세요.');
		$("#filter_lists").focus();
	}
	else{
		if(confirm('해당 키워드를 삭제하시겠습니까?')){
			$("input[name='cmd']").val("action_remove_filtering_keyword");
			$("input[name='remove_keyword']").val($("#filter_lists option:selected").val());
			$("#filter_lists").hide();
			$("#filter_lists option").each(function(){
				if($(this).attr("selected")==false){$(this).attr("selected",true);}
				else{$(this).attr("selected",false)}
			});
			$("form[name='thisform']").submit();
		}
	}
}
function doRegist(){
	if($("input[name='keyword_regist']").val()!=''){
		$("input[name='cmd']").val("action_add_filtering_keyword");
		$("#filter_lists").hide();
		$("#filter_lists option").each(function(){$(this).attr("selected",true);});
		$("form[name='thisform']").submit();
	}
	else{
		alert('등록할 키워드를 입력해 주세요.');
		$("input[name='keyword_regist']").focus();
	}
}
</script>
<br/><br/>
<link href='../common/css/calendar.css' rel='stylesheet' type='text/css' />
<form name='thisform' method='post' action='./action_admin.php'>
<input type='hidden' name='cmd' value=''/>
<input type='hidden' name='remove_keyword' value='' />
<table border='0' width='90%' bordercolor='#b4b4b4' align='center'>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702' size='3'><b>| 금지 키워드</b></font></td>
	</tr>
	<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr>
		<td>
			<table border='0' width='100%' >
				<tr>
					<td width='40%'>
						<select name='filter_lists[]' id='filter_lists' style='width:400px;' size='30' multiple='true'>
							<?=$options?>
						</select>
						<br/><br/>
						<input type='button' value='삭제' style='width:300px;height:30px;border:1px solid #000000;' onclick='doRemove()'/>
					</td>
					<td>
						<table>
							<tr>
								<td><input type='text' name='keyword_regist' style='width:100%;' value=''/></td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td><input type='button' value='금지 키워드 등록'  style='width:100%;height:30px;border:1px solid #000000;' onclick='doRegist()'/></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>