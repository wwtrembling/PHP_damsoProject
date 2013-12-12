<?
/*
----------------------------------------------------------------------
댓글 관련 클래스 by pkch at 2012.2.3
----------------------------------------------------------------------
각 필드에 형태를 추가 by pkch at 2012.5.11
----------------------------------------------------------------------
*/
class MkAddDB extends MkMongoDB{
	public function __construct(){
		$this->db_name="mkadd";
		$this->collection_name="mktable";
		$this->fields=array(
			"from_url"=>array("type"=>"string"),
			"unique_twitter"=>array("type"=>"string"),
			"unique_facebook"=>array("type"=>"string"),
			"unique_yozm"=>array("type"=>"string"),
			"unique_metoday"=>array("type"=>"string"),
			"unique_cyworld"=>array("type"=>"string"),
			"unique_maekyung"=>array("type"=>"string"),
			"hometown"=>array("type"=>"string"),
			"user_id"=>array("type"=>"string"),
			"user_name"=>array("type"=>"string"),
			"user_pic"=>array("type"=>"string"),
			"text"=>array("type"=>"string"),
			"text_hash"=>array("type"=>"hash"),
			"good"=>array("type"=>"integer"),
			"bad"=>array("type"=>"integer"),
			"bad2"=>array("type"=>"integer"),
			"bad2_regdate"=>array("type"=>"integer"),
			"usage"=>array("type"=>"string"),
			"remove_type"=>array("type"=>"string"),
			"regdate"=>array("type"=>"string"),
			"removedate"=>array("type"=>"string"),
			"title"=>array("type"=>"string"),
			"unique_id"=>array("type"=>"string"),
			"app_id"=>array("type"=>"string"),
			"cat_01"=>array("type"=>"string"),
			"cat_02"=>array("type"=>"string"));
		try {
			$this->conn = new Mongo('mongodb://mkadd:mkdev@localhost');
			$this->db = $this->conn->selectDB($this->db_name);
			$this->col = $this->db->selectCollection($this->collection_name);
		} catch (MongoConnectionException $e) {
			die('Error connecting to MongoDB server');
		} catch (MongoException $e) {
			die('Error: ' . $e->getMessage());
		}
	}
}
?>