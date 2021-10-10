<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = array();

$input = filter_input_array(INPUT_POST);

$input['id'] = (int) $input['id'];
$input['id_city'] = (int) $input['id_city'];

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$isAdmin = 0;

if(isEdit())
	$isAdmin = 1;

$liter = strtoupper(mb_substr($input['city_name'], 0, 1));

setcookie("PANEL_COUNTRY", $input['id'], time()+31536000, "/", ".vuchebe.com");
setcookie("PANEL_ABC", $liter, time()+31536000, "/", ".vuchebe.com");
setcookie("PANEL_CITY", $input['id_city'], time()+31536000, "/", ".vuchebe.com");
setcookie("PANEL_CITY_NAME", $input['city_name'], time()+31536000, "/", ".vuchebe.com");

$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_UTM", "PROPERTY_REGION", "PROPERTY_TOPCITY");
$arFilter = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $input['id_city']);
$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
if($row = $res->GetNext()) {
	$utm = (int) str_replace("UTC", "", $row['PROPERTY_UTM_VALUE']);

	setcookie("PANEL_UTM", $utm, time()+31536000, "/", ".vuchebe.com");

	$result['UTM'] = $utm;

	if($row['PROPERTY_TOPCITY_VALUE'] == "Y") {
		$topcity = 1;
	} else {
		$topcity = 0;
	}

	$_SESSION['PANEL']['COUNTRY'] = $input['id'];
	$_SESSION['PANEL']['CITY'] = $row['ID'];
	$_SESSION['PANEL']['CITY_NAME'] = $row['NAME'];
	$_SESSION['PANEL']['UTM'] = $utm;
	$_SESSION['PANEL']['REGION'] = $row['PROPERTY_REGION_VALUE'];
	$_SESSION['PANEL']['TOPCITY'] = $topcity;

}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>