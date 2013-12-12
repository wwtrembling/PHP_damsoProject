<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title></title>
	</head>
	<body>
		<form action="SendPushNotificationMan.php" method="POST">
			<input type="text" name="title" value="test message <?=date('Y/m/d H:i:s')?>" style="width:500px;"><br>
			<input type="submit" value="Send"><br>
		</form>
	</body>
</html>