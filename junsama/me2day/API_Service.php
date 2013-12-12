<?
session_start();

if ( $_GET['service'] && $_GET['redirect_path'] ) {
?>
<script type='text/javascript'>
location.href='<?=$_SESSION["me2day_url"]?>';
</script>
<?} else {
	$_SESSION["token"] = $_GET["token"];
    $_SESSION["user_id"] = $_GET["user_id"];
    $_SESSION["user_key"] = $_GET["user_key"];
    $_SESSION["result"] = $_GET["result"];
?>
	<script type='text/javascript'>
	window.open('about:blank','_self').close();
	</script>
<?}?>
