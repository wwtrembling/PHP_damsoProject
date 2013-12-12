<?
/*
개발자전용 > 소셜댓글 LIMIT 체크
*/
if(!defined("checksum")) exit;
?>
<br/><br/>
<link href='../common/css/calendar.css' rel='stylesheet' type='text/css' />
<script type='text/javascript'>
<!--
$(document).ready(
	function(){
		//트위터 받아오기
		setInterval(function(){getTwitLimit();},1000);
	}
);

function getTwitLimit(){
	$.post("./action_admin.php",{cmd:"action_deveoper_twitter_ajax"},function(data){
		var obj= $.parseJSON(data);
		var hourly_limit=obj.hourly_limit;
		var remaining_hits=obj.remaining_hits;
		var sec=obj.reset_time;
		var dt= new Date(sec);
		$("#twit0").html(hourly_limit);
		$("#twit1").html(remaining_hits);
		$("#twit2").html(sec);
	});
}
//-->
</script>
<table border='0' width='90%' bordercolor='#b4b4b4' align='center'>
	<tr>
		<td style='padding-bottom:8px;'><font color='#ff8702' size='3'><b>| 소셜댓글 LIMIT</b></font></td>
	</tr>
	<tr><td>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr>
		<td>
			<table border='1' width='100%' bordercolor='#b4b4b4' align='center'>
				<tr>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222; width:300px;'>댓글 종류</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222; width:150px;'>총 Hits</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222; width:150px;'>남은 Hits</td>
					<td bgcolor='#ececec' style='padding-top:5px; padding-bottom:2px; text-align:center; color:#222222;'>Reset Time</td>
				</tr>
				<tr>
					<td style='padding:10px;text-align:center;'><img src='http://img.mk.co.kr/sns/b_twitter.png' style='vertical-align:middle'/>&nbsp;Twitter</td>
					<td style='padding:10px;text-align:center;'><span id='twit0'></span></td>
					<td style='padding:10px;text-align:center;'><span id='twit1'></span></td>
					<td style='padding:10px;text-align:center;'><span id='twit2'></span></td>
				</tr>
				<tr>
					<td style='padding:10px;text-align:center;'><img src='http://img.mk.co.kr/sns/b_face_book.png' style='vertical-align:middle'/>&nbsp;FaceBook</td>
					<td style='padding:10px;text-align:center;'></td>
					<td style='padding:10px;text-align:center;'></td>
					<td style='padding:10px;text-align:center;'></td>
				</tr>
				<tr>
					<td style='padding:10px;text-align:center;'><img src='http://img.mk.co.kr/sns/b_thesedays.png' style='vertical-align:middle'/>&nbsp;요즘</td>
					<td style='padding:10px;text-align:center;'></td>
					<td style='padding:10px;text-align:center;'></td>
					<td style='padding:10px;text-align:center;'></td>
				</tr>
				<tr>
					<td style='padding:10px;text-align:center;'><img src='http://img.mk.co.kr/sns/b_metoday.png' style='vertical-align:middle'/>&nbsp;미투데이</td>
					<td style='padding:10px;text-align:center;'></td>
					<td style='padding:10px;text-align:center;'></td>
					<td style='padding:10px;text-align:center;'></td>
				</tr>
				<tr>
					<td style='padding:10px;text-align:center;'><img src='http://img.mk.co.kr/sns/b_cyworld.png' style='vertical-align:middle'/>&nbsp;싸이로그</td>
					<td style='padding:10px;text-align:center;'></td>
					<td style='padding:10px;text-align:center;'></td>
					<td style='padding:10px;text-align:center;'></td>
				</tr>
			</table>
		</td>
	</tr>
</table>