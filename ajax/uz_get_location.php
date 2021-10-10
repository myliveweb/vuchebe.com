<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if($input['type'] == 'country') {

    $arSelectList = array("ID", "NAME", "IBLOCK_ID");
    $arFilterList = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "NAME" => $input['str_country'] . "%");

    $resList = CIBlockSection::GetList(array("NAME" => "ASC"), $arFilterList, false, false, $arSelectList);
    while ($rowList = $resList->GetNext()) {
        $result[] = $rowList;
    }
} elseif($input['type'] == 'region') {

    $groupBy = array();
    $arSelectList = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION");
    $arFilterList = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "SECTION_ID" => $input['id_country'], "PROPERTY_REGION" => $input['str_region'] . "%");

    $resList = CIBlockElement::GetList(array("PROPERTY_REGION" => "ASC"), $arFilterList, false, false, $arSelectList);
    while ($rowList = $resList->GetNext()) {
        if(!in_array($rowList['PROPERTY_REGION_VALUE'], $groupBy)) {
            $groupBy[] = $rowList['PROPERTY_REGION_VALUE'];
            $result[] = $rowList;
        }
    }
} elseif($input['type'] == 'city') {

    $arSelectList = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION");
    $arFilterList = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "SECTION_ID" => $input['id_country'], "PROPERTY_REGION" => $input['str_region'], "NAME" => $input['str_city'] . "%");

    $resList = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterList, false, false, $arSelectList);
    while ($rowList = $resList->GetNext()) {
        $result[] = $rowList;
    }
}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>