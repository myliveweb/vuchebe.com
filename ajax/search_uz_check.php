<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] < $b['sort']) ? -1 : 1;
}

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE");

$arrFilter = array();
$arrFilter["IBLOCK_ID"] = "50";
$arrFilter["NAME"] = $input['str_check'] . "%";

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arrFilter, false, false, $arSelect);

while($obRes = $res->GetNextElement()) {

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $tempOut = array();

    $tempOut = $row;

    $tempOut['sort'] = false;

    if (mb_stripos($row['NAME'], $input['str_check']) !== false) {
        $tempOut['TYPE'] = 'Название заведения';
        $tempOut['TYPE_ID'] = 'NAME';
        $tempOut['sort'] = mb_stripos($row['NAME'], $input['str_check']);
        $tempOut['NAME_DISPLAY'] = $row['NAME'];
    }

    $result[] = $tempOut;
}

$arrFilter = array();
$arrFilter["IBLOCK_ID"] = "50";
$arrFilter["PROPERTY_SITE"] = $input['str_check'] . "%";

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arrFilter, false, false, $arSelect);

while($obRes = $res->GetNextElement()) {

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['SITE'] = $props['SITE']['VALUE'];

    $tempOut = array();

    $tempOut = $row;

    $tempOut['sort'] = false;

    if(mb_stripos($row['SITE'], $input['str_check']) !== false) {
        $tempOut['TYPE'] = 'Сайт';
        $tempOut['TYPE_ID'] = 'SITE';
        $tempOut['sort'] = mb_stripos($row['SITE'], $input['str_check']);
        $tempOut['NAME_DISPLAY'] = $row['SITE'];
    }

    $result[] = $tempOut;
}

$arrFilter = array();
$arrFilter["IBLOCK_ID"] = "50";
$arrFilter["PROPERTY_PHONE"] = $input['str_check'] . "%";

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arrFilter, false, false, $arSelect);

while($obRes = $res->GetNextElement()) {

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['PHONE'] = $props['PHONE']['VALUE'];

    $tempOut = array();

    $tempOut = $row;

    $tempOut['sort'] = false;

    if(mb_stripos($row['PHONE'], $input['str_check']) !== false) {
        $tempOut['TYPE'] = 'Телефон';
        $tempOut['TYPE_ID'] = 'PHONE';
        $tempOut['sort'] = mb_stripos($row['PHONE'], $input['str_check']);
        $tempOut['NAME_DISPLAY'] = $row['PHONE'];
    }

    $result[] = $tempOut;
}

usort($result, "cmp");

$data = array("status" => "success", 'data' => $result);

die(json_encode($data));
?>