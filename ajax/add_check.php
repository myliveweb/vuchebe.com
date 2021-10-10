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

$isAdmin = 0;

if(isEdit())
  $isAdmin = 1;

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] < $b['sort']) ? -1 : 1;
}

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE");

$arrFilter = array();
$arrFilter["IBLOCK_ID"] = "47";
if($input['pro'] !== 'moderate') {
    $arrFilter["PROPERTY_USER"] = $input['pro'];
}
$arrFilter["PROPERTY_IDSTR"] = $input['str_check'] . "%";

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arrFilter, false, false, $arSelect);

while($obRes = $res->GetNextElement()) {

    //if($arUser['ACTIVE'] != 'Y')
    //    continue;

    /*-------- Отсекаем техподдержку ---------*/

    //if(isEdit())
    //    continue;

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['IDSTR'] = $props['IDSTR']['VALUE'];

    $tempOut = array();

    $tempOut = $row;

    $tempOut['sort'] = false;

    if (mb_stripos($row['IDSTR'], $input['str_check']) !== false) {
        $tempOut['TYPE'] = '№ счёта';
        $tempOut['TYPE_ID'] = 'IDSTR';
        $tempOut['sort'] = mb_stripos($row['IDSTR'], $input['str_check']);
        $tempOut['NAME_DISPLAY'] = $row['IDSTR'];
    }

    $result[] = $tempOut;
}

$arrFilter = array();
$arrFilter["IBLOCK_ID"] = "47";
if($input['pro'] !== 'moderate') {
    $arrFilter["PROPERTY_USER"] = $input['pro'];
}
$arrFilter["PROPERTY_OGRN"] = $input['str_check'] . "%";

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arrFilter, false, false, $arSelect);

while($obRes = $res->GetNextElement()) {

    //if($arUser['ACTIVE'] != 'Y')
    //    continue;

    /*-------- Отсекаем техподдержку ---------*/

    //if(isEdit())
    //    continue;

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['OGRN'] = $props['OGRN']['VALUE'];

    $tempOut = array();

    $tempOut = $row;

    $tempOut['sort'] = false;

    if(mb_stripos($row['OGRN'], $input['str_check']) !== false) {
        $tempOut['TYPE'] = 'ОГРН';
        $tempOut['TYPE_ID'] = 'OGRN';
        $tempOut['sort'] = mb_stripos($row['OGRN'], $input['str_check']);
        $tempOut['NAME_DISPLAY'] = $row['OGRN'];
    }

    $result[] = $tempOut;
}

$arrFilter = array();
$arrFilter["IBLOCK_ID"] = "47";
if($input['pro'] !== 'moderate') {
    $arrFilter["PROPERTY_USER"] = $input['pro'];
}
$arrFilter["PROPERTY_INN"] = $input['str_check'] . "%";

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arrFilter, false, false, $arSelect);

while($obRes = $res->GetNextElement()) {

    //if($arUser['ACTIVE'] != 'Y')
    //    continue;

    /*-------- Отсекаем техподдержку ---------*/

    //if(isEdit())
    //    continue;

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['INN'] = $props['INN']['VALUE'];

    $tempOut = array();

    $tempOut = $row;

    $tempOut['sort'] = false;

    if(mb_stripos($row['INN'], $input['str_check']) !== false) {
        $tempOut['TYPE'] = 'ИНН';
        $tempOut['TYPE_ID'] = 'INN';
        $tempOut['sort'] = mb_stripos($row['INN'], $input['str_check']);
        $tempOut['NAME_DISPLAY'] = $row['INN'];
    }

    $result[] = $tempOut;
}

$arrFilter = array();
$arrFilter["IBLOCK_ID"] = "47";
if($input['pro'] !== 'moderate') {
    $arrFilter["PROPERTY_USER"] = $input['pro'];
}
$arrFilter["PROPERTY_KPP"] = $input['str_check'] . "%";

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arrFilter, false, false, $arSelect);

while($obRes = $res->GetNextElement()) {

    //if($arUser['ACTIVE'] != 'Y')
    //    continue;

    /*-------- Отсекаем техподдержку ---------*/

    //if(isEdit())
    //    continue;

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['KPP'] = $props['KPP']['VALUE'];

    $tempOut = array();

    $tempOut = $row;

    $tempOut['sort'] = false;

    if(mb_stripos($row['KPP'], $input['str_check']) !== false) {
        $tempOut['TYPE'] = 'КПП';
        $tempOut['TYPE_ID'] = 'KPP';
        $tempOut['sort'] = mb_stripos($row['KPP'], $input['str_check']);
        $tempOut['NAME_DISPLAY'] = $row['KPP'];
    }

    $result[] = $tempOut;
}

usort($result, "cmp");

$data = array("status" => "success", 'data' => $result);

die(json_encode($data));
?>