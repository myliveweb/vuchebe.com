<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$input['id'] = (int) $input['id'];
$book        = (int) $input['book'];

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

$bookId = array();
$arrBaookmark = $dbh->query('SELECT uz_id from a_bookmark WHERE type = 5 AND user_id = ' . $user_id . ' ORDER BY date_create DESC')->fetchAll();
foreach($arrBaookmark as $itemBook)
    $bookId[] = $itemBook['uz_id'];

if(mb_stripos($input['str_user'],'vuchebe.com/user/') !== false || is_numeric($input['str_user'])) {
    $digit = (int) preg_replace('/[^0-9]/', '', $input['str_user']);
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

  if(in_array($arUser['ID'], $input['load']) || $arUser['ACTIVE'] != 'Y')
    continue;

  if(!isEdit()) {
      /*-------- Отсекаем PRO аккаунты и техподдержку ---------*/
      $arGroups = CUser::GetUserGroup($arUser['ID']);
      if (in_array(6, $arGroups) || in_array(7, $arGroups) || isSupport($arUser['ID']))
          continue;
  }

  if(!in_array($arUser['ID'], $bookId) && $book)
      continue;

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

  $tempOut['BOOK'] = 0;
  if(in_array($arUser['ID'], $bookId))
      $tempOut['BOOK'] = 1;

  $tempOut['sort'] = false;

  $tempOut['sort'] = mb_stripos($arUser['NAME_DISPLAY'], $input['str_user']);

  $result[] = $tempOut;
}

usort($result, "cmp");

$data = array("status" => "success", 'user' => $result);

die(json_encode($data));
?>