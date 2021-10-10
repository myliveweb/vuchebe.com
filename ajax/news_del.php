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
	if($input['type'] == 'news') {

		$result = CIBlockElement::Delete($input['id_block']);

	} elseif($input['type'] == 'events') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"ADD_EVENTS"));
		while($ob = $res->GetNext()) {
			$tempEventsMath = explode('#', $ob['VALUE']);
			if($tempEventsMath[14] !== $input['id_block']) {
				$arrEvents[] = $tempEventsMath;
			}
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "ADD_EVENTS", $arrEvents);

	} elseif($input['type'] == 'opendoor') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"OPENDOOR"));
		while($ob = $res->GetNext()) {
			$tempEventsMath = explode('#', $ob['VALUE']);
			if($tempEventsMath[12] !== $input['id_block']) {
				$arrEvents[] = $tempEventsMath;
			}
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "OPENDOOR", $arrEvents);

	} elseif($input['type'] == 'programs') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"PROGRAMS"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			if($n == $input['id_block'])
				continue;
			$finalArray[] = explode('#', $itemEvent);
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "PROGRAMS", $finalArray);

	} elseif($input['type'] == 'corpus') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"DOP_ADRESS"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			if($n == $input['id_block'])
				continue;
			$finalArray[] = explode('#', $itemEvent);
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "DOP_ADRESS", $finalArray);

	} elseif($input['type'] == 'fillials') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"FILLIALS_VUZ"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			if($n == $input['id_block'])
				continue;
			$finalArray[] = explode('#', $itemEvent);
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "FILLIALS_VUZ", $finalArray);

	} elseif($input['type'] == 'units') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"MORE_U"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			if($n == $input['id_block'])
				continue;
			$finalArray[] = explode('#', $itemEvent);
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "MORE_U", $finalArray);

	} elseif($input['type'] == 'obchegitie') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"OBG"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			if($n == $input['id_block'])
				continue;
			$finalArray[] = explode('#', $itemEvent);
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "OBG", $finalArray);

	} elseif($input['type'] == 'ring') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"TIME_RING"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			if($n == $input['id_block'])
				continue;
			$finalArray[] = explode('#', $itemEvent);
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "TIME_RING", $finalArray);

	} elseif($input['type'] == 'sections') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"SECTIONS_VUZ"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			if($n == $input['id_block'])
				continue;
			$finalArray[] = explode('#', $itemEvent);
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "SECTIONS_VUZ", $finalArray);

	} elseif($input['type'] == 'fakultets') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"FAKULTETS"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			if($n == $input['id_block'])
				continue;
			$finalArray[] = explode('#', $itemEvent);
		}

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "FAKULTETS", $finalArray);

	}
}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>