<?
/*
ADMIN action 페이지
*/
include('./inc/conf_admin.inc.php');
if(!defined("checksum")) exit;

$cls_add= new MkAddDB();
$cls_cat= new MkAddCateDB();
$cls_mem= new MkAddMemDB();

$cmd=isset($_POST['cmd'])?htmlspecialchars(trim($_POST['cmd'])):null;

//로그인 확인
if($cmd=='chk_login'){
	$admin_id = trim($_POST['id']);
	$admin_pw = trim($_POST['password']);
	
	$lists = array();

	$cond['company_user_id']=$admin_id;
	$cond['company_user_pw']=$admin_pw;
	$total_no=0;
	$lists=$cls_mem->lists(null, $cond, '', '', $total_no);
	
	if ( count($lists) > 0  ){
		$_SESSION['admin_id']	= $lists[0]['company_user_id'];
		$_SESSION['admin_name'] = $lists[0]['company_name'];
		$_SESSION['admin_auth'] = $lists[0]['admin_auth'];
		$_SESSION['app_id']		= $lists[0]['app_id'];

		goUrl('./index_admin.php');
	} else{
		echo "
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8;' />
		<script type='text/javascript'>
		alert('회원 정보를 확인해 주시기 바랍니다.');
		history.back();
		</script>
		";
	}
}
else if(isset($_GET['cmd']) && $_GET['cmd']=='action_logout'){
	unset($_SESSION);
	session_destroy();
	goUrl("./index.php");
}
else if($cmd=='action_remove_multi'){
	foreach($_POST as $key=>$val){
		preg_match("/(removeid)+/",$key, $out);
		if(!empty($out)){
			$tmp= explode("__",$key);
			$id=trim($tmp[1]);
			$mong_id=$cls_add->getMongoId($id);
			$cls_add->remove(array("_id"=>$mong_id));
		}
	}
	goUrl("./index_admin.php?mn1=add");
}
else if($cmd=='action_remove'){
	$_id=trim($_POST['_id']);
	$mong_id=$cls_add->getMongoId($_id);
	$cls_add->remove(array("_id"=>$mong_id));
	goUrl("./index_admin.php?mn1=add&mn2=add_list");
}
else if($cmd=='action_remove_multi_fake'){
	foreach($_POST as $key=>$val){
		preg_match("/(removeid)+/",$key, $out);
		if(!empty($out)){
			$f=array();
			$tmp= explode("__",$key);
			$id=trim($tmp[1]);
			$mong_id=$cls_add->getMongoId($id);
			$f['usage']='N';
			$f['remove_type']='관리자 삭제';
			$f['removedate']=time();
			$cls_add->modify($f, array("_id"=>$mong_id));
		}
	}
	goUrl("./index_admin.php?mn1=add");
}
else if($cmd=='action_remove_fake'){
	$_id=trim($_POST['_id']);
	$mong_id=$cls_cat->getMongoId($_id);
	$f=array();
	$f['usage']='N';
	$f['remove_type']='관리자 삭제';
	$f['removedate']=time();
	$cls_add->remove(array("_id"=>$mong_id));
	goUrl("./index_admin.php?mn1=add");
}

//-------------------------------------------------- 카테고리 관련
//카테고리 등록/수정
else if($cmd=='action_category_regist'){
	$_id=trim($_POST['_id']);
	$f=array();
	$f['app_id']=$_SESSION['app_id'];
	$f['category_name']=trim($_POST['category_name']);
	$category_sub=array();
	foreach($_POST as $key=>$val) {
		preg_match("/(subtitle)+/",$key,$out);
		if(!empty($out)){
			$idx=0;
			foreach($val as $key=>$val){
				if(!empty($val)){
					//$category_sub[$idx]['sub_category_no']="sc".$_SESSION['app_id']."_".($idx+1);
					$category_sub[$idx]['sub_category_name']=$val;
					$idx++;
				}
			}
		}
	}
	$f['category_sub']=$category_sub;
	if(!empty($_id)){	 //수정
		$f['category_moddate']=time();
		$mong_id=$cls_cat->getMongoId($_id);
		$cls_cat->modify($f, array("_id"=>$mong_id));
	}
	else{	// 등록
		$f['category_regdate']=time();
		$info=$cls_cat->lists(null, array("app_id"=>$_SESSION['app_id']),0,1);
		$cls_cat->add($f);
	}
	goUrl("./index_admin.php?mn1=category&mn2=category_list");
}
//카테고리 복수 삭제
else if($cmd=='action_category_remove_multi'){
	foreach($_POST as $key=>$val){
		preg_match("/(removeid)+/",$key, $out);
		if(!empty($out)){
			$tmp= explode("__",$key);
			$id=trim($tmp[1]);
			$mong_id=$cls_cat->getMongoId($id);
			$cls_cat->remove(array("_id"=>$mong_id));
		}
	}
	goUrl("./index_admin.php?mn1=category");
}
else if($cmd=='action_category_remove'){
	$_id=trim($_POST['_id']);
	$mong_id=$cls_cat->getMongoId($_id);
	$cls_cat->remove(array("_id"=>$mong_id));
	goUrl("./index_admin.php?mn1=category");
}

//-------------------------------------------------- 회원 관련
//회원 등록/수정
else if($cmd=='action_members_regist'){
	function randon_app_id($id_len) {
		$k = "01234567890_ABCDEFGHJKLMNPQRSTUVWXYZ";
		$ran_app_id = "";

		for ($i=0; $i<$id_len; $i++) {
			$ran_app_id .= substr($k,rand(0,strlen($k)-1),1);
		}

		return $ran_app_id;
	}

	$f=array();

	$f['company_name']=trim($_POST['company_name']);
	$f['company_user_name']=trim($_POST['company_user_name']);
	$f['company_user_id']=trim($_POST['company_user_id']);
	$f['company_user_pw']=trim($_POST['company_user_pw']);
	$f['company_user_tel']=trim($_POST['company_user_tel']);
	$f['company_user_mobile']=trim($_POST['company_user_mobile']);
	$f['company_user_email']=trim($_POST['company_user_email']);
	$f['contract_price']=trim($_POST['contract_price']);
	$f['contract_start']=trim($_POST['contract_start']);
	$f['contract_end']=trim($_POST['contract_end']);
	$f['use_yn']=$_POST['use_yn'];
	$f['admin_auth']=$_POST['admin_auth'];
	$f['mk_user_id']=trim($_POST['mk_user_id']);

	if(!empty($_POST['_id'])){	 //수정
		$f['update_date']=time();
		$mong_id=$cls_mem->getMongoId($_POST['_id']);

		$cls_mem->modify($f, array("_id"=>$mong_id));
	}
	else{	// 등록
		$f['app_id']=randon_app_id(6);
		$f['reg_date']=time();
		$f['update_date']=time();
		$info=$cls_mem->lists(null, array("app_id"=>$_SESSION['app_id']),0,1);

		$cls_mem->add($f);
	}

	goUrl("./index_admin.php?mn1=members&mn2=members_list");
}
//회원 삭제
else if($cmd=='action_members_remove'){
	foreach($_POST as $key=>$val){
		preg_match("/(removeid)+/",$key, $out);
		if(!empty($out)){
			$tmp= explode("__",$key);
			$id=trim($tmp[1]);
			$mong_id=$cls_mem->getMongoId($id);
			$cls_mem->remove(array("_id"=>$mong_id));
		}
	}
	goUrl("./index_admin.php?mn1=members");
}

//-------------------------------------------------- 필터링 관련
else if($cmd=='action_add_filtering_keyword'){	 // 키워드등록
	$filter_lists=isset($_POST['filter_lists'])?$_POST['filter_lists']:null;
	$filter_content=null;
	if(is_array($filter_lists)){
		foreach($filter_lists as $key=>$val) {
			$val=trim($val);
			$filter_content.=$val."^";
		}
	}
	$keyword_regist=trim($_POST['keyword_regist']);
	$filter_content.=$keyword_regist;
	$keyword_file=upload_dir."forbidden_keyword_".$_SESSION['app_id'].".dat";
	$fp=fopen($keyword_file,"w");
	fwrite($fp, $filter_content);
	fclose($fp);
	goUrl("./index_admin.php?mn1=filter&mn2=filter_list_keyword");
}
else if($cmd=='action_remove_filtering_keyword'){ //키워드삭제
	$filter_lists=isset($_POST['filter_lists'])?$_POST['filter_lists']:null;
	$filter_content=null;
	if(is_array($filter_lists)){
		foreach($filter_lists as $key=>$val) {
			$val=trim($val);
			$filter_content.=$val."^";
		}
	}
	$keyword_file=upload_dir."forbidden_keyword_".$_SESSION['app_id'].".dat";
	$fp=fopen($keyword_file,"w");
	fwrite($fp, $filter_content);
	fclose($fp);
	goUrl("./index_admin.php?mn1=filter&mn2=filter_list_keyword");
}
else if($cmd=='action_add_filtering_id'){	// 아이디 등록
	$filter_uid_hometown=trim($_POST['choice_hometown']);
	$filter_uid=trim($_POST['id_regist']);
	$cls_filter= new MkFilterDB();
	$f=array();
	$f['app_id']=$_SESSION['app_id'];
	$f['filter_uid_hometown']=$filter_uid_hometown;
	$f['filter_uid']=$filter_uid;
	$cls_filter->add($f);
	goUrl("./index_admin.php?mn1=filter&mn2=filter_list_id");
}
else if($cmd=='action_remove_filtering_id'){
	$cls_filter= new MkFilterDB();
	$filter_id_lists=$_POST['filter_id_lists'];
	foreach($filter_id_lists as $key=>$val) {
		$_id=trim($val);
		$mongo_id=$cls_filter->getMongoId($_id);
		$cls_filter->remove(array('_id'=>$mongo_id));
	}
	goUrl("./index_admin.php?mn1=filter&mn2=filter_list_id");
}

//-------------------------------------------------- 개발자전용 관련
//SNS 별로 limit 제한 받아오기
else if($cmd=='action_deveoper_twitter_ajax'){
	$get_url="https://api.twitter.com/1/account/rate_limit_status.json";
	$fcontent=@file_get_contents($get_url);
	echo $fcontent;
}
?>