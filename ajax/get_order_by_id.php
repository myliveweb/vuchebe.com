<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);
$id = (int) $input['id'];

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

if($user_id) {

  $arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "PROPERTY_URL", "PROPERTY_COUNTER", "PROPERTY_LIMIT", "PROPERTY_CLICK", "PROPERTY_HIDE", "PROPERTY_MODERATION", "PROPERTY_REJECTED", "PROPERTY_REASON", "PROPERTY_LAUNCHED", "PROPERTY_COUNTRY", "PROPERTY_REGION", "PROPERTY_CITY");

  $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "CREATED_BY" => $user_id, "ID" => $id);
  $res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
  if($row = $res->Fetch())
  {
    $row["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);

    if($row["PROPERTY_COUNTRY_VALUE"]) {

      $arSelectLoc = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION");
      $arFilterLoc = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "SECTION_ID" => $row["PROPERTY_COUNTRY_VALUE"], "!PROPERTY_REGION" => false);
      $resLoc = CIBlockElement::GetList(array("PROPERTY_REGION" => "ASC"), $arFilterLoc, array("PROPERTY_REGION"));
      while($rowLoc = $resLoc->GetNext()) {
        $result['region'][] = $rowLoc["PROPERTY_REGION_VALUE"];
      }
    }

    if($row["PROPERTY_CITY_VALUE"]) {

      $arSelectList = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_TOPCITY", "PROPERTY_CAPITAL");
      $arFilterList = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $row["PROPERTY_CITY_VALUE"]);

      if($row["PROPERTY_COUNTRY_VALUE"])
        $arFilterList['SECTION_ID'] = $row["PROPERTY_COUNTRY_VALUE"];
      if($row["PROPERTY_REGION_VALUE"])
        $arFilterList['PROPERTY_REGION'] = $row["PROPERTY_REGION_VALUE"];


      $resList = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterList, false, false, $arSelectList);
      if($rowList = $resList->GetNext()) {

        if(!$rowList['PROPERTY_TOPCITY_VALUE'])
          $rowList['PROPERTY_TOPCITY_VALUE'] = 'N';

        if(!$rowList['PROPERTY_CAPITAL_VALUE'])
          $rowList['PROPERTY_CAPITAL_VALUE'] = 'N';

        $result['city'] = $rowList;
      }
    }

    $result['res'] = $row;
  }
  $result['status'] = 'success';
} else {
  $result['status'] = 'error';
  $result['message'] = 'Требуется авторизация';
}

die(json_encode($result));
?>