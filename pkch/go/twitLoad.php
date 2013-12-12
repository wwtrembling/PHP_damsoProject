<?
/*
폴리톡톡에서 사용할 데이타를 RSS 피드를 통해서 받아옴 by pkch at 2012.3.7
1.Congressman.dat 에 해당하는 정치인이름/ 정치인 아이디 받아옴(탭문자와 개행문자로 구분함)
2.해당 RSS 피딩을 가지고와서 파싱
3.파싱한데이터를 선별(중복확인) 하여 DB에 저장
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
**** 주의할 점
-트위터 정책상 HTTP GET 형태로 접근하는 모든 요청들은 ip별로 카운트를 매기고 인증(oAuth)되니 않은 IP에 대한 요청에 대해서는 시간당 150번의 요청만 할당한다.
-요청이 얼마나 남았는지는 https://api.twitter.com/1/account/rate_limit_status.json 에서 remaining_hits 을 보고 확인이 가능하다
ex1) 괜찮은 경우 : {"hourly_limit":150,"reset_time_in_seconds":1331098356,"reset_time":"Wed Mar 07 05:32:36 +0000 2012","remaining_hits":73}
ex2) 안괜찮은 경우 : {"hourly_limit":150,"reset_time_in_seconds":1331098356,"reset_time":"Wed Mar 07 05:32:36 +0000 2012","remaining_hits":0}
-remaining_hits 가 0인 순간부터는 rss를 비롯한 모든 get 요청에 대해서는 아래와 같은 메시지와 함께 먹통이 된다.
<error>Rate limit exceeded. Clients may not make more than 150 requests per hour.</error>


*/

include('./lastRss.php');
$Congressman_file="./Congressman.dat";

//파일 받아오기
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
//해당별로 rss 피드 받아오기
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