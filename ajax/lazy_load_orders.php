<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);
$cnt = (int) $input['cnt'];
$type = $input['type'];

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

if($user_id) {

    $arrPlan = array();
    $arSelectPlan = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PLAN_ID");
    $arFilterPlan = array("IBLOCK_ID" => array(38, 44), "ACTIVE" => "Y");
    $resPlan = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilterPlan, false, false, $arSelectPlan);
    while($rowPlan = $resPlan->GetNext()) {

        if($rowPlan['IBLOCK_ID'] == 38)
            $tarifPlan = 34;
        else
            $tarifPlan = 35;
        $arrPlan[$tarifPlan][$rowPlan['PROPERTY_PLAN_ID_VALUE']] = $rowPlan['NAME'];
    }

  $arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "CREATED_BY");

  $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "!PROPERTY_DELETE" => "Y", "PROPERTY_OWNER" => $user_id);

  if($type == 'start') {
      $arFilter['!PROPERTY_LAUNCHED']   = 'N';
      $arFilter['!PROPERTY_MODERATION'] = 'N';
  } elseif($type == 'stop') {
      $arFilter['!PROPERTY_LAUNCHED']   = 'Y';
      $arFilter['!PROPERTY_MODERATION'] = 'N';
  } elseif($type == 'moderate') {
      $arFilter['!PROPERTY_REJECTED']   = 'Y';
      $arFilter['!PROPERTY_MODERATION'] = 'Y';
  } elseif($type == 'otklon') {
      $arFilter['!PROPERTY_REJECTED']   = 'N';
      $arFilter['!PROPERTY_MODERATION'] = 'Y';
  }

  if($input['load'])
    $arFilter["!ID"] = $input['load'];

  $n = 0;

  $res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
  while($obRes = $res->GetNextElement()) {

      $row = $obRes->GetFields();
      $props = $obRes->GetProperties();

      $row['PROPERTY_PLAN_VALUE'] = $props['PLAN']['VALUE'];
      $row['PROPERTY_PLAN_TAX_VALUE'] = $props['PLAN_TAX']['VALUE'];
      $row['PROPERTY_URL_VALUE'] = $props['URL']['VALUE'];
      $row['PROPERTY_COUNTER_VALUE'] = $props['COUNTER']['VALUE'];
      $row['PROPERTY_LIMIT_VALUE'] = $props['LIMIT']['VALUE'];
      $row['PROPERTY_CLICK_VALUE'] = $props['CLICK']['VALUE'];
      $row['PROPERTY_HIDE_VALUE'] = $props['HIDE']['VALUE'];
      $row['PROPERTY_REJECTED_VALUE'] = $props['REJECTED']['VALUE'];
      $row['PROPERTY_REASON_VALUE'] = $props['REASON']['VALUE'];
      $row['PROPERTY_LAUNCHED_VALUE'] = $props['LAUNCHED']['VALUE'];
      $row['PROPERTY_MODERATION_VALUE'] = $props['MODERATION']['VALUE'];

      $row['PROMOCODE'] = $props['PROMOCODE']['VALUE'];
      $row['DISCOUNT']  = $props['DISCOUNT']['VALUE'];

      if($type == 'stop' && $row["PROPERTY_COUNTER_VALUE"] >= $row["PROPERTY_LIMIT_VALUE"])
          continue;

      if($type == 'finich' && $row["PROPERTY_COUNTER_VALUE"] < $row["PROPERTY_LIMIT_VALUE"])
          continue;

      if($n >= $cnt)
          continue;
      else
          $n++;

    list($dateFormat) = explode(' ', $row["DATE_CREATE"]);
    $row["DATE_FORMAT"] = $dateFormat;

    $row["ARTICLE"] = 'Заказ №' . $row["ID"] . ' от ' . $row["DATE_FORMAT"];

    $row["PLAN"] = $arrPlan[$row["IBLOCK_ID"]][$row["PROPERTY_PLAN_VALUE"]];

    $row["PLAN_CODE"] = $row["PROPERTY_PLAN_VALUE"];

    $row["PLAN_TAX"] = 0;
    if($row["PROPERTY_PLAN_TAX_VALUE"] > 0) {
      $row["PLAN_TAX"] = round($row["PROPERTY_PLAN_TAX_VALUE"], 2);
    }

    if($row["IBLOCK_ID"] == 34) {
      $row["REPEAT"] = '/user/' . $user_id . '/topbanner/' . $row["ID"] . '/';
    } elseif($row["IBLOCK_ID"] == 35) {
      $row["REPEAT"] = '/user/' . $user_id . '/sidebanner/' . $row["ID"] . '/';
    }

    $row["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);

    if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] == 'Y') {
      $row['STATUS_NAME'] = 'Активен';
      $row['STATUS_STYLE'] = 'color: green;';
    }

    if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] != 'Y') {
      $row['STATUS_NAME'] = 'Остановлен';
      $row['STATUS_STYLE'] = 'color: #9f9f9f;';
    }

    if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y') {
      $row['STATUS_NAME'] = 'На модерации';
      $row['STATUS_STYLE'] = 'color: #9f9f9f;';
    }

    if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] == 'Y') {
      $row['STATUS_NAME'] = 'Отклонён';
      $row['STATUS_STYLE'] = 'color: red;';
    }

    if ($row['PROPERTY_COUNTER_VALUE'] >= $row['PROPERTY_LIMIT_VALUE']) {
      $row['STATUS_NAME'] = 'Завершён';
      $row['STATUS_STYLE'] = 'color: #000000;';
    }

    $result['res'][] = $row;
  }
  $result['status'] = 'success';
} else {
  $result['status'] = 'error';
  $result['message'] = 'Требуется авторизация';
}

die(json_encode($result));
?>