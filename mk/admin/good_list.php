<?
/*
댓글관리 > 인기댓글목록
*/
//-------------------------------------------------------- 기본 변수 체크
if(!defined("checksum")) exit;
$page=isset($_GET['page'])?intval($_GET['page']):0;
$sch_recommand=isset($_GET['sch_recommand'])?intval($_GET['sch_recommand']):null;
$sch_st_time=null;
$sch_st_date=isset($_GET['sch_st_date'])?trim($_GET['sch_st_date']):null;
$sch_ed_time=null;
$sch_ed_date=isset($_GET['sch_ed_date'])?trim($_GET['sch_ed_date']):null;
if(!empty($sch_st_date)){$sch_st_time=(string)strtotime($sch_st_date." 00:00:00");}
if(!empty($sch_ed_date)){$sch_ed_time=(string)strtotime($sch_ed_date." 23:59:59");}

//-------------------------------------------------------- 데이터 불러오기
$cls= new MkAddDB();
$lists=array();
$total_no=0;
$cond=array();
$cond['usage']='Y';	 // 사용여부
$cond['good']=array('$gt'=>0);	 // 인기 댓글은 추천수가 0 초과

//기간설정
if(isset($sch_st_time) && isset($sch_ed_time)){$cond['regdate']=array('$gte'=>$sch_st_time,'$lte'=>$sch_ed_time);}
else if(isset($sch_st_time)){$cond['regdate']=array('$gte'=>$sch_st_time);}		// 검색시작일
else if(isset($sch_ed_time)){$cond['regdate']=array('$lte'=>$sch_ed_time);}	// 검색종료일

//추천수
if($sch_recommand){$maxlist=$sch_recommand;}
else{$maxlist=20;}

$orderby=array('good'=>'-1'); // -1 : desc, 1:asc

//최고 권한자 일경우
if($admin_auth=='S'){
	$lists=$cls->lists(null, $cond, $page, $maxlist, $orderby);
	$total_no=$cls->listsCount($cond);
}
//일반 사용자 일경우
else{
	$cond['app_id']=$app_id;
	$lists=$cls->lists(null, $cond, $page, $maxlist, $orderby);
	$total_no=$cls->listsCount($cond);
}

echo "
<br/><br/>
<link href='../common/css/calendar.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='../common/js/calendar.js'></script>
<script type='text/javascript'>
function reLoad(){
	var sch_recommand=document.getElementsByName('sch_recommand')[0];
	sch_recommand=sch_recommand.options[sch_recommand.selectedIndex].value;

	var sch_st_date=document.getElementsByName('sch_st_date')[0].value;
	var sch_ed_date=document.getElementsByName('sch_ed_date')[0].value;
	location.href='./index_admin.php?mn1=good&mn2=good_list&sch_recommand='+sch_recommand+'&sch_st_date='+sch_st_date+'&sch_ed_date='+sch_ed_date;
}

</script>
<form name='thisform' method='post' action='./action_admin.php'>
<input type='hidden' name='cmd' value='' />
<table border='0' width='90%' bordercolor='#b4b4b4' align='center'>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702' size='3'><b>| 인기댓글 목록</b></font></td>
	</tr>
	<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr>
		<td>
			<table border='0' width='500' >
				<tr>
					<td style='padding:7px; text-align:left;'>▶추천수</td>
					<td>
						<select name='sch_recommand' onchange='reLoad()'>
							<option value='20' ".(($sch_recommand==20)?"selected='true'":"").">20위</option>
							<option value='40' ".(($sch_recommand==40)?"selected='true'":"").">40위</option>
							<option value='60' ".(($sch_recommand==60)?"selected='true'":"").">60위</option>
							<option value='100' ".(($sch_recommand==100)?"selected='true'":"").">100위</option>
						</select>
					</td>
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
			<table border='1' width='100%' bordercolor='#b4b4b4' align='center'>
				<tr>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>구분</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;width:300px;'>뉴스제목</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;width:300px;'>댓글</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>추천수</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>반대수</td>
				</tr>";
foreach($lists as $key=>$val){
	$gubun=null;
	if(!empty($val['unique_maekyung'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_mk.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_twitter'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_twitter.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_facebook'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_face_book.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_yozm'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_thesedays.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_metoday'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_metoday.png' />&nbsp;&nbsp;";}
	if(!empty($val['unique_cyworld'])){$gubun.="<img src='http://img.mk.co.kr/sns/b_cyworld.png' />&nbsp;&nbsp;";}
	echo "
				<tr>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$gubun."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$val['title']."</a></td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$val['text']."</a></td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$val['good']."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$val['bad']."</td>
				</tr>";
}
echo "
			</table>
		</td>
	</tr>
</table>";

?>