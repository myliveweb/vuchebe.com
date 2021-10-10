<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$input['id'] = (int) $input['id'];

CModule::IncludeModule('iblock');

if(isEdit()) {

    $rsUser = CUser::GetByID($input['id']);
    $dataUser = $rsUser->Fetch();

    $balance    = $dataUser['WORK_FAX'];
    $hold       = $dataUser['WORK_PAGER'];
    $newBalance = $dataUser['WORK_FAX'] - $dataUser['WORK_PAGER'];

    $user = new CUser;

    $fields = array(
        "WORK_FAX"   => $newBalance,
        "WORK_PAGER" => 0
    );

    if ($user->Update($input['id'], $fields)) {

        $result['status'] = 'success';
        $result['balance'] = round($newBalance, 2);
        $result['free'] = round($newBalance, 2);
        $result['hold'] = 0;
        $result['date'] = date('d.m.Y');
        $result['sum'] = round($hold, 2);

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_OWNER");
        $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "PROPERTY_OWNER" => $input['id']);
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        while($row = $res->GetNext()) {

            CIBlockElement::SetPropertyValueCode($row["ID"], "BALANCE", $result['free']);
        }

        $createTime = time();

        $stmt = $dbh->prepare("INSERT INTO a_balance_history (user_id, tax, free, hold, balance, direction, disc, create_at, date_at) VALUES (:user_id, :tax, :free, :hold, :balance, 4, 'Произведен возврат денежных средств', :create_at, NOW())");
        $stmt->bindParam(':user_id', $input['id'], PDO::PARAM_INT);
        $stmt->bindParam(':tax', $result['sum']);
        $stmt->bindParam(':free', $result['free']);
        $stmt->bindParam(':hold', $result['hold']);
        $stmt->bindParam(':balance', $result['balance']);
        $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
        $stmt->execute();

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_USER");
        $arFilter = array("IBLOCK_ID" => 42, "ACTIVE" => "Y", "!PROPERTY_PAY" => "Y", "!PROPERTY_CANCEL" => "Y", "PROPERTY_USER" => $input['id']);
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        while ($row = $res->GetNext()) {
            CIBlockElement::SetPropertyValueCode($row['ID'], "PAY", "Y");
        }

    } else {
        $result['status'] = 'error';
        $result['message'] = html_entity_decode("Error: " . $user->LAST_ERROR);
    }
}
echo json_encode($result);
?>