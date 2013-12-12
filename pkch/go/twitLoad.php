<?
/*
�������忡�� ����� ����Ÿ�� RSS �ǵ带 ���ؼ� �޾ƿ� by pkch at 2012.3.7
1.Congressman.dat �� �ش��ϴ� ��ġ���̸�/ ��ġ�� ���̵� �޾ƿ�(�ǹ��ڿ� ���๮�ڷ� ������)
2.�ش� RSS �ǵ��� ������ͼ� �Ľ�
3.�Ľ��ѵ����͸� ����(�ߺ�Ȯ��) �Ͽ� DB�� ����
create table candidate_twit (
	no int primary key auto_increment, 
	twit_uid varchar(30), 
	twit_name varchar(30),
	uid varchar(30), 
	title text,
	description text,
	pub_date varchar(20),
	reg_date varchar(20)
);
**** ������ ��
-Ʈ���� ��å�� HTTP GET ���·� �����ϴ� ��� ��û���� ip���� ī��Ʈ�� �ű�� ����(oAuth)�Ǵ� ���� IP�� ���� ��û�� ���ؼ��� �ð��� 150���� ��û�� �Ҵ��Ѵ�.
-��û�� �󸶳� ���Ҵ����� https://api.twitter.com/1/account/rate_limit_status.json ���� remaining_hits �� ���� Ȯ���� �����ϴ�
ex1) ������ ��� : {"hourly_limit":150,"reset_time_in_seconds":1331098356,"reset_time":"Wed Mar 07 05:32:36 +0000 2012","remaining_hits":73}
ex2) �ȱ����� ��� : {"hourly_limit":150,"reset_time_in_seconds":1331098356,"reset_time":"Wed Mar 07 05:32:36 +0000 2012","remaining_hits":0}
-remaining_hits �� 0�� �������ʹ� rss�� ����� ��� get ��û�� ���ؼ��� �Ʒ��� ���� �޽����� �Բ� ������ �ȴ�.
<error>Rate limit exceeded. Clients may not make more than 150 requests per hour.</error>


*/

include('./lastRss.php');
$Congressman_file="./Congressman.dat";

//���� �޾ƿ���
$fcontent=file_get_contents($Congressman_file);
$arr_p=array();
$arr=array();
$arr=explode("\n",$fcontent);
$max=count($arr);
for($i=0,$j=0;$i<$max;$i++){
	if(trim($arr[$i]) !=null ) {
		$tmp=explode("\t",$arr[$i]);
		$arr_p[$j]['twit_name']=trim($tmp[0]);
		$arr_p[$j]['twit_uid']=trim($tmp[1]);
		$j++;
	}
}


$rss_cls= new lastRSS();
//�ش纰�� rss �ǵ� �޾ƿ���
$max=count($arr_p);
for($i=0;$i<$max;$i++){
	$twit_uid=trim($arr_p[$i]['twit_uid']);
	$twit_name=$arr_p[$i]['twit_name'];
	$twit_rss="http://twitter.com/statuses/user_timeline/".$twit_uid.".rss";

	//Rss Parsing
	$list=$rss_cls->Get($twit_rss);
	$items=$list['items'];
	echo $twit_name." , ".$twit_rss." ".date('Y-m-d H:i:s')." \n";
}
?>