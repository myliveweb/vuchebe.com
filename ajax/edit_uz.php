<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

CModule::IncludeModule('iblock');

$error = 0;
$result = array();
$out = array();

$input = filter_input_array(INPUT_POST);

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if($user_id && $input['id']) {
	$result = $dbh->query('SELECT * from a_user_uz WHERE id = ' . $input['id'] . ' AND user_id = ' . $user_id . ' ORDER BY id DESC')->fetch();

	$result['arRegion'] = array();
	if($result['country_id']) {
		$arSelectRegion = array("ID", "NAME", "IBLOCK_ID");
		$arFilterRegion = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "SECTION_ID" => $result['country_id']);
		$resRegion = CIBlockElement::GetList(array("PROPERTY_REGION" => "ASC"), $arFilterRegion, array("PROPERTY_REGION"), false, $arSelectRegion);
		while($rowRegion = $resRegion->GetNext()) {
			$result['arRegion'][] = $rowRegion['PROPERTY_REGION_VALUE'];
		}
	}

	if($result['type'] == 1) {

		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_COUNTRY", "PROPERTY_REGION", "PROPERTY_CITY");
		$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ID" => $result['uz_id']);
		$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext()) {
			$result['data'] = $row;

			$fack = array();
			$res = CIBlockElement::GetProperty(2, $result['uz_id'], array("sort" => "asc"), array("CODE"=>"FAKULTETS"));
			while($ob = $res->GetNext()) {
				$arrFackEx = explode('#', $ob['VALUE']);
				$fack[] = $arrFackEx[0];
			}
			$result['fack_arr'] = $fack;
		}
	} elseif($result['type'] == 2) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
		$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "ID" => $result['uz_id']);
		$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext())
		{
			$result['data'] = $row;
			$result['data']['PROPERTY_COUNTRY_VALUE'] = 'РФ';
			$result['data']['PROPERTY_COUNTRY_ENUM_ID'] = 37;

			$city = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
			$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "!PROPERTY_CITY" => false);
			$res = CIBlockElement::GetList(array("PROPERTY_CITY" => "ASC"), $arFilter, array("PROPERTY_CITY"), false, $arSelect);
			while($row = $res->GetNext())
			{
				$city[] = $row['PROPERTY_CITY_VALUE'];
			}
			$result['city'] = $city;
		}
	} elseif($result['type'] == 3) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
		$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "ID" => $result['uz_id']);
		$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext())
		{
			$result['data'] = $row;
			$result['data']['PROPERTY_COUNTRY_VALUE'] = 'РФ';
			$result['data']['PROPERTY_COUNTRY_ENUM_ID'] = 37;

			$city = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
			$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "!PROPERTY_CITY" => false);
			$res = CIBlockElement::GetList(array("PROPERTY_CITY" => "ASC"), $arFilter, array("PROPERTY_CITY"), false, $arSelect);
			while($row = $res->GetNext())
			{
				$city[] = $row['PROPERTY_CITY_VALUE'];
			}
			$result['city'] = $city;
		}
	} elseif($result['type'] == 4) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
		$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "ID" => $result['uz_id']);
		$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext())
		{
			$result['data'] = $row;
			$result['data']['PROPERTY_COUNTRY_VALUE'] = 'РФ';
			$result['data']['PROPERTY_COUNTRY_ENUM_ID'] = 37;

			$city = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
			$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "!PROPERTY_CITY" => false);
			$res = CIBlockElement::GetList(array("PROPERTY_CITY" => "ASC"), $arFilter, array("PROPERTY_CITY"), false, $arSelect);
			while($row = $res->GetNext())
			{
				$city[] = $row['PROPERTY_CITY_VALUE'];
			}
			$result['city'] = $city;
		}
	}

}

$data = array("status" => "success", "res" => $result);
die(json_encode($data));
?>