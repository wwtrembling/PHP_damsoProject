<?
/*�Ϲ� �Լ� ����*/

//�迭 �����Լ� ���� : getList("�迭", "�ʵ��","desc"); // ����� �������� ���ĸ� ������
function getList($result, $i, $type='desc')
{
	if(is_array($result)==false)  { echo "�迭���� �Է��� �ּ���.";}
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


//�ѱ�/���� bytes�� ���ϴ� �Լ�
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