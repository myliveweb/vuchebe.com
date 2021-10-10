<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);
$cnt = (int) $input['cnt'];
$type = $input['type'];

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

if($user_id) {

  $arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE");

  $arFilter = array("IBLOCK_ID" => 50);

  if($type == 'new') {
      $arFilter['!PROPERTY_PENDING'] = 'Y';
      $arFilter['!PROPERTY_ADD']     = 'Y';
      $arFilter['!PROPERTY_DEL']     = 'Y';
  } elseif($type == 'pending') {
      $arFilter['PROPERTY_PENDING']  = 'Y';
  } elseif($type == 'add') {
      $arFilter['PROPERTY_ADD']   = 'Y';
  } elseif($type == 'del') {
      $arFilter['PROPERTY_DEL']      = 'Y';
  }

  if($input['load'])
      $arFilter["!ID"] = $input['load'];

  $n = 0;

  $res = CIBlockElement::GetList(array("ID" => 'DESC'), $arFilter, false, false, $arSelect);
  while($obRes = $res->GetNextElement()) {

    if($n >= $cnt)
      continue;
    else
      $n++;

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['COUNTRY'] = $props['COUNTRY']['VALUE'];
    $row['REGION']  = $props['REGION']['VALUE'];
    $row['CITY']    = $props['CITY']['VALUE'];

    $row['COUNTRY_ID'] = $props['COUNTRY_ID']['VALUE'];
    $row['REGION_ID']  = $props['REGION_ID']['VALUE'];
    $row['CITY_ID']    = $props['CITY_ID']['VALUE'];

    $row['ADRESS'] = $props['ADRESS']['VALUE'];
    $row['PHONE']  = $props['PHONE']['VALUE'];
    $row['EMAIL']  = $props['EMAIL']['VALUE'];
    $row['SITE']   = $props['SITE']['VALUE'];

    $row['PENDING'] = $props['PENDING']['VALUE'];
    $row['ADD']     = $props['ADD']['VALUE'];
    $row['DEL']     = $props['DEL']['VALUE'];

    $row['TYPE']   = $props['TYPE']['VALUE'];
    $row['TICKET'] = $props['TICKET']['VALUE'];
    $row['AUTHOR'] = $props['AUTHOR']['VALUE'];

    $row['UZ_ID']  = $props['UZ_ID']['VALUE'];

    if($row['TICKET']) {
      $arrChat = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $row['TICKET'] . ' ORDER BY id DESC')->fetch();
      if($arrChat['del_owner']) {
          $row['TICKET_COLOR'] = 'red';
      } else {
          $row['TICKET_COLOR'] = 'green';
      }
    }

    list($dateFormat, $timeFormat) = explode(' ', $row["DATE_CREATE"]);
    list($hoursFormat, $minFormat) = explode(':', $timeFormat);

    $row["DATE_FORMAT"] = $dateFormat . ' (' . $hoursFormat . '.' . $minFormat .  ')';

    $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $row['AUTHOR'])->fetch();
    if($arrTeacher['cnt'] > 0) {
      $row['TEACHER'] = 1;
    } else {
      $row['TEACHER'] = 0;
    }

      if($row['AUTHOR']) {
          $rsAuthorData = CUser::GetByID($row['AUTHOR']);
          $authorData = $rsAuthorData->Fetch();

          $row["URL"] = getUserUrl($authorData);

          if (strlen(trim($authorData['NAME']))) {
              $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['NAME']), 1);
              if ($authorData['SECOND_NAME']) {
                  $format_name .= ' ';
                  $format_name .= '<span>' . strtoupper(mb_substr($authorData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($authorData['SECOND_NAME'], 1);
              }
              if ($authorData['LAST_NAME']) {
                  $format_name .= ' ';
                  $format_name .= '<span>' . strtoupper(mb_substr(trim($authorData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LAST_NAME']), 1);
              }
          } else {
              $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LOGIN']), 1);
          }

          $row['FORMAT_NAME'] = $format_name;

          $row["PHOTO"] = $authorData['PERSONAL_PHOTO'];

          if ($authorData['PERSONAL_PHOTO']) {
              $row["PIC"] = CFile::GetPath($authorData['PERSONAL_PHOTO']);
          } else {
              $row["PIC"] = SITE_TEMPLATE_PATH . "/images/user-1.png";
          }
      }

      $row["TICKET_CNT"] = 0;
      $arFilterTicket = Array("IBLOCK_ID"=>50, "!PROPERTY_TICKET" => false);
      $cntTicket = CIBlockElement::GetList(false, $arFilterTicket, array('IBLOCK_ID'))->Fetch()['CNT'];
      if($cntTicket) {
          $row["TICKET_CNT"] = $cntTicket;
      }

    $result['res'][] = $row;
  }
  $result['status'] = 'success';
} else {
  $result['status'] = 'error';
  $result['message'] = 'Требуется авторизация';
}

die(json_encode($result));
?>