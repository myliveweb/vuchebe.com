<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$iblock = $input['iblock'];
if(!$iblock)
	$iblock = 2;

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$isAdmin = 0;
$arrAdmins = array();

$resAdmins = CIBlockElement::GetProperty($iblock, $input['id_vuz'], "sort", "asc", array("CODE" => "ADMINS"));
while ($obAdmins = $resAdmins->GetNext()) {
    $arrAdmins[] = $obAdmins['VALUE'];
}

if(in_array($user_id, $arrAdmins) || isEdit())
	$isAdmin = 1;

if($isAdmin) {
	if($input['type'] == 'first') {
		if($iblock == 6) {
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID", "PROPERTY_FULL_NAME", "PROPERTY_ABBR", "PROPERTY_YEAR", "PROPERTY_COUNTRY", "PROPERTY_CITY", "PROPERTY_PHONE", "PROPERTY_PHONE_PK", "PROPERTY_EMAIL", "PROPERTY_EMAIL_PK", "PROPERTY_SITE", "PROPERTY_ELECTRON_PR", "PROPERTY_COST_HOUR", "PROPERTY_COST_MONTH", "PROPERTY_LICENCE", "PROPERTY_TESTLESSON", "PROPERTY_GROUP_CURS", "PROPERTY_CHILDREN", "PROPERTY_PAYMENT");
			$arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "ID" => $input['id_vuz']);
			$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
			if($row = $res->GetNext())
			{
				$result['NAME']        = $row['NAME'];
				$result['SECTION']     = $row['IBLOCK_SECTION_ID'];
				$result['FULL_NAME']   = $row['PROPERTY_FULL_NAME_VALUE']['TEXT'];
				$result['ABBR']        = $row['PROPERTY_ABBR_VALUE'];
				$result['YEAR']        = $row['PROPERTY_YEAR_VALUE'];
				$result['COUNTRY']     = $row['PROPERTY_COUNTRY_VALUE'];
				$result['CITY']        = $row['PROPERTY_CITY_VALUE'];
				$result['PHONE']       = $row['PROPERTY_PHONE_VALUE'];
				$result['PHONE_PK']    = $row['PROPERTY_PHONE_PK_VALUE'];
				$result['EMAIL']       = $row['PROPERTY_EMAIL_VALUE'];
				$result['EMAIL_PK']    = $row['PROPERTY_EMAIL_PK_VALUE'];
				$result['SITE']        = $row['PROPERTY_SITE_VALUE'];
				$result['ELECTRON_PR'] = $row['PROPERTY_ELECTRON_PR_VALUE'];

				$result['COST_HOUR']   = $row['PROPERTY_COST_HOUR_VALUE'];
				$result['COST_MONTH']  = $row['PROPERTY_COST_MONTH_VALUE'];

				$result['LICENCE']     = $row['PROPERTY_LICENCE_VALUE'];
				$result['TESTLESSON']  = $row['PROPERTY_TESTLESSON_VALUE'];
				$result['GROUP_CURS']  = $row['PROPERTY_GROUP_CURS_VALUE'];
				$result['CHILDREN']    = $row['PROPERTY_CHILDREN_VALUE'];
				$result['PAYMENT']     = $row['PROPERTY_PAYMENT_VALUE'];

			    $result['ADRESS'] = array();
			    $res = CIBlockElement::GetProperty(6, $input['id_vuz'], "sort", "asc", array("CODE" => "ADRESS"));
			    while ($ob = $res->GetNext())
			    {
			        $result['ADRESS'][] = $ob['VALUE'];
			    }

				$result['SECTIONS'] = array();

				$arSelectS = array("ID", "NAME", "IBLOCK_ID");
				$arFilterS = array("IBLOCK_ID" => 6, "ACTIVE" => "Y");
				$SectList = CIBlockSection::GetList(array("NAME" => "ASC"), $arFilterS, false, $arSelectS, false);
				while($ar_result = $SectList->GetNext()) {
				    $result['SECTIONS'][$ar_result['ID']] = $ar_result['NAME'];
				}

			}
		} else {
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_FULL_NAME", "PROPERTY_ABBR", "PROPERTY_YEAR", "PROPERTY_COUNTRY", "PROPERTY_CITY", "PROPERTY_PHONE", "PROPERTY_PHONE_PK", "PROPERTY_EMAIL", "PROPERTY_EMAIL_PK", "PROPERTY_SITE", "PROPERTY_ELECTRON_PR", "PROPERTY_ADRESS");
			$arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "ID" => $input['id_vuz']);
			$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
			if($row = $res->GetNext())
			{
				$result['NAME'] = $row['NAME'];
				$result['FULL_NAME'] = $row['PROPERTY_FULL_NAME_VALUE']['TEXT'];
				$result['ABBR'] = $row['PROPERTY_ABBR_VALUE'];
				$result['YEAR'] = $row['PROPERTY_YEAR_VALUE'];
				$result['COUNTRY'] = $row['PROPERTY_COUNTRY_VALUE'];
				$result['CITY'] = $row['PROPERTY_CITY_VALUE'];
				$result['PHONE'] = $row['PROPERTY_PHONE_VALUE'];
				$result['PHONE_PK'] = $row['PROPERTY_PHONE_PK_VALUE'];
				$result['EMAIL'] = $row['PROPERTY_EMAIL_VALUE'];
				$result['EMAIL_PK'] = $row['PROPERTY_EMAIL_PK_VALUE'];
				$result['SITE'] = $row['PROPERTY_SITE_VALUE'];
				$result['ELECTRON_PR'] = $row['PROPERTY_ELECTRON_PR_VALUE'];

				if($iblock == 4) {
					$result['ADRESS'] = $row['PROPERTY_ADRESS_VALUE']['TEXT'];
				} else {
					$result['ADRESS'] = $row['PROPERTY_ADRESS_VALUE'];
				}
			}
		}

		$result['COUNTRY_ARR'] = array();
	    $arSelectCountry = array("ID", "NAME", "IBLOCK_ID");
	    $arFilterCountry = array("IBLOCK_ID" => 32, "ACTIVE" => "Y");
	    $resCountry = CIBlockSection::GetList(array("ID" => "ASC"), $arFilterCountry, false, $arSelectCountry);
	    while($rowCountry = $resCountry->GetNext()) {
		    $result['COUNTRY_ARR'][] = $rowCountry;
		}

		$result['CITY_ARR'] = array();
	    $arSelectCity = array("ID", "NAME", "IBLOCK_ID");
	    $arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_NALICHIE" => "Y");
	    $resCity = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterCity, false, false, $arSelectCity);
	    while($rowCity = $resCity->GetNext()) {
		    $result['CITY_ARR'][] = $rowCity;
		}

	} elseif($input['type'] == 'soc') {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_VK", "PROPERTY_FB", "PROPERTY_INSTA", "PROPERTY_YOUTUBE", "PROPERTY_TWITTER", "PROPERTY_OK", "PROPERTY_WIKI");
		$arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "ID" => $input['id_vuz']);
		$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext())
		{
			$result['VK'] = $row['PROPERTY_VK_VALUE'];
			$result['FB'] = $row['PROPERTY_FB_VALUE'];
			$result['INSTA'] = $row['PROPERTY_INSTA_VALUE'];
			$result['YOUTUBE'] = $row['PROPERTY_YOUTUBE_VALUE'];
			$result['TWITTER'] = $row['PROPERTY_TWITTER_VALUE'];
			$result['OK'] = $row['PROPERTY_OK_VALUE'];
			$result['WIKI'] = $row['PROPERTY_WIKI_VALUE'];
		}
	} elseif($input['type'] == 'license') {

		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_GOV", "PROPERTY_GA_NUM", "PROPERTY_GA_START", "PROPERTY_GA_END", "PROPERTY_GA_SVID", "PROPERTY_LICESE_NUM", "PROPERTY_LICESE_START", "PROPERTY_LICESE_END", "PROPERTY_LICESE_LINK", "PROPERTY_AKK_NUM", "PROPERTY_AKK_START", "PROPERTY_AKK_END", "PROPERTY_GA_LINK", "PROPERTY_UCHREDITEL", "PROPERTY_RUKOVODSTVO", "PROPERTY_FIO_RUKOVODSTVO");
		$arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "ID" => (int) $input['id_vuz']);
		$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext())
		{
			$result['GOV'] = $row['PROPERTY_GOV_VALUE'];
			$result['GA_NUM'] = $row['PROPERTY_GA_NUM_VALUE'];
			$result['GA_START'] = $row['PROPERTY_GA_START_VALUE'];
			$result['GA_END'] = $row['PROPERTY_GA_END_VALUE'];
			$result['GA_SVID'] = $row['PROPERTY_GA_SVID_VALUE']['TEXT'];
			$result['LICESE_NUM'] = $row['PROPERTY_LICESE_NUM_VALUE'];
			$result['LICESE_START'] = $row['PROPERTY_LICESE_START_VALUE'];
			$result['LICESE_END'] = $row['PROPERTY_LICESE_END_VALUE'];
			$result['LICESE_LINK'] = $row['PROPERTY_LICESE_LINK_VALUE'];
			$result['AKK_NUM'] = $row['PROPERTY_AKK_NUM_VALUE'];
			$result['AKK_START'] = $row['PROPERTY_AKK_START_VALUE'];
			$result['AKK_END'] = $row['PROPERTY_AKK_END_VALUE'];
			$result['GA_LINK'] = $row['PROPERTY_GA_LINK_VALUE'];
			$result['UCHREDITEL'] = $row['PROPERTY_UCHREDITEL_VALUE'];
			$result['RUKOVODSTVO'] = $row['PROPERTY_RUKOVODSTVO_VALUE'];
			$result['FIO_RUKOVODSTVO'] = $row['PROPERTY_FIO_RUKOVODSTVO_VALUE'];
		}
	} elseif($input['type'] == 'service') {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PARKING", "PROPERTY_WIFI", "PROPERTY_STOLOVAYA", "PROPERTY_MEDPUNKT", "PROPERTY_SPORT", "PROPERTY_BOOK", "PROPERTY_WAR", "PROPERTY_MUSEUM", "PROPERTY_WATER", "PROPERTY_AKT_ZAL");
		$arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "ID" => $input['id_vuz']);
		$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext())
		{
			$result['PARKING'] = $row['PROPERTY_PARKING_VALUE'];
			$result['WIFI'] = $row['PROPERTY_WIFI_VALUE'];
			$result['STOLOVAYA'] = $row['PROPERTY_STOLOVAYA_VALUE'];
			$result['MEDPUNKT'] = $row['PROPERTY_MEDPUNKT_VALUE'];
			$result['SPORT'] = $row['PROPERTY_SPORT_VALUE'];
			$result['BOOK'] = $row['PROPERTY_BOOK_VALUE'];
			$result['WAR'] = $row['PROPERTY_WAR_VALUE'];
			$result['MUSEUM'] = $row['PROPERTY_MUSEUM_VALUE'];
			$result['WATER'] = $row['PROPERTY_WATER_VALUE'];
			$result['AKT_ZAL'] = $row['PROPERTY_AKT_ZAL_VALUE'];
		}
	} elseif($input['type'] == 'history') {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_HISTORY_VUZ");
		$arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "ID" => $input['id_vuz']);
		$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext())
		{
			$result['HISTORY'] = $row['PROPERTY_HISTORY_VUZ_VALUE']['TEXT'];
		}
	}
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>