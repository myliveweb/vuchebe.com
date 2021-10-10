<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);
$id = (int) $input['id'];

CModule::IncludeModule('iblock');

$arSelectLaw = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_TEXT", "PROPERTY_DOC", "PROPERTY_SECOND_NAME");
$arFilterLaw = array("IBLOCK_ID" => 41, "ACTIVE" => "Y", "ID" => $id);
$resLaw = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), $arFilterLaw, false, false, $arSelectLaw);
if($rowLaw = $resLaw->GetNext()) {
    $rowLaw['pdf'] = CFile::GetPath($rowLaw["PROPERTY_DOC_VALUE"]);
    $result = $rowLaw;
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>