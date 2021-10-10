<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = 0;

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$isAdmin = 0;
$arrAdmins = array();
$newArrAdmins = array();

$resAdmins = CIBlockElement::GetProperty(2, $input['vuz_id'], "sort", "asc", array("CODE" => "ADMINS"));
while ($obAdmins = $resAdmins->GetNext()) {
    $arrAdmins[] = $obAdmins['VALUE'];
}

if(in_array($user_id, $arrAdmins) || isEdit())
	$isAdmin = 1;

if($isAdmin) {

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_TEXT", "PROPERTY_VUZ_ID", "PROPERTY_PHONE", "PROPERTY_EMAIL", "PROPERTY_CONTACTS", "PROPERTY_FAKULTET");
	$arFilter = array("IBLOCK_ID" => 24, "ACTIVE" => "Y", "ID" => $input['vac_id']);
	$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
	if($row = $res->GetNext())
	{
		$result = $row;
	}
}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>