<?
/*일반 함수 모음*/

//배열 소팅함수 모음 : getList("배열", "필드명","desc"); // 현재는 내림차순 정렬만 가능함
function getList($result, $i, $type='desc')
{
	if(is_array($result)==false)  { echo "배열값을 입력해 주세요.";}
	$a=$result;
	$ret = array();
	foreach( $a as $key => $t )
	{
		$ret[$t[$i].$key] = $t;
	}
	if($type=='desc') krsort($ret);
	else if($type=='asc') ksort($ret);
	reset($ret);
	return array_values($ret);
}


//한글/영어 bytes를 구하는 함수
function countLetters($str){
	$buf_e=0;
	$buf_k=0;
	$maxi=strlen($str);
	for($i=0; $i<$maxi; $i++) {
		if (ord($str[$i])< 128) {
			$buf_e=$buf_e+1;
		}else{
			$buf_k=$buf_k+1;
		}
	}
	$buf=$buf_e+$buf_k/3;
	return $buf;
}
?>