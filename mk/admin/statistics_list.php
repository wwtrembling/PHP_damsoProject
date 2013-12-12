<?
/*
댓글관리 > 댓글목록
*/
//-------------------------------------------------------- 기본 변수 체크
if(!defined("checksum")) exit;
$maxlist='100';
$page=isset($_GET['page'])?intval($_GET['page']):0;
$search_type=isset($_POST['search_type'])?($_POST['search_type']):null;

//-------------------------------------------------------- 데이터 불러오기

//$cls= new MkGroupByDB();
$cls= new MkAddDB();
$cls_counts= new MkAddDB();

$lists=array();
$total_no=0;

$cond=array();
$cond['usage']="Y";

//최고 권한자 일경우
if($admin_auth=='S'){
	$lists=$cls->lists(null, $cond, $page, $maxlist);
	$total_no=$cls->listsCount($cond);
}
//일반 사용자 일경우
else{
	$cond['app_id']=$app_id;
	$lists=$cls->lists(null, $cond, $page, $maxlist);
	$total_no=$cls->listsCount($cond);
}

$good=null;
$bad =null;
$good_type =null;
$bad_type  =null;
$gDate  =null;

if ( $search_type == 'comment' || empty($search_type) ) { // 댓글 출처로 보기
	$lists_counts=array();
	$cond_counts=array();

	$tmpDate=array();
	$gDate=array();

	// distinct, group by 가 없기 때문에 날짜만 따로 배열에 담는다.
	for ( $i = 0; $i < count($lists); $i++ ) {
		$tmpDate[$i] = date('Y-m-d',$lists[$i]['regdate']);
	}
	
	// 배열에서 중복된 날짜를 제거한다.
	$arr_unique = array_unique($tmpDate);

	// 배열 인덱스가 맞지 않으므로 순차적으로 인덱스를 설정한다.
	for ( $i = 0; $i < count($tmpDate); $i++ ) {
		if ( !empty($arr_unique[$i]) ) {
			$gDate[] = $arr_unique[$i];
		}
	}
} else { // 인기검색어 보기
	$good = empty($_POST['good']) ? $good : $_POST['good'];
	$good_type = isset($_POST['good_type'])?$_POST['good_type']:0;
	$bad = empty($_POST['bad']) ? $bad : $_POST['bad'];
	$bad_type = isset($_POST['bad_type'])?$_POST['bad_type']:0;
	
	if ( empty($good) && empty($bad) ) { // 값이 없을 경우 초기화
		$good = 'good';
		$good_type = 0;
		$bad = 'bad';
		$bad_type = 0;
	}
	
	if ( $good ) {
		$target_fields = $good;
		$target_type = $good_type;
	}
	else {
		$target_fields = $bad;
		$target_type = $bad_type;
	}

	$sort_array = array_orderby($lists, $target_fields, $target_type);
}

// 인자 : 배열, 컬럼, 정렬방법(0:내림차순, 1:오름차순)
function array_orderby($array, $column, $ops=0) {
	for ( $i = 0; $i < count($array); $i++ ) {
		$sortarr[] = $array[$i][$column];
	}

	$op = array(SORT_DESC, SORT_ASC); // 내림차순 : 오름차순
	@array_multisort($sortarr, $op[$ops], $array);
	return($array);
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
$pager->addQuery('mn2',"add_list");

if ( $good == "good" && $good_type == 0 ) $icon_good = "desc";
else if ( $good == "good" && $good_type == 1 ) $icon_good = "asc";
else $icon_good = "desc";

if ( $bad == "bad" && $bad_type == 0 ) $icon_bad = "desc";
else if ( $bad == "bad" && $bad_type == 1 ) $icon_bad = "asc";
else $icon_bad = "desc";

echo "
<script type='text/javascript'>
function Orderby(gb, type){
	var oType;
	document.getElementById('good').value = '';
	document.getElementById('good_type').value = '';
	document.getElementById('bad').value = '';
	document.getElementById('bad_type').value = '';

	if ( type == 0 ) {
		oType = 1;
	} else {
		oType = 0;
	}

	document.getElementById(gb).value = gb;
	document.getElementById(gb+'_type').value = oType;
	document.o_form.submit();
}
</script>
<br/><br/>
<table border='0' width='90%' bordercolor='#b4b4b4' align='center'>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702' size='3px'><b>| 일별 통계</b></font></td>
	</tr>
	<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr>
		<td>
			<table border='0' width='100%'>
				<tr>
					<td width='250'>Total : ".count($gDate)."&nbsp;&nbsp; Page : ".($page+1)."/".(ceil($total_no/$maxlist))."</td>
				</tr>
				<tr>
					<td height='10px'>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
		<form name=s_form action='".admin_url."index_admin.php?mn1=statistics' method='post'>
			<div style='border:1px solid #e6e6e6; background-color:#f9f9f9; width:100%; padding:5px;'>
				<table border='0' cellpadding='3' cellspacing='3' width='100%'>
					<tr>
						<td width='10%'><b>선택하세요</b></td>
						<td>
							<input type='radio' name='search_type' value='comment' ".($search_type == 'comment' || empty($search_type) ? 'checked' : '')."> 출처별
							<input type='radio' name='search_type' value='recommend' ".($search_type == 'recommend' ? 'checked' : '')." > 인기댓글&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type='image' src='http://img.mk.co.kr/main/april/b_main_search.gif' border=0 align=absmiddle>
						</td>
					</tr>
				</table>
			</div>
			</form>
		</td>
	</tr>
	<tr>
		<td height='10px'>&nbsp;</td>
	</tr>";
	if ( $search_type == 'comment' || empty($search_type) ) {
		echo "
	<tr>
		<td>
			<table border='1' width='100%' bordercolor='#b4b4b4' align='center'>
				<tr >
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>날짜</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>매일경제</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>트위터</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>페이스북</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>미투데이</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>요즘</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>싸이월드</td>
				</tr>";
				for ( $i = 0; $i < count($gDate); $i++) {
					$date_start = (string)strtotime($gDate[$i]." 00:00:00");
					$date_end = (string)strtotime($gDate[$i]." 23:59:59");

					$cond_counts['usage']="Y";
					$cond_counts['regdate']=array('$gte'=>$date_start,'$lte'=>$date_end);
					echo "
				<tr>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$gDate[$i]."</td>";
					for ( $j = 0; $j < count($mkadd_company); $j++ ) {
						$cond_counts['hometown']=$mkadd_company[$j];
						$lists_counts=$cls_counts->lists(null, $cond_counts, '', '');
						$total_no_counts=$cls_counts->listsCount($cond);
					echo "
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$total_no_counts."</td>";
					}
				echo "
				</tr>";
				}
				echo "
			</table>
		</td>
	</tr>";
	} else {
		echo "
	<tr>
		<td>
			<form name=o_form action='{$PHP_SELF}' method='post'>
			<input type='hidden' name='search_type' value=\"{$search_type}\">
			<input type='hidden' name='good' id='good' value=\"{$good}\">
			<input type='hidden' name='good_type' id='good_type' value=\"{$good_type}\">
			<input type='hidden' name='bad' id='bad' value=\"{$bad}\">
			<input type='hidden' name='bad_type' id='bad_type' value=\"{$bad_type}\">
			<table border='1' width='100%' bordercolor='#b4b4b4' align='center'>
				<tr >
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222; width:14%'>날짜</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>댓글</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222; width:7%'>추천 <img src='http://img.mk.co.kr/mk/order_{$icon_good}.png' border='0' align='absmiddle' style='cursor:hand' onClick=\"javascsript:Orderby('good', '".$good_type."');\"></td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222; width:7%'>반대 <img src='http://img.mk.co.kr/mk/order_{$icon_bad}.png' border='0' align='absmiddle' style='cursor:hand' onClick=\"javascsript:Orderby('bad', '".$bad_type."');\"></td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222; width:30%'>URL</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222; width:10%'>계정</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222; width:10%'>ID</td>
				</tr>";
				for ( $i = 0; $i < count($sort_array); $i++) {
					echo "
				<tr>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".date('Y-m-d H:i',$sort_array[$i]['regdate'])."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$sort_array[$i]['text']."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$sort_array[$i]['good']."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$sort_array[$i]['bad']."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$sort_array[$i]['from_url']."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$sort_array[$i]['hometown']."</td>
					<td style='padding-top:5px; padding-bottom:2px; padding-left:3px; text-align:center;'>".$sort_array[$i]['user_id']."</td>
				</tr>";
				}
				echo "
			</table>
			</form>
		</td>
	</tr>";
	}
	echo "
	<tr>
		<td height='10px'>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<div style='text-align:center;height:30px;'>";
$pager->stroke();
echo "
			</div>
		</td>
	</tr>
</table>";
?>