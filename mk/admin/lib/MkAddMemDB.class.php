<?
/*
소셜댓글 관리자 페이지 관련 클래스
*/
class MkAddMemDB extends MkMongoDB{
	public function __construct(){
		$this->db_name="mkadd";
		$this->collection_name="mkmem";
		$this->fields=array(
			"company_name"=>array("type"=>"string"),
			"app_id"=>array("type"=>"string"),
			"company_user_name"=>array("type"=>"string"),
			"company_user_id"=>array("type"=>"string"),
			"company_user_pw"=>array("type"=>"string"),
			"company_user_tel"=>array("type"=>"string"),
			"company_user_mobile"=>array("type"=>"string"),
			"company_user_email"=>array("type"=>"string"),
			"contract_price"=>array("type"=>"string"),
			"contract_start"=>array("type"=>"string"),
			"contract_end"=>array("type"=>"string"),
			"use_yn"=>array("type"=>"string"),
			"mk_user_id"=>array("type"=>"string"),
			"admin_auth"=>array("type"=>"string"),
			"reg_date"=>array("type"=>"string"),
			"update_date"=>array("type"=>"string")
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