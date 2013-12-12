var MkAdd={
	touch_url:"http://110.13.170.154/tempProject/mk/",
	title:null, /*제목*/
	unique_id:null, /*뉴스페이지 고유아이디*/
	app_id:null, /*할당받은 아이디*/
	from_url:null, /*URL*/
	cat_01:null, /*카테고리1*/
	cat_02:null,/*카테고리2*/
	result:null,	/*받아온 로그인 데이타*/
	hometown:null, /*대표계정*/
	lists:null,					/*받아온 댓글 데이터*/
	lists_type:"recent", /*받아올 댓글 데이터 형태, recent:최신순, good:추천순, bad:반대순*/
	lists_maxlist:20,		/*받아올 댓글 데이터 page당 갯수*/
	lists_last_regdate:'',	/*받아올 댓글 데이터에서 가장 오래된 데이터 regdate*/
	company_imgarr:{'maekyung':'http://img.mk.co.kr/sns/b_mk.png','twitter':'http://img.mk.co.kr/sns/b_twitter.png','facebook':'http://img.mk.co.kr/sns/b_face_book.png','metoday':'http://img.mk.co.kr/sns/b_metoday.png','yozm':'http://img.mk.co.kr/sns/b_thesedays.png','cyworld':'http://img.mk.co.kr/sns/b_cyworld.png'}, /*이미지 모음: 순서는 매경, 트위터, 페이스북, 미투데이, 요즘, 싸이월드*/
	init:function(title, unique_id, app_id, cat_01, cat_02, from_url){/*----------------------------------------------------- 시작1*/
		this.title=title;
		this.unique_id=unique_id;
		this.app_id=app_id;
		this.cat_01=cat_01;
		this.cat_02=cat_02;
		/*현재 글이 어디서부터 왔는지 확인해 볼것*/
		var fromwhere=document.referrer.match(/110.13.170.154/gi);
		if(fromwhere){ opener.document.location.reload();window.close();}
		this.from_url=from_url;
		/*전체 글 출력*/
		document.write("<style type='text/css'>/*이하는 공통css*/body{background-color:#fff;color:#333;font-size:9pt;font-family:돋움,Dotum,AppleGothic,sans-serif;line-height:16px;text-align:center; } th, td, ul, li {color:#333;font-size:9pt;font-family:돋움,Dotum,AppleGothic,sans-serif;line-height:16px;}table {clear:both;} li{list-style:none;} fieldset,img{border:none;} address,em{font-style:normal;} input,textarea,select{background-color:#fff;font-family:inherit;} select{height:20px;} a{color:#333;text-decoration:none;font-family:돋움,Dotum,AppleGothic,sans-serif;} a:visited,a:visited {color:#333;font-family:돋움,Dotum,AppleGothic,sans-serif;} a:hover,a:hover{color:#333;text-decoration:underline;font-family:돋움,Dotum,AppleGothic,sans-serif;} button{border:none 0;background:none;cursor:pointer;_cursor /**/:hand;} ol,ul,li,dl,dt,dd,h4 { list-style:none; margin:0px; padding:0px; } html {-webkit-text-size-adjust: none;}/*이하는 추가css*/.tit_sns {position:relative;width:530px;background:url(http://img.mk.co.kr/sns/bg_stab1_bg.gif) repeat-x left top; height:28px;}.tit_sns_l {float:left;}.tit_sns_r  {float:right; height:28px;}.t_gray_txt_b {font-family:verdana; font-size:12px; font-weight:bold; color:#6f6f6f;}.t_gray_txt {font-family:verdana; font-size:11px; font-weight:bold; color:#6f6f6f;}.f_org {color:#e84c00}.input_sns {background:url(http://img.mk.co.kr/sns/bg_stable_bg.gif) repeat-x left top; height:110px; width:530px; border-left:1px solid #d9dcdf;  border-right:1px solid #d9dcdf; white-space:nowrap; overflow:hidden;}.sns_id {position:relative}.sns_id_l {float:left}.sns_id_r {float:right; padding-top:2px; padding-right:8px;}.input_style1 {border-top:1px solid #a2a1a1; border-left:1px solid #a2a1a1; border-right:1px solid #d5d4d4; border-bottom:1px solid #d5d4d4;width:442px; height:62px;}.input_style2 {border-top:1px solid #a2a1a1; border-left:1px solid #a2a1a1; border-right:1px solid #d5d4d4; border-bottom:1px solid #d5d4d4;width:360px; height:20px;}.tit_reple {position:relative; width:530px; text-align:left; margin-top:20px; background:url(http://img.mk.co.kr/sns/bg_tabr1.gif) repeat-x left top; height:29px; padding-top:5px;}.tab_reple {position:absolute; right:0; top:0}.tit_all {font-size:12px; font-weight:bold}.box_reple {position:relative;border-bottom:1px solid #e5e5e5; width:530px; margin-top:20px;padding-bottom:7px; white-space:nowrap; overflow:hidden;}.box_reple_l {float:left; width:67px; height:61px; background:url(http://img.mk.co.kr/sns/bg_img_back.gif) no-repeat 0 0 ; padding: 4px 0 0 4px; text-align:left}.box_reple_r {float:right; width:450px; text-align:left; position:relative;   text-align:left ; word-break:break-word; white-space:normal; }.reple_txt {padding-top:7px; word-break:break-word; white-space:normal; width:450px; }.reple_action {position:absolute; right:0; top:0;}.reple_action li {float:left}.action_p {padding-left:10px; padding-bottom:5px; padding-right:10px;}.input_reple {position:relative; margin-top:10px; border:1px solid #e2e2e6; background-color:#fafafc; padding:10px; text-align:center}.input_reple1 {position:relative; margin-top:10px; border:1px solid #e2e2e6; background-color:#fafafc; padding:10px; text-align:left }.input_close {position:absolute; right:8px; top:8px;}.popup {position:relative; width:400px; text-align:center}.popup_tit {border-bottom:1px solid #bac5d0; text-align:left; height:25px}.popup_close {position:absolute; right:2px; top:4px}</style><div id='mkadd_div' class='tit_sns'><div class='tit_sns_l'><img src='http://img.mk.co.kr/sns/t_sns_login.gif' alt='sns로그인'  border='0'></div><div class='tit_sns_r'><img src='http://img.mk.co.kr/sns/im_stab1_off.gif' alt='매일경제'  border='0' id='mkadd_maekyung_logo' style='cursor:pointer;'/><img src='http://img.mk.co.kr/sns/im_stab2_off.gif' alt='트위터'  border='0' id='mkadd_twitter_logo' style='cursor:pointer;'/><img src='http://img.mk.co.kr/sns/im_stab3_off.gif' alt='페이스북'  border='0' id='mkadd_facebook_logo' style='cursor:pointer;'/><img src='http://img.mk.co.kr/sns/im_stab4_off.gif' alt='미투데이'  border='0' id='mkadd_metoday_logo' style='cursor:pointer;' /><img src='http://img.mk.co.kr/sns/im_stab5_off.gif' alt='요즘'  border='0' id='mkadd_yozm_logo' style='cursor:pointer;'/><img src='http://img.mk.co.kr/sns/im_stab6_off.gif' alt='싸이월드'  border='0' id='mkadd_cyworld_logo' style='cursor:pointer;'/></div></div><div class='input_sns'><table style='margin-top:5px;' border='0' cellpadding='0' cellspacing='0'><tr><td><div class='sns_id'><ul><li class='sns_id_l'><img id='mkadd_representation_img' src='http://kf-apac.zenfs.com/01fe71e6-ac1f-4357-9466-b1c3dc9c44ea/logo_big' border='0' align='absmiddle' style='width:24px;height:24px;'> <span class='t_gray_txt' id='mkadd_representation_span'></span></li><li class='sns_id_r'> <span class='t_gray_txt' id='mkadd_check_length'>0/140</span></li></ul></div></td><td><img src='http://img.mk.co.kr/sns/b_logout.gif' alt='전체로그아웃'  border='0' id='mkadd_alllogout_btn' style='cursor:pointer;'/></td></tr><tr><td><textarea class='input_style1' id='mkadd_text'></textarea></td><td style='padding-left:2px; padding-top:2px'><img src='http://img.mk.co.kr/sns/b_login.gif' id='mkadd_register_btn' alt='로그인'  border='0' style='cursor:pointer;' /></td></tr></table></div><h5 class='tit_reple'><span class='tit_all'>전체댓글수</span>  &nbsp; <span class='t_gray_txt_b f_org' id='mkadd_total_cnt'></span><div class='tab_reple'><img src='http://img.mk.co.kr/sns/im_tabr1_on.gif' alt='최신순'  border='0'  style='margin-left:2px;cursor:pointer;' id='mkadd_list_recent'/><img src='http://img.mk.co.kr/sns/im_tabr2_off.gif' alt='추천순' border='0'  style='margin-left:2px;cursor:pointer;' id='mkadd_list_good'/><img src='http://img.mk.co.kr/sns/im_tabr3_off.gif' alt='반대순'  border='0'  style='margin-left:2px;cursor:pointer;' id='mkadd_list_bad'/></div></h5><div id='mkadd_div2'></div>\
		<div style='margin: 15px 8px 15px 22px; padding: 10px; background-color: rgb(243, 243, 243); border: 1px solid rgb(228, 228, 228);display:none; text-align: center;cursor: pointer;' id='mkadd_showmorediv' cmt='더보기 DIV'><img border='0'  alt='' src='http://img.mk.co.kr/estate/twit/b_mktwit_more.gif'></div>\
		<div id='mkadd_coverdiv' style='z-index:3;display:none;background-color:#000000;position:absolute;top:0;left:0;opacity:0.5;filter: alpha(opacity=50);cursor:pointer;' cmt='alertDiv 가리는 전체 DIV'></div><div id='mkadd_alertdiv' style='z-index:4;display:none;width:350px;height:70px;position:absolute;top:0;left:0;background-color:#ffffff;border:1px solid #000000;font-size:18px;padding-top:30px;cursor:pointer;' cmt='alertDiv 내에서 코멘트창 보여주는 DIV'><div id='mkadd_alertdiv_txt' cmt='alertDiv 내에서 글귀 보여주는 DIV'></div></div>");

		/*최신/추천/반대순 이벤트 달기*/
		$("#mkadd_list_recent").click(function(){
			/*이미지교체*/
			$("#mkadd_list_recent").attr("src",$("#mkadd_list_recent").attr("src").replace("off","on"));
			$("#mkadd_list_good").attr("src",$("#mkadd_list_good").attr("src").replace("on","off"));
			$("#mkadd_list_bad").attr("src",$("#mkadd_list_bad").attr("src").replace("on","off"));
			MkAdd.lists_type='recent';
			/*초기화 로딩*/
			MkAdd.loadAdd("new"); // 처음으로 불러올 경우에는 최신

		});
		/*추천순으로 정렬하는 이벤트 달기:mkadd_list_good*/
		$("#mkadd_list_good").click(function(){
			$("#mkadd_list_recent").attr("src",$("#mkadd_list_recent").attr("src").replace("on","off"));
			$("#mkadd_list_good").attr("src",$("#mkadd_list_good").attr("src").replace("off","on"));
			$("#mkadd_list_bad").attr("src",$("#mkadd_list_bad").attr("src").replace("on","off"));
			MkAdd.lists_type='good';
			/*초기화 로딩*/
			MkAdd.loadAdd("new"); // 처음으로 불러올 경우에는 최신
		});
		/*반대순으로 정렬하는 이벤트 달기:mkadd_list_bad*/
		$("#mkadd_list_bad").click(function(){
			$("#mkadd_list_recent").attr("src",$("#mkadd_list_recent").attr("src").replace("on","off"));
			$("#mkadd_list_good").attr("src",$("#mkadd_list_good").attr("src").replace("on","off"));
			$("#mkadd_list_bad").attr("src",$("#mkadd_list_bad").attr("src").replace("off","on"));
			MkAdd.lists_type='bad';
			/*초기화 로딩*/
			MkAdd.loadAdd("new"); // 처음으로 불러올 경우에는 최신
		});

		/*등록버튼 이벤트 달기*/
		$("#mkadd_register_btn").click(function(){
			MkAdd.registAdd($("#mkadd_text").val());
		});

		/*글 불러오기(초기화)*/
		this.loadAdd("new"); // 처음으로 불러올 경우에는 최신

		/*글 불러오기 더보기 버튼 이벤트*/
		jQuery("#mkadd_showmorediv").click(function(){MkAdd.loadAdd("add");});

		/*로그인 확인*/
		$.getJSON(this.touch_url+"action_mysql.php?cmd=check_login&callback=?",function(data){
			var result=data.result;
			var hometown=data.hometown;
			MkAdd.result=result;
			MkAdd.hometown=hometown;
			var hometown_obj=null;
			for(i=0;i<result.length;i++){
				/*버튼세팅*/
				result[i].idx=i;
				MkAdd.initSub(result[i]);
				//대표계정 obj 찾기
				if(result[i].type==hometown){hometown_obj=result[i];}
			}

			/*대표계정 설정*/
			if(hometown_obj!=null) {
				MkAdd.setCommon(hometown_obj);
				/*전체 로그아웃 버튼 설정*/
				$("#mkadd_alllogout_btn").click(function(){
					if(confirm('전체 로그아웃을 하시겠습니까?')) {
						//window.open(MkAdd.touch_url+"action_mysql.php?cmd=action_alllogout",'mkpop');
						var mkpop= window.open('','mkpop');
						mkpop.location.href=MkAdd.touch_url+"action_mysql.php?cmd=action_alllogout";
					}
				});
			}
		});
	},
	initSub:function(obj){
		var _url=obj.rtnUrl;
		var _type= obj.type;
		var _usage= obj.usage;
		var _userpic= obj.userpic;
		var _usernick= obj.usernick;
		var _check_login= obj.check_login;
		var _idx=obj.idx;
		var tmp_logo=$("#mkadd_"+_type+"_logo").attr("src");
		if(_check_login ==1){	/*로그인상태*/
			var html="<div class='popup' id='mkadd_"+_type+"_sub_div' style='z-index:10;display:none;position:absolute;top:30px;left:"+(80+(_idx*47))+"px;background-color:#ffffff;border:1px solid #000000;'><h5 class='popup_tit'><img src='"+_userpic+"' width='24' height='24' border='0' align='absmiddle' /> <span class='t_gray_txt'>"+_usernick+"</span> </h5><div class='popup_close'><img src='http://img.mk.co.kr/sns/ic_close.png' alt='닫기' border='0' style='cursor:pointer;' alt='닫기' id='mkadd_"+_type+"_sub_close_btn'/></div><img src='http://img.mk.co.kr/sns/bt_me.gif' alt='대표계정'  border='0' style='cursor:pointer;' id='mkadd_"+_type+"_sub_chief_btn'/>&nbsp;"+((_usage=='Y')?"<img src='http://img.mk.co.kr/sns/bt_stop.gif' alt='전송중지'  border='0' style='cursor:pointer;' id='mkadd_"+_type+"_sub_usage_btn'/>":"<img src='http://img.mk.co.kr/sns/bt_send.gif' alt='전송'  border='0' style='cursor:pointer;' id='mkadd_"+_type+"_sub_usage_btn'/>")+"<img src='http://img.mk.co.kr/sns/bt_logout.gif' alt='로그아웃'  border='0' style='cursor:pointer;'  id='mkadd_"+_type+"_sub_logout_btn'/></div>";
			$("#mkadd_div").append(html);
			/*전체DIV*/
			$("#mkadd_"+_type+"_logo").attr("src",tmp_logo.replace("off","on")).click(function(){
				$("#mkadd_"+_type+"_sub_div").toggle('fast');
			});
			/*닫기버튼*/
			$("#mkadd_"+_type+"_sub_close_btn").click(function(){
				$("#mkadd_"+_type+"_sub_div").toggle('fast');
			});
			/*대표계정버튼*/
			$("#mkadd_"+_type+"_sub_chief_btn").click(function(){
				MkAdd.setCommon(obj);
				$("#mkadd_"+_type+"_sub_div").toggle('fast');
				$.getJSON(MkAdd.touch_url+"action_mysql.php?cmd=action_modify_commontype&hometown="+_type+"&callback=?",function(data){});
			});
			/*전송중지버튼*/
			$("#mkadd_"+_type+"_sub_usage_btn").click(function(){
				if(MkAdd.result[obj.idx].usage=='Y'){
					if(confirm(_type+'로 전송을 중지하시겠습니까?')) {
						/*대표계정일 경우에는 교체함*/
						if(MkAdd.result[obj.idx].type==MkAdd.hometown){
							alert('대표계정을 바꾸어 주신 후에 전송 중지를 선택하실 수 있습니다.');
						}else{
							MkAdd.result[obj.idx].usage='N';
							/*서버로 전송하기*/
							$.getJSON(MkAdd.touch_url+"action_mysql.php?cmd=action_modify_usage&type="+MkAdd.result[obj.idx].type+"&usage="+MkAdd.result[obj.idx].usage+"&callback=?",function(data){
								/*이미지교체*/
							$("#mkadd_"+_type+"_sub_usage_btn").attr("src","http://img.mk.co.kr/sns/bt_send.gif");
							});
						}
					}
				}
				else{
					if(confirm(_type+'로 전송을 허용하시겠습니까?')) {
						MkAdd.result[obj.idx].usage='Y';
						/*서버로 전송하기*/
						$.getJSON(MkAdd.touch_url+"action_mysql.php?cmd=action_modify_usage&type="+MkAdd.result[obj.idx].type+"&usage="+MkAdd.result[obj.idx].usage+"&callback=?",function(data){
							/*이미지교체*/
								$("#mkadd_"+_type+"_sub_usage_btn").attr("src","http://img.mk.co.kr/sns/bt_stop.gif");
						});
					}
				}
			});
			/*로그아웃버튼*/
			$("#mkadd_"+_type+"_sub_logout_btn").click(function(){
				if(confirm(_type+'에서 로그아웃 하시겠습니까?')) {
					var mkpop= window.open('','mkpop');
					mkpop.location.href=_url;
				}
			});
		}
		else if(_check_login ==-1){/*로그아웃상태*/
			$("#mkadd_"+_type+"_logo").attr("src",tmp_logo.replace("on","off")).click(function(){
				if(confirm(_type+'에 로그인 하시겠습니까?')) {
					/*Referer 정보가 제대로 가지 않으므로 팝업 열리는 방식을 수정*/
					var mkpop= window.open('','mkpop');
					mkpop.location.href=_url;
				}
			});
		}
		/*글자 갯수 확인*/
		$("#mkadd_text").bind('keyup', function(e){
				var str_length=MkAdd.doCheckCount($("#mkadd_text").val());
				if(str_length>140) {
					$("#mkadd_check_length").html("<font color='red'>"+str_length+"</font>/140");
					if(e.which!=8 && e.which!=9 && e.which!=13 && e.which!=17 && e.which!=18 && e.which!=20 && e.which!=27 && e.which!=46 && e.which!=229){
						MkAdd.showAlertDiv("140자 까지만 입력이 가능합니다.", "mkadd_text");
					}
				}
				else{
					$("#mkadd_check_length").html(str_length+"/140");
				}
			}
		);
	},
	setCommon:function(obj){/*----------------------------------------------------- 대표계정설정!!!!!!!!!!!!*/
		$("#mkadd_representation_img").attr("src",obj.userpic);
		$("#mkadd_representation_span").html(obj.usernick)
	},
	registAdd:function(str){	/*----------------------------------------------------- 등록하기!!!!!!!!!!!!*/
		var obj=this.result;
		var type='';
		for(i=0;i<obj.length;i++){
			if(obj[i].usage=='Y') {
				type+=obj[i].type+"_";
			}
		}
		/*글이 입력되었는지 확인*/
		if(this.hometown==null){alert('로그인을 해주시기 바랍니다.');return;}
		if(str=='' || str==null){ alert('글을 입력해 주세요~');$("#mkadd_text").focus(); return;}

		/*글의 갯수 확인*/
		var str_length=MkAdd.doCheckCount($("#mkadd_text").val());
		if(str_length>140) {
			$("#mkadd_check_length").html("<font color='red'>"+str_length+"</font>/140");
			MkAdd.showAlertDiv("140자 까지만 입력이 가능합니다.", "mkadd_text");
		}

		$.getJSON(this.touch_url+"action_mysql.php?cmd=action_regist&cat_01="+encodeURIComponent(this.cat_01)+"&cat_02="+encodeURIComponent(this.cat_02)+"&title="+encodeURIComponent(this.title)+"&unique_id="+this.unique_id+"&app_id="+this.app_id+"&type="+type+"&str="+encodeURIComponent(str)+"&from_url="+encodeURIComponent(this.from_url)+"&callback=?",
			function(data){
				if(data.flag!='undefined' && data.flag=='-1'){
					alert(data.errlog);
				}else{
					MkAdd.checkResultForRegist(data.yozm,"요즘");
					MkAdd.checkResultForRegist(data.facebook,"페이스북");
					MkAdd.checkResultForRegist(data.twitter,"트위터");
					MkAdd.checkResultForRegist(data.metoday,"미투데이");
					MkAdd.checkResultForRegist(data.cyworld,"싸이로그");
					MkAdd.checkResultForRegist(data.maekyung,"매경");

					/*초기화 로딩*/
					MkAdd.loadAdd("new"); // 처음으로 불러올 경우에는 최신
					
					/*등록박스 초기화*/
					$("#mkadd_text").val("");
				}
			});
	},
	removeAdd:function(_id){	/*----------------------------------------------------- 삭제!!!!!!!!!!!!*/
		$.getJSON(this.touch_url+"action_mysql.php?cmd=action_remove&_id="+_id+"&callback=?",
			function(data){
				MkAdd.checkResultForRemove(data.yozm,"요즘");
				MkAdd.checkResultForRemove(data.facebook,"페이스북");
				MkAdd.checkResultForRemove(data.twitter,"트위터");
				MkAdd.checkResultForRemove(data.metoday,"미투데이");
				MkAdd.checkResultForRemove(data.cyworld,"싸이로그");
				MkAdd.checkResultForRemove(data.maekyung,"매경");
				/*초기화 로딩*/
				MkAdd.loadAdd("new"); // 처음으로 불러올 경우에는 최신
			});
	},
	loadAdd:function(flag){/*----------------------------------------------------- 불러오기!!!!!!!!!!!!*/
		if(flag=='new'){
			this.lists_last_regdate='';
		}
		$.getJSON(this.touch_url+"action_mysql.php?cmd=action_list&lists_type="+this.lists_type+"&lists_maxlist="+this.lists_maxlist+"&lists_last_regdate="+this.lists_last_regdate+"&unique_id="+encodeURIComponent(this.unique_id)+"&callback=?",
			function(data){
				if(flag=='new'){/*초기화 로딩*/
					$("#mkadd_div2").html("");
				}
				MkAdd.lists=data.lists;
				MkAdd.lists_last_regdate=data.lists_last_regdate;
				var obj=MkAdd.lists;
				var html="";
				for(i=0;i<obj.length;i++){
					var tmp=MkAdd.loadAddTimeline(obj[i]);
					/*댓글 붙이기*/
					$("#mkadd_div2").append(tmp);
					/*이벤트 붙이기*/
					MkAdd.bindEventTimeline(obj[i]._id);
				}
				/*전체 댓글수 수정*/
				$("#mkadd_total_cnt").html(MkAdd.addCommas(data.total_no));
				/*더보기버튼 노출유무 확인*/
				if(MkAdd.lists.length < MkAdd.lists_maxlist){
					jQuery("#mkadd_showmorediv").hide();
				}
				else{
					jQuery("#mkadd_showmorediv").show('slow');
				}
		});
	},
	bindEventTimeline:function(_id){	/*타임라인 이벤트 붙이기*/
		$("#mkadd_"+_id+"_del_btn").click(function(){if(confirm('삭제하시겠습니까?')){MkAdd.removeAdd(_id);}});	/*삭제*/
		$("#mkadd_"+_id+"_add_btn").click(function(){});	/*댓글*/
		$("#mkadd_"+_id+"_good_btn").click(function(){	/*추천*/
			$.getJSON(MkAdd.touch_url+"action_mysql.php?cmd=action_update_click&_id="+_id+"&_clicked_type=g&callback=?",
				function(data){
					/*해당데이타 업데이트*/
					if(data.flag>0){
						var total=data.total;
						$("#mkadd_"+_id+"_good_txt").html(total);
					}
					else if(data.flag<0){
						MkAdd.showAlertDiv("이미 추천하셨습니다.",null);
					}
			});
		});/*추천*/
		$("#mkadd_"+_id+"_bad_btn").click(function(){
			$.getJSON(MkAdd.touch_url+"action_mysql.php?cmd=action_update_click&_id="+_id+"&_clicked_type=b&callback=?",
				function(data){
					if(data.flag>0){
						var total=data.total;
						$("#mkadd_"+_id+"_bad_txt").html(total);
					}
					else if(data.flag<0){
						MkAdd.showAlertDiv("이미 반대하셨습니다.",null);
					}
			});
		});/*반대*/
		$("#mkadd_"+_id+"_bad2_btn").click(function(){
			$.getJSON(MkAdd.touch_url+"action_mysql.php?cmd=action_update_click&_id="+_id+"&_clicked_type=b2&callback=?",
				function(data){
					if(data.flag<0){
						$("#mkadd_"+_id+"_bad2_btn").attr("src","http://img.mk.co.kr/sns/ic_alram_on.gif");
						MkAdd.showAlertDiv("이미 신고하셨습니다.",null);
					}else{
						$("#mkadd_"+_id+"_bad2_btn").attr("src","http://img.mk.co.kr/sns/ic_alram_on.gif");
						MkAdd.showAlertDiv("신고하셨습니다.",null);
					}
			});
		});/*신고*/
	},
	loadAddTimeline:function(obj){	/*타임라인 디자인 생성후 결과값 리턴*/
		var result="";
		var user_id= obj._id;
		var user_name= obj.user_name;
		var user_pic=obj.user_pic;
		var user_text= obj.text;
		var user_regdate= obj.reg_date;
		var user_good=obj.good;
		var user_bad=obj.bad;
		var user_bad2=obj.bad2;
		var user_regdate=obj.regdate;
		var user_del=obj.del_yn;
		var user_hometown=obj.hometown;
		var user_hometown_pic='';
		for( var key in this.company_imgarr){
			if(key==user_hometown){
				user_hometown_pic=this.company_imgarr[key];
			}
		}
		result="<div class='box_reple'><div class='box_reple_l'><img src='"+user_pic+"' alt='' width='50' height='50' border='0'></div><div class='box_reple_r'><img src='"+user_hometown_pic+"' border='0' align='absmiddle'> <span class='t_gray_txt'>"+user_name+"</span> "+((user_del=='Y')?"<img src='http://img.mk.co.kr/sns/ic_close.png' style='cursor:pointer;' id='mkadd_"+user_id+"_del_btn'/>":"")+"<br><div class='reple_txt'>"+user_text+"&nbsp; "+this.calculateCurtime(user_regdate)+"<!--<img src='http://img.mk.co.kr/sns/ic_replay.gif' alt='댓글달기' border='0' align='absmiddle' id='mkadd_"+user_id+"_add_btn' style='cursor:pointer;'/>--></div><div class='reple_action'><ul><li><img src='http://img.mk.co.kr/sns/ic_bestc.gif' alt='추천'  border='0' align='absmiddle' style='cursor:pointer;' id='mkadd_"+user_id+"_good_btn' /></li><li class='action_p'> <span class='t_gray_txt f_org' id='mkadd_"+user_id+"_good_txt' >"+user_good+"</span></li><li><img src='http://img.mk.co.kr/sns/ic_deny.gif' alt='반대'  border='0' align='absmiddle' style='cursor:pointer;' id='mkadd_"+user_id+"_bad_btn'></li><li class='action_p'> <span class='t_gray_txt' id='mkadd_"+user_id+"_bad_txt' >"+user_bad+"</span></li><li><img src='http://img.mk.co.kr/sns/ic_alram.gif' alt='신고'  border='0' align='absmiddle' style='cursor:pointer;' id='mkadd_"+user_id+"_bad2_btn'></li></ul></div></div></div>";
		$("#mkadd_"+user_id+"_delbtn").click(function(){alert('a');});
		return result;
	},
	checkResultForRegist:function(obj,str){	/*-----------------------------------------------------등록후action!!!!!!!!!!!!*/
		if(typeof(obj)!='undefined' && obj.flag<0) {
			alert(str+' 서버 상황이 문제가 있어서 해당 서버에 등록을 하지 못했습니다.');
			return;
		}
	},
	checkResultForRemove:function(obj,str){	/*-----------------------------------------------------삭제후action!!!!!!!!!!!!*/
		if(typeof(obj)!='undefined' && obj.flag==-1) {
			alert(str+' 서버 상황이 문제가 있어서 해당 서버에 삭제를 하지 못했습니다.');
			return;
		}else if( typeof(obj)!='undefined' && obj.flag==-2){
			alert(' 권한이 없습니다. ');
			return;
		}
	},
	showAlertDiv:function(message, obj_id){/*----------------------------------------------------- 경고창DIV 보이기!!!!!!!!!!!!*/
		var w=jQuery(window).width();
		var h=jQuery(document).height();
		var target_h=0;
		if(obj_id!=null) {target_h=jQuery("#"+obj_id).position().top;}
		else{target_h=jQuery(window).scrollTop()+300;}
		/*전체 덮어 씌우기*/
		jQuery("#mkadd_coverdiv").css({'display':'block'}).height(h).width(w).click(function(){MkAdd.hideAlertDiv();}).focus();
		/*해당하는 id 위치에 div 보여줌*/
		jQuery("#mkadd_alertdiv").css({"display":"block","top":target_h,"left":w/2-170}).html(message).click(function(){jQuery("#"+obj_id).focus();MkAdd.hideAlertDiv();});
		/*키가 입력되었을 경우에는 닫힘*/
		jQuery(document).keydown(function(){jQuery("#"+obj_id).focus();MkAdd.hideAlertDiv();});
	},
	hideAlertDiv:function(){/*----------------------------------------------------- 경고창DIV 숨기기!!!!!!!!!!!!*/
			$("#mkadd_alertdiv").css({"display":"none"});
			$("#mkadd_coverdiv").css({"display":"none"});
	},
	doCheckCount:function(val){
		var len=val.length;
		var temp=val;
		var bytes=0;
		for(var k=0;k<len;k++){
			var onechar=temp.charAt(k);
			if(escape(onechar).length>4){
				bytes+=1; /* 한글도 1개로 출력*/
			}else if(onechar!='\r'){
				bytes+=1;
			}
		}
		return bytes;
	},
	calculateCurtime:function(ptime){/*현재시간-ptime > 결과값 */
		var ctimeObj= new Date();
		var ctime=ctimeObj.getTime();
		var msec= parseInt(ctime/1000)-ptime;	/*msec초전*/
		if(msec<60) {/*return msec+"초 전";*/ 
			return "방금";
		}
		else if(msec<60*60) return parseInt(msec/60)+"분 전";
		else if(msec<60*60*24) return parseInt(msec/(60*60))+"시간 전";
		else return parseInt(msec/(60*60*24))+"일 전";
	},
	addCommas:function(nStr){
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

};

