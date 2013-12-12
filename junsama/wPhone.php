<?

require_once("wPhone.class.php");

$uri="http://db3.notify.live.net/throttledthirdparty/01.00/AAHmsYS0OSkjSoPAcYsmRqZpAgAAAAADAwAAAAQUZm52OkJCMjg1QTg1QkZDMkUxREQ"; //uri sended by Microsoft plateform
$notif=new WindowsPhonePushNotification($uri);
$result = $notif->push_toast("this is a title","this is the sub title");

echo "<pre>";
echo "NotificationStatus : ".$result['X-NotificationStatus']."<br>";
echo "DeviceConnectionStatus : ".$result['X-DeviceConnectionStatus']."<br>";
echo "SubscriptionStatus : ".$result['X-SubscriptionStatus'];
echo "</pre>";

?>