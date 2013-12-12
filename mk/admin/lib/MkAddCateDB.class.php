<?
/*
소셜댓글 카테고리 클래스 by pkch at 2012.4.1
*/
class MkAddCateDB extends MkMongoDB{
	public function __construct(){
		$this->db_name="mkadd";
		$this->collection_name="mkcate";
		$this->fields=array(
			"app_id" => array('type'=>'string'),
			"category_no"=> array('type'=>'int'),
			"category_name" =>array('type'=>'string'),
			"category_sub"=>array("sub_category_name"=> array('type'=>'string')),
			"category_regdate"=> array('type'=>'string'),
			"category_moddate"=> array('type'=>'string')
			);
		$this->type_integer=array(); //integer 형은 따로 선언함
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