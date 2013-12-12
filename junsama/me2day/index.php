<?
/*
세션을 이용해서 로그인 하기전 페이지를 저장을 해서 
현재 페이지로 미투 로그인을 연결 및 연동을 한후 
콜백 페이지에서 아까저장한 페이지를 호출해서 그페이지로 다시 넘기면 되는거였습니다... 
증 후 다시 페이지로 돌아오게 만들었습니다.
*/
require_once("config.php");

?>
<?
if ( !$_GET['result'] ) {
	$result = json_decode(file_get_contents("http://me2day.net/api/get_auth_url.json?akey=" . A_KEY));
?>
<!doctype html>
<html>
    <head>
        <meta charset="EUC-KR">
        <title>me2day</title>
      </head>
    <body>
        <br />
        <a href="<?=$result->url?>">login</a>
      </body>
</html>
<?} else {?>
<?
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
        <title>me2day</title>
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
					$.getJSON("post.php?flag=delete&callback=?", {body:body}, function(data){});
					
                });
            });
        </script>
      </head>
    <body>
        <input type="text" id="inputPost" name="inputPost" /><br />
        <input type="button" id="btnPost" name="btnPost" value="write"/>
        <input type="button" id="btnDel" name="btnDel" value="del"/>
      </body>
</html>
<?}?>