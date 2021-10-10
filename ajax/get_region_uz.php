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
	if($input['country']) {
		$arSelectRegion = array("ID", "NAME", "IBLOCK_ID");
		$arFilterRegion = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "SECTION_ID" => $input['country']);
		$resRegion = CIBlockElement::GetList(array("PROPERTY_REGION" => "ASC"), $arFilterRegion, array("PROPERTY_REGION"), false, $arSelectRegion);
		while($rowRegion = $resRegion->GetNext()) {
			$result[] = $rowRegion['PROPERTY_REGION_VALUE'];
		}
	}
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>