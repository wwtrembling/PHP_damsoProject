<?
/*
댓글 금칙아이디 목록 클래스 by pkch at 2012.2.27
app_id : 해당 App ID
filter_uid_hometown : (금지아이디만 사용) 아이디가 등록된 대표계정(매경/요즘/싸이월드/미투데이/트위터/페이스북)
filter_uid : 필터 컨텐츠
*/

class MkFilterDB  extends MkMongoDB{
	public function __construct(){
		$this->db_name="mkadd";
		$this->collection_name="mktable_filter_uid";
		$this->fields=array(
			"app_id"=>array("type"=>"string"),
			"filter_uid_hometown"=>array("type"=>"string"),
			"filter_uid"=>array("type"=>"string")
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