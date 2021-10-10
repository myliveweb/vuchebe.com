<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$result = array();

$input = filter_input_array(INPUT_POST);
$email = $input['email'];

CModule::IncludeModule('iblock');

global $USER;
global $DB;

$arFields = array();
$arFields['EMAIL'] = $email;

$filter = Array("EMAIL" => $email);
$rsUsers = CUser::GetList(($by = "ID"), ($order = "desc"), $filter, array("SELECT"=>array("UF_ACTIVATE")));
if($arUser = $rsUsers->Fetch()) {
    $arFields['USER_ID']    = $arUser['ID'];
    $arFields['ACTIVATE']   = $arUser['UF_ACTIVATE'];
    $arFields['NAME']       = $arUser['NAME'];
    $arFields['LAST_NAME']  = $arUser['LAST_NAME'];
    $arFields['LOGIN']      = $arUser['LOGIN'];
}

$arEventFields = $arFields;

$event = new CEvent;
$event->SendImmediate("USER_INFO", SITE_ID, $arEventFields);

$data = array("status" => "success", $arEventFields);

die(json_encode($data));
?>