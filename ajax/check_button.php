<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$error = array();
$result = array();

$user_id = 0;

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
}

$input = filter_input_array(INPUT_POST);

$id          = (int) $input['id'];
$type        = $input['type'];
$userCurrent = (int) $input['user'];
$sumIn       = (int) $input['sum'];

if(isEdit() || $_SESSION['USER_DATA']['PRO_TYPE'] === 'U') {

    $rsAuthorData = CUser::GetByID($userCurrent);
    $authorData = $rsAuthorData->Fetch();

    if($type === 'del') {

        CIBlockElement::SetPropertyValueCode($id, "STATE", 'Отменён');
        CIBlockElement::SetPropertyValueCode($id, "CANCEL", 'Y');
        CIBlockElement::SetPropertyValueCode($id, "PENDING", 'N');
        CIBlockElement::SetPropertyValueCode($id, "PAID", 'N');

        $free = $authorData['WORK_FAX'] - $authorData['WORK_PAGER'];

        $result['status']  = 'success';
        $result['balance'] = round($authorData['WORK_FAX'], 2);
        $result['free']    = round($free, 2);
        $result['hold']    = round($authorData['WORK_PAGER'], 2);
        $result['date']    = date('d.m.Y');
        $result['sum']     = round($sumIn, 2);

        $createTime = time();

        $stmt = $dbh->prepare("INSERT INTO a_balance_history (user_id, tax, free, hold, balance, direction, disc, create_at, date_at) VALUES (:user_id, :tax, :free, :hold, :balance, 6, 'Счёт отменён', :create_at, NOW())");
        $stmt->bindParam(':user_id', $authorData['ID'], PDO::PARAM_INT);
        $stmt->bindParam(':tax', $sumIn);
        $stmt->bindParam(':free', $result['free']);
        $stmt->bindParam(':hold', $result['hold']);
        $stmt->bindParam(':balance', $result['balance']);
        $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
        $stmt->execute();

    } elseif($type === 'add') {

        CIBlockElement::SetPropertyValueCode($id, "STATE", 'Ожидает оплаты');
        CIBlockElement::SetPropertyValueCode($id, "PENDING", 'Y');
        CIBlockElement::SetPropertyValueCode($id, "PAID", 'N');
        CIBlockElement::SetPropertyValueCode($id, "CANCEL", 'N');

        $free = $authorData['WORK_FAX'] - $authorData['WORK_PAGER'];

        $result['status']  = 'success';
        $result['balance'] = round($authorData['WORK_FAX'], 2);
        $result['free']    = round($free, 2);
        $result['hold']    = round($authorData['WORK_PAGER'], 2);
        $result['date']    = date('d.m.Y');
        $result['sum']     = round($sumIn, 2);

        $createTime = time();

        $stmt = $dbh->prepare("INSERT INTO a_balance_history (user_id, tax, free, hold, balance, direction, disc, create_at, date_at) VALUES (:user_id, :tax, :free, :hold, :balance, 7, 'Вам выставлен счёт (ожидается оплата)', :create_at, NOW())");
        $stmt->bindParam(':user_id', $authorData['ID'], PDO::PARAM_INT);
        $stmt->bindParam(':tax', $sumIn);
        $stmt->bindParam(':free', $result['free']);
        $stmt->bindParam(':hold', $result['hold']);
        $stmt->bindParam(':balance', $result['balance']);
        $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
        $stmt->execute();

    } elseif($type === 'pay') {

        $user = new CUser;

        $many = $authorData['WORK_FAX'] + $sumIn;
        $roundMany = round($many, 2);
        $fields = array(
            "WORK_FAX" => $roundMany,
        );

        $user->Update($authorData['ID'], $fields);

        CIBlockElement::SetPropertyValueCode($id, "STATE", 'Оплачен');
        CIBlockElement::SetPropertyValueCode($id, "PAID", 'Y');
        CIBlockElement::SetPropertyValueCode($id, "PENDING", 'N');
        CIBlockElement::SetPropertyValueCode($id, "CANCEL", 'N');

        $free = $roundMany - $authorData['WORK_PAGER'];

        $result['status']  = 'success';
        $result['balance'] = $roundMany;
        $result['free']    = round($free, 2);
        $result['hold']    = round($authorData['WORK_PAGER'], 2);
        $result['date']    = date('d.m.Y');
        $result['sum']     = round($sumIn, 2);

        $createTime = time();

        $stmt = $dbh->prepare("INSERT INTO a_balance_history (user_id, tax, free, hold, balance, direction, disc, create_at, date_at) VALUES (:user_id, :tax, :free, :hold, :balance, 8, 'Оплата подтверждена', :create_at, NOW())");
        $stmt->bindParam(':user_id', $authorData['ID'], PDO::PARAM_INT);
        $stmt->bindParam(':tax', $sumIn);
        $stmt->bindParam(':free', $result['free']);
        $stmt->bindParam(':hold', $result['hold']);
        $stmt->bindParam(':balance', $result['balance']);
        $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
        $stmt->execute();

    } elseif($type === 'pdf') {

    }

    /* Кто и когда изменял запись */
    CIBlockElement::SetPropertyValueCode($id, "MODERATOR", $user_id);
    CIBlockElement::SetPropertyValueCode($id, "MODERATE_TIME", date('d.m.Y H:i:s'));

    /* Сбор данных для отрисовки счётчиков */
    $arFilterCntNew = Array("IBLOCK_ID" => 47, "ACTIVE" => "Y", "!PROPERTY_PENDING" => "Y", "!PROPERTY_PAID" => "Y", "!PROPERTY_CANCEL" => "Y");
    $resCntNew = CIBlockElement::GetList(array(), $arFilterCntNew, Array(), false, Array());
    $result['NEW'] = $resCntNew ? $resCntNew : 0;

    $arFilterCntPending = Array("IBLOCK_ID" => 47, "ACTIVE" => "Y", "PROPERTY_PENDING" => "Y");
    $resCntPending = CIBlockElement::GetList(array(), $arFilterCntPending, Array(), false, Array());
    $result['PENDING'] = $resCntPending ? $resCntPending : 0;

    $arFilterCntPaid = Array("IBLOCK_ID" => 47, "ACTIVE" => "Y", "PROPERTY_PAID" => "Y");
    $resCntPaid = CIBlockElement::GetList(array(), $arFilterCntPaid, Array(), false, Array());
    $result['PAID'] = $resCntPaid ? $resCntPaid : 0;

    $arFilterCntCancel = Array("IBLOCK_ID" => 47, "ACTIVE" => "Y", "PROPERTY_CANCEL" => "Y");
    $resCntCancel = CIBlockElement::GetList(array(), $arFilterCntCancel, Array(), false, Array());
    $result['CANCEL'] = $resCntCancel ? $resCntCancel : 0;

    $arFilterCntAll = Array("IBLOCK_ID" => 47, "ACTIVE" => "Y");
    $resCntAll = CIBlockElement::GetList(array(), $arFilterCntAll, Array(), false, Array());
    $result['ALL'] = $resCntAll ? $resCntAll : 0;

}

$data = $result ? array("status" => "success", "res" => $result ) : array("status" => "error", 'message' => 'Ошибка обработки запроса.');
die(json_encode($data));
?>