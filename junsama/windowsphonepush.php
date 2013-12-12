<?

/**
* @company : mobile
* @desc : Send Push Notifications to Windows Phone 7
* @planner : 조은순
* @worker : junsama(dev), 조철호T(dev)
* @date : 2012-01-12
* @update : http://110.13.170.154/tempProject/junsama/windowsphonepush.php
**/

define('WIN_PUSH_URL', 'http://110.13.170.154/tempProject/junsama/');

require_once("windowsphonepush.class.php");

$uri="http://db3.notify.live.net/throttledthirdparty/01.00/AAHmsYS0OSkjSoPAcYsmRqZpAgAAAAADAwAAAAQUZm52OkJCMjg1QTg1QkZDMkUxREQ"; //uri sended by Microsoft plateform 
$notif=new WindowsPhonePushNotification($uri);
$result = $notif->push_toast("title","subtitle");
/*
echo "NotificationStatus : ".$result['X-NotificationStatus']."<br>";
echo "DeviceConnectionStatus : ".$result['X-DeviceConnectionStatus']."<br>";
echo "SubscriptionStatus : ".$result['X-SubscriptionStatus'];
*/
echo "<pre>";
var_dump($result);
echo "</pre>";

?>