<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = 0;
$result = array();

//$input = json_decode(file_get_contents('php://input'), true);
$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if($user_id) {

	if($input['obr'] == 1) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID");
		$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%", "PROPERTY_COUNTRY" => $input['country'], "PROPERTY_CITY" => $input['city']);
		$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
		while($row = $res->GetNext())
		{
			$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
		}

		if(!$result) {
			$arSelect = array("ID", "NAME", "IBLOCK_ID");
			$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%", "PROPERTY_COUNTRY" => $input['country'], "PROPERTY_REGION" => $input['region']);
			$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
			while($row = $res->GetNext())
			{
				$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
			}
		}

	} elseif($input['obr'] == 2) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID");
		$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%", "PROPERTY_COUNTRY" => $input['country'], "PROPERTY_CITY" => $input['city']);
		$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
		while($row = $res->GetNext())
		{
			$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
		}

		if(!$result) {
			$arSelect = array("ID", "NAME", "IBLOCK_ID");
			$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%", "PROPERTY_COUNTRY" => $input['country'], "PROPERTY_REGION" => $input['region']);
			$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
			while($row = $res->GetNext())
			{
				$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
			}
		}
	} elseif($input['obr'] == 3) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID");
		$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%", "PROPERTY_COUNTRY" => $input['country'], "PROPERTY_CITY" => $input['city']);
		$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
		while($row = $res->GetNext())
		{
			$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
		}

		if(!$result) {
			$arSelect = array("ID", "NAME", "IBLOCK_ID");
			$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%", "PROPERTY_COUNTRY" => $input['country'], "PROPERTY_REGION" => $input['region']);
			$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
			while($row = $res->GetNext())
			{
				$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
			}
		}
	} elseif($input['obr'] == 4) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID");
		$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%", "PROPERTY_COUNTRY" => $input['country'], "PROPERTY_CITY" => $input['city']);
		$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
		while($row = $res->GetNext())
		{
			$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
		}

		if(!$result) {
			$arSelect = array("ID", "NAME", "IBLOCK_ID");
			$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "NAME" => "%".$input['name']."%", "PROPERTY_COUNTRY" => $input['country'], "PROPERTY_REGION" => $input['region']);
			$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
			while($row = $res->GetNext())
			{
				$result[] = array('id' => $row['ID'], 'name' => $row['NAME']);
			}
		}
	}
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>