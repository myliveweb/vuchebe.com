<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$input['id'] = (int) $input['id'];
$input['status'] = (int) $input['status'];

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

$arSelect = array("ID", "NAME", "IBLOCK_ID", "CREATED_BY", "PROPERTY_LAUNCHED", "PROPERTY_OWNER", "PROPERTY_BALANCE", "PROPERTY_PLAN_TAX", "PROPERTY_LIMIT", "PROPERTY_COUNTER");
$arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "ID" => $input['id']);
$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
if($row = $res->GetNext()) {

  $createdUser = (int) $row["PROPERTY_OWNER_VALUE"];

  if($user_id == $createdUser) {

    if($input['status']) {
        $newLaunched = 'N';
    } else {

        $plan    = (int) $row["PROPERTY_PLAN_TAX_VALUE"];
        $balance = (int) $row["PROPERTY_BALANCE_VALUE"];
        $limit   = (int) $row["PROPERTY_LIMIT_VALUE"];
        $counter = (int) $row["PROPERTY_COUNTER_VALUE"];

        if($balance >= $plan && $limit > $counter) {
            $newLaunched = 'Y';
        } elseif($plan > $balance) {
            $result["status"] = "error";
            $result["message"] = "Недостаточно средств";
            die(json_encode($result));
        } else {
            $result["status"] = "error";
            $result["message"] = "Показы закончились";
            die(json_encode($result));
        }
    }

    CIBlockElement::SetPropertyValueCode($row["ID"], "LAUNCHED", $newLaunched);

    if($newLaunched == 'Y') {
        setBannerHistory(2, $row["ID"], $row["PROPERTY_OWNER_VALUE"], 0);
    } elseif($newLaunched == 'N') {
        setBannerHistory(3, $row["ID"], $row["PROPERTY_OWNER_VALUE"], 0);
    }

    $start = CIBlockElement::GetList(
      array(),
      array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "PROPERTY_OWNER" => $user_id, "!PROPERTY_MODERATION" => "N", "!PROPERTY_LAUNCHED" => "N"),
      array(),
      false,
      array('ID', 'NAME')
    );

    $stop = CIBlockElement::GetList(
      array(),
      array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "PROPERTY_OWNER" => $user_id, "!PROPERTY_MODERATION" => "N", "!PROPERTY_LAUNCHED" => "Y"),
      array(),
      false,
      array('ID', 'NAME')
    );

    $result["status"] = "success";
    $result["start"] = $start;
    $result["stop"] = $stop;
  } else {
    $result["status"] = "error";
    $result["message"] = "Неверный ID пользователя";
  }
}

die(json_encode($result));
?>