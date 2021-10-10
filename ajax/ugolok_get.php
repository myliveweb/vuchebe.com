<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);
$id = (int) $input['id'];

CModule::IncludeModule('iblock');

$arSelectLaw = array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "DETAIL_TEXT", "DETAIL_PICTURE", "IBLOCK_SECTION_ID", "PROPERTY_WIKI", "PROPERTY_SIGN");
$arFilterLaw = array("IBLOCK_ID" => 5, "ACTIVE" => "Y", "ID" => $id);
$resLaw = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), $arFilterLaw, false, false, $arSelectLaw);
if($rowLaw = $resLaw->GetNext()) {
    $tmp = array();

    $tmp['id']      = $rowLaw['ID'];
    $tmp['name']    = $rowLaw['NAME'];
    $tmp['preview'] = preg_replace("#(<br */?>\s*)+#i", "\n", $rowLaw['PREVIEW_TEXT']);;
    //$tmp['preview'] = $rowLaw['PREVIEW_TEXT'];
    $tmp['detail']  = preg_replace("#(<br */?>\s*)+#i", "\n", $rowLaw['DETAIL_TEXT']);;
    //$tmp['detail']  = $rowLaw['DETAIL_TEXT'];
    $tmp['section'] = $rowLaw['IBLOCK_SECTION_ID'];

    if($rowLaw['DETAIL_PICTURE']) {
        $tmp['src'] = CFile::GetPath($rowLaw['DETAIL_PICTURE']);
    } else {
        $tmp['src'] = '';
    }

    $tmp['wiki'] = $rowLaw['PROPERTY_WIKI_VALUE'];
    $tmp['sign'] = $rowLaw['PROPERTY_SIGN_VALUE'];

    $arr_section = array();
    $ElementId = $arResult['ID'];
    $db_groups = CIBlockElement::GetElementGroups($rowLaw['ID'], true);
    while($ar_group = $db_groups->Fetch()) {
        $arr_section[] = $ar_group["ID"];
    }

    $tmp['sections'] = $arr_section;

    $result = $tmp;
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>