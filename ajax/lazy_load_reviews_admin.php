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

  $arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "DETAIL_TEXT");

  $arFilter = array("IBLOCK_ID" => 23);

  if($type == 'new') {
      $arFilter['!PROPERTY_WARNING'] = 'Y';
      $arFilter['!PROPERTY_REJECT']  = 'Y';
      $arFilter['!PROPERTY_DEL']     = 'Y';
  } elseif($type == 'warning') {
      $arFilter['PROPERTY_WARNING']  = 'Y';
  } elseif($type == 'otklon') {
      $arFilter['PROPERTY_REJECT']   = 'Y';
  } elseif($type == 'del') {
      $arFilter['PROPERTY_DEL']      = 'Y';
  }

  if($input['load'])
    $arFilter["!ID"] = $input['load'];

  $n = 0;

  $res = CIBlockElement::GetList(array("ID" => 'ASC'), $arFilter, false, false, $arSelect);
  while($obRes = $res->GetNextElement()) {

    if($n >= $cnt)
      continue;
    else
      $n++;

      $row = $obRes->GetFields();
      $props = $obRes->GetProperties();

      $row['WARNING'] = $props['WARNING']['VALUE'];
      $row['REJECT']  = $props['REJECT']['VALUE'];
      $row['DEL']     = $props['DEL']['VALUE'];

      $row['TICKET']  = $props['TICKET']['VALUE'];

      $row['AUTHOR']  = $props['AUTHOR']['VALUE'];
      $row['ABUSE']   = $props['ABUSE']['VALUE'];

      $row['POST']   = $props['POST_ID']['VALUE'];
      $row['URL_POST']   = $props['URL']['VALUE'];

      $uz = $props['VUZ_ID']['VALUE'];

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

    $rsAuthorData = CUser::GetByID($row['AUTHOR']);
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

    /* Инфо о том кто подал жалобу */

    $rsAbuseData = CUser::GetByID($row['ABUSE']);
    $abuseData = $rsAbuseData->Fetch();

    if($abuseData['PERSONAL_PHOTO']) {
      $row["PIC_ABUSE"] = CFile::GetPath($abuseData['PERSONAL_PHOTO']);
    } else {
      $row["PIC_ABUSE"] = SITE_TEMPLATE_PATH . "/images/user-1.png";
    }

    $row["URL_ABUSE"] = getUserUrl($abuseData);

    $arrTeacherAbuse = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $row['ABUSE'])->fetch();
    if($arrTeacherAbuse['cnt'] > 0) {
      $row['TEACHER_ABUSE'] = 1;
    } else {
      $row['TEACHER_ABUSE'] = 0;
    }

      if (strlen(trim($abuseData['NAME']))) {
          $format_name_abuse = '<span>' . strtoupper(mb_substr(trim($abuseData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($abuseData['NAME']), 1);
          $title_abuse = $abuseData['NAME'];
          if($abuseData['SECOND_NAME']) {
              $format_name_abuse .= ' ';
              $format_name_abuse .= '<span>' . strtoupper(mb_substr($abuseData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($abuseData['SECOND_NAME'], 1);
              $title_abuse .= ' ';
              $title_abuse .= $abuseData['SECOND_NAME'];
          }
          if($abuseData['LAST_NAME']) {
              $format_name_abuse .= ' ';
              $format_name_abuse .= '<span>' . strtoupper(mb_substr(trim($abuseData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($abuseData['LAST_NAME']), 1);
              $title_abuse .= ' ';
              $title_abuse .= $abuseData['LAST_NAME'];
          }
      } else {
          $format_name_abuse = '<span>' . strtoupper(mb_substr(trim($abuseData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($abuseData['LOGIN']), 1);
      }

      $row['FORMAT_NAME_ABUSE'] = $format_name_abuse;
      $row['TITLE_ABUSE'] = $title_abuse;

      $arSelectUz = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL");
      $arFilterUz = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "ID" => $uz);
      $resUz = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterUz, false, false, $arSelectUz);
      if($obResUz = $resUz->GetNextElement()) {

          $rowUz = $obResUz->GetFields();
          $propsUz = $obResUz->GetProperties();

          $rowUz['LOGO']   = $propsUz['LOGO']['VALUE'];

      }

      if($rowUz['LOGO']) {
          $rowUz["PIC"] = CFile::GetPath($rowUz['LOGO']);
      } elseif($row["PREVIEW_PICTURE"]) {
          $rowUz["PIC"] = CFile::GetPath($rowUz["PREVIEW_PICTURE"]);
      } else {
          $rowUz["PIC"] = '/local/templates/vuchebe/images/noimage-2.png';
      }

      $row['UZ'] = $rowUz;

      $arSelectPost = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE");
      $arFilterPost = array("IBLOCK_ID" => 21, "ACTIVE" => "Y", "ID" => $row['POST']);
      $resPost = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterPost, false, false, $arSelectPost);
      if($obResPost = $resPost->GetNextElement()) {

          $rowPost = $obResPost->GetFields();

          list($dateFormatPost, $timeFormatPost) = explode(' ', $rowPost["DATE_CREATE"]);
          list($hoursFormatPost, $minFormatPost) = explode(':', $timeFormatPost);

          $row["DATE_FORMAT_POST"] = $dateFormatPost . ' (' . $hoursFormatPost . '.' . $minFormatPost .  ')';

      }

    $row["TOTAL"] = 0;
    $arFilter = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"]);
    $cntTotal = CIBlockElement::GetList(false, $arFilter, array('IBLOCK_ID'))->Fetch()['CNT'];
    if($cntTotal) {
      $row["TOTAL"] = $cntTotal;
    }

    $row["WARNING_CNT"] = 0;
    $arFilterWarning = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"], "PROPERTY_WARNING" => "Y");
    $cntWarning = CIBlockElement::GetList(false, $arFilterWarning, array('IBLOCK_ID'))->Fetch()['CNT'];
    if($cntWarning) {
      $row["WARNING_CNT"] = $cntWarning;
    }

    $row["REJECT_CNT"] = 0;
    $arFilterReject = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"], "PROPERTY_REJECT" => "Y");
    $cntReject = CIBlockElement::GetList(false, $arFilterReject, array('IBLOCK_ID'))->Fetch()['CNT'];
    if($cntReject) {
      $row["REJECT_CNT"] = $cntReject;
    }

    $row["DEL_CNT"] = 0;
    $arFilterDel = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"], "PROPERTY_DEL" => "Y");
    $cntDel = CIBlockElement::GetList(false, $arFilterDel, array('IBLOCK_ID'))->Fetch()['CNT'];
    if($cntDel) {
      $row["DEL_CNT"] = $cntDel;
    }

    $row["TICKET_CNT"] = 0;
    $arFilterTicket = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"], "!PROPERTY_TICKET" => false);
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