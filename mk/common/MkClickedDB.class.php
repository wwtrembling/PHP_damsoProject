<?
/*
소셜댓글 클릭 DB 서버 접속 클래스 by pkch at 2012.2.27
article_id		클릭한 글의 고유 아이디
session_id		등록한 세션id
clicked_type		클릭한 종류(추천:g/반대:b/신고:b2)
*/

class MkClickedDB  extends MkMongoDB{
	public function __construct(){
		$this->db_name="mkadd";
		$this->collection_name="mktable_clicked";
		$this->fields=array(
			"article_id"=>array("type"=>"string"),
			"session_id"=>array("type"=>"string"),
			"clicked_type"=>array("type"=>"string")
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