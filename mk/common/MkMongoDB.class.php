<?
class MkMongoDB {
	protected $conn=null;
	protected $db=null;
	protected $col=null;
	protected $db_name=null;
	protected $collection_name=null;
	protected $fields=null;

	//����
	public function add($row){
		$f=array();
		$f=$this->retrieveEncode($row, $this->fields);
		try{
			$this->col->insert($f);
		}catch(MongoCursorException $e) {
			exit;
		}
		$unique_mk=(string)$f['_id'];
		return $unique_mk;
	}

	//����
	public function remove($cond){
		$this->col->remove($cond);
	}

	public function modify($row, $cond){
		/*
		$newdata=array('$set'=>array("num"=>123456789));
		$collection->update(array("idx"=>0),$newdata);	// update wholeCollection set num=123467890 where idx=0
		*/
		$newdata=array('$set'=>$row);
		$this->col->update($cond,$newdata);	// update wholeCollection set num=123467890 where idx=0
	}
	
	/*����Ʈ �޾ƿ��� : 	array("idx"=>array('$in'=>array(0,1))) : select * from where id in (0,1), array("num"=>array('$gt'=>0,'$lt'=>5000)) : select * from where 0<num and num<1000*/
	public function lists($fields=null, $cond=null, $page=null, $maxlist=null, $orderby=null){ //orderby �� ��쿡�� �ݵ�� ���ڷ� ǥ���� ��
		$cursor=null;
		if(isset($cond) && is_array($cond)){$cursor=$this->col->find($cond);	}
		else{$cursor=$this->col->find();}
		if(is_numeric($maxlist) && is_numeric($page)){$cursor=$cursor->skip($page*$maxlist)->limit($maxlist);}
		if(isset($fields) && is_array($fields)){$cursor=$cursor->fields($fields);	}
		if(!empty($orderby) && is_array($orderby)){
			$cursor->sort($orderby); //-1:desc, 1 : asc
		}
		$idx=0;
		$rows=array();
		foreach($cursor as $row){
			$rows[$idx++]=$this->retrieveDecode($row, $this->fields);
		}
		return $rows;
	}

	//����Ʈ ��ü ������ �޾ƿ���
	public function listsCount($cond=null){
		$total_no=0;
		if(!empty($cond)) {
			$total_no=$this->col->find($cond)->count();
		}else{
			$total_no=$this->col->find()->count();
		}
		return $total_no;
	}

	public function getMongoId($str){
		return new MongoId($str);
	}

	//�� ����Ÿ encode
	public function retrieveEncode($arr_data, $arr_field){
		$result=array();
		if(is_array($arr_data)){
			foreach($arr_data as $key=>$val){
				$max=count($val);
				if($max==1){
					$field_name=$key;
					$field_type=isset($arr_field[$key]['type'])?$arr_field[$key]['type']:null;
					if($field_type=='string'){$result[$key]=urlencode(htmlspecialchars($val));}
					else if($field_type=='integer'){$result[$key]=intval(trim($val));}
					else if($field_type=='hash'){$result[$key]=$val;}
				}
				else{
					for($i=0;$i<$max;$i++){
						$result[$key][$i]=$this->retrieveEncode($val[$i], $arr_field[$key]);
					}
				}
			}
		}
		return $result;
	}

	//�� ����Ÿ decode
	public function retrieveDecode($arr_data, $arr_field){
		$result=array();
		if(is_array($arr_data)){
			foreach($arr_data as $key=>$val){
				$max=count($val);
				if($max==1){
					$field_name=$key;
					$field_type=isset($arr_field[$key]['type'])?$arr_field[$key]['type']:null;
					if($field_name=='_id'){$result[$key]=(string)$val;}
					else if($field_type=='string'){$result[$key]=urldecode($val);}
					else {$result[$key]=$val;}
				}
				else{
					for($i=0;$i<$max;$i++){
						$result[$key][$i]=$this->retrieveDecode($val[$i], $arr_field[$key]);
					}
				}
			}
		}
		return $result;
	}
}
?>