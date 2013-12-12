<?
/*
댓글관리 > 삭제댓글목록
*/
//-------------------------------------------------------- 기본 변수 체크
if(!defined("checksum")) exit;
$maxlist=7;
$page=isset($_GET['page'])?intval($_GET['page']):0;
$sch_cat_01=isset($_GET['sch_cat_01'])?trim($_GET['sch_cat_01']):null;
$sch_cat_02=isset($_GET['sch_cat_02'])?trim($_GET['sch_cat_02']):null;
$sch_keyword=isset($_GET['sch_keyword'])?trim($_GET['sch_keyword']):null;
$sch_st_time=null;
$sch_st_date=isset($_GET['sch_st_date'])?trim($_GET['sch_st_date']):null;
$sch_ed_time=null;
$sch_ed_date=isset($_GET['sch_ed_date'])?trim($_GET['sch_ed_date']):null;
if(!empty($sch_st_date)){$sch_st_time=(string)strtotime($sch_st_date." 00:00:00");}
if(!empty($sch_ed_date)){$sch_ed_time=(string)strtotime($sch_ed_date." 23:59:59");}

//-------------------------------------------------------- 데이터 불러오기
$cls= new MkAddDB();
$cls_cat= new MkAddCateDB();
$lists=array();
$total_no=0;

$cond=array();
$cond['usage']="N";	 // 사용여부

//등록처
if($sch_cat_01){
	$cond['cat_01']=urlencode($sch_cat_01);
	if($sch_cat_02){$cond['cat_02']=urlencode($sch_cat_02);}
}

//검색어
if($sch_keyword){$cond['text']="/(".urlencode($sch_keyword).")+/";}

//기간설정
if(isset($sch_st_time) && isset($sch_ed_time)){$cond['regdate']=array('$gte'=>$sch_st_time,'$lte'=>$sch_ed_time);}
else if(isset($sch_st_time)){$cond['regdate']=array('$gte'=>$sch_st_time);}		// 검색시작일
else if(isset($sch_ed_time)){$cond['regdate']=array('$lte'=>$sch_ed_time);}	// 검색종료일


//최고 권한자 일경우
if($admin_auth=='S'){
	$lists=$cls->lists(null, $cond, $page, $maxlist);
	$total_no=$cls->listsCount($cond);
	$lists_cat=$cls_cat->lists();
}
//일반 사용자 일경우
else{
	$cond['app_id']=$app_id;
	$lists=$cls->lists(null, $cond, $page, $maxlist);
	$total_no=$cls->listsCount($cond);
	$lists_cat=$cls_cat->lists(null, array("app_id"=>$app_id));
}

$cat_names=array();
$cat_sub_names=array();
$idx=0;
foreach($lists_cat as $key=>$val){
	$cat_names[$idx]['name']=$val['category_name'];
	if($val['category_name']==$sch_cat_01){
		if(is_array($val['category_sub'])){
			$idx2=0;
			foreach($val['category_sub'] as $key2=>$val2){
				$cat_sub_names[$idx2]['name']=$val2['sub_category_name'];
				$idx2++;
			}
		}
	}
	$idx++;
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

$pager->addQuery('mn1',"add");
$pager->addQuery('mn2',"del_list");
if(isset($sch_cat_01)){
	$pager->addQuery('sch_cat_01',$sch_cat_01);
	if(isset($sch_cat_02)){$pager->addQuery('sch_cat_02',$sch_cat_02);}
}
if(isset($sch_st_date)){$pager->addQuery('sch_st_date',$sch_st_date);}
if(isset($sch_ed_date)){$pager->addQuery('sch_ed_date',$sch_ed_date);}
if(isset($sch_keyword)){$pager->addQuery('sch_keyword',$sch_keyword);}


echo "
<br/><br/>
<link href='../common/css/calendar.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='../common/js/calendar.js'></script>
<script type='text/javascript'>
function doRemoveForever(){
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
		if(confirm('해당 댓글을 영구삭제하시겠습니까?(복구 불가)')){
			document.getElementsByName('cmd')[0].value='action_remove_multi';
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

function reLoad(){
	var sch_cat_01=document.getElementsByName('sch_cat_01')[0];
	sch_cat_01=sch_cat_01.options[sch_cat_01.selectedIndex].value;
	var sch_cat_02=document.getElementsByName('sch_cat_02')[0];
	sch_cat_02=sch_cat_02.options[sch_cat_02.selectedIndex].value;
	var sch_st_date=document.getElementsByName('sch_st_date')[0].value;
	var sch_ed_date=document.getElementsByName('sch_ed_date')[0].value;
	location.href='./index_admin.php?mn1=add&mn2=add_list_del&sch_cat_01='+sch_cat_01+'&sch_cat_02='+sch_cat_02+'&sch_st_date='+sch_st_date+'&sch_ed_date='+sch_ed_date;
}
</script>
<form name='thisform' method='post' action='./action_admin.php'>
<input type='hidden' name='cmd' value='' />
<table border='0' width='90%' bordercolor='#b4b4b4' align='center'>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702' size='3'><b>| 삭제댓글 목록</b></font></td>
	</tr>
	<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr>
		<td>
			<table border='0' width='500' >
				<tr>
					<td style='padding:7px; text-align:left;'>▶등록처</td>
					<td>";
$options=null;
foreach($cat_names as $key=>$val){
	$options.="<option value='".$val['name']."' ".(($val['name']==$sch_cat_01)?"selected='true'":"").">".$val['name']."</option>";
}
echo "
						<select name='sch_cat_01' onchange='reLoad()'>
							<option value=''>-----카테고리 선택-----</option>
							".$options."
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$options=null;
foreach($cat_sub_names as $key=>$val){
	$options.="<option value='".$val['name']."' ".(($val['name']==$sch_cat_02)?"selected='true'":"").">".$val['name']."</option>";
}
echo "
						<select name='sch_cat_02' onchange='reLoad()'>
							<option value=''>-----서브카테고리 선택-----</option>
							".$options."
						</select>
					</td>
				</tr>
				<tr>
					<td style='padding:7px; text-align:left;'>▶검색어</td>
					<td><input type='text' name='sch_keyword' value='".(isset($sch_keyword)?$sch_keyword:null)."' /></td>
				</tr>
				<tr>
					<td style='padding:7px; text-align:left;'>▶기간설정</td>
					<td>
						<input type='text' name='sch_st_date' value='".(isset($sch_st_date)?$sch_st_date:"")."' class='input_style1' onclick=\"displayCalendar(this,'yyyy-mm-dd',this)\" />
						~ 
						<input type='text' name='sch_ed_date' value='".(isset($sch_ed_date)?$sch_ed_date:"")."' class='input_style1' onclick=\"displayCalendar(this,'yyyy-mm-dd',this)\" />
						<input type='button' value='검색' onclick='reLoad()'/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr>
		<td>
			<table border='0' width='100%'>
				<tr>
					<td width='250'>Total : ".$total_no."&nbsp;&nbsp; Page : ".($page+1)."/".(ceil($total_no/$maxlist))."</td>
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
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'><input type='checkbox' name='all_checked' onclick='doCheckedAll()'/></td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>NO</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>구분</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;width:300px;'>뉴스제목</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;width:300px;'>댓글</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>삭제일</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>삭제유형</td>
				</tr>";
$no=$total_no-$page*$maxlist;
foreach($lists as $key=>$val){
	$gubun=null;
	if(!empty($val['unique_maekyung'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_mk.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_twitter'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_twitter.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_facebook'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_face_book.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_yozm'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_thesedays.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_metoday'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_metoday.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_cyworld'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_cyworld.png' />&nbsp;&nbsp;";}
	$alink="./index_admin.php?mn1=add&mn2=add_edit&_id=".$val['_id']."&rtnmn1=add&rtnmn2=add_list_del";
	echo "
				<tr>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'><input type='checkbox' name='removeid__".$val['_id']."' /></td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".($no--)."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$gubun."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:10px; text-align:left;'><a href='".$alink."'>".$val['title']."</a></td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:10px; text-align:left;'><a href='".$alink."'>".str_replace("\n","<br/>",$val['text'])."</a></td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".date('Y-m-d H:i',$val['removedate'])."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$val['remove_type']."</td>
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
						<input type='button' value='영구삭제' onclick='doRemoveForever()' class='button1'/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div style='text-align:center;height:30px;'>";
$pager->stroke();
echo "
			</div>
		</td>
	</tr>
</table>
</form>";
?>