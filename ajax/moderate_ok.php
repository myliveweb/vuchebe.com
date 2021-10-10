<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$user_id = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
    $user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$input = filter_input_array(INPUT_POST);

$id = (int) $input['id'];

if($id) {
    CIBlockElement::SetPropertyValueCode($id, "REASON", '');
    CIBlockElement::SetPropertyValueCode($id, "REJECTED", 'N');
    CIBlockElement::SetPropertyValueCode($id, "LAUNCHED", 'N');
    CIBlockElement::SetPropertyValueCode($id, "MODERATION", 'Y');

    $arFilterCntNew = Array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "!PROPERTY_DELETE" => "Y", "!PROPERTY_MODERATION" => "Y", "!PROPERTY_REJECTED" => "Y");
    $resCntNew = CIBlockElement::GetList(array(), $arFilterCntNew, Array(), false, Array());
    $result['NEW'] = $resCntNew;

    $arFilterCntRej = Array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "!PROPERTY_DELETE" => "Y", "PROPERTY_REJECTED" => "Y");
    $resCntRej = CIBlockElement::GetList(array(), $arFilterCntRej, Array(), false, Array());
    $result['REJ'] = $resCntRej;

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_COUNTER", "PROPERTY_LIMIT");
    $arFilterCntStop = Array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "!PROPERTY_DELETE" => "Y", "PROPERTY_MODERATION" => "Y", "!PROPERTY_REJECTED" => "Y", "!PROPERTY_LAUNCHED" => "Y");
    $resCntStop = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterCntStop, false, false, $arSelect);
    while($rowStop = $resCntStop->Fetch()) {
        if ($rowStop['PROPERTY_COUNTER_VALUE'] < $rowStop['PROPERTY_LIMIT_VALUE']) {
            $result['STOP']++;
        }
    }
}

$data = $result ? array("status" => "success", "res" => $result ) : array("status" => "error", 'message' => 'Ошибка добавления баннера.');
die(json_encode($data));
?>