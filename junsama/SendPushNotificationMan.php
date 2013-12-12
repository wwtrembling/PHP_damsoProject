<?
//===============================================================================
// 윈도우폰 Push Notificaton (Toast) 보내기 PHP 샘플
// 작성자 : Gilbok Lee, 휴즈플로우
// 작성일 : 2012-01-13
//===============================================================================

///
/// GUID 문자열을 만들어 반환합니다.
///
function guid()
{
	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

///
/// Toast Notification을 보냅니다.
///
function sendToastNotification($url, $message)
{
	// 바디 준비
	$toastMessage = "<?xml version=\"1.0\" encoding=\"utf-8\"?>".
					"<wp:Notification xmlns:wp=\"WPNotification\">".        
					"<wp:Toast>".
					"<wp:Text1>".
					"<![CDATA[".$message."]]>".
					"</wp:Text1>".
					"<wp:Text2></wp:Text2>".
					"</wp:Toast> ".
					"</wp:Notification>";      

	// 헤더 준비
	$httpHeaders = array('Content-type: text/xml; charset=utf-8', 
					     'X-MessageID: '.guid(),
					     'X-WindowsPhone-Target: toast', 
						 'Accept: application/*', 
						 'X-NotificationClass: 2',
						 'Content-Length:'.strlen($toastMessage));     

	// 요청 생성     
	$r = curl_init();     
	curl_setopt($r, CURLOPT_URL, $url);     
	curl_setopt($r, CURLOPT_POST, true);     
	curl_setopt($r, CURLOPT_HEADER, true);      
	curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);     
	curl_setopt($r, CURLOPT_HTTPHEADER, $httpHeaders);     
	curl_setopt($r, CURLOPT_POSTFIELDS, $toastMessage);    

	// 요청 실행
	$output = curl_exec($r);     
	curl_close($r); 

	// 디버그용, 테스트 시에만 해제하시오.
	
	echo $output."11";
}

//===============================================================================
// 호출 예제
//===============================================================================

// 임의의 Microsoft Push Notification Server URL, 실제로는 Update되는 살아있는 URL로 대체하여 사용.
$url = "http://db3.notify.live.net/throttledthirdparty/01.00/AAGTOeyu-ziKTYcrOvWnZGJXAgAAAAADAQAAAAQUZm52OjIzOEQ2NDJDRkI5MEVFMEQ";

// 전송할 내용, 실제로는 DB나 웹서비스에서 얻은 데이터로 대체하여 사용.
//$url = $_GET['uri'];
//$content = $_GET['msg'];

// Notification 호출!!
sendToastNotification($url, $content);

?>