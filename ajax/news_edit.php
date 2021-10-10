<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

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
	if($input['type'] == 'news') {

		$arrIbNews = array(1 => 31, 2 => 22, 3 => 28, 4 => 29, 6 => 30);
		$ibNews = $arrIbNews[$iblock];

		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_TEXT");
		$arFilter = array("IBLOCK_ID" => $ibNews, "ACTIVE" => "Y", "ID" => $input['id_block'], "PROPERTY_VUZ_ID" => $input['id_vuz']);
		$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext())
		{
			$result['NAME'] = $row['NAME'];
			$result['DETAIL_TEXT'] = $row["~DETAIL_TEXT"];

    		$pathMP = CFile::GetPath($row['PREVIEW_PICTURE']);
    		$morePhoto[] = array('SRC' => $pathMP, 'ID' => $row['PREVIEW_PICTURE']);
			$result["MORE_PHOTO"] = $morePhoto;
		}
	} elseif($input['type'] == 'events') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"ADD_EVENTS"));
		while($ob = $res->GetNext()) {
			$tempEvents = explode('#', $ob['VALUE']);
			if($tempEvents[14] === $input['id_block']) {
				$arrEventsEx = $tempEvents;
				break;
			}
		}

		$result['NAME'] 	= $arrEventsEx[0];
		$result['DATE'] 	= $arrEventsEx[1];
		$result['TIME'] 	= $arrEventsEx[2];
		$result['PHONE'] 	= $arrEventsEx[5];
		$result['COORD'] 	= $arrEventsEx[4];
		$result['CONTACT']  = $arrEventsEx[6];
		$result['LINK'] 	= $arrEventsEx[7];
		$result['ADRESS'] 	= $arrEventsEx[3];
		$result['TEXT'] 	= $arrEventsEx[9];
		$result['COMMENT'] 	= $arrEventsEx[8];
		$result['KEY'] 		= $arrEventsEx[14];

	} elseif($input['type'] == 'opendoor') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"OPENDOOR"));
		while($ob = $res->GetNext()) {
			$tempEvents = explode('#', $ob['VALUE']);
			if($tempEvents[12] === $input['id_block']) {
				$arrEventsEx = $tempEvents;
				break;
			}
		}

		$result['NAME'] 	= $arrEventsEx[0];
		$result['DATE'] 	= $arrEventsEx[1];
		$result['TIME'] 	= $arrEventsEx[2];
		$result['PHONE'] 	= $arrEventsEx[5];
		$result['COORD'] 	= $arrEventsEx[4];
		$result['LINK'] 	= $arrEventsEx[6];
		$result['ADRESS'] 	= $arrEventsEx[3];
		$result['TEXT'] 	= $arrEventsEx[8];
		$result['COMMENT'] 	= $arrEventsEx[7];
		$result['KEY'] 		= $arrEventsEx[12];

	} elseif($input['type'] == 'programs') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"PROGRAMS"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$currEvents = $arrEvents[$input['id_block']];
		$arrEventsEx = explode('#', $currEvents);

		$result['NAME'] = $arrEventsEx[0];
		$result['BASE'] = $arrEventsEx[1];
		$result['UST'] = $arrEventsEx[7];
		$result['CODE'] = $arrEventsEx[45];
		$result['LINK'] = $arrEventsEx[8];
		$result['TEXT'] = $arrEventsEx[41];
		$result['COMMENT'] = $arrEventsEx[40];

		$result['OCH_START'] = $arrEventsEx[9];
		$result['OCH_DUR'] = $arrEventsEx[14];
		$result['OCH_PRICE'] = $arrEventsEx[19];
		$result['OCH_PB'] = $arrEventsEx[34];
		$result['OCH_EKZAMEN'] = $arrEventsEx[24];
		$result['OCH_DOP'] = $arrEventsEx[25];

		$result['OCHZOCH_START'] = $arrEventsEx[10];
		$result['OCHZOCH_DUR'] = $arrEventsEx[15];
		$result['OCHZOCH_PRICE'] = $arrEventsEx[20];
		$result['OCHZOCH_PB'] = $arrEventsEx[35];
		$result['OCHZOCH_EKZAMEN'] = $arrEventsEx[26];
		$result['OCHZOCH_DOP'] = $arrEventsEx[27];

		$result['ZOCH_START'] = $arrEventsEx[11];
		$result['ZOCH_DUR'] = $arrEventsEx[16];
		$result['ZOCH_PRICE'] = $arrEventsEx[21];
		$result['ZOCH_PB'] = $arrEventsEx[36];
		$result['ZOCH_EKZAMEN'] = $arrEventsEx[28];
		$result['ZOCH_DOP'] = $arrEventsEx[29];

		$result['GVD_START'] = $arrEventsEx[12];
		$result['GVD_DUR'] = $arrEventsEx[17];
		$result['GVD_PRICE'] = $arrEventsEx[22];
		$result['GVD_PB'] = $arrEventsEx[37];
		$result['GVD_EKZAMEN'] = $arrEventsEx[30];
		$result['GVD_DOP'] = $arrEventsEx[31];

		$result['DIS_START'] = $arrEventsEx[13];
		$result['DIS_DUR'] = $arrEventsEx[18];
		$result['DIS_PRICE'] = $arrEventsEx[23];
		$result['DIS_PB'] = $arrEventsEx[38];
		$result['DIS_EKZAMEN'] = $arrEventsEx[32];
		$result['DIS_DOP'] = $arrEventsEx[33];

	} elseif($input['type'] == 'corpus') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"DOP_ADRESS"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$currEvents = $arrEvents[$input['id_block']];
		$arrEventsEx = explode('#', $currEvents);

		$result['NAME'] = $arrEventsEx[0];
		$result['ADRESS'] = $arrEventsEx[1];
		$result['PHONE'] = $arrEventsEx[2];
		$result['LINK'] = $arrEventsEx[3];
		$result['COORD'] = $arrEventsEx[4];
		$result['METRO'] = $arrEventsEx[5];
		$result['TEXT'] = $arrEventsEx[7];

	} elseif($input['type'] == 'fillials') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"FILLIALS_VUZ"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$currEvents = $arrEvents[$input['id_block']];
		$arrEventsEx = explode('#', $currEvents);

		$result['NAME'] = $arrEventsEx[0];
		$result['ID_MAIN'] = $arrEventsEx[1];
		$result['ADRESS'] = $arrEventsEx[2];
		$result['COORD'] = $arrEventsEx[3];
		$result['METRO'] = $arrEventsEx[4];
		$result['PHONE'] = $arrEventsEx[5];
		$result['LINK'] = $arrEventsEx[6];
		$result['TEXT'] = $arrEventsEx[8];

	} elseif($input['type'] == 'units') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"MORE_U"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$currEvents = $arrEvents[$input['id_block']];
		$arrEventsEx = explode('#', $currEvents);

		$result['NAME'] = $arrEventsEx[0];
		$result['ID_V'] = $arrEventsEx[1];
		$result['ID_K'] = $arrEventsEx[2];
		$result['ID_S'] = $arrEventsEx[3];
		$result['ADRESS'] = $arrEventsEx[4];
		$result['COORD'] = $arrEventsEx[5];
		$result['METRO'] = $arrEventsEx[6];
		$result['PHONE'] = $arrEventsEx[7];
		$result['LINK'] = $arrEventsEx[8];
		$result['E_MAIL'] = $arrEventsEx[9];
		$result['TEXT'] = $arrEventsEx[10];

	} elseif($input['type'] == 'obchegitie') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"OBG"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$currEvents = $arrEvents[$input['id_block']];
		$arrEventsEx = explode('#', $currEvents);

		$result['ADRESS'] = $arrEventsEx[0];
		$result['COORD'] = $arrEventsEx[1];
		$result['METRO'] = $arrEventsEx[2];
		$result['PHONE'] = $arrEventsEx[3];
		$result['CONTACT'] = $arrEventsEx[4];
		$result['LINK'] = $arrEventsEx[5];
		$result['TEXT'] = $arrEventsEx[7];

	} elseif($input['type'] == 'ring') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"TIME_RING"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$currEvents = $arrEvents[$input['id_block']];
		$arrEventsEx = explode('#', $currEvents);

		$result['NAME'] = $arrEventsEx[0];
		$result['Z_1'] = $arrEventsEx[1];
		$result['Z_2'] = $arrEventsEx[2];
		$result['Z_3'] = $arrEventsEx[3];
		$result['Z_4'] = $arrEventsEx[4];
		$result['Z_5'] = $arrEventsEx[5];
		$result['Z_6'] = $arrEventsEx[6];
		$result['Z_7'] = $arrEventsEx[7];
		$result['Z_8'] = $arrEventsEx[8];
		$result['Z_9'] = $arrEventsEx[9];
		$result['Z_10'] = $arrEventsEx[10];
		$result['Z_11'] = $arrEventsEx[11];
		$result['Z_12'] = $arrEventsEx[12];

	} elseif($input['type'] == 'sections') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"SECTIONS_VUZ"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$currEvents = $arrEvents[$input['id_block']];
		$arrEventsEx = explode('#', $currEvents);

		$result['NAME'] = $arrEventsEx[0];
		$result['PHONE'] = $arrEventsEx[1];
		$result['CONTACT'] = $arrEventsEx[2];
		$result['LINK'] = $arrEventsEx[3];
		$result['COMMENT'] = $arrEventsEx[4];

	} elseif($input['type'] == 'fakultets') {
		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"FAKULTETS"));
		while($ob = $res->GetNext()) {
			$arrEvents[] = $ob['VALUE'];
		}

		$currEvents = $arrEvents[$input['id_block']];
		$arrEventsEx = explode('#', $currEvents);

		$result['NAME'] = $arrEventsEx[0];
		$result['ADRESS'] = $arrEventsEx[1];
		$result['COORD'] = $arrEventsEx[2];
		$result['METRO'] = $arrEventsEx[3];
		$result['PHONE'] = $arrEventsEx[4];
		$result['E_MAIL'] = $arrEventsEx[5];
		$result['LINK'] = $arrEventsEx[6];
		$result['TEXT'] = $arrEventsEx[9];
		$result['COMMENT'] = $arrEventsEx[10];
		$result['SPEC'] = $arrEventsEx[11];

	}
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>