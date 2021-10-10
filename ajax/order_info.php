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

$isAdmin = 0;
$admin = 0;

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE");
$arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "ID" => $id);
$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
if($obRes = $res->GetNextElement()) {

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $admin = $props['OWNER']['VALUE'];

    if($user_id == $admin)
        $isAdmin = 1;

    if($row['IBLOCK_ID'] == 34) {
        $typeBanner = 'top';
    } else {
        $typeBanner = 'side';
    }
}

if($isAdmin || isEdit()) {

    $arrPlan = array();
    $arSelectPlan = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PLAN_ID");
    $arFilterPlan = array("IBLOCK_ID" => array(38, 44), "ACTIVE" => "Y");
    $resPlan = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilterPlan, false, false, $arSelectPlan);
    while($rowPlan = $resPlan->GetNext()) {

        if($rowPlan['IBLOCK_ID'] == 38) {
            $tarifPlan = 34;
        } else {
            $tarifPlan = 35;
        }
        $arrPlan[$tarifPlan][$rowPlan['PROPERTY_PLAN_ID_VALUE']] = $rowPlan['NAME'];
    }

    list($dateFormat) = explode(' ', $row["DATE_CREATE"]);
    $row["DATE_FORMAT"] = $dateFormat;

    $row["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);

    $props = $obRes->GetProperties();

    $planTax = 0;
    if($props['PLAN_TAX']['VALUE'] > 0) {
        $planTax = round($props['PLAN_TAX']['VALUE'], 2);
    }

    $row['TYPE_BANNER'] = $typeBanner;
    $row['PLAN_TAX']    = $planTax;
    $row['PLAN']        = $props['PLAN']['VALUE'];
    $row['PLAN_NAME']   = $arrPlan[$row["IBLOCK_ID"]][$row['PLAN']];
    $row['URL']         = $props['URL']['VALUE'];
    $row['COUNTER']     = $props['COUNTER']['VALUE'];
    $row['LIMIT']       = $props['LIMIT']['VALUE'];
    $row['B_CLICK']     = $props['CLICK']['VALUE'];
    $row['HIDE']        = $props['HIDE']['VALUE'];
    $row['REJECTED']    = $props['REJECTED']['VALUE'];
    $row['REASON']      = $props['REASON']['VALUE'];
    $row['LAUNCHED']    = $props['LAUNCHED']['VALUE'];
    $row['MODERATION']  = $props['MODERATION']['VALUE'];

    $row['LIMIT_PROMO']  = $props['LIMIT_PROMO']['VALUE'];
    $row['LIMIT_CURENT'] = $props['LIMIT_CURENT']['VALUE'];

    $row['PROMOCODE'] = $props['PROMOCODE']['VALUE'];
    $row['DISCOUNT']  = $props['DISCOUNT']['VALUE'];

    if($row['PROMOCODE']) {
        $row['STRPROMOCODE'] = 'Промокод: ' . $row['PROMOCODE'] . ' (скидка ' . $row['DISCOUNT'] . '%';
        if($row['LIMIT_PROMO']) {
            $row['STRPROMOCODE'] .= ' на ' . $row['LIMIT_PROMO'] . ' показов, осталось ' . $row['LIMIT_CURENT'];
        }
        $row['STRPROMOCODE'] .= ')';
    }

    if ($row['MODERATION'] == 'Y' && $row['REJECTED'] != 'Y' && $row['LAUNCHED'] == 'Y') {
        $row['STATUS_NAME'] = 'Активен';
        $row['STATUS_STYLE'] = 'color: green;';
    }

    if ($row['MODERATION'] == 'Y' && $row['REJECTED'] != 'Y' && $row['LAUNCHED'] != 'Y') {
        $row['STATUS_NAME'] = 'Остановлен';
        $row['STATUS_STYLE'] = 'color: #9f9f9f;';
    }

    if ($row['MODERATION'] != 'Y' && $row['REJECTED'] != 'Y') {
        $row['STATUS_NAME'] = 'На модерации';
        $row['STATUS_STYLE'] = 'color: #9f9f9f;';
    }

    if ($row['MODERATION'] != 'Y' && $row['REJECTED'] == 'Y') {
        $row['STATUS_NAME'] = 'Отклонён';
        $row['STATUS_STYLE'] = 'color: red;';
    }

    if ($row['COUNTER'] >= $row['LIMIT']) {
        $row['STATUS_NAME'] = 'Завершён';
        $row['STATUS_STYLE'] = 'color: #000000;';
    }

    $list = array();

    $sth = $dbh->prepare('SELECT * from a_banner_history WHERE user_id = ? AND banner_id = ? ORDER BY create_at DESC');
    $sth->execute(array($admin, $row['ID']));
    $arrList = $sth->fetchAll();

    foreach($arrList as $itemList) {

        $temp = array();

        $temp['date_format'] = date("d.m.Y (H:i)", $itemList['create_at']);
        $temp['tax'] = $itemList['tax'];
        $temp['disc'] = $itemList['disc'];
        $temp['direction'] = $itemList['direction'];

        $list[] = $temp;
    }

    $result = $row;
}

if($result) {
    $data = array("status" => "success", 'res' => $result, 'list' => $list);
} else {
    $data = array("status" => "error", 'res' => 'Ошибка получения данных');
}

die(json_encode($data));
?>