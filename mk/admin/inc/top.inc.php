<html>
<head> 
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>::매경닷컴 담소 관리자 페이지::</title>
<head>
<link rel='stylesheet' type='text/css' href='http://common.mk.co.kr/common/css/common.css'>
<script type='text/javascript' src='http://common.mk.co.kr/common/include/mklib/jquery.js'></script>
<style TYPE="text/css">
<!--	
body {margin:0px; padding:0px; background-color:#ffffff; *word-break:break-all; -ms-word-break:break-all;}
/* 기본 스타일 */
div, form, fieldset, td, th, h1, h2, h3, h4, p, address {margin:0; padding:0;}
ul,li,ol,dl,dt,dd {list-style:none; margin:0; padding:0;}	
img.middle {vertical-align:middle; margin-bottom:3px;}
div.hide, h1.hide, h2.hide, span.hide {display:none;}
fieldset, img {border:none;}
/* 클리어 */
.clear {clear:both;}
/* 기본 링크 스타일 */
A.link1:link {font-size:12px; text-decoration:none; color:#d4d4d4;}
A.link1:visited {font-size:12px; text-decoration:none; color:#d4d4d4;}
A.link1:hover {font-size:12px; text-decoration:underline; color:#ff8702;}
table {border-collapse:collapse; word-break:break-all; word-wrap:break-word;}
th, td {font-weight:normal; font-size:12px;}
img.bar {vertical-align:middle; margin:0px 15px 2px 15px;}	
input.radio {margin:0px 0px -2px 0px; border-width:0px;}
.button1 {padding-top:2px; border:1x solid #333333; background-Color:#555555; color:#ffffff; font-size:12px;}
.button2 {padding-top:2px; border:1x solid #acacac; background-Color:#f4f4f4; color:#484848; font-size:12px;}
.txt1{height:20px; font-size:12px; color:#484848; padding:3px 4px 0; border:1px solid #cccccc;}
.txtBox1 {overflow:auto; z-index:1; font-size:12px; color:#484848; background-color:#ffffff; border:1px solid #cccccc; padding:4px; overflow-X:hidden;
scrollbar-face-color: #e9e9e9; 
scrollbar-shadow-color: #b7b7b7; 
scrollbar-highlight-color: #f2f2f2; 
scrollbar-3dlight-color: #e9e9e9; 
scrollbar-darkshadow-color: #b7b7b7; 
scrollbar-track-color: #f4f4f4; 
scrollbar-arrow-color: #999999;
}
.ic_gray {position:relative; display:inline-block; color: #989898 !important; font-weight:bold; text-decoration:none; font-style:normal; font-variant:normal; font-size:11px; font-family:verdana; _width:10px; min-width:7px; line-height:10px; border:1px solid #e4e4e4; margin:0 1px 0 0; padding:3px 5px 4px 5px; background-color:#ffffff; cursor:hand; }
.ic_gray1 {position:relative; display:inline-block; color: #989898 !important; font-weight:bold; text-decoration:none; font-style:normal; font-variant:normal; font-size:11px; font-family:돋움; _width:37px; min-width:30px; line-height:10px; margin:0 1px 0 0; padding:6px 2px 2px 2px; background-color:#ffffff; letter-spacing:-1px; cursor:hand; }
.ic_blue {position:relative; display:inline-block; color: #0083d0 !important; font-weight:bold; text-decoration:none; font-style:normal; font-variant:normal; font-size:11px; font-family:verdana; _width:10px; min-width:7px; line-height:10px; border:1px solid #539fcc; margin:0 1px 0 0; padding:3px 5px 4px 5px; background-color:#ffffff ;cursor:hand; }
.icon_g {font-size:10px; color:#cdcdcd;}
-->
</style>
</head> 
<body >

<table border='0' width='100%'>
	<tr>
		<td height='60' style='padding-left:25px; vertical-align:center; padding-bottom:0px;'>
			<table border='0' width='100%'>
				<tr>
					<td width='72%' align='center'>
						<!--로고영역-->
						<table border='0' >
							<tr>
								<td>매경닷컴 담소 로고좀 만들어주세요 TT</td>
							</tr>
						</table>
						<!--//로고영역-->
					</td>
					<td style="text-align:right;">
					<!--상단 우측버튼영역-->
						<table border="0" width='450'>
							<tr>
								<td style='padding-right:10px;'>
									 <?=(isset($_SESSION['admin_name'])?"<font color='blue'><strong>".$_SESSION['admin_name']."</strong></font> 님 (App ID :".$_SESSION['app_id'].") 환영합니다.":"") ?>
								</td>
								<td style="padding-right:5px;"><a href="./index_admin.php">
									<img src="http://ui.mk.co.kr/design/wkf2010/admin/images/bt_admin_home.gif" alt="관리자홈"><!--관리자홈--></a>
								</td>
								<td>
									<a href="#" onclick="if(confirm('로그아웃 하시겠습니까?')){location.href='./action_admin.php?cmd=action_logout';}"><img src="http://ui.mk.co.kr/design/wkf2010/admin/images/bt_logout.gif" alt="로그아웃"><!--로그아웃--></a>
								</td>
							</tr>
						</table>
					<!--//상단 우측버튼영역-->
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<!--메뉴영역-->
	<tr>
		<td height='35' style="padding:3px 0px 0px 25px; font-weight:bold; background:url('http://ui.mk.co.kr/design/wkf2010/admin/images/bg_topmenu.gif') repeat-x left top;">
			<a href='./index_admin.php?mn1=category' class='link1'>카테고리관리</a>
			<img border='0' src='http://ui.mk.co.kr/design/wkf2010/admin/images/menu_bar.gif' width='1' height='11' class='bar'>
			<a href='./index_admin.php?mn1=add' class='link1'>댓글관리</a>
			<img border='0' src='http://ui.mk.co.kr/design/wkf2010/admin/images/menu_bar.gif' width='1' height='11' class='bar'>
<?
if($_SESSION['admin_auth']=='N'){
?>
			<a href='./index_admin.php?mn1=filter' class='link1'> 필터링관리</a>
			<img border='0' src='http://ui.mk.co.kr/design/wkf2010/admin/images/menu_bar.gif' width='1' height='11' class='bar'>
<?
}
?>
			<a href='./index_admin.php?mn1=good' class='link1'>인기댓글관리</a>
			<img border='0' src='http://ui.mk.co.kr/design/wkf2010/admin/images/menu_bar.gif' width='1' height='11' class='bar'>
			<a href='./index_admin.php?mn1=bad2' class='link1'>신고댓글관리</a>
			<img border='0' src='http://ui.mk.co.kr/design/wkf2010/admin/images/menu_bar.gif' width='1' height='11' class='bar'>
			<a href='./index_admin.php?mn1=statistics' class='link1'>통계관리</a>
			<img border='0' src='http://ui.mk.co.kr/design/wkf2010/admin/images/menu_bar.gif' width='1' height='11' class='bar'>
<?
if($_SESSION['admin_auth']=='S'){
?>
			<a href='./index_admin.php?mn1=members' class='link1'>회원관리</a>
			<img border='0' src='http://ui.mk.co.kr/design/wkf2010/admin/images/menu_bar.gif' width='1' height='11' class='bar'>
			<a href='./index_admin.php?mn1=developers' class='link1'>개발자전용</a>
			<img border='0' src='http://ui.mk.co.kr/design/wkf2010/admin/images/menu_bar.gif' width='1' height='11' class='bar'>
<?
}
?>
		</td>
	</tr>
	<!--//메뉴영역-->
	<!--라인맵영역-->
<?
if($mn1=='category'){
	switch($mn2){
		case 'list': $str="카테고리 목록";break;
		default : $str="카테고리 목록";break;
	}
	echo "
	<tr><td height='32' style='padding:3px 0px 0px 25px; font-size:11px;'><font color='#999999'> 카테고리 관리 &gt; </font><b><font color='#555555'>".$str."</font></b></td></tr>";
}
else if($mn1=='add'){
	switch($mn2){
		case 'add_list': $str="댓글 목록";break;
		case 'add_list_dup': $str="중복 댓글 목록";break;
		case 'add_list_del': $str="삭제 댓글 목록";break;
		default : $str="댓글 목록";break;
	}
	echo "
	<!--라인맵영역-->
	<tr>
		<td height='28' style='padding:3px 0px 0px 25px; font-size:12px; background:#cccccc; font-weight:bold;'>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=add&mn2=add_list'><b>댓글 목록</b></a>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=add&mn2=add_list_del'><b>삭제 댓글 목록</b></a>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=add&mn2=add_list_dup'><b>중복 댓글 목록</b></a>
		</td>
	</tr>
	<tr><td height='32' style='padding:3px 0px 0px 25px; font-size:11px;'><font color='#999999'> 댓글관리&gt; </font><b><font color='#555555'>".$str."</font></b></td></tr>";
}
else if($mn1=='filter'){
	switch($mn2){
		case 'filter_list_keyword': $str="키워드 목록";break;
		case 'filter_list_id': $str="금칙아이디 목록";break;
		default : $str="키워드 목록";break;
	}
	echo "
	<!--라인맵영역-->
	<tr>
		<td height='28' style='padding:3px 0px 0px 25px; font-size:12px; background:#cccccc; font-weight:bold;'>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=filter&mn2=filter_list_keyword'><b>키워드 목록관리</b></a>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=filter&mn2=filter_list_id'><b>금칙아이디 목록관리</b></a>
		</td>
	</tr>
	<tr><td height='32' style='padding:3px 0px 0px 25px; font-size:11px;'><font color='#999999'> 키워드관리&gt; </font><b><font color='#555555'>".$str."</font></b></td></tr>";
}
else if($mn1=='good'){
	switch($mn2){
		case 'good_list': $str="인기댓글 목록";break;
		default : $str="인기댓글 목록";break;
	}
	echo "
	<!--라인맵영역-->
	<tr>
		<td height='28' style='padding:3px 0px 0px 25px; font-size:12px; background:#cccccc; font-weight:bold;'>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=good&mn2=good_list'><b>인기댓글 목록</b></a>
		</td>
	</tr>
	<tr><td height='32' style='padding:3px 0px 0px 25px; font-size:11px;'><font color='#999999'> 인기댓글관리&gt; </font><b><font color='#555555'>".$str."</font></b></td></tr>";
}
else if($mn1=='bad2'){
	switch($mn2){
		case 'bad2_list': $str="신고댓글 목록";break;
		default : $str="신고댓글 목록";break;
	}
	echo "
	<!--라인맵영역-->
	<tr>
		<td height='28' style='padding:3px 0px 0px 25px; font-size:12px; background:#cccccc; font-weight:bold;'>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=bad2&mn2=bad2_list'><b>신고댓글 목록</b></a>
		</td>
	</tr>
	<tr><td height='32' style='padding:3px 0px 0px 25px; font-size:11px;'><font color='#999999'> 신고댓글관리&gt; </font><b><font color='#555555'>".$str."</font></b></td></tr>";
}
else if($mn1=='statistics'){
	switch($mn2){
		case 'list_day': $str="일별 통계";break;
		default : $str="일별 목록";break;
	}
	echo "
	<!--라인맵영역-->
	<tr>
		<td height='28' style='padding:3px 0px 0px 25px; font-size:12px; background:#cccccc; font-weight:bold;'>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=statistics&mn2=list_day'><b>일별통계</b></a>
		</td>
	</tr>
	<tr><td height='32' style='padding:3px 0px 0px 25px; font-size:11px;'><font color='#999999'> 통계관리&gt; </font><b><font color='#555555'>".$str."</font></b></td></tr>";
}
else if($mn1=='members'){
	switch($mn2){
		case 'list_members': $str="회원 목록";break;
		default : $str="회원 목록";break;
	}
	echo "
	<!--라인맵영역-->
	<tr>
		<td height='28' style='padding:3px 0px 0px 25px; font-size:12px; background:#cccccc; font-weight:bold;'>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=members&mn2=list_members'><b>회원목록</b></a>
		</td>
	</tr>
	<tr><td height='32' style='padding:3px 0px 0px 25px; font-size:11px;'><font color='#999999'> 회원관리&gt; </font><b><font color='#555555'>".$str."</font></b></td></tr>";
}
else if($mn1=='developers'){
	switch($mn2){
		case 'developers_list': $str="소셜댓글 LIMIT 체크";break;
		default : $str="소셜댓글 LIMIT 체크";break;
	}
	echo "
	<!--라인맵영역-->
	<tr>
		<td height='28' style='padding:3px 0px 0px 25px; font-size:12px; background:#cccccc; font-weight:bold;'>
			&nbsp;&nbsp;&nbsp; <a href='./index_admin.php?mn1=developers&mn2=developers_list'><b>개발자전용</b></a>
		</td>
	</tr>
	<tr><td height='32' style='padding:3px 0px 0px 25px; font-size:11px;'><font color='#999999'> 개발자전용&gt; </font><b><font color='#555555'>".$str."</font></b></td></tr>";
}

echo "
	<!--//라인맵영역-->
	<tr>
		<td bgcolor='#d8d8d8' height='1'></td>
	</tr>
</table>";
?>
