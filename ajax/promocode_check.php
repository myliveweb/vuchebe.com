<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$input = filter_input_array(INPUT_POST);

// SEPTEMBER

$arSelectPromo = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PROMOCODE", "PROPERTY_DISCOUNT", "PROPERTY_LIMIT");
$arFilterPromo = array("IBLOCK_ID" => 45, "ACTIVE" => "Y", "ACTIVE_DATE"=>"Y", "=PROPERTY_PROMOCODE" => $input['promo']);
$resPromo = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterPromo, false, false, $arSelectPromo);
if($rowPromo = $resPromo->GetNext()) {
    $result['status'] = 'success';
    $result['promo']['str'] = $rowPromo['PROPERTY_PROMOCODE_VALUE'];
    $result['promo']['percent'] = $rowPromo['PROPERTY_DISCOUNT_VALUE'];
    $result['promo']['limit'] = $rowPromo['PROPERTY_LIMIT_VALUE'];
    $result['promo']['title'] = 'применён, скидка ' . $rowPromo['PROPERTY_DISCOUNT_VALUE'] . '%';

    if($rowPromo['PROPERTY_LIMIT_VALUE']) {
        $result['promo']['title'] .= ' на ' . $rowPromo['PROPERTY_LIMIT_VALUE'] . ' показов';
    }
} else {
    $result['status'] = 'error';
}

echo json_encode($result);
?>