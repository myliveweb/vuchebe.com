<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$input['sum'] = (int) $input['sum'];

$sumIn = round($input['sum'], 2);

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA'])
    $user_id = $_SESSION['USER_DATA']['ID'];

$name = trim($_SESSION['USER_DATA']['WORK_COMPANY']);

if($input['type'] === 'add' && $user_id) {

    $el = new CIBlockElement;

    $PROP = array();
    $PROP['SUM']     = $sumIn;
    $PROP['STATE']   = 'Новый';
    $PROP['ORG']     = $name;
    $PROP['OGRN']    = $_SESSION['USER_DATA']['UF_OGRN'];
    $PROP['INN']     = $_SESSION['USER_DATA']['UF_INN'];
    $PROP['KPP']     = $_SESSION['USER_DATA']['UF_KPP'];
    $PROP['ADRESS']  = $_SESSION['USER_DATA']['WORK_STREET'];
    $PROP['PHONE']   = $_SESSION['USER_DATA']['PERSONAL_PHONE'];
    $PROP['EMAIL']   = $_SESSION['USER_DATA']['EMAIL'];
    $PROP['USER']    = $user_id;
    $PROP['PENDING'] = 'N';
    $PROP['PAID']    = 'N';
    $PROP['CANCEL']  = 'N';

    $arLoadProductArray = Array(
        "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
        "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
        "IBLOCK_ID"         => 47,
        "PROPERTY_VALUES"   => $PROP,
        "NAME"              => 'Счёт на сумму ' . $sumIn . 'руб. (' . $name . ')',
        "ACTIVE"            => "Y"             // активен
    );

    if ($PRODUCT_ID = $el->Add($arLoadProductArray)){

        CIBlockElement::SetPropertyValueCode($PRODUCT_ID, "IDSTR", $PRODUCT_ID);

        $result['checkId'] = $PRODUCT_ID;

        $free = $_SESSION['USER_DATA']['WORK_FAX'] - $_SESSION['USER_DATA']['WORK_PAGER'];

        $result['status']  = 'success';
        $result['balance'] = round($_SESSION['USER_DATA']['WORK_FAX'], 2);
        $result['free']    = round($free, 2);
        $result['hold']    = round($_SESSION['USER_DATA']['WORK_PAGER'], 2);
        $result['date']    = date('d.m.Y');
        $result['sum']     = round($sumIn, 2);

        $createTime = time();

        $stmt = $dbh->prepare("INSERT INTO a_balance_history (user_id, tax, free, hold, balance, direction, disc, create_at, date_at) VALUES (:user_id, :tax, :free, :hold, :balance, 5, 'Заявка на выставление счёта', :create_at, NOW())");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':tax', $sumIn);
        $stmt->bindParam(':free', $result['free']);
        $stmt->bindParam(':hold', $result['hold']);
        $stmt->bindParam(':balance', $result['balance']);
        $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
        $stmt->execute();

        $result['status'] = 'success';
        $result['message'] = 'Вы заказали счёт на сумму ' . number_format((float) $sumIn, 2, '.', '') . ' руб.';
    } else {
        $result['status'] = 'error';
        $result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
    }
}

echo json_encode($result);
?>