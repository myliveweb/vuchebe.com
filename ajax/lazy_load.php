<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = 0;
$newsList = array();
$out = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$isAdmin = 0;
$arrAdmins = array();

$resAdmins = CIBlockElement::GetProperty(2, $input['id_vuz'], "sort", "asc", array("CODE" => "ADMINS"));
while ($obAdmins = $resAdmins->GetNext()) {
    $arrAdmins[] = $obAdmins['VALUE'];
}

if(in_array($user_id, $arrAdmins) || isEdit())
	$isAdmin = 1;

$arrType = array('universities' => 2, 'colleges' => 3, 'schools' => 4, 'language-class' => 6);
$iblick = $arrType[$input['cur']];

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] > $b['sort']) ? -1 : 1;
}

if($input['type'] == 'news') {

	$like_news = array();
	$like_news_sql = $dbh->query('SELECT * from a_like_news WHERE id_vuz = ' . $input['id_vuz'])->fetchAll();
	foreach($like_news_sql as $like_news_item) {
		$like_news[$like_news_item['id_news']][] = $like_news_item;
	}

	$deslike_news = array();
	$deslike_news_sql = $dbh->query('SELECT * from a_deslike_news WHERE id_vuz = ' . $input['id_vuz'])->fetchAll();
	foreach($deslike_news_sql as $deslike_news_item) {
		$deslike_news[$deslike_news_item['id_news']][] = $deslike_news_item;
	}

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_LIKE", "PROPERTY_DESLIKE");
	$arFilter = array("IBLOCK_ID" => 22, "ACTIVE" => "Y", "PROPERTY_VUZ_ID" => $input['id_vuz']);
	$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
	while($row = $res->Fetch())
	{

		$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

		$row["PICTURE"] = CFile::GetPath($row["PREVIEW_PICTURE"]);

		$row["TYPE"] = 'news';

		$arrStr = explode('</p>', $row["DETAIL_TEXT"]);
		if(sizeof($arrStr) > 1) {
			$outText = strip_tags($arrStr[0]);
			$row["FULL_TEXT"] = $outText;
		} else {
			$outText = mb_substr(strip_tags($row["DETAIL_TEXT"]), 0, 148);
			$row["FULL_TEXT"] = $outText . '..';
		}

		if($like_news[$row["ID"]])
			$row["LIKE"] = $like_news[$row["ID"]];

		if($deslike_news[$row["ID"]])
			$row["DESLIKE"] = $deslike_news[$row["ID"]];

		$row["ADMINS"] = $isAdmin;

		$row["sort"] = strtotime($row['DATE_CREATE']);

		$newsList[] = $row;
	}
}

if($input['type'] == 'events') {

	$placeholder = array('Название',
			            'Дата',
			            'Время',
			            'Адрес',
			            'Координаты Яндекс',
			            'Телефон',
			            'Контактное лицо',
			            'Ссылка на страницу',
			            'Комментарий',
			            'Текст',
			            'Облако тегов',
			            'Тег',
			            'Дата создания',
			            'Дополнительная строка',
			            'Уникальный ключ');

	$eventList = array();

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_ADD_EVENTS");
	$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "ID" => $input['id_vuz']);
	$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
	while($row = $res->Fetch())
	{
		$eventList[] = $row['PROPERTY_ADD_EVENTS_VALUE'];
	}

	$like_events = array();
	$like_events_sql = $dbh->query('SELECT key_event from a_like_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $input['id_vuz'])->fetchAll();
	foreach($like_events_sql as $like_events_item) {
		$like_events[] = $like_events_item['key_event'];
	}

	$deslike_events = array();
	$deslike_events_sql = $dbh->query('SELECT key_event from a_deslike_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $input['id_vuz'])->fetchAll();
	foreach($deslike_events_sql as $deslike_events_item) {
		$deslike_events[] = $deslike_events_item['key_event'];
	}

	$like_events_cnt = array();
	$like_events_cnt_sql = $dbh->query('SELECT * from a_like_events WHERE id_vuz = ' . $input['id_vuz'])->fetchAll();
	foreach($like_events_cnt_sql as $like_events_cnt_item) {
		$like_events_cnt[$like_events_cnt_item['key_event']][] = $like_events_cnt_item;
	}

	$deslike_events_cnt = array();
	$deslike_events_cnt_sql = $dbh->query('SELECT * from a_deslike_events WHERE id_vuz = ' . $input['id_vuz'])->fetchAll();
	foreach($deslike_events_cnt_sql as $deslike_events_cnt_item) {
		$deslike_events_cnt[$deslike_events_cnt_item['key_event']][] = $deslike_events_cnt_item;
	}

	$deslike_events_go = array();
	$deslike_events_go_sql = $dbh->query('SELECT key_event from a_events_go WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $input['id_vuz'])->fetchAll();
	foreach($deslike_events_go_sql as $deslike_events_go_item) {
		$deslike_events_go[] = $deslike_events_go_item['key_event'];
	}

	$deslike_events_go_cnt = array();
	$deslike_events_go_cnt_sql = $dbh->query('SELECT * from a_events_go WHERE id_vuz = ' . $input['id_vuz'])->fetchAll();
	foreach($deslike_events_go_cnt_sql as $deslike_events_go_cnt_item) {
		$deslike_events_go_cnt[$deslike_events_go_cnt_item['key_event']][] = $deslike_events_go_cnt_item;
	}

	foreach($eventList as $item) {
		$arrTemp = array();
		$arrItem = explode('#', $item);

		if(!$arrItem[0] || in_array($arrItem[14], $input['u_key']))
			continue;

		$idEvent = $arrItem[14];

		$arrTemp["TYPE"] = 'events';
		$arrTemp['ID'] = $idEvent;
		$arrTemp['ID_OPENDOOR'] = $arrItem[14];
		$arrTemp["NAME_OPENDOOR"]  = $arrItem[0];

		$arrTemp['DATA'] = $arrItem;

		$fullTime = $arrItem[1] . ' ' . $arrItem[2];

		$strDate = get_str_time_post(strtotime($fullTime));
		$arrTemp["FORMAT_DATE"] = $strDate;

		$curDate = explode(' ', $strDate);
		$arrTemp["DAY"] = $curDate[0];
		$arrTemp["MONTH"] = $curDate[1];

		$arrTemp['LIKE_USER'] = array();

		if(sizeof($like_events_cnt[$idEvent])) {
			foreach ($like_events_cnt[$idEvent] as $idLike => $eventsItem) {

				$tempLike = array();

				$rsUserData = CUser::GetByID($eventsItem["id_user"]);
				$userData = $rsUserData->Fetch();

				if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
					$format_name = $userData['NAME'];
					if($userData['SECOND_NAME']) {
						$format_name .= ' ';
						$format_name .= $userData['SECOND_NAME'];
					}
					$format_name .= ' ';
					$format_name .= $userData['LAST_NAME'];
				} else {
					$format_name = $userData['LOGIN'];
				}

				if($userData['PERSONAL_PHOTO']) {
					$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
				} else {
					$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
				}

				$arrTemp['LIKE_USER'][] = array('ID' => $userData['ID'], 'NAME' => $format_name, 'AVATAR' => $avatar_baloon);
			}
		}

		$arrTemp['DESLIKE_USER'] = array();

		if(sizeof($deslike_events_cnt[$idEvent])) {
			foreach ($deslike_events_cnt[$idEvent] as $idDeslike => $eventsItem) {

				$tempDeslike = array();

				$rsUserData = CUser::GetByID($eventsItem["id_user"]);
				$userData = $rsUserData->Fetch();

				if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
					$format_name = $userData['NAME'];
					if($userData['SECOND_NAME']) {
						$format_name .= ' ';
						$format_name .= $userData['SECOND_NAME'];
					}
					$format_name .= ' ';
					$format_name .= $userData['LAST_NAME'];
				} else {
					$format_name = $userData['LOGIN'];
				}

				if($userData['PERSONAL_PHOTO']) {
					$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
				} else {
					$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
				}

				$arrTemp['DESLIKE_USER'][] = array('ID' => $userData['ID'], 'NAME' => $format_name, 'AVATAR' => $avatar_baloon);
			}
		}

		$arrTemp["ADMINS"] = $isAdmin;

		if(in_array($idEvent, $like_events))
			$arrTemp['LIKE_ON'] = 1;
		else
			$arrTemp['LIKE_ON'] = 0;

		if(in_array($idEvent, $deslike_events))
			$arrTemp['DESLIKE_ON'] = 1;
		else
			$arrTemp['DESLIKE_ON'] = 0;

		if(in_array($idEvent, $deslike_events_go))
			$arrTemp['GO'] = 1;
		else
			$arrTemp['GO'] = 0;

		$arrTemp['GO_CNT'] = sizeof($deslike_events_go_cnt[$idEvent]);
		$arrTemp['GO_USER'] = array();

		if(sizeof($deslike_events_go_cnt[$idEvent])) {
			foreach ($deslike_events_go_cnt[$idEvent] as $eventsItem) {
				$rsUserData = CUser::GetByID($eventsItem["id_user"]);
				$userData = $rsUserData->Fetch();

				if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
					$format_name = $userData['NAME'];
					if($userData['SECOND_NAME']) {
						$format_name .= ' ';
						$format_name .= $userData['SECOND_NAME'];
					}
					$format_name .= ' ';
					$format_name .= $userData['LAST_NAME'];
				} else {
					$format_name = $userData['LOGIN'];
				}

				if($userData['PERSONAL_PHOTO']) {
					$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
				} else {
					$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
				}
				$arrTemp['GO_USER'][] = array('ID' => $userData['ID'], 'NAME' => $format_name, 'AVATAR' => $avatar_baloon);
			}
		}
		$arrTemp['USER'] = $user_id;
		$arrTemp["sort"] = strtotime($fullTime);

		$newsList[] = $arrTemp;

	}
}

if($input['type'] == 'opendoor') {
	$opendoorList = array();

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_OPENDOOR");
	$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "ID" => $input['id_vuz']);
	$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
	while($row = $res->Fetch())
	{
		$opendoorList[] = $row['PROPERTY_OPENDOOR_VALUE'];
	}

	foreach($opendoorList as $item) {
		$arrTemp = array();
		$arrItem = explode('#', $item);
		if(!$arrItem[0])
			continue;

		$idOd = $arrItem[12];

		if(sizeof($arrItem) < sizeof($placeholder)) {
			$arrItem[9]	= $arrItem[5];
			$arrItem[5] = '';
		}

		$arrTemp["TYPE"] = 'opendoor';
		$arrTemp['ID'] = $idOd;
		$arrTemp['DATA'] = $arrItem;

		$fullTime = $arrItem[1] . ' ' . $arrItem[2];

		$strDate = get_str_time_post(strtotime($fullTime));
		$arrTemp["FORMAT_DATE"] = $strDate;

		$curDate = explode(' ', $strDate);
		$arrTemp["DAY"] = $curDate[0];
		$arrTemp["MONTH"] = $curDate[1];

		$arrTemp["ADMINS"] = $isAdmin;

		$arrTemp["sort"] = strtotime($fullTime);

		$newsList[] = $arrTemp;
	}
}

usort($newsList, "cmp");

if($input['type'] == 'events') {
	$cnt = 10;
	for($n = 0; $n < $cnt; $n++) {
		if($newsList[$n])
			$out[] = $newsList[$n];
	}
} else {
	$go = 0;
	$goMax = (int) $input['startFrom'] + 10;
	foreach ($newsList as $item) {
		if($go < $input['startFrom']) {
			$go++;
			continue;
		}
		if($go >= $goMax) {
			break;
		}
		$out[] = $item;
		$go++;
	}
}



$data = array("status" => "success", 'res' => $out, 'user_id' => $user_id );
die(json_encode($data));
?>