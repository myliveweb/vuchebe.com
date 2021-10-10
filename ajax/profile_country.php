<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = array();

$input = filter_input_array(INPUT_POST);

$input['id'] = (int) $input['id'];

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$isAdmin = 0;

if(isEdit())
	$isAdmin = 1;

$result["CNT_CITY"] = 0;
$result["ABC"] = array();
$result["ACTIVE_ABC"] = '';
$result["CITY"] = array();
$result["CITY_ACTIVE"] = 0;
$result["CITY_TOP"] = array();

$arSelectUtm = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_UTM");
$arFilterUtm = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_NALICHIE" => "Y", "SECTION_ID" => $input['id'], "PROPERTY_CAPITAL" => "Y");
$resUtm = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterUtm, false, false, $arSelectUtm);
if($rowUtm = $resUtm->GetNext()) {
	$result["UTM"] = (int) str_replace("UTC", "", $rowUtm['PROPERTY_UTM_VALUE']);
} else {
	$result["UTM"] = 3;
}

if($input['set_cookies']) {
	setcookie("PANEL_COUNTRY", $input['id'], time()+31536000, "/", ".vuchebe.com");
	setcookie("PANEL_ABC", '', time()-31536000, "/", ".vuchebe.com");
	setcookie("PANEL_CITY", '', time()-31536000, "/", ".vuchebe.com");
	setcookie("PANEL_CITY_NAME", '', time()-31536000, "/", ".vuchebe.com");
	setcookie("PANEL_UTM", $result["UTM"], time()+31536000, "/", ".vuchebe.com");

	$_SESSION['PANEL']['COUNTRY'] = $input['id'];
	$_SESSION['PANEL']['CITY'] = 0;
	$_SESSION['PANEL']['CITY_NAME'] = '';
	$_SESSION['PANEL']['UTM'] = $result["UTM"];
	$_SESSION['PANEL']['REGION'] = '';
	$_SESSION['PANEL']['TOPCITY'] = 0;

	if($user_id) {

		$user = new CUser;

		$fields = Array(
			"UF_COUNTRY" => $input['id'],
			"UF_REGION"  => '',
			"UF_CITY"    => 0
		);

		$user->Update($user_id, $fields);
	}
}

$arFilter = Array("IBLOCK_ID"=>32, "ACTIVE"=>"Y", "PROPERTY_NALICHIE" => "Y", "SECTION_ID" => $input['id']);
$cnt_city = CIBlockElement::GetList(false, $arFilter, array('IBLOCK_ID'))->Fetch()['CNT'];
if($cnt_city) {
	$result["CNT_CITY"] = $cnt_city;
}

if($result["CNT_CITY"]) {

	$arrLiter = array();
    $arSelectLiter = array("ID", "NAME", "IBLOCK_ID");
    $arFilterLiter = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_NALICHIE" => "Y", "SECTION_ID" => $input['id']);
    $resLiter = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterLiter, false, false, $arSelectLiter);
	while($rowLiter = $resLiter->GetNext()) {
		$liter = mb_strtoupper(mb_substr($rowLiter['NAME'], 0, 1));
		if(!in_array($liter, $arrLiter))
			$arrLiter[] = $liter;
	}
	if($arrLiter) {
		asort($arrLiter);
		$result["ABC"] = $arrLiter;
	}

	$result["ABC_ACTIVE"] = $result["ABC"][0];

	$_SESSION['PANEL']['ABC'] = $result["ABC"][0];

    $arSelectList = array("ID", "NAME", "IBLOCK_ID");
    $arFilterList = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_NALICHIE" => "Y", "SECTION_ID" => $input['id'], "NAME" => $result["ABC_ACTIVE"] . "%");
    $resList = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterList, false, false, $arSelectList);
	while($rowList = $resList->GetNext()) {
		$result["CITY"][] = $rowList;
	}

    $arSelectCity = array("ID", "NAME", "IBLOCK_ID");
    $arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_NALICHIE" => "Y", "PROPERTY_TOP" => "Y", "SECTION_ID" => $input['id']);
    $resCity = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterCity, false, false, $arSelectCity);
	while($rowCity = $resCity->GetNext()) {
		$result["CITY_TOP"][] = $rowCity;
	}



}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>