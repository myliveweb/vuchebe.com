<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle('Поиск по сайту');

CModule::IncludeModule('iblock');

$user_id = 0;

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
}

$input = filter_input_array(INPUT_POST);

$filter = 'all';
if($input['filter']) {
    $filter = $input['filter'];
}

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] < $b['sort']) ? -1 : 1;
}

$arrTypeUrlEvents = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');

$showCount = 0;
$countUser = 0;
$countTeacher = 0;
$countUz = 0;
$countNews = 0;
$countEvents = 0;
$countUg = 0;

$out = array();
if(strlen($input['s']) > 2) {

    $dataUser = CUser::GetList($by="ID", $order="ASC", array('NAME' => $input['s']));
    while($arUser = $dataUser->Fetch()) {

        $tempOut = array();
        $bookmark = 0;

        $tempOut['data'] = $arUser;
        $tempOut['type'] = 'user';

        if($arUser['WORK_WWW']) {
            $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $arUser['ID'])->fetch();
            if($arrTeacher['cnt'] > 0) {
                $tempOut['type'] = 'teacher';
            }
        }

        if($tempOut['type'] == 'user') {
            $countUser++;
        } elseif($tempOut['type'] == 'teacher') {
            $countTeacher++;
        }

        if($user_id) {
            $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 5 AND uz_id = ' . $arUser['ID'] . ' AND user_id = ' . $user_id)->fetch();
        }

        if($bookmark) {
            $tempOut['bookmark'] = 1;
        }

        $tempOut['sort'] = mb_stripos($arUser['LAST_NAME'], $input['s']);

        if($filter == 'all' || $filter == 'user' || $filter == 'teacher') {
            $out[] = $tempOut;
        }
    }

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_SITE", "PROPERTY_EMAIL");
    $arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", array("LOGIC" => "OR", array("NAME" => "%" . $input['s'] . "%"), array("PROPERTY_ABBR" => "%" . $input['s'] . "%")));
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
        } else {
            $tempOut['data']["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);
        }

        if($user_id) {
            $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 1 AND uz_id = ' . $row['ID'] . ' AND user_id = ' . $user_id)->fetch();
        }

        if($bookmark) {
            $tempOut['bookmark'] = 1;
        }

        $tempOut['sort'] = mb_stripos($row['NAME'], $input['s']);

        $countUz++;

        if($filter == 'all' || $filter == 'uz') {
            $out[] = $tempOut;
        }
    }

    //echo '<pre>'; print_r($out); echo '</pre>';

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_SITE", "PROPERTY_EMAIL", "PROPERTY_LOGO");
    $arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", array("LOGIC" => "OR", array("NAME" => "%" . $input['s'] . "%"), array("PROPERTY_ABBR" => "%" . $input['s'] . "%")));
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
        } else {
            $tempOut['data']["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);
        }

        if($user_id) {
            $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 2 AND uz_id = ' . $row['ID'] . ' AND user_id = ' . $user_id)->fetch();
        }

        if($bookmark) {
            $tempOut['bookmark'] = 1;
        }

        $tempOut['sort'] = mb_stripos($row['NAME'], $input['s']);

        $countUz++;

        if($filter == 'all' || $filter == 'uz') {
            $out[] = $tempOut;
        }
    }

    /*$arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_SITE", "PROPERTY_EMAIL", "PROPERTY_LOGO");
    $arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", array("LOGIC" => "OR", array("NAME" => "%" . $input['s'] . "%"), array("PROPERTY_ABBR" => "%" . $input['s'] . "%")));
    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
    while($row = $res->GetNext()) {
        $tempOut = array();
        $bookmark = 0;

        $tempOut['data'] = $row;
        $tempOut['type'] = 'uz';
        $tempOut['data']["type"] = 3;

        $tempOut['data']["ADRESS"] = $row["PROPERTY_ADRESS_VALUE"]["TEXT"];

        if($row["PROPERTY_LOGO_VALUE"]) {
            $tempOut['data']["PIC"] = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
        } else {
            $tempOut['data']["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);
        }

        if($user_id) {
            $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 3 AND uz_id = ' . $row['ID'] . ' AND user_id = ' . $user_id)->fetch();
        }

        if($bookmark) {
            $tempOut['bookmark'] = 1;
        }

        $tempOut['sort'] = stripos($row['NAME'], $input['s']);

        $out[] = $tempOut;
    }*/

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_YEAR", "PROPERTY_CITY", "PROPERTY_SITE", "PROPERTY_PHONE");
    $arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", array("LOGIC" => "OR", array("NAME" => "%" . $input['s'] . "%"), array("PROPERTY_ABBR" => "%" . $input['s'] . "%")));
    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
    while($row = $res->GetNext()) {
        $tempOut = array();
        $bookmark = 0;

        $tempOut['data'] = $row;
        $tempOut['type'] = 'uz';
        $tempOut['data']["type"] = 4;

        $tempOut['data']["PIC"] = CFile::GetPath($row["DETAIL_PICTURE"]);

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

        $countUz++;

        if($filter == 'all' || $filter == 'uz') {
            $out[] = $tempOut;
        }
    }

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

    ?>
    <script>
    <?php

    echo 'var arrLikeNews = new Array();' . "\n";
    foreach($like as $itemLike) {
        echo 'arrLikeNews.push(' . $itemLike . ');' . "\n";
    }
    echo 'var arrDeslikeNews = new Array();' . "\n";
    foreach($deslike as $itemDeslike) {
        echo 'arrDeslikeNews.push(' . $itemDeslike . ');' . "\n";
    }

    echo 'var arrLikeNewsCnt = new Array();' . "\n";
    foreach($like_news_cnt as $idNews => $arrCnt) {
        echo 'arrLikeNewsCnt[' . $idNews . '] = ' . sizeof($arrCnt) . ';' . "\n";
    }
    echo 'var arrDeslikeNewsCnt = new Array();' . "\n";
    foreach($deslike_news_cnt as $idNews => $arrCnt) {
        echo 'arrDeslikeNewsCnt[' . $idNews . '] = ' . sizeof($arrCnt) . ';' . "\n";
    }
    ?>
    </script>
    <?php

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_LIKE", "PROPERTY_DESLIKE", "PROPERTY_VUZ_ID");
    $arFilter = array("IBLOCK_ID" => array(22, 28, 29, 30, 31), "ACTIVE" => "Y", "NAME" => "%" . $input['s'] . "%");
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

        $br = str_replace(array("\r\n", "\r", "\n"), '<br>', $row["DETAIL_TEXT"]);
        $row["DETAIL_TEXT"] = substr($br, 0, 148) . '..';

        $tempOut['data'] = $row;
        $tempOut['type'] = 'news';

        $tempOut['sort'] = mb_stripos($row['NAME'], $input['s']);

        $countNews++;

        if($filter == 'all' || $filter == 'news') {
            $out[] = $tempOut;
        }
    }


    $vuz_id = 0;
    $event_id = 0;

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO");
    $arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false);

    $resCarusel = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
    while($row = $resCarusel->Fetch()) {

        if($row["PROPERTY_LOGO_VALUE"]):
            $srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
        elseif($row["PREVIEW_PICTURE"]):
            $srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
        elseif($row["DETAIL_PICTURE"]):
            $srcLogo = CFile::GetPath($row["DETAIL_PICTURE"]);
        else:
            $srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
        endif;

        $row["TYPE"]  = $arrTypeUrlEvents[$row["IBLOCK_ID"]];

        $url = '/uchebnye-zavedeniya/' . $rowCarusel["TYPE"] . '/' . $row["CODE"] . '/?sect=events';

        $resEvents = CIBlockElement::GetProperty($row["IBLOCK_ID"], $row["ID"], "id", "asc", array("CODE" => "ADD_EVENTS"));
        while ($obEvents = $resEvents->GetNext()) {

            $tempOut = array();

            $arrEvents = explode('#', $obEvents['VALUE']);

            if($arrEvents[0] && mb_stripos($arrEvents[0], $input['s'], 0, 'UTF-8') !== false) {

                $tempOut['data']['ID'] = $arrEvents[14];
                $tempOut['data']['name'] = $arrEvents[0];
                $tempOut['data']['map'] = $arrEvents[4];
                $tempOut['data']['adress'] = $arrEvents[3];
                $tempOut['data']['phone'] = $arrEvents[5];
                $tempOut['data']['contact'] = $arrEvents[6];
                $tempOut['data']['link'] = $arrEvents[7];
                $tempOut['data']['message'] = $arrEvents[8];
                $tempOut['data']['date'] = $arrEvents[1];
                $tempOut['data']['time'] = $arrEvents[2];
                $tempOut['data']['vuz_id'] = $row['ID'];
                $tempOut['data']['event_id'] = $arrEvents[14];
                $tempOut['data']['logo'] = $srcLogo;
                $tempOut['data']['url'] = $url;

                $tempOut['type'] = 'events';

                $tempOut['sort'] = mb_stripos($arrEvents[0], $input['s']);

                $countEvents++;

                if($filter == 'all' || $filter == 'events') {
                    $out[] = $tempOut;
                }
            }
        }
    }

    $arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID", "CODE", "PREVIEW_TEXT", "PREVIEW_PICTURE", "PROPERTY_SIGN");
    $arFilter = Array("IBLOCK_ID"=>5, "ACTIVE"=>"Y", "NAME" => "%" . $input['s'] . "%");
    $res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
    while($row = $res->Fetch()) {
        $tempOut = array();

        $tempOut['data'] = $row;
        $tempOut['type'] = 'ug';

        $tempOut['data']["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);

        $tempOut['sort'] = mb_stripos($row['NAME'], $input['s']);

        $countUg++;

        if($filter == 'all' || $filter == 'ug') {
            $out[] = $tempOut;
        }
    }

    usort($out, "cmp");

    // Limit Array
    $outLimit = array();
    $startFromSearch = 0;

    //echo '<pre>'; print_r($out); echo '</pre>';
    //echo '<pre>'; print_r($startFrom); echo '</pre>';

    $go = 0;
    $goMax = 10;
    foreach($out as $item) {
        if($go >= $goMax) {
            break;
        }
        $outLimit[] = $item;
        $go++;
    }
    //echo '<pre>'; print_r($outLimit); echo '</pre>';
    // From LazyLoad All Start
    ?>
    <script>
    <?php
    echo 'startFromSearch = 10;' . "\n";
    echo 'search = "' . $input['s'] . '";' . "\n";
    ?>
    </script>
    <?php

    $showCount = $countUser + $countTeacher + $countUz + $countNews + $countEvents + $countUg;

}

?>
<link rel="stylesheet" href="main.css">

<div class="st-content-search">
    <div class="breadcrumbs">
        <a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i>  <span>Поиск</span>
    </div>
    <div class="page-content" id="page">
        <div class="st-content-bottom clear">
            <div class="module st-news">
    <div class="structure-cat bg-silver text-center">
        <div class="row-line">
            <form action="/search/" method="post" accept-charset="utf-8">
                <div class="col-10 search-filed" style="padding: 0 0 0 15px;">
                    <input type="text" name="s" value="<?php echo $input['s']; ?>" />
                    <input type="hidden" name="p" value="1" />
                    <input type="hidden" name="filter" id="filterinput" value="<?php echo $filter; ?>" />
                </div>
                <div class="col-2 button-filed">
                    <button type="submit" style="line-height: 30px; width: 100%;">
                        <span class="short"><i class="fa fa-search"></i></span>
                        <span class="full">найти</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="row-line filter-search">
            <div class="col-12">
                <div data-filter="all" data-cnt="<?php echo $showCount; ?>" class="filter<?php if($filter == 'all') { echo ' active'; } ?>">
                    <span style="white-space: nowrap;">все</span>
                    <?php if($showCount) { ?>
                    <div class="bulet"><?php echo $showCount; ?></div>
                    <?php } ?>
                </div>
                <div data-filter="user" data-cnt="<?php echo $countUser; ?>" class="filter<?php if($filter == 'user') { echo ' active'; } ?>">
                    <span style="white-space: nowrap;">пользователи</span>
                    <?php if($countUser) { ?>
                    <div class="bulet"><?php echo $countUser; ?></div>
                    <?php } ?>
                </div>
                <div data-filter="teacher" data-cnt="<?php echo $countTeacher; ?>" class="filter<?php if($filter == 'teacher') { echo ' active'; } ?>">
                    <span style="white-space: nowrap;">преподаватели</span>
                    <?php if($countTeacher) { ?>
                    <div class="bulet"><?php echo $countTeacher; ?></div>
                    <?php } ?>
                </div>
                <div data-filter="uz" data-cnt="<?php echo $countUz; ?>" class="filter<?php if($filter == 'uz') { echo ' active'; } ?>">
                    <span style="white-space: nowrap;">учебные заведения</span>
                    <?php if($countUz) { ?>
                    <div class="bulet"><?php echo $countUz; ?></div>
                    <?php } ?>
                </div>
                <div data-filter="news" data-cnt="<?php echo $countNews; ?>" class="filter<?php if($filter == 'news') { echo ' active'; } ?>">
                    <span style="white-space: nowrap;">новости</span>
                    <?php if($countNews) { ?>
                    <div class="bulet"><?php echo $countNews; ?></div>
                    <?php } ?>
                </div>
                <div data-filter="events" data-cnt="<?php echo $countEvents; ?>" class="filter<?php if($filter == 'events') { echo ' active'; } ?>">
                    <span style="white-space: nowrap;">события</span>
                    <?php if($countEvents) { ?>
                    <div class="bulet"><?php echo $countEvents; ?></div>
                    <?php } ?>
                </div>
                <div data-filter="ug" data-cnt="<?php echo $countUg; ?>" class="filter<?php if($filter == 'ug') { echo ' active'; } ?>">
                    <span style="white-space: nowrap;">уголок знаний</span>
                    <?php if($countUg) { ?>
                    <div class="bulet"><?php echo $countUg; ?></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if($outLimit) { ?>
    <div style="margin-bottom: 25px;"><span style="margin: 0 10px 0 0; font-weight: bold;"><?php echo $input['s']; ?>:</span>результаты поиска (<span id="show-count"><?php echo $showCount; ?></span>)</div>
    <?php } ?>
    <div class="line all" id="box-line" data-type="search">
    <?php
    if($outLimit) {
        foreach($outLimit as $dataSearch) {
            if($dataSearch['type'] == 'user' || $dataSearch['type'] == 'teacher') {

                $userData = $dataSearch['data'];

                if($userData['PERSONAL_PHOTO']) {
                    $avatar_url = CFile::GetPath($userData['PERSONAL_PHOTO']);
                } else {
                    $avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
                }

                if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
                    $format_name = '<span>' . strtoupper(mb_substr(trim($userData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['NAME']), 1);
                    if($userData['SECOND_NAME']) {
                        $format_name .= ' ';
                        $format_name .= '<span>' . strtoupper(mb_substr($userData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($userData['SECOND_NAME'], 1);
                    }
                    $format_name .= ' ';
                    $format_name .= '<span>' . strtoupper(mb_substr(trim($userData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['LAST_NAME']), 1);
                } else {
                    $format_name = '<span>' . strtoupper(mb_substr(trim($userData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($userData['LOGIN']), 1);
                }

                $month_1 = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
                if($userData['PERSONAL_BIRTHDAY']) {
                    list($dayShow, $monthShow, $yearShow) = explode('.', $userData['PERSONAL_BIRTHDAY']);
                    $showBd = (int) $dayShow . ' ' . $month_1[(int) $monthShow] . ' ' . (int) $yearShow . ' г.';
                }

                $filterType = 'user';

                if($dataSearch['type'] == 'teacher') {
                    $userData['TEACHER'] = 1;
                    $filterType = 'teacher';
                }

            } elseif($dataSearch['type'] == 'uz') {
                $arrData = $dataSearch['data'];
                $filterType = 'uz';

            } elseif($dataSearch['type'] == 'news') {
                $itemList = $dataSearch['data'];

                $arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL");
                $arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "ID" => $dataSearch['data']["VUZ_ID"]);
                $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
                if($row = $res->GetNext()) {
                    $itemList["DETAIL_PAGE_URL"] = $row["DETAIL_PAGE_URL"];
                }

                $filterType = 'news';

            } elseif($dataSearch['type'] == 'events') {
                $itemList = $dataSearch['data'];

                $fullTime = $itemList['date'] . ' ' . $itemList['time'];

                $strDate = get_str_time_post(strtotime($fullTime));
                $itemList["FORMAT_DATE"] = $strDate;

                $curDate = explode(' ', $strDate);
                $itemList["DAY"] = $curDate[0];
                $itemList["MONTH"] = $curDate[1];

                $filterType = 'events';

            } elseif($dataSearch['type'] == 'ug') {
                $itemList = $dataSearch['data'];

                $current_section = '';
                $res = CIBlockSection::GetByID($itemList["IBLOCK_SECTION_ID"]);
                if($ar_res = $res->GetNext())
                    $current_section = $ar_res['CODE'];

                $filterType = 'ug';
            }
            ?>
            <div class="news-item <?php echo $filterType; ?>" data-id="<?php echo $dataSearch['data']["ID"]?>" style="position: relative;<?php if($filterType != $filter && $filter != 'all') { echo ' display: none;'; } ?>">
            <?php if($dataSearch['type'] == 'user' || $dataSearch['type'] == 'teacher') { ?>
                <div class="col-3 content-left" style="padding-right: 0; padding: 0;">
                    <div class="image brd rad-50" style="text-align: center; width: 142px;">
                        <img src="<?=$avatar_url?>" alt="img" style="height: 111px; width: 111px;<?php if($userData['TEACHER']) { echo ' border: 3px solid #ff5b32;'; } ?>">
                    </div>
                </div>
                <div class="col-9 content-right" style="padding: 0;">
                    <div class="page-info" style="position: absolute; width: 480px;">
                        <h1 class="name-user">
                            <span><a href="/user/<?=$userData['ID']?>/" class="display-name"><?=$format_name?></a></span>
                            <?php if(CUser::IsOnLine($userData['ID'], 30) && $userData['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
                            <div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
                            <?php } ?>
                        </h1>
                        <div class="contact-info">
                            <div class="btns" style="margin-left: 20px; margin-top: 25px; width: 145px; display: inline-block;">
                                <a style="height: 33px;" href="#" class="button js-bookmark<?php if($dataSearch['bookmark']) { echo ' active'; } ?>" data-state="<?php if($dataSearch['bookmark']) { echo '1'; } else {echo '0';} ?>" data-type="<?php echo '5'; ?>" data-id="<?php echo $userData['ID']; ?>" data-no-close="1">
                                    <span style="font-size: 16px; padding-top: 5px;">закладки</span>
                                </a>
                            </div>
                            <div class="btns right" style="margin-left: 25px; cursor: pointer; display: inline-block; float: none; position: relative; top: -2px;">
                                <a style="height: 31px;" href="/user/chat/<?=$userData['ID']?>/" class="button small">сообщение</a>
                            </div>
                        </div><!-- contact-info -->
                        <br>
                    </div>
                </div><!-- content-right -->
                <?php } elseif($dataSearch['type'] == 'uz') { ?>
                <?php if($arrData['type'] == 4) { ?>
                <div class="col-3 content-left" style="padding-right: 0; padding: 0;">
                    <?if($arrData["PIC"]):?>
                        <div class="image left brd" style="width: 100%;">
                            <img style="width: 100%;" src="<?=$arrData["PIC"]?>" alt="<?=$arrData["NAME"]?>" title="<?=$arrData["NAME"]?>" />
                        </div>
                    <?endif?>
                    <div class="btns" style="margin-top: 10px;">
                        <a href="#" class="button js-bookmark<?php if($dataSearch['bookmark']) { echo ' active'; } ?>" data-state="<?php if($dataSearch['bookmark']) { echo '1'; } else {echo '0';} ?>" data-type="<?php echo $arrData["type"]?>" data-id="<?php echo $arrData["ID"]?>" data-no-close="1">
                            <span style="font-size: 18px;">закладки</span>
                        </a>
                    </div>
                </div>
                <div class="col-9 content-right">
                    <div class="news-name">
                        <a href="<?echo $arrData["DETAIL_PAGE_URL"]?>"><span><?echo $arrData["NAME"]?></span></a>
                    </div>
                    <p>
                    <?if($arrData["PROPERTY_YEAR_VALUE"]):?>
                    Год основания:&nbsp;<?echo $arrData["PROPERTY_YEAR_VALUE"];?><br>
                    <?endif?>
                    <?if($arrData["PROPERTY_CITY_VALUE"]):?>
                    Город:&nbsp;<?echo $arrData["PROPERTY_CITY_VALUE"];?><br>
                    <?endif?>
                    <?if($arrData["PROPERTY_SITE_VALUE"]):
                        $arrUrl = explode('?', $arrData["PROPERTY_SITE_VALUE"]);
                    ?>
                    Сайт:&nbsp;<a href="<?=$arrUrl[0];?>"><? if(strlen($arrUrl[0]) > 52) { echo substr($arrUrl[0], 0, 50) . '..'; } else { echo $arrUrl[0]; }?></a><br>
                    <?endif?>
                    <?if($arrData["PROPERTY_PHONE_VALUE"]):?>
                    Телефон:&nbsp;<?echo $arrData["PROPERTY_PHONE_VALUE"];?><br>
                    <?endif?>
                    <?if($arrData["PROPERTY_CITY_VALUE"]):?>
                    <a href="<?echo $arrData["DETAIL_PAGE_URL"]?>?adress=show">Адреса в городе <?echo $arrData["PROPERTY_CITY_VALUE"];?></a><br>
                    <?endif?>
                    </p>
                </div>
                <?php } else { ?>
                <div class="col-3 content-left" style="padding-right: 0; padding: 0;">
                    <?if($arrData["PIC"]):?>
                        <div class="image left brd" style="width: 100%;">
                            <img style="width: 100%;" src="<?=$arrData["PIC"]?>" alt="<?=$arrData["NAME"]?>" title="<?=$arrData["NAME"]?>" />
                        </div>
                    <?endif?>
                    <div class="btns" style="margin-top: 10px;">
                        <a href="#" class="button js-bookmark<?php if($dataSearch['bookmark']) { echo ' active'; } ?>" data-state="<?php if($dataSearch['bookmark']) { echo '1'; } else {echo '0';} ?>" data-type="<?php echo $arrData["type"]?>" data-id="<?php echo $arrData["ID"]?>" data-no-close="1">
                            <span style="font-size: 18px;">закладки</span>
                        </a>
                    </div>
                </div>
                <div class="col-9 content-right">
                    <div class="news-name">
                        <a href="<?php echo $arrData["DETAIL_PAGE_URL"]?>"><span><?php echo $arrData["NAME"]?></span></a>
                    </div>
                    <p>
                    <?if($arrData["ADRESS"]):?>
                    Адрес:&nbsp;<?php echo $arrData["ADRESS"]?><br>
                    <?endif?>
                    <?if($arrData["PROPERTY_SITE_VALUE"]):?>
                    Сайт:&nbsp;<a href="<?php echo $arrData["PROPERTY_SITE_VALUE"]?>"><?php echo $arrData["PROPERTY_SITE_VALUE"]?></a><br>
                    <?endif?>
                    <?if($arrData["PROPERTY_PHONE_VALUE"]):?>
                    Телефон:&nbsp;<?php echo $arrData["PROPERTY_PHONE_VALUE"]?><br>
                    <?endif?>
                    <?if($arrData["PROPERTY_EMAIL_VALUE"]):?>
                    Электронная почта:&nbsp;<a href="mailto:<?php echo $arrData["PROPERTY_EMAIL_VALUE"]?>"><?php echo $arrData["PROPERTY_EMAIL_VALUE"]?></a>
                    <?endif?>
                    </p>
                </div>
                <?php } ?>
                <?php } elseif($dataSearch['type'] == 'news') { ?>
                <div class="col-3 content-left" style="padding-right: 0; padding: 0;">
                <?if($itemList["PIC"]):?>
                    <div class="image left brd" style="width: 100%;">
                        <img style="width: 100%;" src="<? echo $itemList["PIC"]; ?>" alt="<?=$itemList["NAME"]?>" title="<?=$itemList["NAME"]?>" />
                    </div>
                <?endif?>
                </div>
                <div class="col-9 content-right">
                    <div class="date" style="margin-bottom: 7px;"><?php echo $itemList["FORMAT_DATE"]; ?></div>
                    <div class="news-name">
                        <a href="<?=$itemList["DETAIL_PAGE_URL"]?>?sect=news&s=<?php echo $itemList["ID"]; ?>"><span><?=$itemList["NAME"]?></span></a>
                    </div>
                    <p><?php echo $row["DETAIL_TEXT"]; ?></p>
                    <div class="page-rating" data-news="<?=$itemList['ID']?>" data-vuz="<?=$itemList["VUZ_ID"]?>" data-name="<?=$itemList["NAME"]?>" style="margin: 0px 0px 5px 0px; text-align: right; position: absolute; bottom: 14px; right: 0px;">
                        <?php
                        if(sizeof($like_news_cnt[$itemList['ID']])) {
                            if(sizeof($like_news_cnt[$itemList['ID']]) > 4) {
                                $showBaloon = 3;
                            } else {
                                $showBaloon = 4;
                            }
                        ?>
                        <div class="st-baloon" style="right: 100px; height: 52px;">
                        <?php
                        $en = 0;
                        foreach($like_news_cnt[$itemList['ID']] as $news_item) {
                            if($en >= $showBaloon) {
                                echo '<div class="more-baloon"><span data-id-vuz="' . $itemList["VUZ_ID"] . '" data-type="news" data-id="' . $itemList["ID"] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';                                                    break;
                            } else {
                                $en++;
                                $rsUserData = CUser::GetByID($news_item["id_user"]);
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
                            }
                            ?>
                            <a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
                                <div class="image" style="height: 42px;">
                                    <img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
                                </div>
                            </a>
                        <?php } ?>
                        </div>
                        <?php } ?>
                        <a href="#" data-my="<?php if(in_array($itemList['ID'], $like)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($like_news_cnt[$itemList['ID']])) { echo sizeof($like_news_cnt[$itemList['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-news-left'; } else { echo 'js-noauth'; } ?> b-left<?php if(in_array($itemList['ID'], $like)) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px;">
                            <span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($like_news_cnt[$itemList['ID']])) { echo sizeof($like_news_cnt[$itemList['ID']]); } else { echo '0'; } ?></span>
                        </a>
                        <?php
                        if(sizeof($deslike_news_cnt[$itemList['ID']])) {
                            if(sizeof($deslike_news_cnt[$itemList['ID']]) > 4) {
                                $showBaloon = 3;
                            } else {
                                $showBaloon = 4;
                            }
                        ?>
                        <div class="st-baloon" style="right: 0px; height: 52px;">
                        <?php
                        $en = 0;
                        foreach($deslike_news_cnt[$itemList['ID']] as $news_item) {
                            if($en >= $showBaloon) {
                                echo '<div class="more-baloon"><span data-id-vuz="' . $itemList["VUZ_ID"] . '" data-type="news" data-id="' . $itemList["ID"] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';                                                 break;
                            } else {
                                $en++;
                                $rsUserData = CUser::GetByID($news_item["id_user"]);
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
                            }
                            ?>
                            <a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
                                <div class="image" style="height: 42px;">
                                    <img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
                                </div>
                            </a>
                        <?php } ?>
                        </div>
                        <?php } ?>
                        <a href="#" data-my="<?php if(in_array($itemList['ID'], $deslike)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($deslike_news_cnt[$itemList['ID']])) { echo sizeof($deslike_news_cnt[$itemList['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-news-right'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($itemList['ID'], $deslike)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px; margin-left: 5px;">
                            <span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($deslike_news_cnt[$itemList['ID']])) { echo sizeof($deslike_news_cnt[$itemList['ID']]); } else { echo '0'; } ?></span>
                        </a>
                    </div>
                </div>
                <?php } elseif($dataSearch['type'] == 'events') {

                    $like_events = array();
                    $like_events_sql = $dbh->query('SELECT key_event from a_like_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $itemList['vuz_id'] . ' ORDER BY id DESC')->fetchAll();
                    foreach($like_events_sql as $like_events_item) {
                        $like_events[] = $like_events_item['key_event'];
                    }

                    $deslike_events = array();
                    $deslike_events_sql = $dbh->query('SELECT key_event from a_deslike_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $itemList['vuz_id'] . ' ORDER BY id DESC')->fetchAll();
                    foreach($deslike_events_sql as $deslike_events_item) {
                        $deslike_events[] = $deslike_events_item['key_event'];
                    }

                    $like_events_cnt = array();
                    $like_events_cnt_sql = $dbh->query('SELECT * from a_like_events WHERE id_vuz = ' . $itemList['vuz_id'] . ' ORDER BY id DESC')->fetchAll();
                    foreach($like_events_cnt_sql as $like_events_cnt_item) {
                        $like_events_cnt[$like_events_cnt_item['key_event']][] = $like_events_cnt_item;
                    }

                    $deslike_events_cnt = array();
                    $deslike_events_cnt_sql = $dbh->query('SELECT * from a_deslike_events WHERE id_vuz = ' . $itemList['vuz_id'] . ' ORDER BY id DESC')->fetchAll();
                    foreach($deslike_events_cnt_sql as $deslike_events_cnt_item) {
                        $deslike_events_cnt[$deslike_events_cnt_item['key_event']][] = $deslike_events_cnt_item;
                    }

                    $deslike_events_go = array();
                    $deslike_events_go_sql = $dbh->query('SELECT key_event from a_events_go WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $itemList['vuz_id'])->fetchAll();
                    foreach($deslike_events_go_sql as $deslike_events_go_item) {
                        $deslike_events_go[] = $deslike_events_go_item['key_event'];
                    }

                    $deslike_events_go_cnt = array();
                    $deslike_events_go_cnt_sql = $dbh->query('SELECT * from a_events_go WHERE id_vuz = ' . $itemList['vuz_id'])->fetchAll();
                    foreach($deslike_events_go_cnt_sql as $deslike_events_go_cnt_item) {
                        $deslike_events_go_cnt[$deslike_events_go_cnt_item['key_event']][] = $deslike_events_go_cnt_item;
                    }

                ?>
                <script>
                <?php
                echo 'var arrLikeEvents = new Array();' . "\n";
                foreach($like_events as $itemLikeEvents) {
                    echo 'arrLikeEvents.push("' . $itemLikeEvents . '");' . "\n";
                }
                echo 'var arrDeslikeEvents = new Array();' . "\n";
                foreach($deslike_events as $itemDeslikeEvents) {
                    echo 'arrDeslikeEvents.push("' . $itemDeslikeEvents . '");' . "\n";
                }
                echo 'var arrLikeEventsCnt = new Array();' . "\n";
                foreach($like_events_cnt as $idEvent => $arrCnt) {
                    echo 'arrLikeEventsCnt["' . $idEvent . '"] = ' . sizeof($arrCnt) . ';' . "\n";
                }
                echo 'var arrDeslikeEventsCnt = new Array();' . "\n";
                foreach($deslike_events_cnt as $idEvent => $arrCnt) {
                    echo 'arrDeslikeEventsCnt["' . $idEvent . '"] = ' . sizeof($arrCnt) . ';' . "\n";
                }

                echo 'var arrGoEvents = new Array();' . "\n";
                foreach($deslike_events_go as $itemDeslikeEvents) {
                    echo 'arrGoEvents.push("' . $itemDeslikeEvents . '");' . "\n";
                }
                echo 'var arrGoEventsCnt = new Array();' . "\n";
                foreach($deslike_events_go_cnt as $idEvent => $arrCnt) {
                    echo 'arrGoEventsCnt["' . $idEvent . '"] = ' . sizeof($arrCnt) . ';' . "\n";
                }
                ?>
                </script>
                <div class="col-3 content-left" style="padding-right: 0; padding: 0;">
                    <?if($itemList['logo']):?>
                        <div class="image left brd" style="width: 100%;">
                            <img style="width: 100%;" src="<?=$itemList['logo']?>" alt="<?=$itemList['name']?>" title="<?=$itemList['name']?>" />
                        </div>
                    <?endif?>
                </div>
                <div class="col-9 content-right" style="padding-right: 0;">
                    <div class="right" data-vuz="<?=$itemList['vuz_id']?>" data-event="<?=$itemList['event_id']?>" style="position: relative;">
                    <? if($itemList['date']) { ?>
                    <div class="date-ico" style="margin-bottom: 10px;"><span><?=$itemList['DAY']?></span><?=$itemList['MONTH']?></div>
                    <? } ?>
                    <? if($itemList['map']) { ?>
                        <div class="btns text-right" style="text-align: left;"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>
                    <? } ?>
                        <div class="btns text-right" style="margin-top: 15px; text-align: left; position: relative;">
                            <?php
                            if(sizeof($like_events_cnt[$itemList['event_id']])) {
                                if(sizeof($like_events_cnt[$itemList['event_id']]) > 4) {
                                    $showBaloon = 3;
                                } else {
                                    $showBaloon = 4;
                                }
                                ?>
                                <div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
                                    <?php
                                    $en = 0;
                                    foreach($like_events_cnt[$itemList['event_id']] as $events_item) {
                                        if($en >= $showBaloon) {
                                            echo '<div class="more-baloon"><span data-id-vuz="' . $itemList['vuz_id'] . '" data-type="events" data-id="' . $itemList['event_id'] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';                                                  break;
                                        } else {
                                            $en++;
                                            $rsUserData = CUser::GetByID($events_item["id_user"]);
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
                                        }
                                        ?>
                                        <a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
                                            <div class="image">
                                                <img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
                                            </div>
                                        </a>
                                <?php } ?>
                                </div>
                            <?php } ?>
                            <a href="#" data-my="<?php if(in_array($itemList['event_id'], $like_events)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($like_events_cnt[$itemList['event_id']])) { echo sizeof($like_events_cnt[$itemList['event_id']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-event-left'; } else { echo 'js-noauth'; } ?> b-left<?php if(in_array($itemList['event_id'], $like_events)) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px;">
                                <span style="text-decoration: none;"><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($like_events_cnt[$itemList['event_id']])) { echo sizeof($like_events_cnt[$itemList['event_id']]); } else { echo '0'; } ?></span>
                            </a>
                        </div>
                        <div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
                            <?php
                            if(sizeof($deslike_events_cnt[$itemList['event_id']])) {
                                if(sizeof($deslike_events_cnt[$itemList['event_id']]) > 4) {
                                    $showBaloon = 3;
                                } else {
                                    $showBaloon = 4;
                                }
                                ?>
                                <div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
                                    <?php
                                    $en = 0;
                                    foreach($deslike_events_cnt[$itemList['event_id']] as $events_item) {
                                        if($en >= $showBaloon) {
                                            echo '<div class="more-baloon"><span data-id-vuz="' . $itemList['vuz_id'] . '" data-type="events" data-id="' . $itemList['event_id'] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
                                            break;
                                        } else {
                                            $en++;
                                            $rsUserData = CUser::GetByID($events_item["id_user"]);
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
                                        }
                                        ?>
                                        <a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
                                            <div class="image">
                                                <img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
                                            </div>
                                        </a>
                                <?php } ?>
                                </div>
                            <?php } ?>
                            <a href="#" data-my="<?php if(in_array($itemList['event_id'], $deslike_events)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($deslike_events_cnt[$itemList['event_id']])) { echo sizeof($deslike_events_cnt[$itemList['event_id']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-event-right'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($itemList['event_id'], $deslike_events)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px;">
                                <span style="text-decoration: none;"><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($deslike_events_cnt[$itemList['event_id']])) { echo sizeof($deslike_events_cnt[$itemList['event_id']]); } else { echo '0'; } ?></span>
                            </a>
                        </div>
                        <div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
                            <?php
                            if(sizeof($deslike_events_go_cnt[$itemList['event_id']])) {
                                if(sizeof($deslike_events_go_cnt[$itemList['event_id']]) > 4) {
                                    $showBaloon = 3;
                                } else {
                                    $showBaloon = 4;
                                }
                                ?>
                                <div class="st-baloon" style="height: 52px; right: 0px;">
                                <?php
                                $en = 0;
                                foreach($deslike_events_go_cnt[$itemList['event_id']] as $events_item) {
                                        if($en >= $showBaloon) {
                                            echo '<div class="more-baloon"><span data-id-vuz="' . $itemList['vuz_id'] . '" data-type="events" data-id="' . $itemList['event_id'] . '" data-hash="go" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
                                            break;
                                        } else {
                                            $en++;
                                            $rsUserData = CUser::GetByID($events_item["id_user"]);
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
                                        }
                                        ?>
                                        <a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
                                            <div class="image">
                                                <img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
                                            </div>
                                        </a>
                                <?php } ?>
                                </div>
                            <?php } ?>
                            <a href="#" data-lk="0" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-event-go'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($itemList['event_id'], $deslike_events_go)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px;"><span style="text-decoration: none;">Я пойду (<?php echo sizeof($deslike_events_go_cnt[$itemList['event_id']]); ?>)</span></a>
                        </div>
                    </div>
                    <div class="date" style="margin-bottom: 7px;"><?php echo $itemList["FORMAT_DATE"]; ?></div>
                    <div class="news-name">
                        <a href="<?=$itemList['url']?>">
                            <span><?=$itemList['name']?></span>
                        </a>
                    </div>
                    <p>
                        <?php if($itemList['adress']) { ?>
                        Адрес: <?php echo $itemList['adress']; ?><br>
                        <?php } ?>
                        <?php if($itemList['phone']) { ?>
                        Телефон: <?php echo $itemList['phone']; ?><br>
                        <?php } ?>
                        <?php if($itemList['contact']) { ?>
                        Контактное лицо: <?php echo $itemList['contact']; ?><br>
                        <?php } ?>
                        <?php if($itemList['link']) { ?>
                        Ссылка на страницу: <a href="<?php echo $itemList['link']; ?>"><?php echo $itemList['link']; ?></a><br>
                        <?php } ?>
                        <?php if($itemList['message']) { ?>
                            <?php echo $itemList['message']; ?>
                        <?php } ?>
                    </p>
                </div>
                <?php } elseif($dataSearch['type'] == 'ug') { ?>
                <div style="width: 232px;" class="image left brd">
                    <?if($itemList['PIC']):?>
                    <img src="<?=$itemList['PIC']?>" alt="<?=$itemList["NAME"]?>" title="<?=$itemList["NAME"]?>" />
                    <?endif?>
                </div>
                <div class="news-name">
                    <?if($current_section):?>
                    <a href="/ugolok-znaniy/<?=$current_section?>/<?echo $itemList["CODE"]?>/"><span><?echo $itemList["NAME"]?></span></a>
                    <?else:?>
                    <a href="<?echo $itemList["DETAIL_PAGE_URL"]?>"><span><?echo $itemList["NAME"]?></span></a>
                    <?endif;?>
                </div>
                <p>
                    <? echo substr($itemList['PREVIEW_TEXT'], 0, 160) . '..'; ?>
                    <?if($itemList["PROPERTY_SIGN_VALUE"]):?>
                    <div style="text-align: right;">
                        <? echo $itemList["PROPERTY_SIGN_VALUE"]; ?>
                    </div>
                    <?endif;?>
                </p>
                <?php } ?>
            </div>
            <?php
        }
    } elseif(strlen($input['s']) <= 2) {
        echo '<div style="margin-bottom: 25px;">Запрос должен быть не менее 3 символов.</div>';
    } else{
        if($showCount > 0) {
            echo '<div style="margin-bottom: 25px;">По вашему запросу найдено ' . $showCount . ' совпадения в других категориях.</div>';
        } else {
            echo '<div style="margin-bottom: 25px;">По вашему запросу ничего не найдено.</div>';
        }
    }
    ?>
    </div>
    </div>
    </div>
    </div>
</div>
<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>