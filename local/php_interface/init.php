<?php
CModule::IncludeModule('iblock');

require_once 'buildlist.php'; // Пользовательские свойства

function translit($str) {
    $russian = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ', '.', ',');
    $translit = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya', '-', '-', '-');
    return str_replace($russian, $translit, $str);
}

function get_str_time($time_work) { // Форматированный вывод времени (чат)
    $month = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
    $str_time = '';
    $cur_time = time();
    $diff = $cur_time - $time_work;
    $sutki = 60 * 60 * 24;
    $sutki_1 = 60 * 60 * 24 * 2;
    if($diff < $sutki) {
        $str_time = date('H:i', $time_work);
    } elseif($diff > $sutki && $diff < $sutki_1) {
        $str_time = 'вчера ' . date('H:i', $time_work);
    } else {
        $month_int = (int) date('n', $time_work);
        $str_time = date('j', $time_work) . ' ' . $month[$month_int] . ' ' . date('Y', $time_work) . ' ' . date('H:i', $time_work);
    }

    return $str_time;
}

function get_str_time_post($time_work) { // Форматированный вывод времени (отзывы)
    $month = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
    $month_int = (int) date('n', $time_work);
    $str_time = date('j', $time_work) . ' ' . $month[$month_int] . ' ' . date('Y', $time_work) . ' ' . date('H:i', $time_work);
    return $str_time;
}

require_once($_SERVER["DOCUMENT_ROOT"].'/ajax/lp.php');

// PDO база данных подключение
$initArr = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+03:00'", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
try {
    $dbh = new PDO('mysql:host=localhost;dbname=admin_vuchebe', $user, $pass, $initArr);
    $dbh->exec("set names utf8mb4");
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

mb_internal_encoding("UTF-8");

function mb_ucfirst($text) {
    return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
}

// Событие для правильного отображения Online статуса
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
$eventManager = EventManager::getInstance();
$eventManager->addEventHandler('main', 'OnBeforeProlog', 'setUserLastActivityDate');
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'OnAfterIBlockElementUpdateHandler');
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'OnBeforeIBlockElementUpdateHandler');
/**
 * @global $USER
 * @return void
 */
function setUserLastActivityDate() {
    if (!Loader::includeModule('socialnetwork')) {
        return;
    }
    global $USER;
    if ($USER->IsAuthorized()) {
        CUser::SetLastActivityDate($USER->GetID());
    }
}

function OnAfterIBlockElementUpdateHandler(&$arFields) {
    if($arFields["RESULT"]) {
        if($arFields["IBLOCK_ID"] == 40) {

            global $USER, $dbh;

            $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CLOSE");
            $arFilter = array("IBLOCK_ID" => $arFields["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arFields["ID"]);
            $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
            if ($row = $res->Fetch()) {
                if ($row['PROPERTY_CLOSE_VALUE'] == 'Y') {
                    $user_id = $USER->GetID();
                } else {
                    $user_id = 0;
                }
                $stmt = $dbh->prepare("UPDATE a_chat_support SET del_owner = :del_owner WHERE group_chat = :group_chat");
                $stmt->bindParam(':del_owner', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':group_chat', $arFields["ID"], PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
    else
        AddMessage2Log("Ошибка изменения записи ".$arFields["ID"]." (".$arFields["RESULT_MESSAGE"].").");
}

function OnBeforeIBlockElementUpdateHandler(&$arFields) {

    if($arFields["IBLOCK_ID"] == 34 || $arFields["IBLOCK_ID"] == 35) {

        global $USER, $dbh;

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_MODERATION", "PROPERTY_OWNER");
        $arFilter = array("IBLOCK_ID" => $arFields["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arFields["ID"]);
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        if ($row = $res->Fetch()) {

            $test = '';

            if($row['IBLOCK_ID'] == 34) {
                foreach($arFields['PROPERTY_VALUES'][478] as $arr) {
                    $test = $arr['VALUE'];
                }
            } elseif($row['IBLOCK_ID'] == 35) {
                foreach($arFields['PROPERTY_VALUES'][479] as $arr) {
                    $test = $arr['VALUE'];
                }
            }

            if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $test == 'Y') {
                setBannerHistory(1, $row['ID'], $row['PROPERTY_OWNER_VALUE'], 0);
            }
        }
    }
}

function getLawPopUpStr($politic) {
    $arrLaw = array();
    $arSelectLaw = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_SECOND_NAME");
    $arFilterLaw = array("IBLOCK_ID" => 41, "ACTIVE" => "Y", $politic => "Y");
    $resLaw = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), $arFilterLaw, false, false, $arSelectLaw);
    while($rowLaw = $resLaw->GetNext()) {
        $arrLaw[] = $rowLaw;
    }

    $lavStr = 'Я соглашаюсь с условиями';

    if(sizeof($arrLaw) > 1) {
        foreach($arrLaw as $idLav => $itemLav) {
            $numLav = $idLav + 1;
            if(sizeof($arrLaw) == $numLav) {
                $lavStr .= ' и <a href="#" class="lav-href js-lav-popup" data-popup="' . $itemLav["ID"] . '">' . $itemLav["PROPERTY_SECOND_NAME_VALUE"] . '</a>.';
            } elseif($numLav == 1) {
                $lavStr .= ' <a href="#" class="lav-href js-lav-popup" data-popup="' . $itemLav["ID"] . '">' . $itemLav["PROPERTY_SECOND_NAME_VALUE"] . '</a>';
            } else {
                $lavStr .= ', <a href="#" class="lav-href js-lav-popup" data-popup="' . $itemLav["ID"] . '">' . $itemLav["PROPERTY_SECOND_NAME_VALUE"] . '</a>';
            }
        }
    } elseif(sizeof($arrLaw) == 1) {
        $lavStr .= ' <a href="#" class="lav-href js-lav-popup" data-popup="' . $arrLaw[0]["ID"] . '">' . $arrLaw[0]["PROPERTY_SECOND_NAME_VALUE"] . '</a>.';
    }

    return $lavStr;
}

require_once 'link_user.php'; //Функции для работы с пользовательскими ссылками
require_once 'banner.php'; // Функции для работы с баннерами
require_once 'mailsender.php'; // Функции для работы с почтой (резерв)
require_once 'group.php'; // Функции для работы с правами и группами

?>