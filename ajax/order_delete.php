<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$id = (int) $input['id'];

CModule::IncludeModule('iblock');

$user_id = 0;

if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

$isAdmin = 0;
$admin = 0;

$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_OWNER");
$arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "ID" => $id);
$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
if($row = $res->GetNext()) {

    $admin = $row['PROPERTY_OWNER_VALUE'];
}

if($user_id == $admin || isEdit())
	$isAdmin = 1;

if($isAdmin) {
    CIBlockElement::SetPropertyValueCode($id, "DELETE", 'Y');
}

$result['id'] = $id;
$result['is_admin'] = $isAdmin;
$result['admins'] = $admin;

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>