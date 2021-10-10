<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);
$id = (int) $input['id'];

CModule::IncludeModule('iblock');

if(isEdit()) {

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_OWNER");
    $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "ID" => $id);
    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
    if($row = $res->GetNext()) {

        $userObj = CUser::GetByID($row['PROPERTY_OWNER_VALUE']);
        $userChat = $userObj->Fetch();

        $row['USER_ID'] = $userChat['ID'];

        $full_name = trim($userChat['NAME']);
        if(trim($userChat['SECOND_NAME'])) {
            $full_name .= ' ' . trim($userChat['SECOND_NAME']);
        }
        $full_name .= ' ' . trim($userChat['LAST_NAME']);

        if(strlen($full_name) <= 0)
            $full_name = trim($userChat['LOGIN']);

        $row['FULL_NAME'] = $full_name;

        if($userChat['PERSONAL_PHOTO']) {
            $avatar_url = CFile::GetPath($userChat['PERSONAL_PHOTO']);
        } else {
            $avatar_url = SITE_TEMPLATE_PATH . "/img/foto-user.png";
        }

        $row['AVATAR'] = $avatar_url;

        $row['URL'] = getUserUrl($userChat);

        $row['BALANCE'] = $userChat['WORK_FAX'];

        $row['HOLD'] = '';
        if($userChat['WORK_PAGER'])
            $row['HOLD'] = ' (к возврату ' . $userChat['WORK_PAGER'] . ' руб.)';

        list($dateStr, $timeStr) = explode(' ', $userChat['DATE_REGISTER']);
        $row['REGISTER'] = $dateStr;

        $arFilterCnt = Array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "PROPERTY_OWNER" => $userChat['ID']);
        $resCnt = CIBlockElement::GetList(array(), $arFilterCnt, Array(), false, Array());
        $row['COUNT'] = $resCnt;

        $arFilterCntAct = Array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "PROPERTY_OWNER" => $userChat['ID'], "!PROPERTY_DELETE" => "Y");
        $resCntAct = CIBlockElement::GetList(array(), $arFilterCntAct, Array(), false, Array());
        $row['ACT'] = $resCntAct;

        $result['res'] = $row;
    }
    $result['status'] = 'success';
} else {
    $result['status'] = 'error';
    $result['message'] = 'Требуется авторизация';
}

die(json_encode($result));
?>