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

$user = new CUser;

if($input['type'] == 'add') {
    $many = $_SESSION['USER_DATA']['WORK_FAX'] + $input['sum'];
    $fields = array(
        "WORK_FAX" => round($many, 2),
    );
} elseif($input['type'] == 'hold') {
    $many = $_SESSION['USER_DATA']['WORK_PAGER'] + $input['sum'];
    $fields = array(
        "WORK_PAGER" => round($many, 2),
    );
} elseif($input['type'] == 'del') {
    $sumIn = round($_SESSION['USER_DATA']['WORK_PAGER'], 2);
    $fields = array(
        "WORK_PAGER" => '0',
    );
}


if ($user->Update($USER->GetId(), $fields)) {

    $rsUser = CUser::GetByID($USER->GetId());
    $_SESSION['USER_DATA'] = $rsUser->Fetch();

    $free = $_SESSION['USER_DATA']['WORK_FAX'] - $_SESSION['USER_DATA']['WORK_PAGER'];

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_OWNER");
    $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "PROPERTY_OWNER" => $_SESSION['USER_DATA']['ID']);
    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
    while($row = $res->GetNext()) {

        CIBlockElement::SetPropertyValueCode($row["ID"], "BALANCE", $free);
    }

    $result['status']  = 'success';
    $result['balance'] = round($_SESSION['USER_DATA']['WORK_FAX'], 2);
    $result['free']    = round($free, 2);
    $result['hold']    = round($_SESSION['USER_DATA']['WORK_PAGER'], 2);
    $result['date']    = date('d.m.Y');
    $result['sum']     = round($sumIn, 2);

    $createTime = time();

    if($input['type'] == 'add') {

        $stmt = $dbh->prepare("INSERT INTO a_balance_history (user_id, tax, free, hold, balance, direction, disc, create_at, date_at) VALUES (:user_id, :tax, :free, :hold, :balance, 1, 'Пополнение денежных средств', :create_at, NOW())");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':tax', $sumIn);
        $stmt->bindParam(':free', $result['free']);
        $stmt->bindParam(':hold', $result['hold']);
        $stmt->bindParam(':balance', $result['balance']);
        $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
        $stmt->execute();

    } elseif($input['type'] == 'hold') {

        $stmt = $dbh->prepare("INSERT INTO a_balance_history (user_id, tax, free, hold, balance, direction, disc, create_at, date_at) VALUES (:user_id, :tax, :free, :hold, :balance, 2, 'Запрос на возврат денежных средств', :create_at, NOW())");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':tax', $sumIn);
        $stmt->bindParam(':free', $result['free']);
        $stmt->bindParam(':hold', $result['hold']);
        $stmt->bindParam(':balance', $result['balance']);
        $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
        $stmt->execute();

        $el = new CIBlockElement;

        $PROP = array();

        $PROP['USER']    = $user_id;
        $PROP['SUM']     = $result['sum'];
        $PROP['BALANCE'] = $result['balance'];

        $PROP['PAY']    = 'N';
        $PROP['CANCEL'] = 'N';

        $name = 'Заявка на возврат ' . $result['sum'] . ' руб.';

        $arLoadProductArray = Array(
            "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
            "IBLOCK_ID"         => 42,
            "PROPERTY_VALUES"   => $PROP,
            "NAME"              => $name,
            "ACTIVE"            => "Y"
        );

        $ORDER_ID = $el->Add($arLoadProductArray);

    } elseif($input['type'] == 'del') {

        $stmt = $dbh->prepare("INSERT INTO a_balance_history (user_id, tax, free, hold, balance, direction, disc, create_at, date_at) VALUES (:user_id, :tax, :free, :hold, :balance, 3, 'Отмена возврата денежных средств', :create_at, NOW())");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':tax', $sumIn);
        $stmt->bindParam(':free', $result['free']);
        $stmt->bindParam(':hold', $result['hold']);
        $stmt->bindParam(':balance', $result['balance']);
        $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
        $stmt->execute();

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_USER");
        $arFilter = array("IBLOCK_ID" => 42, "ACTIVE" => "Y", "!PROPERTY_PAY" => "Y", "!PROPERTY_CANCEL" => "Y", "PROPERTY_USER" => $user_id);
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        while($row = $res->GetNext())
        {
            CIBlockElement::SetPropertyValueCode($row['ID'], "CANCEL", "Y");
        }
    }

} else {
    $result['status'] = 'error';
    $result['message'] = html_entity_decode("Error: " . $user->LAST_ERROR);
}

echo json_encode($result);
?>