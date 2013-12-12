<?
include('./inc/conf_admin.inc.php');
if(empty($admin_id)) {goUrl("./index.php");}

$mn1=isset($_GET['mn1'])?trim($_GET['mn1']):"add";
$mn2=isset($_GET['mn2'])?trim($_GET['mn2']):null;
include('./inc/top.inc.php');

if($mn1=='add'){
	if(empty($mn2) || $mn2=='add_list'){	include('./add_list.php');}
	else if($mn2=='add_list_dup'){	include('./add_list_dup.php');}
	else if($mn2=='add_list_del'){	include('./add_list_del.php');}
	else if($mn2=='add_edit'){include('./add_edit.php');}
}
else if($mn1=='category'){
	if(empty($mn2) || $mn2=='category_list'){	include('./category_list.php');}
	else if($mn2=='category_edit'){include('./category_edit.php');}
}
else if($mn1=='filter'){
	if(empty($mn2) || $mn2=='filter_list_keyword'){	include('./filter_list_keyword.php');}
	else if($mn2=='filter_list_id'){include('./filter_list_id.php');}
}
else if($mn1=='good'){
	if(empty($mn2) || $mn2=='good_list'){include('./good_list.php');}
}
else if($mn1=='bad2'){
	if(empty($mn2) || $mn2=='bad2_list'){include('./bad2_list.php');}
}
else if($mn1=='members'){
	if(empty($mn2) || $mn2=='members_list'){include('./members_list.php');}
	else if($mn2=='members_edit'){include('./members_edit.php');}
}
else if($mn1=='statistics'){
	if(empty($mn2) || $mn2=='statistics_list'){include('./statistics_list.php');}
	else if($mn2=='statistics_edit'){include('./statistics_edit.php');}
}
else if($mn1=='developers'){
	if(empty($mn2) || $mn2=='developers_list'){include('./developers_list.php');}
}

include('./inc/bottom.inc.php');
?>