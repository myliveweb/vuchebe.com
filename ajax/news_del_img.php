<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$result = array();

CModule::IncludeModule('iblock');

$input = filter_input_array(INPUT_POST);

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

$resAdmins = CIBlockElement::GetProperty($iblock, $input['id_vuz'], "sort", "asc", array("CODE" => "ADMINS"));
while ($obAdmins = $resAdmins->GetNext()) {
    $arrAdmins[] = $obAdmins['VALUE'];
}

if(in_array($user_id, $arrAdmins) || isEdit())
	$isAdmin = 1;

if($isAdmin) {

	global $USER;
	global $DB;

    $el = new CIBlockElement;

    $arLoadProductArray = Array(
        "MODIFIED_BY"       => $USER->GetID(),
        "IBLOCK_SECTION"    => false,
        "PREVIEW_PICTURE"   => array('del' => 'Y')
    );

    $el->Update($input['id_block'], $arLoadProductArray);

    $result['status'] = 'success';

}

$data = $result ? $result : array('error' => 'Ошибка загрузки файлов.');

die(json_encode($data));
?>