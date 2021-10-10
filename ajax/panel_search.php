<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = array();
$cookie_city = 0;

$input = filter_input_array(INPUT_POST);
$getCookie = filter_input_array(INPUT_COOKIE);

$input['id'] = (int) $input['id'];

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

$arSelectList = array("ID", "NAME", "IBLOCK_ID");
$arFilterList = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_NALICHIE" => "Y", "SECTION_ID" => $input['id'], "NAME" => $input['search'] . "%");
$resList = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterList, false, false, $arSelectList);
while($rowList = $resList->GetNext()) {
	$result['CITY'][] = $rowList;
}

if(sizeof($result['CITY']) > 0) {
	$result['ABC'] = $liter = strtoupper(mb_substr($input['search'], 0, 1));
}

if(isset($getCookie['PANEL_CITY']) && $getCookie['PANEL_CITY'] && !$cookie_city)
	$cookie_city = $getCookie['PANEL_CITY'];

if(!$cookie_city && isset($getCookie['PANEL_COUNTRY']) && $getCookie['PANEL_COUNTRY'] == 79)
	$cookie_city = 279030;

$result['CITY_ACTIVE'] = $cookie_city;

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>