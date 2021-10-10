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
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

$arrType = array('universities' => 2, 'colleges' => 3, 'schools' => 4, 'language-class' => 6, 'news-universities' => 2, 'news-colleges' => 3, 'news-schools' => 4, 'news-language-class' => 6);
$arrTypeEvents = array('news-universities' => 22, 'news-colleges' => 28, 'news-schools' => 29, 'news-language-class' => 30, 'news-education' => 31);
$arrTypeUrl = array('news-universities' => 'universities', 'news-colleges' => 'colleges', 'news-schools' => 'schools', 'news-language-class' => 'language-class', 'news-education' => 'education');
$arrTypeUrlEvents = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');

$iblick = $arrType[$input['cur']];

if($input['type'] == 'rand') {
	$sort = array("RAND" => "ASC");
} elseif($input['type'] == 'date') {
    $sort = array("PROPERTY_YEAR" => "ASC");
} else {
	$sort = array("NAME" => "ASC");
}

$cnt = $input['cnt'];

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] > $b['sort']) ? -1 : 1;
}

$placeholder = array('Название',
    'Дата',
    'Время',
    'Адрес',
    'Координаты Яндекс',
    'Телефон',
    'Ссылка на страницу',
    'Комментарий',
    'Текст',
    'ucheba.ru',
    'Дата создания',
    'Дополнительная строка',
    'Уникальный ключ');

if($input['cur'] == 'user') {

	$perPage = 20;
	$search = $input['search'];

	$n = 0;

	if($search)
		$userBy = "ID";
	else
		$userBy = "LAST_NAME";

	$userOrder = "ASC";

	if($search) {

		if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
			$filter = array("NAME" => $search, "ACTIVE" => "Y", "UF_CITY" => $_SESSION['PANEL']['CITY']);
		elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
			$filter = array("NAME" => $search, "ACTIVE" => "Y", "UF_REGION" => $_SESSION['PANEL']['REGION']);
		elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
			$filter = array("NAME" => $search, "ACTIVE" => "Y", "UF_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
		else
			$filter = array("NAME" => $search, "ACTIVE" => "Y");

		$rsUsers = CUser::GetList($by="ID", $order="ASC", $filter);
	} else {

		if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
			$filter = array("ACTIVE" => "Y", "UF_CITY" => $_SESSION['PANEL']['CITY']);
		elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
			$filter = array("ACTIVE" => "Y", "UF_REGION" => $_SESSION['PANEL']['REGION']);
		elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
			$filter = array("ACTIVE" => "Y", "UF_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
		else
			$filter = array("ACTIVE" => "Y");

		$rsUsers = CUser::GetList($userBy, $userOrder, $filter);
	}
		while($arResult['USER'] = $rsUsers->Fetch()) {

            if(in_array($arResult['USER']['ID'], $input['load']) || isSupport($arResult['USER']['ID']))
                continue;

			if($arResult['USER']['PERSONAL_PHOTO']) {
				$avatar_url = CFile::GetPath($arResult['USER']['PERSONAL_PHOTO']);
			} else {
				$avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
			}
			$arResult['USER']['AVATAR'] = $avatar_url;

			if (strlen(trim($arResult['USER']['NAME'])) && strlen(trim($arResult['USER']['LAST_NAME']))) {
				$format_name = '<span>' . strtoupper(mb_substr(trim($arResult['USER']['NAME']), 0, 1)) . '</span>' . mb_substr(trim($arResult['USER']['NAME']), 1);
				if($arResult['USER']['SECOND_NAME']) {
					$format_name .= ' ';
					$format_name .= '<span>' . strtoupper(mb_substr($arResult['USER']['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($arResult['USER']['SECOND_NAME'], 1);
				}
				$format_name .= ' ';
				$format_name .= '<span>' . strtoupper(mb_substr(trim($arResult['USER']['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($arResult['USER']['LAST_NAME']), 1);
			} else {
				$format_name = '<span>' . strtoupper(mb_substr(trim($arResult['USER']['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($arResult['USER']['LOGIN']), 1);
			}
			$arResult['USER']['F_NAME'] = $format_name;

			if($arResult['USER']['WORK_WWW']) {
				$arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $arResult['USER']['ID'])->fetch();
				if($arrTeacher['cnt'] > 0) {
					$arResult['USER']['TEACHER'] = 1;
				} else {
					$arResult['USER']['TEACHER'] = 0;
				}
			} else {
				$arResult['USER']['TEACHER'] = 0;
			}

			if($user_id) {
			    $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 5 AND uz_id = ' . $arResult['USER']['ID'] . ' AND user_id = ' . $user_id)->fetch();
			}
			$arResult['USER']['BOOKMARK'] = $bookmark;

			$arResult['USER']['AUTHORIZE'] = $user_id;

			if(CUser::IsOnLine($arResult['USER']['ID'], 30) && $arResult['USER']['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) {
				$arResult['USER']['ONLINE'] = 1;
			} else {
				$arResult['USER']['ONLINE'] = 0;
			}

            $arResult['USER']['URL'] = getUserUrl($arResult['USER']);

			if($input['type'] == 'all')
				$out[] = $arResult['USER'];
			elseif($input['type'] == 'us' && !$arResult['USER']['TEACHER'])
				$out[] = $arResult['USER'];
			elseif($input['type'] == 'teacher' && $arResult['USER']['TEACHER'])
				$out[] = $arResult['USER'];

			$n++;
			if($n > $perPage)
				break;
		}

} elseif($input['cur'] == 'open-days') {

	$iblick = $arrType[$input['type']];
	if(!$iblick)
		$iblick = array(2, 3, 4, 6);

	$arrNews = array();
	$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO");

	if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
		$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
	elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
		$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
	else
		$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

	$resCarusel = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
	while($rowCarusel = $resCarusel->Fetch()) {

		if($rowCarusel["PROPERTY_LOGO_VALUE"]):
			$srcLogo = CFile::GetPath($rowCarusel["PROPERTY_LOGO_VALUE"]);
		elseif($rowCarusel["PREVIEW_PICTURE"]):
			$srcLogo = CFile::GetPath($rowCarusel["PREVIEW_PICTURE"]);
		elseif($rowCarusel["DETAIL_PICTURE"]):
			$srcLogo = CFile::GetPath($rowCarusel["DETAIL_PICTURE"]);
		else:
			$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
		endif;

		$rowCarusel["TYPE"]  = $arrTypeUrlEvents[$rowCarusel["IBLOCK_ID"]];

		$url = '/uchebnye-zavedeniya/' . $rowCarusel["TYPE"] . '/' . $rowCarusel["CODE"] . '/?sect=opendoor';

		$idOd = 0;
		$resEvents = CIBlockElement::GetProperty($rowCarusel["IBLOCK_ID"], $rowCarusel["ID"], "sort", "asc", array("CODE" => "OPENDOOR"));
		while ($obEvents = $resEvents->GetNext()) {

			$html = '';

			$tempEvents = explode('#', $obEvents['VALUE']);

			if(!$tempEvents[0] || in_array($tempEvents[12], $input['u_key']))
				continue;

			if($rowCarusel["IBLOCK_ID"] == 2) {
				$tempEvents[6] = $tempEvents[5];
				$tempEvents[5] = '';
			}

			$rowCarusel["DATA"] = $tempEvents;
			$rowCarusel["ID_OPENDOOR"]  = $tempEvents[12];
			$rowCarusel["NAME_OPENDOOR"]  = $tempEvents[0];
			$rowCarusel["IMG"]  = $srcLogo;
			$rowCarusel["URL"]  = $url;

			$fullTime = $tempEvents[1] . ' ' . $tempEvents[2];
			$strDate = get_str_time_post(strtotime($fullTime));
			$rowCarusel["FORMAT_DATE"] = $strDate;
			$curDate = explode(' ', $strDate);
			$rowCarusel["DAY"] = $curDate[0];
			$rowCarusel["MONTH"] = $curDate[1];

			$rowCarusel["sort"] = strtotime($fullTime);

			$arrItem = $rowCarusel["DATA"];

			for($n = 1; $n < sizeof($placeholder); $n++) {
				if($n == 1 || $n == 2 || $n == 4 || $n == 9 || $n == 10 || $n == 11 || $n == 12)
					continue;

				if(trim($arrItem[$n])) {
					if($n == 6) {
						$html .= $placeholder[$n] . ': <a href="' . $arrItem[$n] . '" target="blank">' . trim($arrItem[$n]) . '</a><br>';
					} elseif($n == 8) {
						$html .= trim($arrItem[$n]) . '<br>';
					} else {
						$html .= $placeholder[$n] . ': ' . trim($arrItem[$n]) . '<br>';
					}
				}
			}

			$rowCarusel['HTML'] = $html;

			$arrNews[] = $rowCarusel;
			$idOd++;
		}
		//echo '<pre>'; print_r($rowCarusel); echo '</pre>';
	}

	usort($arrNews, "cmp");

	for($n = 0; $n < $cnt; $n++) {
		if($arrNews[$n])
			$out[] = $arrNews[$n];
	}

} elseif($input['cur'] == 'news-universities'
	|| $input['cur'] == 'news-colleges'
	|| $input['cur'] == 'news-schools'
	|| $input['cur'] == 'news-language-class'
	|| $input['cur'] == 'news-education') {

	if($input['type'] == 'news') {

		$iblick = $arrTypeEvents[$input['cur']];

		$arrNews = array();
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_VUZ_ID");

		if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
			$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!ID" => $input['load'], "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
		elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
			$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!ID" => $input['load'], "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
		else
			$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!ID" => $input['load'], "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

		$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, array("nPageSize"=>$cnt), $arSelect);
		while($row = $res->Fetch())
		{
			$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

		    $resMP = CIBlockElement::GetProperty(22, $row['ID'], "sort", "asc", array("CODE" => "MORE_PHOTO"));
		    if($obMP = $resMP->GetNext())
		    {
		    	if($obMP['VALUE'])
		        	$row["PREVIEW_PICTURE"] = $obMP['VALUE'];
		    }

		    $row['IMG'] = CFile::GetPath($row["PREVIEW_PICTURE"]);

			$br = str_replace(array("\r\n", "\r", "\n"), '<br>', $row["DETAIL_TEXT"]);
			$out = mb_substr($br, 0, 148);
		    $row['TEXT'] = $out . '..';

			$arSelectUrl = array("ID", "NAME", "IBLOCK_ID", "CODE");
			$arFilterUrl = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ID" => $row["PROPERTY_VUZ_ID_VALUE"]);
			$resUrl = CIBlockElement::GetList(array("ID" => "DESC"), $arFilterUrl, false, false, $arSelectUrl);
			if($rowUrl = $resUrl->Fetch())
			{
				$row['URL']	= '/uchebnye-zavedeniya/' . $arrTypeUrl[$input['cur']] . '/' . $rowUrl['CODE'] . '/?sect=news&s=' . $row["ID"];
			}
			$arrNews[] = $row;
		}

		$out = $arrNews;

	} elseif($input['type'] == 'events') {

		$iblick = $arrType[$input['cur']];

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

		$arrNews = array();
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO");

		if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
			$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false, "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
		elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
			$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false, "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
		else
			$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false, "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

		$resCarusel = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		while($rowCarusel = $resCarusel->Fetch()) {

			$like_events = array();
			$like_events_sql = $dbh->query('SELECT key_event from a_like_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $rowCarusel["ID"])->fetchAll();
			foreach($like_events_sql as $like_events_item) {
				$like_events[] = $like_events_item['key_event'];
			}

			$deslike_events = array();
			$deslike_events_sql = $dbh->query('SELECT key_event from a_deslike_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $rowCarusel["ID"])->fetchAll();
			foreach($deslike_events_sql as $deslike_events_item) {
				$deslike_events[] = $deslike_events_item['key_event'];
			}

			$like_events_cnt = array();
			$like_events_cnt_sql = $dbh->query('SELECT * from a_like_events WHERE id_vuz = ' . $rowCarusel["ID"])->fetchAll();
			foreach($like_events_cnt_sql as $like_events_cnt_item) {
				$like_events_cnt[$like_events_cnt_item['key_event']][] = $like_events_cnt_item;
			}

			$deslike_events_cnt = array();
			$deslike_events_cnt_sql = $dbh->query('SELECT * from a_deslike_events WHERE id_vuz = ' . $rowCarusel["ID"])->fetchAll();
			foreach($deslike_events_cnt_sql as $deslike_events_cnt_item) {
				$deslike_events_cnt[$deslike_events_cnt_item['key_event']][] = $deslike_events_cnt_item;
			}

			$deslike_events_go = array();
			$deslike_events_go_sql = $dbh->query('SELECT key_event from a_events_go WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $rowCarusel["ID"])->fetchAll();
			foreach($deslike_events_go_sql as $deslike_events_go_item) {
				$deslike_events_go[] = $deslike_events_go_item['key_event'];
			}

			$deslike_events_go_cnt = array();
			$deslike_events_go_cnt_sql = $dbh->query('SELECT * from a_events_go WHERE id_vuz = ' . $rowCarusel["ID"])->fetchAll();
			foreach($deslike_events_go_cnt_sql as $deslike_events_go_cnt_item) {
				$deslike_events_go_cnt[$deslike_events_go_cnt_item['key_event']][] = $deslike_events_go_cnt_item;
			}

			if($rowCarusel["PROPERTY_LOGO_VALUE"]):
				$srcLogo = CFile::GetPath($rowCarusel["PROPERTY_LOGO_VALUE"]);
			elseif($rowCarusel["PREVIEW_PICTURE"]):
				$srcLogo = CFile::GetPath($rowCarusel["PREVIEW_PICTURE"]);
			elseif($rowCarusel["DETAIL_PICTURE"]):
				$srcLogo = CFile::GetPath($rowCarusel["DETAIL_PICTURE"]);
			else:
				$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
			endif;

			$rowCarusel["TYPE"]  = $arrTypeUrlEvents[$rowCarusel["IBLOCK_ID"]];

			$url = '/uchebnye-zavedeniya/' . $rowCarusel["TYPE"] . '/' . $rowCarusel["CODE"] . '/?sect=events';

			$idOd = 0;
			$resEvents = CIBlockElement::GetProperty($rowCarusel["IBLOCK_ID"], $rowCarusel["ID"], "id", "asc", array("CODE" => "ADD_EVENTS"));
			while ($obEvents = $resEvents->GetNext()) {

				$html = '';

				$tempEvents = explode('#', $obEvents['VALUE']);

				if(!$tempEvents[0] || in_array($tempEvents[14], $input['u_key']))
					continue;

				$idOd = $tempEvents[14];

				$rowCarusel["DATA"] = $tempEvents;
				$rowCarusel["ID_OPENDOOR"]  = $idOd;
				$rowCarusel["NAME_OPENDOOR"]  = $tempEvents[0];
				$rowCarusel["IMG"]  = $srcLogo;
				$rowCarusel["URL"]  = $url;

				if(in_array($idOd, $like_events))
					$rowCarusel['LIKE_ON'] = 1;
				else
					$rowCarusel['LIKE_ON'] = 0;

				if(in_array($idOd, $deslike_events))
					$rowCarusel['DESLIKE_ON'] = 1;
				else
					$rowCarusel['DESLIKE_ON'] = 0;

				$rowCarusel['LIKE_CNT'] = sizeof($like_events_cnt[$idOd]);
				$rowCarusel['LIKE_USER'] = array();

				if(sizeof($like_events_cnt[$idOd])) {
					foreach ($like_events_cnt[$idOd] as $eventsItem) {
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
						$rowCarusel['LIKE_USER'][] = array('ID' => $userData['ID'], 'NAME' => $format_name, 'AVATAR' => $avatar_baloon);
					}
				}

				$rowCarusel['DESLIKE_CNT'] = sizeof($deslike_events_cnt[$idOd]);
				$rowCarusel['DESLIKE_USER'] = array();

				if(sizeof($deslike_events_cnt[$idOd])) {
					foreach ($deslike_events_cnt[$idOd] as $eventsItem) {
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
						$rowCarusel['DESLIKE_USER'][] = array('ID' => $userData['ID'], 'NAME' => $format_name, 'AVATAR' => $avatar_baloon);
					}
				}

				if(in_array($idOd, $deslike_events_go))
					$rowCarusel['GO'] = 1;
				else
					$rowCarusel['GO'] = 0;

				$rowCarusel['GO_CNT'] = sizeof($deslike_events_go_cnt[$idOd]);
				$rowCarusel['GO_USER'] = array();

				if(sizeof($deslike_events_go_cnt[$idOd])) {
					foreach ($deslike_events_go_cnt[$idOd] as $eventsItem) {
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
						$rowCarusel['GO_USER'][] = array('ID' => $userData['ID'], 'NAME' => $format_name, 'AVATAR' => $avatar_baloon);
					}
				}

				$fullTime = $tempEvents[1] . ' ' . $tempEvents[2];
				$strDate = get_str_time_post(strtotime($fullTime));
				$rowCarusel["FORMAT_DATE"] = $strDate;
				$curDate = explode(' ', $strDate);
				$rowCarusel["DAY"] = $curDate[0];
				$rowCarusel["MONTH"] = $curDate[1];

				$rowCarusel["sort"] = strtotime($fullTime);

				$arrItem = $rowCarusel["DATA"];

				for($n = 1; $n < sizeof($placeholder); $n++) {
					if($n == 1 || $n == 2 || $n == 4 || $n == 10 || $n == 11 || $n == 12 || $n == 13 || $n == 14)
						continue;

					if(trim($arrItem[$n])) {
						if($n == 7) {
							$html .= $placeholder[$n] . ': <a href="' . $arrItem[$n] . '" target="blank">' . trim($arrItem[$n]) . '</a><br>';
						} elseif($n == 8 || $n == 9) {
							$html .= trim($arrItem[$n]) . '<br>';
						} else {
							$html .= $placeholder[$n] . ': ' . trim($arrItem[$n]) . '<br>';
						}
					}
				}

				$rowCarusel['HTML'] = $html;
				$rowCarusel['USER'] = $user_id;

				$arrNews[] = $rowCarusel;
			}
		}

		usort($arrNews, "cmp");

		for($n = 0; $n < $cnt; $n++) {
			if($arrNews[$n])
				$out[] = $arrNews[$n];
		}

	}

} else {
	$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL", "PROPERTY_YEAR");

	if($input['type'] == 'gov')
		$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!ID" => $input['load'], "PROPERTY_CHAST" => "N");
	elseif($input['type'] == 'chast')
		$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!ID" => $input['load'], "PROPERTY_CHAST" => "Y");
	else
		$arFilter = array("IBLOCK_ID" => $iblick, "ACTIVE" => "Y", "!ID" => $input['load']);

	if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
		$arFilter["PROPERTY_CITY"] = $_SESSION['PANEL']['CITY'];
	elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
		$arFilter["PROPERTY_REGION"] = $_SESSION['PANEL']['REGION'];
	elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
		$arFilter["PROPERTY_COUNTRY"] = $_SESSION['PANEL']['COUNTRY'];

    if($input['type'] == 'date')
        $arFilter[">PROPERTY_YEAR"] = '0';

    $n = 0;

	$resCarusel = CIBlockElement::GetList($sort, $arFilter, false, array("nPageSize" => $cnt), $arSelect);
	while($rowCarusel = $resCarusel->Fetch())
	{

		$arrTemp = array();

		$arrTemp['ID'] = $rowCarusel['ID'];
		$arrTemp['IBLOCK_ID'] = $rowCarusel['IBLOCK_ID'];
		$arrTemp['NAME'] = $rowCarusel['NAME'];

		$arrTemp['URL'] = '/uchebnye-zavedeniya/' . $input['cur'] . '/' . $rowCarusel["CODE"] . '/';

        $arrTemp['YEAR'] = preg_replace('~\D+~','', $rowCarusel["PROPERTY_YEAR_VALUE"]);

		if($input['cur'] == 'universities') {

			if($rowCarusel["PROPERTY_LOGO_VALUE"]):
				$srcLogo = CFile::GetPath($rowCarusel["PROPERTY_LOGO_VALUE"]);
			elseif($rowCarusel["PREVIEW_PICTURE"]):
				$srcLogo = CFile::GetPath($rowCarusel["PREVIEW_PICTURE"]);
			else:
				$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
			endif;

			$arrTemp['IMG']	= $srcLogo;

			$arrTemp['ADRESS']	= $rowCarusel["PROPERTY_ADRESS_VALUE"];
			$arrTemp['SITE']	= $rowCarusel["PROPERTY_SITE_VALUE"];

		} elseif($input['cur'] == 'colleges') {

			if($rowCarusel["PROPERTY_LOGO_VALUE"]):
				$srcLogo = CFile::GetPath($rowCarusel["PROPERTY_LOGO_VALUE"]);
			elseif($rowCarusel["PREVIEW_PICTURE"]):
				$srcLogo = CFile::GetPath($rowCarusel["PREVIEW_PICTURE"]);
			else:
				$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
			endif;

			$arrTemp['IMG']	= $srcLogo;

			$arrTemp['ADRESS']	= $rowCarusel["PROPERTY_ADRESS_VALUE"];
			$arrTemp['SITE']	= $rowCarusel["PROPERTY_SITE_VALUE"];

		} elseif($input['cur'] == 'schools') {

			if($rowCarusel["PROPERTY_LOGO_VALUE"]):
				$srcLogo = CFile::GetPath($rowCarusel["PROPERTY_LOGO_VALUE"]);
			elseif($rowCarusel["PREVIEW_PICTURE"]):
				$srcLogo = CFile::GetPath($rowCarusel["PREVIEW_PICTURE"]);
			else:
				$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
			endif;

			$arrTemp['IMG']	= $srcLogo;

			$arrTemp['ADRESS']	= $rowCarusel["PROPERTY_ADRESS_VALUE"]["TEXT"];
			$arrTemp['SITE']	= $rowCarusel["PROPERTY_SITE_VALUE"];

		} elseif($input['cur'] == 'language-class') {

			if($rowCarusel["PROPERTY_LOGO_VALUE"]):
				$srcLogo = CFile::GetPath($rowCarusel["PROPERTY_LOGO_VALUE"]);
			elseif($rowCarusel["DETAIL_PICTURE"]):
				$srcLogo = CFile::GetPath($rowCarusel["DETAIL_PICTURE"]);
			else:
				$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
			endif;

			$arrTemp['IMG']	= $srcLogo;

			$arrAdress = explode('&', $rowCarusel["PROPERTY_ADRESS_VALUE"]);
			$arrTemp['ADRESS']	= $arrAdress[0];

			$arrUrl = explode('?', $rowCarusel["PROPERTY_SITE_VALUE"]);
			$arrTemp['SITE']	= $arrUrl[0];

		}

		$arrTemp['PHONE']	= $rowCarusel["PROPERTY_PHONE_VALUE"];
		$arrTemp['EMAIL']	= $rowCarusel["PROPERTY_EMAIL_VALUE"];

		$out[] = $arrTemp;
	}
}

$data = array("status" => "success", 'res' => $out, 'iblock' => $iblick );
die(json_encode($data));
?>