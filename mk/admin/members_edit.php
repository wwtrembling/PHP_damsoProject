<?
/*
카테고리관리 > 카테고리등록/수정
*/
//-------------------------------------------------------- 기본 변수 체크
if(!defined("checksum")) exit;
$page=isset($_GET['page'])?intval($_GET['page']):0;
$_id=isset($_GET['_id'])?trim($_GET['_id']):0;

//-------------------------------------------------------- 데이타 불러오기
$cls_mem= new MkAddMemDB();
$info=array();
if($_id>0){
	if($admin_auth=='S'){
		$info=$cls_mem->getRow(array("_id"=>$_id));
	}
	else{
		$info=$cls_mem->getRow(array("app_id"=>$app_id, "_id"=>$_id));
	}
}

?>

<script type='text/javascript'>
function doRemove(){
	if(confirm('삭제하시겠습니까?')){
		document.getElementsByName('cmd')[0].value='action_members_remove';
		thisform.submit();
	}
}

function doRegist(){
	var company_name=document.getElementsByName('company_name')[0];
	if(company_name.value!=''){
		document.getElementsByName('cmd')[0].value='action_members_regist';
		document.getElementsByName('thisform')[0].submit();
	}
	else{
		company_name.focus();
		alert('app_id를 입력해 주세요.');
		return false;
	}
}
</script>

<?
echo "

<script type='text/javascript' src='".js_url."calendar_beans_v2.0.js' charset='utf-8'></script>
<script type='text/javascript' src='".js_url."jquery.min.js'  charset='utf-8'></script>

<form name='thisform' method='post' action='./action_admin.php'>
<input type='hidden' name='cmd' value='' />
<input type='hidden' name='_id' value='".$_id."' />
<br/><br/>
<table border='0' width='600' align='center'>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702'><b>| 회원 기본정보</b></font></td>
	</tr>
	<tr>
		<td>
			<!--인적사항-->
			<table border='1' width='100%' bordercolor='#b4b4b4'> 
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;width:20%;' >회사명</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:100%;' name='company_name' value=\"".(isset($info['company_name'])?$info['company_name']:"")."\" /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >담당자명</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:100%;' name='company_user_name' value=\"".(isset($info['company_user_name'])?$info['company_user_name']:"")."\" /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >담당자아이디</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:100%;' name='company_user_id' value=\"".(isset($info['company_user_id'])?$info['company_user_id']:"")."\" /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >비밀번호</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:100%;' name='company_user_pw' value=\"".(isset($info['company_user_pw'])?$info['company_user_pw']:"")."\" /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >담당자연락처</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:100%;' name='company_user_tel' value=\"".(isset($info['company_user_tel'])?$info['company_user_tel']:"")."\" /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >담당자핸드폰</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:100%;' name='company_user_mobile' value=\"".(isset($info['company_user_mobile'])?$info['company_user_mobile']:"")."\" /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >담당자이메일</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:100%;' name='company_user_email' value=\"".(isset($info['company_user_email'])?$info['company_user_email']:"")."\" /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >계약금액</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:100%;' name='contract_price' value=\"".(isset($info['contract_price'])?$info['contract_price']:"")."\" /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >계약시작일</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:50%;' name='contract_start' value=\"".(isset($info['contract_start'])?$info['contract_start']:"")."\" id='contract_start' /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >계약종료일</td>
					<td style='padding:10px;' colspan='3'><input type='text' style='width:50%;' name='contract_end' value=\"".(isset($info['contract_end'])?$info['contract_end']:"")."\"  id='contract_end' /></td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >슈퍼관리자</td>
					<td style='padding:10px;' colspan='3'>
						<select name='admin_auth'>
							<option value='S' ".(isset($info['admin_auth']) && $info['admin_auth'] =="S" ? "selected" : "").">S</option>
							<option value='N' ".(isset($info['admin_auth']) && $info['admin_auth'] =="N" ? "selected" : "").">N</option>
						</select>
					</td>
				</tr>
				<tr>
					<td bgcolor='#ececec' style='padding-left:5px; padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;' >사용여부</td>
					<td style='padding:10px;' colspan='3'>
						<select name='use_yn'>
							<option value='Y' ".(isset($info['use_yn']) && $info['use_yn'] =="Y" ? "selected" : "").">Y</option>
							<option value='N' ".(isset($info['use_yn']) && $info['use_yn'] =="N" ? "selected" : "").">N</option>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width='100%'>
				<tr>
					<td align='left' style='padding:10px;'><input type='button' value='목록' onclick=\"location.href='./index_admin.php?mn1=members';\"/></td>
					<td align='right' style='padding:10px;'>
						<input type='button' value='확인' onclick='doRegist()'/>&nbsp;&nbsp;&nbsp;
						<input type='button' value='삭제' onclick='doRemove()'/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script type='text/javascript'>			
	CalAddCss(); // !!제일 상단에 필수!!
	/*
		id		:텍스트박스Id   // *필수
		type	:day,mon	    // 둘중 하나입력		,기본값> 일달력 출력
		minYear :xxxx			// 최소년도 4자리 입력	,기본값> 2000
		maxYear :xxxx  			// 최대년도 4자리 입력	,기본값> 현재년도
		splitKey:'-','/'		// 달력 구분값        	,기본값> '-'
		todayYN :'y','n'		// today 표시         	,기본값> 안보여주기
		iconYN  :'y','n'		// 달력그림표시여부	  	,기본값> 안보여주기
		iconUrl :fullUrl 혹은 해당위치 상대경로 url'	,기본값> jquery 사이트 달력
	 */
	initCal({id:'contract_start',type:'day',today:'y',icon:'y',iconUrl:'http://img.mk.co.kr/icon/calendar.gif'});
	initCal({id:'contract_end',type:'day',today:'y',icon:'y',iconUrl:'http://img.mk.co.kr/icon/calendar.gif'});
</script>
</form>";
?>