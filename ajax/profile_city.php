<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = array();

$input = filter_input_array(INPUT_POST);

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

$result["CITY"] = array();

$arSelectList = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_TOPCITY", "PROPERTY_CAPITAL");
$arFilterList = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "NAME" => $input['str_city'] . "%");

if($input['country_id'])
	$arFilterList['SECTION_ID'] = $input['country_id'];
if($input['region_name'])
	$arFilterList['PROPERTY_REGION'] = $input['region_name'];


$resList = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterList, false, false, $arSelectList);
while($rowList = $resList->GetNext()) {

  if(!$rowList['PROPERTY_TOPCITY_VALUE'])
    $rowList['PROPERTY_TOPCITY_VALUE'] = 'N';

  if(!$rowList['PROPERTY_CAPITAL_VALUE'])
    $rowList['PROPERTY_CAPITAL_VALUE'] = 'N';

	$result["CITY"][] = $rowList;
}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>