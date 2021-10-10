<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$arSelectCity = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION");
$arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "NAME" => $input['str_city'] . "%");
$resCity = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterCity, false, false, $arSelectCity);
while($rowCity = $resCity->GetNext()) {
    $result[] = array('id' => $rowCity['ID'], 'name' => strtoupper($rowCity['NAME']), 'region' => $rowCity['PROPERTY_REGION_VALUE']);
}

$data = array("status" => "success", 'res' => $result, 'len' => mb_strlen($input['city']));
die(json_encode($data));
?>