<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$load = $input['load'];

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

list($idBanner, $srcBanner, $hrefBanner, $targetBanner, $clickBanner, $nameBanner) = getRandomBanner(35, 222, 222, $load);

$result['id'] = $idBanner;
$result['src'] = $srcBanner;
$result['href'] = $hrefBanner;
$result['target'] = $targetBanner;
$result['click'] = $clickBanner;
$result['name'] = $nameBanner;

$data = array("status" => "success", 'banner' => $result);

die(json_encode($data));
?>