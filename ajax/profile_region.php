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

$result["REGION"] = array();

if($input['country_id']) {

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION");
	$arFilter = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "SECTION_ID" => $input['country_id'], "!PROPERTY_REGION" => false);
	$res = CIBlockElement::GetList(array("PROPERTY_REGION" => "ASC"), $arFilter, array("PROPERTY_REGION"));
	while($row = $res->GetNext()) {
		$result["REGION"][] = $row["PROPERTY_REGION_VALUE"];
	}

}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>