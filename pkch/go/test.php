<?
$rss_url="http://twitter.com/statuses/user_timeline/KimYoojung.rss";
// Open and load RSS file
if ($f = fopen($rss_url, 'r')) {
	$rss_content = '';
	while (!feof($f)) {
		$rss_content .= fgets($f, 4096);
	}
	fclose($f);
	echo "<pre>";var_dump($rss_content);echo "</pre>";
}
exit;
exec("wget --output-document=/svc/shell-script/linux/www1/polytoktok/twitter/rate.json https://api.twitter.com/1/account/rate_limit_status.json");
$fc=file_get_contents("./rate.json");
echo "<pre>";var_dump($fc);echo "</pre>";
?>