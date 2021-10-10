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

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$isAdmin = 0;

if(isEdit())
  $isAdmin = 1;

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] < $b['sort']) ? -1 : 1;
}

$domainName = 0;

if(mb_stripos($input['str_user'],'vuchebe.com/user/') !== false) {

    preg_match('#vuchebe.com/user/([^/]*)/?#', $input['str_user'], $matches);

    if(is_numeric($matches[1])) {
        $digit = (int) $matches[1];
        $dataUser = CUser::GetByID($digit);
    } elseif(trim($matches[1]) != '') {

        $domainName =1;

        $arFilter= array(
            "WORK_PHONE" => $matches[1]
        );
        $dataUser = CUser::GetList($by="ID", $order="ASC", $arFilter);
    } else {
        $arFilter= array(
            "WORK_PHONE" => "###"
        );
        $dataUser = CUser::GetList($by="ID", $order="ASC", $arFilter);
    }

} elseif(is_numeric($input['str_user'])) {

    $digit = (int) $input['str_user'];
    $dataUser = CUser::GetByID($digit);

} else {

    $GLOBALS["FILTER_logic"] = "or";
    $arFilter= array(
        "NAME" => $input['str_user'] . "%",
        "WORK_COMPANY" => $input['str_user'] . "%"
    );
    $dataUser = CUser::GetList($by="ID", $order="ASC", $arFilter);

}
while($arUser = $dataUser->Fetch()) {

    if($arUser['ACTIVE'] != 'Y')
    continue;

    if($domainName) {
        if($arUser['WORK_PHONE'] != $matches[1])
            continue;
    }

    /*-------- Отсекаем техподдержку ---------*/

    //if(isEdit())
    //    continue;

    /*-------- Отсекаем PRO аккаунты ---------*/

    if(!isEdit()) {
      $arGroups = CUser::GetUserGroup($arUser['ID']);
      if(in_array(6, $arGroups) || in_array(7, $arGroups))
          continue;
    }

  $tempOut = array();

  if($arUser['PERSONAL_PHOTO']) {
      $arUser['AVATAR'] = CFile::GetPath($arUser['PERSONAL_PHOTO']);
  } else {
      $arUser['AVATAR'] = SITE_TEMPLATE_PATH . "/images/user-1.png";
  }

  if (strlen(trim($arUser['NAME'])) && strlen(trim($arUser['LAST_NAME']))) {
      $format_name = '<span>' . strtoupper(mb_substr(trim($arUser['NAME']), 0, 1)) . '</span>' . mb_substr(trim($arUser['NAME']), 1);
      if($arUser['SECOND_NAME']) {
          $format_name .= ' ';
          $format_name .= '<span>' . strtoupper(mb_substr($arUser['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($arUser['SECOND_NAME'], 1);
      }
      $format_name .= ' ';
      $format_name .= '<span>' . strtoupper(mb_substr(trim($arUser['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($arUser['LAST_NAME']), 1);
  } else {
      $format_name = '<span>' . strtoupper(mb_substr(trim($arUser['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($arUser['LOGIN']), 1);
  }

  $arUser['FULL_NAME'] = $format_name;

  $nameDisplay = trim($arUser['NAME']);
  if(trim($arUser['SECOND_NAME']))
    $nameDisplay .= ' ' . trim($arUser['SECOND_NAME']);
  if(trim($arUser['LAST_NAME']))
    $nameDisplay .= ' ' . trim($arUser['LAST_NAME']);

  if (strlen($nameDisplay) <= 0)
    $nameDisplay = $USER->GetLogin();

  $arUser['NAME_DISPLAY'] = $nameDisplay;

  $arUser['ONLINE'] = 0;
  if(CUser::IsOnLine($arUser['ID'], 30) && $arUser['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) {
      $arUser['ONLINE'] = 1;
  }

  $tempOut = $arUser;
  $tempOut['TYPE'] = 'user';

  $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $arUser['ID'])->fetch();
  if($arrTeacher['cnt'] > 0) {
      $tempOut['TYPE'] = 'teacher';
  }

  $tempOut['sort'] = false;

  $tempOut['sort'] = mb_stripos($arUser['NAME_DISPLAY'], $input['str_user']);

  $result[] = $tempOut;
}

usort($result, "cmp");

$data = array("status" => "success", 'user' => $result);

die(json_encode($data));
?>