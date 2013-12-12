<?
session_start();
session_destroy();
echo "<script>location.href='./".$_GET['rtn']."';</script>";
?>