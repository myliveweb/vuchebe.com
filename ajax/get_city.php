<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = 0;
$result = array();

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
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
		$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $input['country'], "!PROPERTY_CITY" => false);
		$res = CIBlockElement::GetList(array("PROPERTY_CITY" => "ASC"), $arFilter, array("PROPERTY_CITY"), false, $arSelect);
		while($row = $res->GetNext())
		{
			$result[] = $row['PROPERTY_CITY_VALUE'];
		}
	} elseif($input['obr'] == 2 && $input['country'] == 37) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
		$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "!PROPERTY_CITY" => false);
		$res = CIBlockElement::GetList(array("PROPERTY_CITY" => "ASC"), $arFilter, array("PROPERTY_CITY"), false, $arSelect);
		while($row = $res->GetNext())
		{
			$result[] = $row['PROPERTY_CITY_VALUE'];
		}
	} elseif($input['obr'] == 3 && $input['country'] == 37) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
		$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "!PROPERTY_CITY" => false);
		$res = CIBlockElement::GetList(array("PROPERTY_CITY" => "ASC"), $arFilter, array("PROPERTY_CITY"), false, $arSelect);
		while($row = $res->GetNext())
		{
			$result[] = $row['PROPERTY_CITY_VALUE'];
		}
	} elseif($input['obr'] == 4 && $input['country'] == 37) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
		$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "!PROPERTY_CITY" => false);
		$res = CIBlockElement::GetList(array("PROPERTY_CITY" => "ASC"), $arFilter, array("PROPERTY_CITY"), false, $arSelect);
		while($row = $res->GetNext())
		{
			$result[] = $row['PROPERTY_CITY_VALUE'];
		}
	}

	$city = array();

    $arSelectCity = array("ID", "NAME", "IBLOCK_ID");
    $arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y");
    $resCity = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterCity, false, false, $arSelectCity);
    while($rowCity = $resCity->GetNext()) {
	    $city[] = $rowCity;
	}

	$result = $city;
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>