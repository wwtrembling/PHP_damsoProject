<?
/*
리스트/삭제/등록/수정 action 통합 페이지
*/
session_start();
if(empty($_SERVER['HTTP_REFERER'])) exit; // Referer 체크
require('./common/MKAdd.conf.php');
if(!defined("checksum")) exit;
require($mkadd_common_dir."MkAddDB.class.php");	// 몽고DB 댓글 클래스
require($mkadd_common_dir."MkClickedDB.class.php");	// 몽고DB 댓글 추천/반대/신고 클래스
require($mkadd_common_dir."MkFilterDB.class.php");	// 몽고DB 금지아이디 클래스
require(admin_dir."lib/MkAddMemDB.class.php");	// 몽고DB 회원 관리 클래스
require($mkadd_common_dir."MkAdd.func.php");		//매경댓글 서비스 공통 php 함수

//Default Parameter setting
$cmd=isset($_GET['cmd'])?$_GET['cmd']:exit;
$type=isset($_GET['type'])?$_GET['type']:'yozm';

//---------------------------------- 로그인 상태/로그아웃 상태 체크
if($cmd=='check_login'){
	function calRtn($type, $chk,$mkadd_url){
		$result=array();
		$result['type']=$type;
		$result['check_login']=$chk;
		if($chk==1){
			$result['userid']=$_SESSION[$type."_userid"];
			$result['usernick']=$_SESSION[$type."_usernick"];
			$result['userpic']=$_SESSION[$type."_userpic"];
			$result['usage']=$_SESSION[$type."_usage"];
			$result['rtnUrl']=$mkadd_url."action.php?type=".$type."&cmd=action_logout";	// 로그아웃 url 리턴
		}
		else if($chk==-1){
			$result['rtnUrl']=$mkadd_url."action_auth_".$type.".php";		// 로그인 URL 리턴
		}
		return $result;
	}
	$result_obj=array();
	$hometown=null;
	$result_obj_flag=false;	// 로그인 된 것이 있는지 확인하는 flag
	for($i=0,$j=0;$i<count($mkadd_company);$i++){
		$type=$mkadd_company[$i];
		if(!isset($_SESSION[$type.'_request_token'])) $_SESSION[$type.'_request_token']=null;
		if(!isset($_SESSION[$type.'_request_token_secret'])) $_SESSION[$type.'_request_token_secret']=null;
		if(!isset($_SESSION[$type.'_access_token'])) $_SESSION[$type.'_access_token']=null;
		if(!isset($_SESSION[$type.'_access_token_secret'])) $_SESSION[$type.'_access_token_secret']=null;
		$class_file="./".$type."/Mk".$type.".class.php";
		if(!is_file($class_file))  continue;
		include($class_file);
		$class_name="Mk".$type;
		$cls= new $class_name($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'], $_SESSION[$type.'_access_token'], $_SESSION[$type.'_access_token_secret']);
		$result_obj[$j]=calRtn($mkadd_company[$i], $cls->checkAuth(),$mkadd_url);
		if($hometown==null && $result_obj[$j]['check_login']==1){
			$hometown=$result_obj[$j]['type'];
			$result_obj_flag=true;
		}
		$j++;
		unset($cls);
	}
	$result['result']=$result_obj;

	//대표계정 설정여부 확인해서 없을 경우에는 로그인된 첫 type을 반환한다. 없을 경우에는 -1반환
	if(isset($_SESSION['hometown']) && $_SESSION['hometown']){
		$hometown=$_SESSION['hometown'];
	}
	else if($result_obj_flag==false){
		unset($_SESSION['hometown']);
	}
	else{
		$_SESSION['hometown']=$hometown;
	}
	//대표계정정보 보내주기
	$result['hometown']=$hometown;
	$jsonData=json_encode($result);
	echo $_GET["callback"] . "(" . $jsonData . ");";
	exit;
}
//---------------------------------- 로그인 action >> ./action_auth_소셜서비스명.php 에서 수행함
//---------------------------------- 로그아웃 action
else if($cmd=='action_logout'){
	unset($_SESSION[$type.'_request_token']);
	unset($_SESSION[$type.'_request_token_secret']);
	unset($_SESSION[$type.'_access_token']);
	unset($_SESSION[$type.'_access_token_secret']);
	unset($_SESSION[$type.'_userid']);
	unset($_SESSION[$type.'_usernick']);
	unset($_SESSION[$type.'_userpic']);
	unset($_SESSION[$type.'_usage']);
	if($_SESSION['hometown']==$type) {unset($_SESSION['hometown']);}
	$http_referrer=$_SERVER['HTTP_REFERER'];	// 추가
	echo "<script type='text/javascript'>location.href='".$http_referrer."';</script>";
}//---------------------------------- 전체로그아웃 action
else if($cmd=='action_alllogout'){
	foreach($mkadd_company as $key=>$type){
		unset($_SESSION[$type.'_request_token']);
		unset($_SESSION[$type.'_request_token_secret']);
		unset($_SESSION[$type.'_access_token']);
		unset($_SESSION[$type.'_access_token_secret']);
		unset($_SESSION[$type.'_userid']);
		unset($_SESSION[$type.'_usernick']);
		unset($_SESSION[$type.'_userpic']);
		unset($_SESSION[$type.'_usage']);
	}
	unset($_SESSION['hometown']);
	$http_referrer=$_SERVER['HTTP_REFERER'];	// 추가
	echo "<script type='text/javascript'>location.href='".$http_referrer."';</script>";
}
//---------------------------------- 전송상태 변경
else if($cmd=='action_modify_usage'){
	$type=trim($_GET['type']);
	$usage=trim($_GET['usage']);
	$_SESSION[$type.'_usage']=$usage;
	$result=array();
	$result['type']=$type;
	$result['usage']=$usage;
	$jsonData=json_encode($result);
	echo $_GET["callback"] . "(" . $jsonData . ");";
	exit;
}
//---------------------------------- 대표계정 변경
else if($cmd=='action_modify_commontype'){
	$hometown=trim($_GET['hometown']);
	$_SESSION['hometown']=$hometown;
	$result=array();
	$result['hometown']=$hometown;
	$jsonData=json_encode($result);
	echo $_GET["callback"] . "(" . $jsonData . ");";
	exit;
}
//---------------------------------- 리스팅
else if($cmd=='action_list'){
	//필수 파라미터 받아오기
	$unique_id=trim($_GET['unique_id']);	//뉴스페이지 고유 아이디
	$lists_type=htmlspecialchars($_GET['lists_type']);
	$lists_type=(!empty($lists_type)?$lists_type:"recent");		// recent:최신순, good:추천순, bad:반대순
	$lists_maxlist=intval($_GET['lists_maxlist']);						//받아올 댓글 데이터 page당 갯수
	$lists_last_regdate=(isset($_GET['lists_last_regdate'])?htmlspecialchars($_GET['lists_last_regdate']):'');//받아올 댓글 데이터에서 가장 오래된 데이터 regdate

	if(!empty($unique_id) && !empty($lists_type)){
		require_once($mkadd_common_dir."MkAddDB.class.php");	 // 기본 INTERFACE
		$db= new MkAddDB();
		//조건 조합
		$cond=array("unique_id"=>$unique_id,"usage"=>"Y");	// 기본 조건
		if(!empty($lists_last_regdate)){ $cond['regdate']=array('$lt'=>$lists_last_regdate);}
		$list=array();
		$fields=array("user_id"=>1, "user_name"=>1,"user_pic"=>1,"text"=>1,"regdate"=>1,"good"=>1,"bad"=>1,"hometown"=>1);
		if($lists_type=='recent') { $list=$db->lists($fields,$cond, 0, $lists_maxlist, array('_id'=>-1)); }		// 최신순 >> 내림차순
		else if($lists_type=='good') { $list=$db->lists($fields,$cond, 0, $lists_maxlist, array('good'=>-1)); }		// 추천순 >> 내림차순
		else 	if($lists_type=='bad') { $list=$db->lists($fields,$cond, 0, $lists_maxlist, array('bad'=>-1)); }		// 반대순 >> 내림차순
	
		//권한부여해서 삭제여부까지 리턴함
		$max=count($list);
		for($i=0;$i<$max;$i++){
			$row=$list[$i];
			$hometown=$row['hometown'];
			$user_id=$row['user_id'];
			if(isset($_SESSION[$hometown."_userid"])){
				$s_user_id=$_SESSION[$hometown."_userid"];
				if(trim($user_id) && $user_id==$s_user_id){
					$row['del_yn']='Y';
				}else{
					$row['del_yn']='N';
				}
				$row['text']=trim(str_replace("\n","<br/>",$row['text']));
				$list[$i]=$row;
			}
		}
		$resultData=array();
		$resultData['lists']=$list;
		$resultData['total_no']=$db->listsCount(array("unique_id"=>$unique_id,"usage"=>"Y"));
		$resultData['lists_last_regdate']=@$list[(count($list)-1)]['regdate'];
		$jsonData=json_encode($resultData);
		echo $_GET["callback"] . "(" . $jsonData . ");";
	}
}
else if($cmd=='action_regist'){	//---------------------------------- 등록
	$result=array();	// 최종 결과값
	$str=trim($_GET['str']);
	$from_url=trim($_GET['from_url']);
	$cat_01=trim($_GET['cat_01']);
	$cat_02=trim($_GET['cat_02']);
	$title=trim($_GET['title']);
	$unique_id=trim($_GET['unique_id']);
	$app_id=trim($_GET['app_id']);
	$hometown=$_SESSION['hometown'];

	//댓글의 bytes 확인----------------------------------------------------------------------------------------------------------------------------------------
	$cnt_str=countLetters($str);
	if($cnt_str>140) {
		$result=array();
		$result['flag']="-1";	//error
		$result['errlog']="댓글은 140자를 넘기실 경우에는 정상적으로 등록이 되지 않습니다.";	//error
		$jsonData=json_encode($result);
		echo $_GET["callback"] . "(" . $jsonData . ");";
		exit;
	}

	//필터링 모듈 시작----------------------------------------------------------------------------------------------------------------------------------------
	$keyword_file=upload_dir."forbidden_keyword_".$app_id.".dat";
	if(is_file($keyword_file)) {
		$fcontent=file_get_contents($keyword_file);
		if(!empty($fcontent)){
			$tmp=explode("^",$fcontent);
			foreach($tmp as $key=>$val){
				preg_match("/(".$val.")+/",$str,$out);
				if(count($out)>0){
					$result=array();
					$result['flag']="-1";	//error
					$result['errlog']="금지어(".$val.")가 포함되어 있습니다.";	//error
					$jsonData=json_encode($result);
					echo $_GET["callback"] . "(" . $jsonData . ");";
					exit;
				}
			}
		}
	}

	//금지아이디 확인----------------------------------------------------------------------------------------------------------------------------------------
	$cls_filter= new MkFilterDB();
	$cond=array();
	$cond['app_id']=$app_id;
	$cond['filter_uid_hometown']=$_SESSION['hometown'];
	$cond['filter_uid']=$_SESSION[$hometown."_userid"];
	$lists_forbidden_id=$cls_filter->lists(null, $cond);
	if(is_array($lists_forbidden_id) && !empty($lists_forbidden_id)){
		$result=array();
		$result['flag']="-1";	//error
		$result['errlog']="등록이 금지된 아이디 입니다.";	//error
		$jsonData=json_encode($result);
		echo $_GET["callback"] . "(" . $jsonData . ");";
		exit;
	}


	//app_id 확인----------------------------------------------------------------------------------------------------------------------------------------
	$cls_mem= new MkAddMemDB();
	$cond=array();
	$cond['app_id']=trim($_GET['app_id']);
	$cond['use_yn']='Y';
	$lists_mem=$cls_mem->lists(null, $cond);
	if(empty($lists_mem)){
		$result=array();
		$result['flag']="-1";	//error
		$result['errlog']="Application ID를 확인해 주세요";	//error
		$jsonData=json_encode($result);
		echo $_GET["callback"] . "(" . $jsonData . ");";
		exit;
	}

	//중복 댓글을 hash 값을 이용하여 확인----------------------------------------------------------------------------------------------------------------------------------------
	$db= new MkAddDB();
	$cond=array();
	$out=array();
	$cond['app_id']=trim($_GET['app_id']);
	$cond['text_hash']=md5($str);
	$out=$db->lists(null, $cond);
	if(!empty($out)) {
		$result=array();
		$result['flag']="-1";	//error
		$result['errlog']="중복된 댓글 입니다.";	//error
		$jsonData=json_encode($result);
		echo $_GET["callback"] . "(" . $jsonData . ");";
		exit;
	}
	unset($out);
	unset($result);
	unset($cond);


	//댓글 등록----------------------------------------------------------------------------------------------------------------------------------------
	$type_arr=explode("_",$type);
	//Mkconfig.php 와 일치하는지 확인
	$new_type=array();
	foreach($type_arr as $key=>$val){
		$idx=@array_search($val, $mkadd_company );
		if($idx!==false) $new_type[]=$val;
	}
	$type_arr=$new_type;
	$max=count($type_arr);
	for($i=0;$i<$max;$i++){
		$type=$type_arr[$i];
		$class_file="./".$type."/Mk".$type.".class.php";
		if(!is_file($class_file))  continue;
		include($class_file);
		$class_name="Mk".$type;
		$cls= new $class_name($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'], $_SESSION[$type.'_access_token'], $_SESSION[$type.'_access_token_secret']);
		$flag=$cls->checkAuth();// 권한 확인
		if($flag>0) {
			$rtn_uid=$cls->mkWrite($str." ".$from_url);
			if(isset($rtn_uid) && !empty($rtn_uid)){
				$result[$type]['flag']=1;	//social service ok!
				$result[$type]['rtn_uid']=$rtn_uid;	//social service ok!
			}
			else{
				$result[$type]['flag']=-1;	//social service error!
				$result[$type]['rtn_uid']=null;
			}
		}
	}

	//mk DB에 저장
	//$db= new MkAddDB(); // 위에서 선언되어 있으므로 삭제
	$f=array();
	$f['from_url']=$from_url;
	$f['unique_twitter']=(!empty($result['twitter']['rtn_uid'])?$result['twitter']['rtn_uid']:null);				//트위터 등록번호
	$f['unique_facebook']=(!empty($result['facebook']['rtn_uid'])?$result['facebook']['rtn_uid']:null);		//페북 등록번호
	$f['unique_yozm']=(!empty($result['yozm']['rtn_uid'])?$result['yozm']['rtn_uid']:null);				//요즘 등록번호
	$f['unique_metoday']=(!empty($result['metoday']['rtn_uid'])?$result['metoday']['rtn_uid']:null);			//미투데이 등록번호
	$f['unique_cyworld']=(!empty($result['cyworld']['rtn_uid'])?$result['cyworld']['rtn_uid']:null);			//싸이월드 등록번호
	$f['unique_maekyung']=(!empty($result['maekyung']['rtn_uid'])?$result['maekyung']['rtn_uid']:null);				//mk등록번호
	$f['user_id']=$_SESSION[$hometown."_userid"];
	$f['user_name']=$_SESSION[$hometown."_usernick"];
	$f['user_pic']=$_SESSION[$hometown."_userpic"];
	$f['hometown']=$hometown;
	$f['title']=$title;							 // 제목
	$f['unique_id']=$unique_id;		//페이지고유번호
	$f['app_id']=$app_id;				//할당된앱 아이디
	$f['cat_01']=$cat_01;				//카테고리 1
	$f['cat_02']=$cat_02;				//카테고리 2
	$f['text']=$str;
	$f['text_hash']=md5($str);	// hash 값 적용
	$f['good']=0;
	$f['bad']=0;
	$f['bad2']=0;
	$f['usage']="Y";
	$f['regdate']=time();

	$lastid=$db->add($f);	 // 모든 데이터는 urlencode 로 변환되어 들어감
	if($lastid) {
		$result['output']['flag']=1;
	}
	else {
		$result['output']['flag']=-1;	// 
	}
	$jsonData=json_encode($result);
	echo $_GET["callback"] . "(" . $jsonData . ");";
	exit;
}
else if($cmd=='action_remove'){//---------------------------------- 삭제
	$_id=trim($_GET['_id']);
	//해당값을 가지고 옴
	$db= new MkAddDB();
	$mong_id=$db->getMongoId($_id);
	$list=$db->lists(null, array("_id"=>$mong_id));
	$row=$list[0];

	//권한확인
	if($row['user_id']!=$_SESSION[$row['hometown']."_userid"]) {
		$result=array();
		$result['maekyung']['flag']=-2;// no authorization
		$jsonData=json_encode($result);
		echo $_GET["callback"] . "(" . $jsonData . ");";
		exit;
	}

	//mk댓글을 삭제하는 것이 아니라 사용여부를 N으로 교체함
	$f=array();
	$f['usage']='N';
	$f['remove_type']='본인 삭제';
	$f['removedate']=time();
	$db->modify($f, array("_id"=>$mong_id));

	//저장되어 있는 각각의 소셜댓글을 삭제
	$del_home=array();
	if(isset($row['unique_yozm'])) $del_home['yozm']=trim($row['unique_yozm']);
	if(isset($row['unique_cyworld'])) $del_home['cyworld']=trim($row['unique_cyworld']);
	if(isset($row['unique_facebook'])) $del_home['facebook']=trim($row['unique_facebook']);
	if(isset($row['unique_twitter'])) $del_home['twitter']=trim($row['unique_twitter']);
	if(isset($row['unique_metoday'])) $del_home['metoday']=trim($row['unique_metoday']);
	if(isset($row['unique_maekyung'])) $del_home['mk']=trim($row['unique_maekyung']);
	$result=array();
	foreach($del_home as $type=>$val){
		$class_file="./".$type."/Mk".$type.".class.php";
		if(!is_file($class_file))  continue;
		include($class_file);
		$class_name="Mk".$type;
		$cls= new $class_name($_SESSION[$type.'_request_token'], $_SESSION[$type.'_request_token_secret'], $_SESSION[$type.'_access_token'], $_SESSION[$type.'_access_token_secret']);
		$flag=$cls->checkAuth();// 권한 확인
		if($flag>0) {
			$flag=$cls->mkRemove($type, $val);
			$result[$type]['flag']=$flag;
		}
	}
	$jsonData=json_encode($result);
	echo $_GET["callback"] . "(" . $jsonData . ");";
	exit;
}
else if($cmd=='action_update_click'){//----------------------------------  추천/반대/신고 저장
	$_id=trim($_GET['_id']);
	$_sess=$_COOKIE['PHPSESSID'];
	$_clicked=trim($_GET['_clicked_type']);

	//추천/반대/신고 db 확인
	$cdb= new MkClickedDB();
	$list=$cdb->lists(null, array("article_id"=>$_id,"session_id"=>$_sess,"clicked_type"=>$_clicked));
	$result=array();
	//추천가능할 경우
	if(count($list)==0){
		$f=array();
		$f['article_id']=$_id;
		$f['session_id']=$_sess;
		$f['clicked_type']=$_clicked;
		$cdb->add($f);

		//추천/반대/시간 갯수 증가
		$db= new MkAddDB();
		$mongo_id=$db->getMongoId($_id);
		//해당 데이터 행의 갯수 받아오기
		$row=$db->lists(null, array("_id"=>$mongo_id));
		$new_row=array();
		switch($_clicked){
			case "g":
				$total=$row[0]['good']+1;
				$new_row['good']=$total;
				break;
			case "b":
				$total=$row[0]['bad']+1;
				$new_row['bad']=$total;
				break;
			case "b2":
				$total=$row[0]['bad2']+1;
				$new_row['bad2']=$total;
				$new_row['bad2_regdate']=time();
				break;
		}
		//해당 데이터 수정
		$db->modify($new_row, array("_id"=>$mongo_id));
		$row=$db->lists(null, array("_id"=>$mongo_id));
		$result['total']=$total;
		$result['_id']=$mongo_id;
		$result['flag']=1;
	}
	else{
		$result['flag']=-1;
	}
	$jsonData=json_encode($result);
	echo $_GET["callback"] . "(" . $jsonData . ");";
	exit;
}

?>
