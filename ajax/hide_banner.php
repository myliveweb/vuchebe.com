<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = '';

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

$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_HIDE", "PROPERTY_OWNER");
$arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "ID" => $input['id']);
$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
if($row = $res->GetNext()) {

	$newHide = (int) $row["PROPERTY_HIDE_VALUE"] + 1;
	CIBlockElement::SetPropertyValueCode($row["ID"], "HIDE", $newHide);

    setBannerHistory(4, $row["ID"], $row["PROPERTY_OWNER_VALUE"], 0);
}

$data = array("status" => "success");

die(json_encode($data));
?>