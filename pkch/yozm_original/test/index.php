<?
//자.. OAtuh 방식으로 한번 테스트를 해보자
//1. consumer key, consumer secret 키를 저장
$consumer_key="7b286ec7-41fc-41e8-92e9-21d764d73c2e";
$consumer_secret="WD8ZyPncjZ5iM1yWeC9EwnWwjk25P.mhaZUOnJMARE.3xvLxxO6Tlg00";
$request_url="https://apis.daum.net/oauth/requestToken";
$authorize_url="https://apis.daum.net/oauth/authorize";
$access_url="https://apis.daum.net/oauth/accessToken";
$callback_url = 'http://110.13.170.154/tempProject/pkch/yozm/index.php';

$url=$request_url."?oauth_callback=".$callback_url."&oauth_consumer_key=".$consumer_key."&oauth_token_secret=".$consumer_secret;
$ch = curl_init();
curl_setopt($ch, CURLOPT_CAINFO, CURL_CA_BUNDLE_PATH);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
echo "<pre>";var_dump($response);echo "</pre>";
?>
