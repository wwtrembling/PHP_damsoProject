<?

require_once("config.php");
session_start();
?>
<?
// 로그인 없이 처음 접속했을 경우
if ( !$_SESSION['result'] ) {
	$result = json_decode(file_get_contents("http://me2day.net/api/get_auth_url.json?akey=" . A_KEY));

    $_SESSION["me2day_url"] = $result->url;
?>
<!doctype html>
<html>
    <head>
        <meta charset="EUC-KR">
        <title>me2day</title>
      </head>
    <body>
        <br />
        <a href="API_Service.php?service=me2day&redirect_path=<?=urlencode("http://110.13.170.154".$_SERVER['PHP_SELF'])?>" target="_blank">login</a>
      </body>
</html>
<?} else {?>
<?
	// 로그인 후 접속했을 경우
    $token = $_SESSION["token"];
    $user_id = $_SESSION["user_id"];
    $user_key = $_SESSION["user_key"];
    $result = $_SESSION["result"];
    
    $_SESSION["user_id"] = $user_id;
    $_SESSION["user_key"] = $user_key;
	$_SESSION["redirect_path"] = $_SERVER['PHP_SELF'];

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
                        alert("text gogo");
                    } else {
	                    $.getJSON("post.php?flag=insert&callback=?", {body:body}, function(data){});
					}
                });

                $("#btnDel").click(function(){
					if( confirm( "delete??" ) ) {
						$.getJSON("post.php?flag=delete&callback=?", "", function(data){});
					}
                });

				$("#logout").click(function(){
					$.getJSON("post.php?flag=logout", "", function(data){});
                });
            });
        </script>
      </head>
    <body>
		<input type="button" id="logout" name="logout" value="logout"/>
        <input type="text" id="inputPost" name="inputPost" /><br />
        <input type="button" id="btnPost" name="btnPost" value="write"/>
        <input type="button" id="btnDel" name="btnDel" value="del"/>
      </body>
</html>
<?}?>