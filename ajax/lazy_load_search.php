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

$filter = 'all';
if($input['filter']) {
    $filter = $input['filter'];
}

$load = array();
if($input['load']) {
    $load = $input['load'];
}

function cmp($a, $b) {
  if ($a['sort'] == $b['sort']) {
      return 0;
  }
  return ($a['sort'] < $b['sort']) ? -1 : 1;
}

$arrTypeUrlEvents = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');

if(strlen($input['s']) > 2) {

    if($filter == 'all' || $filter == 'user' || $filter == 'teacher') {

        $dataUser = CUser::GetList($by="ID", $order="ASC", array('NAME' => $input['s']));
        while($arUser = $dataUser->Fetch()) {

            if(in_array($arUser['ID'], $load['user']) || in_array($arUser['ID'], $load['teacher']))
                continue;

            $tempOut = array();
            $bookmark = 0;

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

            $arUser['ONLINE'] = 0;
            if(CUser::IsOnLine($arUser['ID'], 30) && $arUser['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) {
                $arUser['ONLINE'] = 1;
            }

            $tempOut['data'] = $arUser;
            $tempOut['type'] = 'user';

            if($arUser['WORK_WWW']) {
                $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $arUser['ID'])->fetch();
                if($arrTeacher['cnt'] > 0) {
                    $tempOut['type'] = 'teacher';
                }
            }

            if($user_id) {
                $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 5 AND uz_id = ' . $arUser['ID'] . ' AND user_id = ' . $user_id)->fetch();
            }

            if($bookmark) {
                $tempOut['bookmark'] = 1;
            }

            $tempOut['sort'] = mb_stripos($arUser['LAST_NAME'], $input['s']);

            if($filter == 'all' || $filter == $tempOut['type']) {
                $newsList[] = $tempOut;
            }
        }
    }

    if($filter == 'all' || $filter == 'uz') {
        $arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_SITE", "PROPERTY_EMAIL");
        $arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "!ID" => $load['uz'], array("LOGIC" => "OR", array("NAME" => "%" . $input['s'] . "%"), array("PROPERTY_ABBR" => "%" . $input['s'] . "%")));
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        while($row = $res->GetNext()) {
            $tempOut = array();
            $bookmark = 0;

            $tempOut['data'] = $row;
            $tempOut['type'] = 'uz';
            $tempOut['data']["type"] = 1;

            $tempOut['data']["ADRESS"] = $row["PROPERTY_ADRESS_VALUE"];

            if($row["PROPERTY_LOGO_VALUE"]) {
                $tempOut['data']["PIC"] = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
            } elseif($row["PREVIEW_PICTURE"]) {
                $tempOut['data']["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);
            } else {
                $tempOut['data']["PIC"] = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
            }

            if($user_id) {
                $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 1 AND uz_id = ' . $row['ID'] . ' AND user_id = ' . $user_id)->fetch();
            }

            if($bookmark) {
                $tempOut['bookmark'] = 1;
            }

            $tempOut['sort'] = mb_stripos($row['NAME'], $input['s']);

            $newsList[] = $tempOut;
        }

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_SITE", "PROPERTY_EMAIL", "PROPERTY_LOGO");
        $arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "!ID" => $load['uz'], array("LOGIC" => "OR", array("NAME" => "%" . $input['s'] . "%"), array("PROPERTY_ABBR" => "%" . $input['s'] . "%")));
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        while($row = $res->GetNext()) {
            $tempOut = array();
            $bookmark = 0;

            $tempOut['data'] = $row;
            $tempOut['type'] = 'uz';
            $tempOut['data']["type"] = 2;

            $tempOut['data']["ADRESS"] = $row["PROPERTY_ADRESS_VALUE"];

            if($row["PROPERTY_LOGO_VALUE"]) {
                $tempOut['data']["PIC"] = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
            } elseif($row["PREVIEW_PICTURE"]) {
                $tempOut['data']["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);
            } else {
                $tempOut['data']["PIC"] = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
            }

            if($user_id) {
                $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 2 AND uz_id = ' . $row['ID'] . ' AND user_id = ' . $user_id)->fetch();
            }

            if($bookmark) {
                $tempOut['bookmark'] = 1;
            }

            $tempOut['sort'] = mb_stripos($row['NAME'], $input['s']);

            $newsList[] = $tempOut;
        }

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_YEAR", "PROPERTY_CITY", "PROPERTY_SITE", "PROPERTY_PHONE");
        $arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "!ID" => $load['uz'], array("LOGIC" => "OR", array("NAME" => "%" . $input['s'] . "%"), array("PROPERTY_ABBR" => "%" . $input['s'] . "%")));
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        while($row = $res->GetNext()) {
            $tempOut = array();
            $bookmark = 0;

            $tempOut['data'] = $row;
            $tempOut['type'] = 'uz';
            $tempOut['data']["type"] = 4;

            if($row["DETAIL_PICTURE"]) {
                $tempOut['data']["PIC"] = CFile::GetPath($row["DETAIL_PICTURE"]);
            } else {
                $tempOut['data']["PIC"] = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
            }

            $resADRESS = CIBlockElement::GetProperty(6, $row['ID'], "sort", "asc", array("CODE" => "ADRESS"));
            while($obADRESS = $resADRESS->GetNext())
            {
                if($obADRESS['VALUE'])
                    $tempOut['data']["ADRESS"][] = $obADRESS['VALUE'];
            }

            if($user_id) {
                $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 4 AND uz_id = ' . $row['ID'] . ' AND user_id = ' . $user_id)->fetch();
            }

            if($bookmark) {
                $tempOut['bookmark'] = 1;
            }

            $tempOut['sort'] = mb_stripos($row['NAME'], $input['s']);

            $newsList[] = $tempOut;
        }
    }

    if($filter == 'all' || $filter == 'news') {
        $like = array();
        $like_sql = $dbh->query('SELECT id_news from a_like_news WHERE id_user = ' . $user_id . ' ORDER BY id DESC')->fetchAll();
        foreach($like_sql as $like_item) {
            $like[] = $like_item['id_news'];
        }

        $deslike = array();
        $deslike_sql = $dbh->query('SELECT id_news from a_deslike_news WHERE id_user = ' . $user_id . ' ORDER BY id DESC')->fetchAll();
        foreach($deslike_sql as $deslike_item) {
            $deslike[] = $deslike_item['id_news'];
        }

        $like_news_cnt = array();
        $like_news_cnt_sql = $dbh->query('SELECT * from a_like_news ORDER BY id DESC')->fetchAll();
        foreach($like_news_cnt_sql as $like_news_cnt_item) {
            $like_news_cnt[$like_news_cnt_item['id_news']][] = $like_news_cnt_item;
        }

        $deslike_news_cnt = array();
        $deslike_news_cnt_sql = $dbh->query('SELECT * from a_deslike_news ORDER BY id DESC')->fetchAll();
        foreach($deslike_news_cnt_sql as $deslike_news_cnt_item) {
            $deslike_news_cnt[$deslike_news_cnt_item['id_news']][] = $deslike_news_cnt_item;
        }

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_LIKE", "PROPERTY_DESLIKE", "PROPERTY_VUZ_ID");
        $arFilter = array("IBLOCK_ID" => array(22, 28, 29, 30, 31), "ACTIVE" => "Y", "!ID" => $load['news'], "NAME" => "%" . $input['s'] . "%");
        $res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false,  false, $arSelect);
        while($row = $res->Fetch()) {
            $tempOut = array();

            $row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));
            $row["VUZ_ID"] = $row['PROPERTY_VUZ_ID_VALUE'];

            if($row["PREVIEW_PICTURE"]) {
                $row["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);
            } else {
                $row["PIC"] = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
            }

            $like_news = array();
            $like_news_sql = $dbh->query('SELECT * from a_like_news ORDER BY id DESC')->fetchAll();
            foreach($like_news_sql as $like_news_item) {
                $like_news[$like_news_item['id_news']][] = $like_news_item;
            }

            $deslike_news = array();
            $deslike_news_sql = $dbh->query('SELECT * from a_deslike_news ORDER BY id DESC')->fetchAll();
            foreach($deslike_news_sql as $deslike_news_item) {
                $deslike_news[$deslike_news_item['id_news']][] = $deslike_news_item;
            }

            $row["LIKE"] = array();
            if($like_news[$row["ID"]]) {
                foreach ($like_news[$row["ID"]] as $likeUser) {

                    $rsUserData = CUser::GetByID($likeUser["id_user"]);
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

                    $row["LIKE"][] = array('id_user' => $userData['ID'], 'user_name' => $format_name, 'user_avatar' => $avatar_baloon);
                }
            }

            $row["DESLIKE"] = array();
            if($deslike_news[$row["ID"]]) {
                foreach ($deslike_news[$row["ID"]] as $deslikeUser) {

                    $rsUserData = CUser::GetByID($deslikeUser["id_user"]);
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

                    $row["DESLIKE"][] = array('id_user' => $userData['ID'], 'user_name' => $format_name, 'user_avatar' => $avatar_baloon);
                }
            }

            $br = str_replace(array("\r\n", "\r", "\n"), '<br>', $row["DETAIL_TEXT"]);
            $row["DETAIL_TEXT"] = substr($br, 0, 148) . '..';

            $arSelectURL = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL");
            $arFilterURL = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "ID" => $row['PROPERTY_VUZ_ID_VALUE']);
            $resURL = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterURL, false, false, $arSelectURL);
            if($rowURL = $resURL->GetNext()) {
                $row["DETAIL_PAGE_URL"] = $rowURL["DETAIL_PAGE_URL"];
            }

            $tempOut['data'] = $row;
            $tempOut['type'] = 'news';

            $tempOut['sort'] = mb_stripos($row['NAME'], $input['s']);

            $newsList[] = $tempOut;
        }
    }

    if($filter == 'all' || $filter == 'events') {

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
        $arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false);

        $resCarusel = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
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

                if(!$tempEvents[0] || in_array($tempEvents[14], $load['events']))
                    continue;

                if(mb_stripos($tempEvents[0], $input['s'], 0, 'UTF-8') !== false) {


                    $idOd = $tempEvents[14];

                    $rowCarusel["DATA"] = $tempEvents;
                    $rowCarusel["ID_OPENDOOR"]  = $idOd;
                    $rowCarusel["NAME_OPENDOOR"]  = $tempEvents[0];
                    $rowCarusel["PIC"]  = $srcLogo;
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

                    $rowCarusel['sort'] = mb_stripos($tempEvents[0], $input['s'], 0, 'UTF-8');

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
                    $rowCarusel['type'] = 'events';

                    $newsList[] = $rowCarusel;
                }
            }
        }
    }

    if($filter == 'all' || $filter == 'ug') {
        $arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID", "CODE", "PREVIEW_TEXT", "PREVIEW_PICTURE", "PROPERTY_SIGN");
        $arFilter = Array("IBLOCK_ID"=>5, "ACTIVE"=>"Y", "!ID" => $load['ug'], "NAME" => "%" . $input['s'] . "%");
        $res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
        while($row = $res->Fetch()) {
            $tempOut = array();

            $tempOut['data'] = $row;
            $tempOut['type'] = 'ug';

            $tempOut['data']["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);

            $tempOut['sort'] = mb_stripos($row['NAME'], $input['s']);

            $newsList[] = $tempOut;
        }
    }

    usort($newsList, "cmp");

	$go = 0;
    $goMax = 10;
	foreach ($newsList as $item) {
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