<HTML>
 <head>
  <TITLE> New Document </TITLE>
  <script type="text/javascript" src="http://110.13.170.154/tempProject/junsama/js/script.js"></script> 
  <script type="text/javascript" src="http://110.13.170.154/tempProject/junsama/config_twitter.php"></script> 
 </head>
 <BODY>
 	<form name='thisform' method='post'>
	 	<input type='hidden' name='act' value=''>
	 	<div>
		 	<img src='http://img.mk.co.kr/news/2010/ic_newsv_cyworld.gif' alt='싸이월드' border='0' style='cursor: pointer;' onClick='cyworld();'>
			<img src='http://img.mk.co.kr/news/2010/ic_newsv_twiter.gif' alt='트위터' border='0' style='cursor: pointer;' onClick='twitter(<?=$PHP_SELF?>);'>
			<img src='http://img.mk.co.kr/news/2010/ic_newsv_fbook.gif' alt='페이스북' border='0' style='cursor:pointer;'onClick='facebook();' >
			<img src='http://img.mk.co.kr/news/2010/ic_newsv_metoday.gif'  alt='미투데이' border='0' style='cursor: pointer;' onClick='me2day();'>
		</div>
		<textarea  rows='5' id='reply' name='reply'  style='width:300px; font-size:12px;' wordwrap></textarea>
	</form>
 </BODY>
</HTML>