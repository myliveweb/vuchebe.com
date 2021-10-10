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



if($type && $userCurrent && isEdit()) {

    $arSelect = array("ID", "IBLOCK_ID", "DATE_CREATE", "DETAIL_PICTURE");

    $arFilter = array("IBLOCK_ID" => 27, "ACTIVE" => "Y", "PROPERTY_AUTHOR" => $userCurrent);

    if($type == 'warning') {
        $arFilter['PROPERTY_WARNING']  = 'Y';
    } elseif($type == 'reject') {
        $arFilter['PROPERTY_REJECT']   = 'Y';
    } elseif($type == 'del') {
        $arFilter['PROPERTY_DEL']      = 'Y';
    } elseif($type == 'ticket') {
        $arFilter['!PROPERTY_TICKET']  = false;
    }

    $res = CIBlockElement::GetList(array("ID" => 'DESC'), $arFilter, false, false, $arSelect);
    while($obRes = $res->GetNextElement()) {

        $row = $obRes->GetFields();
        $props = $obRes->GetProperties();

        if($type == 'abuse') {
           $curDate = $row['DATE_CREATE'];
           $idUser  = $props['ABUSE']['VALUE'];
        } else {
            $curDate = $props['MODERATE_TIME']['VALUE'];
            $idUser  = $props['MODERATOR']['VALUE'];
        }

        list($dateFormat, $timeFormat) = explode(' ', $curDate);
        list($hoursFormat, $minFormat) = explode(':', $timeFormat);

        $row["DATE_FORMAT"] = $dateFormat . ' (' . $hoursFormat . '.' . $minFormat .  ')';

        $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $idUser)->fetch();
        if($arrTeacher['cnt'] > 0) {
            $row['TEACHER'] = 1;
        } else {
            $row['TEACHER'] = 0;
        }

        if($type == 'ticket') {
            $row['TICKET'] = $props['TICKET']['VALUE'];

            $arrChat = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $row['TICKET'] . ' ORDER BY id DESC')->fetch();
            if($arrChat['del_owner']) {
                $row['TICKET_COLOR'] = 'red';
            } else {
                $row['TICKET_COLOR'] = 'green';
            }
        }

        $rsAuthorData = CUser::GetByID($idUser);
        $authorData = $rsAuthorData->Fetch();

        $row["URL"] = getUserUrl($authorData);

        if($authorData['PERSONAL_PHOTO']) {
            $row["PIC"] = CFile::GetPath($authorData['PERSONAL_PHOTO']);
        } else {
            $row["PIC"] = SITE_TEMPLATE_PATH . "/images/user-1.png";
        }

        if (strlen(trim($authorData['NAME']))) {
            $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['NAME']), 1);
            if($authorData['SECOND_NAME']) {
                $format_name .= ' ';
                $format_name .= '<span>' . strtoupper(mb_substr($authorData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($authorData['SECOND_NAME'], 1);
            }
            if($authorData['LAST_NAME']) {
                $format_name .= ' ';
                $format_name .= '<span>' . strtoupper(mb_substr(trim($authorData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LAST_NAME']), 1);
            }
        } else {
            $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LOGIN']), 1);
        }

        $row['FORMAT_NAME'] = $format_name;

        $result[] = $row;
    }
}

$data = $result ? array("status" => "success", "res" => $result ) : array("status" => "error", 'message' => 'Ошибка обработки запроса.');
die(json_encode($data));
?>