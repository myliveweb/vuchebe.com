<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

setcookie("PANEL_HIDE_COOKIES", "1", time()+31536000, "/", ".vuchebe.com");
$_SESSION['PANEL']['HIDE_COOKIES'] = 1;

$data = array("status" => "success");

die(json_encode($data));
?>