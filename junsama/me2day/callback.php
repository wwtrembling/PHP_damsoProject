<?
if ( !$_GET['result'] ) {
	require_once("config.php");
	$result = json_decode(file_get_contents("http://me2day.net/api/get_auth_url.json?akey=" . A_KEY));
	print_r($result);
?>
<!doctype html>
<html>
    <head>
        <meta charset="EUC-KR">
        <title>�������� ���� �� �۾���</title>
      </head>
    <body>
        <br />
        <a href="<?=$result->url?>">�������� �����ϱ�</a>
      </body>
</html>
<?} else {?>
<?
    require_once("config.php");
    
    $token = $_GET["token"];
    $user_id = $_GET["user_id"];
    $user_key = $_GET["user_key"];
    $result = $_GET["result"];
    
    // ��������
    session_start();
    $_SESSION["user_id"] = $user_id;
    $_SESSION["user_key"] = $user_key;
    
    // ������ Ȯ������ Ȯ��
    $authKey = "12345678" . md5("12345678" . $user_key);
    $result = file_get_contents("http://me2day.net/api/noop?uid={$user_id}&ukey={$authKey}&akey=" . A_KEY);
?>
<!doctype html>
<html>
    <head>
        <meta charset="EUC-KR">
        <title>�������� ���� �ݹ�</title>
        <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js'></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#btnPost").click(function(){
                    var body = $("#inputPost").val();
                    if (body.length < 1){
                        alert("���Է��ؿ�!");
                        return;
                    }
                    $.getJSON("post.php?flag=insert&callback=?", {body:body}, function(data){
                        alert("�۾��� ���� = " + data.result);
                    });
                });
                $("#btnDel").click(function(){
                	var body = $("#inputPost").val();
                    $.getJSON("post.php?flag=delete&callback=?", {body:body}, function(data){
                        alert("�۾��� ���� = " + data.result);
                    });
                });
            });
        </script>
      </head>
    <body>
        <input type="text" id="inputPost" name="inputPost" /><br />
        <input type="button" id="btnPost" name="btnPost" value="�۾���"/>
        <input type="button" id="btnDel" name="btnDel" value="�ۻ���"/>
      </body>
</html>
<?}?>