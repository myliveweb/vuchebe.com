<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

global $dbh;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$like = $dbh->query('SELECT COUNT(id_user) as cnt from a_like WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetch();
$deslike = $dbh->query('SELECT COUNT(id_user) as cnt from a_deslike WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetch();
$arResult["LIKE_MY"] = $like['cnt'];
$arResult["DESLIKE_MY"] = $deslike['cnt'];

$likeAll = $dbh->query('SELECT * from a_like WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
$deslikeAll = $dbh->query('SELECT * from a_deslike WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
$arResult["LIKE_ALL"] = $likeAll;
$arResult["DESLIKE_ALL"] = $deslikeAll;

CModule::IncludeModule('iblock');
$arFilter = Array("IBLOCK_ID"=>21, "ACTIVE"=>"Y", "PROPERTY_VUZ_ID" => $arResult["ID"]);
$cnt = CIBlockElement::GetList(false, $arFilter, array('IBLOCK_ID'))->Fetch()['CNT'];
if($cnt) {
	$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE");
	$arFilter = array("IBLOCK_ID" => 21, "ACTIVE" => "Y", "PROPERTY_VUZ_ID" => $arResult["ID"]);
	$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
	if($row = $res->Fetch())
	{
		$arResult["OTZIV_LAST_TIME"] = get_str_time_post(strtotime($row['DATE_CREATE']));
	}
}
$arResult["OTZIV_NUM"] = $cnt;

$arResult["NEWS"] = 0;
$arFilter = Array("IBLOCK_ID"=>30, "ACTIVE"=>"Y", "PROPERTY_VUZ_ID" => $arResult["ID"]);
$cnt_news = CIBlockElement::GetList(false, $arFilter, array('IBLOCK_ID'))->Fetch()['CNT'];
if($cnt_news) {
	$arResult["NEWS"] = $cnt_news;
}

$input = filter_input_array(INPUT_POST);
$search = $input['s'];

$arResult["STUDENTS"] = $dbh->query('SELECT * from a_user_uz WHERE type = 4 AND teacher = 0 AND uz_id = ' . $arResult["ID"] . ' ORDER BY user_name ASC')->fetchAll();
$arResult["MENU_STUDENTS"] = $arResult["STUDENTS"];

if($search) {
    $arrOut = array();
    foreach($arResult["STUDENTS"] as $item) {
        $filter = array("NAME" => $search, "ID" => $item['user_id']);
        $rsUsers = CUser::GetList($by="NAME", $order="ASC", $filter);
        if($userItem = $rsUsers->Fetch()) {
            $arrOut[] = $item;
        }
    }
    $arResult["STUDENTS"] = $arrOut;
}

$arResult["TEACHER"] = $dbh->query('SELECT * from a_user_uz WHERE type = 4 AND teacher = 1 AND uz_id = ' . $arResult["ID"] . ' ORDER BY user_name ASC')->fetchAll();
$arResult["MENU_TEACHER"] = $arResult["TEACHER"];

if($search) {
    $arrOut = array();
    foreach($arResult["TEACHER"] as $item) {
        $filter = array("NAME" => $search, "ID" => $item['user_id']);
        $rsUsers = CUser::GetList($by="NAME", $order="ASC", $filter);
        if($userItem = $rsUsers->Fetch()) {
            $arrOut[] = $item;
        }
    }
    $arResult["TEACHER"] = $arrOut;
}

$arResult["BOOKMARK"] = array();
if($user_id) {
	$arResult["BOOKMARK"] = $dbh->query('SELECT * from a_bookmark WHERE type = 4 AND uz_id = ' . $arResult["ID"] . ' AND user_id = ' . $user_id)->fetch();
}

$arResult["VACANCIES"] = array();

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_TEXT", "PROPERTY_VUZ_ID", "PROPERTY_PHONE", "PROPERTY_EMAIL", "PROPERTY_CONTACTS", "PROPERTY_FAKULTET");
$arFilter = array("IBLOCK_ID" => 24, "ACTIVE" => "Y", "PROPERTY_VUZ_ID" => $arResult["ID"]);
$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
while($row = $res->GetNext())
{
	$arResult["VACANCIES"][] = $row;
}

$arrDopCity = array();

$arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL");
$arFilter = Array("IBLOCK_ID"=>6, "ACTIVE"=>"Y", "NAME"=>$arResult["NAME"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{
	$arFields = $ob->GetFields();

	if($arFields["ID"] == $arResult["ID"])
		continue;

	$arProps = $ob->GetProperties();

    $arSelectCity = array("ID", "NAME", "IBLOCK_ID");
    $arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $arProps["CITY"]["VALUE"]);
    $resCity = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterCity, false, false, $arSelectCity);
    if($rowCity = $resCity->GetNext()) {
	    $cityName = $rowCity["NAME"];
	}

	$arrDopCity[] = array("NAME"=>$cityName, "URL"=>$arFields["DETAIL_PAGE_URL"]);
}
$arResult["CITY_DOP"] = $arrDopCity;
?>