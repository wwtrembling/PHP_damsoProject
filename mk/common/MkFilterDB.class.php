<?
/*
��� ��Ģ���̵� ��� Ŭ���� by pkch at 2012.2.27
app_id : �ش� App ID
filter_uid_hometown : (�������̵� ���) ���̵� ��ϵ� ��ǥ����(�Ű�/����/���̿���/��������/Ʈ����/���̽���)
filter_uid : ���� ������
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
		$this->type_integer=array(); //integer ���� ���� ������
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