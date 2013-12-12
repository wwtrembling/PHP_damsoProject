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
        <title>미투데이 인증 후 글쓰기</title>
      </head>
    <body>
        <br />
        <a href="<?=$result->url?>">미투데이 인증하기</a>
      </body>
</html>
<?} else {?>
<?
    require_once("config.php");
    
    $token = $_GET["token"];
    $user_id = $_GET["user_id"];
    $user_key = $_GET["user_key"];
    $result = $_GET["result"];
    
    // 세션저장
    session_start();
    $_SESSION["user_id"] = $user_id;
    $_SESSION["user_key"] = $user_key;
    
    // 인증이 확실한지 확인
    $authKey = "12345678" . md5("12345678" . $user_key);
    $result = file_get_contents("http://me2day.net/api/noop?uid={$user_id}&ukey={$authKey}&akey=" . A_KEY);
?>
<!doctype html>
<html>
    <head>
        <meta charset="EUC-KR">
        <title>미투데이 인증 콜백</title>
        <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js'></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#btnPost").click(function(){
                    var body = $("#inputPost").val();
                    if (body.length < 1){
                        alert("글입력해요!");
                        return;
                    }
                    $.getJSON("post.php?flag=insert&callback=?", {body:body}, function(data){
                        alert("글쓰기 성공 = " + data.result);
                    });
                });
                $("#btnDel").click(function(){
                	var body = $("#inputPost").val();
                    $.getJSON("post.php?flag=delete&callback=?", {body:body}, function(data){
                        alert("글쓰기 성공 = " + data.result);
                    });
                });
            });
        </script>
      </head>
    <body>
        <input type="text" id="inputPost" name="inputPost" /><br />
        <input type="button" id="btnPost" name="btnPost" value="글쓰기"/>
        <input type="button" id="btnDel" name="btnDel" value="글삭제"/>
      </body>
</html>
<?}?>