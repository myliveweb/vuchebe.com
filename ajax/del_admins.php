<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = 0;

$input = filter_input_array(INPUT_POST);

$input['old_id'] = (int) $input['old_id'];
$input['admin_id'] = (int) $input['admin_id'];

CModule::IncludeModule('iblock');

$iblock = $input['iblock'];
if(!$iblock)
	$iblock = 2;

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$isAdmin = 0;
$arrAdmins = array();
$newArrAdmins = array();

$resAdmins = CIBlockElement::GetProperty($iblock, $input['vuz_id'], "sort", "asc", array("CODE" => "ADMINS"));
while ($obAdmins = $resAdmins->GetNext()) {
    $arrAdmins[] = $obAdmins['VALUE'];
}

if(in_array($user_id, $arrAdmins) || isEdit())
	$isAdmin = 1;

if($isAdmin && $input['admin_id'] > 0 && in_array($input['admin_id'], $arrAdmins)) {


	foreach($arrAdmins as $itemAdmin) {
		if($itemAdmin != $input['admin_id']) {
			$newArrAdmins[] = $itemAdmin;
		}
	}

	CIBlockElement::SetPropertyValueCode($input['vuz_id'], "ADMINS", $newArrAdmins);

	$result = $newArrAdmins;
}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>