<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);
$cnt   = (int) $input['cnt'];
$type  = $input['type'];
$sort  = $input['sort'];
$pro   = $input['pro'];

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

if($user_id) {

  $arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE");

  if($pro == 'moderate') {
      $arFilter = array("IBLOCK_ID" => 47);
  } else {
      $arFilter = array("IBLOCK_ID" => 47, "PROPERTY_USER" => $pro);
  }

  if($type == 'new') {
      $arFilter['!PROPERTY_PENDING'] = 'Y';
      $arFilter['!PROPERTY_PAID']  = 'Y';
      $arFilter['!PROPERTY_CANCEL']     = 'Y';
  } elseif($type == 'pending') {
      $arFilter['PROPERTY_PENDING']  = 'Y';
  } elseif($type == 'pay') {
      $arFilter['PROPERTY_PAID']   = 'Y';
  } elseif($type == 'del') {
      $arFilter['PROPERTY_CANCEL']      = 'Y';
  }

  if($input['load'])
    $arFilter["!ID"] = $input['load'];

  $n = 0;

  $res = CIBlockElement::GetList(array("ID" => $sort), $arFilter, false, false, $arSelect);
  while($obRes = $res->GetNextElement()) {

    if($n >= $cnt)
      continue;
    else
      $n++;

      $row = $obRes->GetFields();
      $props = $obRes->GetProperties();

      $row['PRO']     = $pro;

      $row['SUM']     = $props['SUM']['VALUE'];
      $row['STATE']   = $props['STATE']['VALUE'];
      $row['ORG']     = $props['ORG']['VALUE'];

      $row['TICKET']  = $props['TICKET']['VALUE'];

      $row['OGRN']    = $props['OGRN']['VALUE'];
      $row['INN']     = $props['INN']['VALUE'];
      $row['KPP']     = $props['KPP']['VALUE'];

      $row['ADRESS']  = $props['ADRESS']['VALUE'];
      $row['PHONE']   = $props['PHONE']['VALUE'];
      $row['EMAIL']   = $props['EMAIL']['VALUE'];

      $row['USER']    = $props['USER']['VALUE'];

      $row['PENDING'] = $props['PENDING']['VALUE'];
      $row['PAID']    = $props['PAID']['VALUE'];
      $row['CANCEL']  = $props['CANCEL']['VALUE'];

      if($row['TICKET']) {
          $arrChat = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $row['TICKET'] . ' ORDER BY id DESC')->fetch();
          if($arrChat['del_owner']) {
              $row['TICKET_COLOR'] = 'red';
          } else {
              $row['TICKET_COLOR'] = 'green';
          }
      }

      list($dateFormat, $timeFormat) = explode(' ', $row["DATE_CREATE"]);

      $row["DATE_FORMAT"] = $dateFormat;

      $rsAuthorData = CUser::GetByID($row['USER']);
      $authorData = $rsAuthorData->Fetch();

      $row["URL"] = getUserUrl($authorData);

      if($authorData['PERSONAL_PHOTO']) {
          $row["PIC"] = CFile::GetPath($authorData['PERSONAL_PHOTO']);
      } else {
          $row["PIC"] = SITE_TEMPLATE_PATH . "/images/user-1.png";
      }

      $arGroups = CUser::GetUserGroup($authorData['ID']);
      if(in_array(6, $arGroups)) {
          $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['WORK_COMPANY']), 0, 1)) . '</span>' . mb_substr(trim($authorData['WORK_COMPANY']), 1);
      } elseif(in_array(7, $arGroups)) {
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
      }

      $row['FORMAT_NAME'] = $format_name;

      if($row['STATE'] == 'Новый' || $row['STATE'] == 'Ожидает оплаты') {
          $row['STATE_COLOR'] = '#9f9f9f';
      } elseif($row['STATE'] == 'Оплачен') {
          $row['STATE_COLOR'] = 'green';
      } elseif($row['STATE'] == 'Отменён') {
          $row['STATE_COLOR'] = 'red';
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