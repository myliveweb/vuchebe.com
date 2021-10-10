<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = '';
$result = 0;

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

		if($input['name']) {

			$el = new CIBlockElement;

			$arLoadProductArray = Array(
			  "MODIFIED_BY"    => $USER->GetID(),
			  //"IBLOCK_SECTION" => $input['city'],
			  "NAME"           => $input['name'],
			  "ACTIVE"         => "Y"
			  );

			$result = $el->Update($input['id_vuz'], $arLoadProductArray);

			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "FULL_NAME", array(array("TYPE"=>"html", "TEXT"=>html_entity_decode($input['namefull']))));
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "ABBR", $input['abbr']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "YEAR", $input['year']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "COUNTRY", $input['country']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "PHONE", $input['phone']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "PHONE_PK", $input['phonepk']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "EMAIL", $input['email']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "EMAIL_PK", $input['emailpk']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "SITE", $input['site']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "ELECTRON_PR", $input['epk']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "ADRESS", $input['adress']);

			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "COST_HOUR", $input['hours']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "COST_MONTH", $input['month']);

			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "LICENCE", $input['license']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "TESTLESSON", $input['free']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "GROUP_CURS", $input['group']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "CHILDREN", $input['kind']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "PAYMENT", $input['pay']);
			CIBlockElement::SetPropertyValueCode($input['id_vuz'], "CITY", $input['city']);

			$arSelectS = array("ID", "NAME", "IBLOCK_ID");
			$arFilterS = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "ID" => $input['city']);
			$SectList = CIBlockSection::GetList(array("NAME" => "ASC"), $arFilterS, false, $arSelectS, false);
			if($ar_result = $SectList->GetNext()) {
			    CIBlockElement::SetPropertyValueCode($input['id_vuz'], "CITY", $ar_result['NAME']);
			}
		}

	} elseif($input['type'] == 'soc') {

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "VK", $input['vk']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "FB", $input['fb']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "INSTA", $input['inst']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "YOUTUBE", $input['you']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "TWITTER", $input['tw']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "OK", $input['ok']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "WIKI", $input['wik']);

	} elseif($input['type'] == 'license') {

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "GOV", $input['gov']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "GA_NUM", $input['ganum']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "GA_START", $input['gastart']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "GA_END", $input['gaend']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "GA_SVID", array(array("TYPE"=>"html", "TEXT"=>html_entity_decode($input['gasvid']))));
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "LICESE_NUM", $input['licesenum']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "LICESE_START", $input['licesestart']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "LICESE_END", $input['liceseend']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "LICESE_LINK", $input['liceselink']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "AKK_NUM", $input['akknum']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "AKK_START", $input['akkstart']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "AKK_END", $input['akkend']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "GA_LINK", $input['galink']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "UCHREDITEL", $input['uchreditel']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "RUKOVODSTVO", $input['rukovodstvo']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "FIO_RUKOVODSTVO", $input['fiorukovodstvo']);

	} elseif($input['type'] == 'service') {

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "PARKING", $input['park']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "WIFI", $input['wifi']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "STOLOVAYA", $input['stol']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "MEDPUNKT", $input['medpunkt']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "SPORT", $input['sport']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "BOOK", $input['book']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "WAR", $input['war']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "MUSEUM", $input['muzey']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "WATER", $input['water']);
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "AKT_ZAL", $input['aktzal']);

	} elseif($input['type'] == 'history') {
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "HISTORY_VUZ", $input['message']);
	}
}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>