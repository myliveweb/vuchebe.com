<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = 0;
$result = array();
$city = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if($user_id) {

	if(mb_strlen($input['city']) && mb_strlen($input['region'])) {
	    $arSelectCity = array("ID", "NAME", "IBLOCK_ID");
	    $arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_REGION" => $input['region'], "NAME" => $input['city'] . "%");
	    $resCity = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterCity, false, false, $arSelectCity);
	    while($rowCity = $resCity->GetNext()) {
		    $city[] = array('id' => $rowCity['ID'], 'name' => $rowCity['NAME']);
		}
	}

	if(!sizeof($city) && mb_strlen($input['city']) && $input['country']) {
	    $arSelectCity = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION");
	    $arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $input['country'], "NAME" => $input['city'] . "%");
	    $resCity = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterCity, false, false, $arSelectCity);
	    while($rowCity = $resCity->GetNext()) {
		    $city[] = array('id' => $rowCity['ID'], 'name' => strtoupper($rowCity['NAME']), 'region' => $rowCity['PROPERTY_REGION_VALUE']);
		}
	}

	$result = $city;
}

$data = array("status" => "success", 'res' => $result, 'len' => mb_strlen($input['city']));
die(json_encode($data));
?>