<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

if($input['obr'] == 1) {
	$arSelect = array("ID", "NAME", "IBLOCK_ID");
	$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%");
	$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
	while($row = $res->GetNext())
	{
		$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
	}
} elseif($input['obr'] == 2) {
	$arSelect = array("ID", "NAME", "IBLOCK_ID");
	$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%");
	$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
	while($row = $res->GetNext())
	{
		$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
	}
} elseif($input['obr'] == 3) {
	$arSelect = array("ID", "NAME", "IBLOCK_ID");
	$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%");
	$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
	while($row = $res->GetNext())
	{
		$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
	}
} elseif($input['obr'] == 4) {
	$arSelect = array("ID", "NAME", "IBLOCK_ID");
	$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%");
	$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
	while($row = $res->GetNext())
	{
		$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
	}
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>