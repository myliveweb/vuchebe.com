<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$result = array();

$input = filter_input_array(INPUT_POST);

global $USER;
global $DB;

$iblockArray = array('top' => 34, 'side' => 35);
$currentIblock = $iblockArray[$input['type']];

$path = $_SERVER["DOCUMENT_ROOT"] . $input['img'];
$arFile = CFile::MakeFileArray($path);

CModule::IncludeModule('iblock');
$el = new CIBlockElement;

$PROP = array();

$PROP['COUNTRY'] = $input['country'];
$PROP['REGION']  = $input['region'];
$PROP['CITY']    = $input['city'];

$PROP['URL']     = $input['link'];
$PROP['COUNTER'] = 0;
$PROP['CLICK']   = 0;
$PROP['HIDE']    = 0;

$PROP['LIMIT']   = $input['num'];

$PROP['MODERATION'] = 'N';

$PROP['PLAN']     = $input['plan'];
$PROP['PLAN_TAX'] = $input['plan_tax'];

$PROP['OWNER']    = $_SESSION['USER_DATA']['ID'];

$free = $_SESSION['USER_DATA']['WORK_FAX'] - $_SESSION['USER_DATA']['WORK_PAGER'];
$PROP['BALANCE']  = round($free, 2);

$PROP['PROMOCODE']  = $input['promocode'];
$PROP['DISCOUNT']   = $input['promoDiscount'];

$PROP['LIMIT_PROMO']    = $input['promoLimit'];
$PROP['LIMIT_CURENT']   = $input['promoLimit'];

$PROP['TICKET']   = '';

if($PROP['DISCOUNT']) {
    if($PROP['LIMIT_PROMO']) {
        $sumDiscount = $PROP['LIMIT_CURENT'] * $PROP['PLAN_TAX'] / 100 * $PROP['DISCOUNT'];
        $PROP['TOTAL'] = $input['total'] - $sumDiscount;
    } else {
        $PROP['TOTAL'] = $input['total'] / 100 * $PROP['DISCOUNT'];
    }
} else {
    $PROP['TOTAL'] = $input['total'];
}

$arLoadProductArray = Array(
  "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
  "IBLOCK_ID"         => $currentIblock,
  "PROPERTY_VALUES"   => $PROP,
  "NAME"              => $input['name'],
  "ACTIVE"            => "Y",
  "PREVIEW_PICTURE"	  => $arFile,
  );


if ($ORDER_ID = $el->Add($arLoadProductArray)) {

    setBannerHistory(0, $ORDER_ID, $PROP['OWNER'], 0);

	$result['status']  = 'success';
    $result['message'] = 'Ваша заказ оформлен';
    $result['id']      = $ORDER_ID;
} else {
	$result['status']  = 'error';
	$result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
}

echo json_encode($result);
?>