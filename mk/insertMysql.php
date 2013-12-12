<?
//MONGODB 테스트
function randStr(){
	$arr_subject=array("쥬얼리","박정아","이지현","서인영","조민아","신화","전진","민우","신혜성","에릭","김동완","앤디","베이비복스","간미연","이희진","심은진","이지","윤은혜","슈가","수진","혜승","아유미","하린","핑클","이효리","옥주현","이진","성유리","NRG","천명훈","이성진","노유민","SS501","김형준","김현중","허영생","박정민","규종","동방신기","유노윤호","영웅재중","시아준수","최강창민","믹키유천","플라이투더스카이","환희","브라이언","더빨강","오승은","배슬기","추소영","더자두","자두","강두","코요태","신지","김종민","백가","장나라","백지영","이정현","김현정","이루","원우","KCM","mc몽","린","거미","휘성","바다","서지영","이지혜","마야","춘자","하리수","채연","김완선","길건","심은진","조성모","팀","Tei","성시경","이승기","김종국","박명수","하하","장윤정","이제은","장우혁","윤도현","싸이","홍경민","한고은","배종옥","하희라","빈우","문근영","김태희","이완","최지우","조인성","정다빈","김정화","조한선","이청아","한지민","송혜교","이유리","유선","남상미","김홍수","고두심","김선화","현빈","이병헌","박용하","배용준","장동건","원빈","사강","한은정","양미라","고수","박예진","박은혜","이윤미","임성언","이영아","강경준","홍수아","김하늘","최진실","이태란","낭궁민","전지현","박한별","김혜수","이영애","정준하","소유진","정태우","박경림","김효진(논스톰)","김효진(골뱅이)","예지원","김지영","지현우","이다혜","김정은","김원희","박신영","이동건","김수미","추상미","정선경","정회석","조윤희","서수남","원더걸스","박탐희","태진아","하하","신보라","김장훈","박미선","신봉선","노사연","최화정","정정아","윤종신","박강성","아이비","브라이언","강타","이수영","김영철","이지연","김자옥","김수미","이영자","신현준","황정민","박수홍","이혁재","윤복희","임동진","정려원","바다","유진","한스벤드","박정현","차태현","김혜자","황수관","고승덕","유승준","현진영","정애리","정선희","이현주","임주완","동방신기믹키유천","동방신기유노윤호","노현정","이지훈","타블로","성유리","이진","(핑클)","다나","토니안","세븐","비","장나라","이천수","안정환","송종국","이영표","김희선","정우성","류승범","클릭비","오종혁","최강희","이동욱","장혁","강성","신동엽","베이비복스","버블시스터즈","김건모","김현정","박지윤","심태윤","엄정화","최지우","김지선","송혜교","최정원","슈가","밀크","배유미","이진","조성모","김빈우","이동욱","이경규","강수정","주영훈","지누션","윤도현","오연수","김혜수","김정화","김정민","한예슬","배직키드","윤영아","윤은경","조신애","서세원","이효리","권상우","엠씨몽","심은하","고수","박효신","이을용","한지혜","가수하은","가수성영은","죠앤","오세준","황보","김영아","한은정","이태란","원타임","오진환","유희열","차태현","문근영","김원희","안선영","정종철","김태균","이홍렬","자두","차인표","신애라","최수종","윤시윤","심형래","김예령","김경호","한승희","팀");
	$arr_action=array("장난치고 있다.","장난치다 맞았다.","공부를 잘했다고 한다.","공부는 잘 못했다고 한다.","매달리고 있다.","깝치고 있다."," 옛날부터 꿈은 가수였다고 한다.","미국에 갔었다.","한물갔다.","두물 갔다.","얼레리 꼴레리","불붙었다","파파라치가 관심도 없다고 한다.","스캔들 없이 조용하다.","신실한 크리스챤 이라고 한다.","돌고래 IQ라고 한다.","처음으로 무대에 섰다고 한다.","매경닷컴 짱","노래하다 삑사리 났다.","넘어져셔 다쳤다.","소리치고 있다.","짜증냈다.");
	$str=null;
	$cnt_sub= rand(1,5);	//명사 갯수(최대 3개)
	for($i=0;$i<$cnt_sub;$i++){
		$idx_sub=rand(0,(count($arr_subject)-1));
		$str.=$arr_subject[$idx_sub]."와 ";
	}
	$str.=" (은)는 ";
	$idx_action=rand(0,(count($arr_action)-1));
	$str.=$arr_action[$idx_action]."";
	return $str;
}

function randPara(){
	$str=null;
	$cnt_writing= rand(1,5);	//문장 랜덤 갯수
	for($i=0;$i<$cnt_writing;$i++){
		$str.=randStr()." \n";
	}
	return $str;
}

function randVal($arr){
	$max=count($arr);
	$rand_idx=rand(0,$max-1);
	return $arr[$rand_idx];
}

$conn= mysql_connect("localhost","root","sulung2");
mysql_select_db("mkadd");
$f=array();
$total_no=0;
while($total_no<=100000){
	$str=trim(randPara());
	$unique_id=randVal(array("index","index2","index3"));
	$app_id=randVal(array("CZYMAL","YDSP0E"));
	$f=array();
	$f['from_url']='http://220.73.139.94/mkadd/';
	$f['unique_twitter']=null;
	$f['unique_facebook']=null;
	$f['unique_yozm']=null;
	$f['unique_metoday']=null;
	$f['unique_cyworld']=null;
	$f['unique_maekyung']='200';
	$f['user_id']='sullung2';
	$f['user_name']='박기찬';
	$f['user_pic']='http://img.mk.co.kr/main/april/l_main_mklogo1.gif';
	$f['hometown']='maekyung';
	$f['title']='제목입니다!!!!';
	$f['unique_id']=$unique_id;
	$f['app_id']=$app_id;
	$f['cat_01']='뉴스';
	$f['cat_02']='기타';
	$f['text']=$str;
	$f['text_hash']=md5($str);
	$f['good']='0';
	$f['bad']='0';
	$f['bad2']='0';
	$f['usage_yn']='Y';
	$f['regdate']=time();
	$fields=null;
	$values=null;
	foreach($f as $key=>$val){
		$fields.=$key.", ";
		$values.=" \"".$val."\", ";
	}
	$fields=substr($fields,0,-2);
	$values=substr($values,0,-2);
	$query="insert into mktable (".$fields.") 
	values(".$values.") ";
	mysql_query($query);
	$total_no++;
	/*
	$query="select count(*) cnt from mktable ";
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	$total_no=$row['cnt'];
	*/
}
/*

create table mktable(
	_id int primary key auto_increment , 
	from_url varchar(100),
	unique_twitter varchar(30),
	unique_facebook varchar(30),
	unique_yozm varchar(30),
	unique_metoday varchar(30),
	unique_cyworld varchar(30),
	unique_maekyung varchar(30),
	hometown varchar(30),
	user_id varchar(50),
	user_name varchar(50),
	user_pic varchar(50),
	text text,
	text_hash text,
	good int(5),
	bad int(5),
	bad2 int(5),
	bad2_regdate varchar(20),
	usage_yn char(1),
	remove_type char(10),
	regdate varchar(20),
	removedate varchar(20),
	title varchar(100), 
	unique_id varchar(20), 
	app_id varchar(10),
	cat_01 varchar(20), 
	cat_02 varchar(20)
)

인덱스 생성
create index idx_mktable on mktable (
	usage_yn, unique_id, app_id,cat_01, cat_02, good, bad, bad2
)
*/
?>
