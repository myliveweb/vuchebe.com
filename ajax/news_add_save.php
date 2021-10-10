<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
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

		if($input['name']) {

			$arrIbNews = array(1 => 31, 2 => 22, 3 => 28, 4 => 29, 6 => 30);
			$ibNews = $arrIbNews[$iblock];

			$el = new CIBlockElement;

			$PROP = array();

			$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_COUNTRY", "PROPERTY_REGION", "PROPERTY_CITY");
			$arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "ID" => $input['id_vuz']);
			$resCarusel = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
			if($rowCarusel = $resCarusel->Fetch())
			{
				$PROP['COUNTRY'] = $rowCarusel['PROPERTY_COUNTRY_VALUE'];
				$PROP['REGION'] = $rowCarusel['PROPERTY_REGION_VALUE'];
				$PROP['CITY'] = $rowCarusel['PROPERTY_CITY_VALUE'];
			}

			$PROP['VUZ_ID'] = $input['id_vuz'];

			$arLoadProductArray = Array(
			  "MODIFIED_BY"    => $USER->GetID(),
			  "IBLOCK_SECTION_ID" => false,
			  "IBLOCK_ID"      => $ibNews,
			  "PROPERTY_VALUES"=> $PROP,
			  "NAME"           => $input['name'],
			  "DETAIL_TEXT"    => html_entity_decode($input['message']),
			  "ACTIVE"         => "Y"
			  );

			if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
				$result['id'] = $PRODUCT_ID;
				$result['ib'] = $ibNews;
			} else {
			  	$result = "Error: ".$el->LAST_ERROR;
			}



			$morePhoto = array();

			if($input['images']) {
			    foreach($input['images'] as $path)
			    {
			    	$fullPath = $_SERVER["DOCUMENT_ROOT"] . $path;
		    		$arFile = CFile::MakeFileArray($fullPath);
		        	$morePhoto[] = array('VALUE' => $arFile, 'DESCRIPTION' => '');
			    }

				$PropFileArr['MORE_PHOTO'] = $morePhoto;
				CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, $ibNews, $PropFileArr);
			}
		}

	} elseif($input['type'] == 'events') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"ADD_EVENTS"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
                $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 15, '');

		$tempEvents[0]  = $input['name'];
		$tempEvents[1]  = str_replace('-', '.', $input['dateev']);
		$tempEvents[2]  = str_replace('.', ':', $input['timeev']);
		$tempEvents[3]  = $input['adress'];
		$tempEvents[4]  = $input['coord'];
		$tempEvents[5]  = $input['phoneev'];
		$tempEvents[6]  = $input['contact'];
		$tempEvents[7]  = $input['link'];
		$tempEvents[8]  = $input['message'];
		$tempEvents[9]  = $input['textev'];
		$tempEvents[12] = date('d.m.Y H:i');
		$tempEvents[13] = '';
		$tempEvents[14] = uniqid('', true);

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			$test = explode('#', $itemEvent);
			if($test[0] != '')
				$finalArray[] = $test;
		}
		$finalArray[] = $tempEvents;

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "ADD_EVENTS", $finalArray);

	} elseif($input['type'] == 'opendoor') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"OPENDOOR"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
                $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 13, '');

		$tempEvents[0] = $input['name'];
		$tempEvents[1] = $input['dateev'];
		$tempEvents[2] = $input['timeev'];
		$tempEvents[3] = $input['adress'];
		$tempEvents[4] = $input['coord'];
		$tempEvents[5] = $input['phoneev'];
		$tempEvents[6] = $input['link'];
		$tempEvents[7] = $input['message'];
		$tempEvents[8] = $input['textev'];
		$tempEvents[10] = date('d.m.Y H:i');
		$tempEvents[11] = '';
		$tempEvents[12] = uniqid('', true);

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			$test = explode('#', $itemEvent);
			if($test[0] != '')
				$finalArray[] = $test;
		}
		$finalArray[] = $tempEvents;

		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "OPENDOOR", $finalArray);

	} elseif($input['type'] == 'programs') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"PROGRAMS"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
                $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 48, '');

		$tempEvents[0] = $input['name'];
		$tempEvents[1] = $input['base'];
		$tempEvents[7] = $input['ust'];
		$tempEvents[45] = $input['code'];
		$tempEvents[8] = $input['link'];
		$tempEvents[41] = $input['textpr'];
		$tempEvents[40] = $input['message'];

		$och_triger = '';
		if($input['och_start']
			|| $input['och_dur']
			|| $input['och_price']
			|| $input['och_pb']
			|| $input['och_ekzamen']
			|| $input['och_dop']) {
			$och_triger = 'Очная';
		}

		$tempEvents[2] = $och_triger;
		$tempEvents[9] = $input['och_start'];
		$tempEvents[14] = $input['och_dur'];
		$tempEvents[19] = $input['och_price'];
		$tempEvents[34] = $input['och_pb'];
		$tempEvents[24] = $input['och_ekzamen'];
		$tempEvents[25] = $input['och_dop'];

		$ochzoch_triger = '';
		if($input['ochzoch_start']
			|| $input['ochzoch_dur']
			|| $input['ochzoch_price']
			|| $input['ochzoch_pb']
			|| $input['ochzoch_ekzamen']
			|| $input['ochzoch_dop']) {
			$ochzoch_triger = 'Очно-заочная';
		}

		$tempEvents[3] = $ochzoch_triger;
		$tempEvents[10] = $input['ochzoch_start'];
		$tempEvents[15] = $input['ochzoch_dur'];
		$tempEvents[20] = $input['ochzoch_price'];
		$tempEvents[35] = $input['ochzoch_pb'];
		$tempEvents[26] = $input['ochzoch_ekzamen'];
		$tempEvents[27] = $input['ochzoch_dop'];

		$zoch_triger = '';
		if($input['zoch_start']
			|| $input['zoch_dur']
			|| $input['zoch_price']
			|| $input['zoch_pb']
			|| $input['zoch_ekzamen']
			|| $input['zoch_dop']) {
			$zoch_triger = 'Заочная';
		}

		$tempEvents[4] = $zoch_triger;
		$tempEvents[11] = $input['zoch_start'];
		$tempEvents[16] = $input['zoch_dur'];
		$tempEvents[21] = $input['zoch_price'];
		$tempEvents[36] = $input['zoch_pb'];
		$tempEvents[28] = $input['zoch_ekzamen'];
		$tempEvents[29] = $input['zoch_dop'];

		$gvd_triger = '';
		if($input['gvd_start']
			|| $input['gvd_dur']
			|| $input['gvd_price']
			|| $input['gvd_pb']
			|| $input['gvd_ekzamen']
			|| $input['gvd_dop']) {
			$gvd_triger = 'Группа выходного дня';
		}

		$tempEvents[5] = $gvd_triger;
		$tempEvents[12] = $input['gvd_start'];
		$tempEvents[17] = $input['gvd_dur'];
		$tempEvents[22] = $input['gvd_price'];
		$tempEvents[37] = $input['gvd_pb'];
		$tempEvents[30] = $input['gvd_ekzamen'];
		$tempEvents[31] = $input['gvd_dop'];

		$dis_triger = '';
		if($input['dis_start']
			|| $input['dis_dur']
			|| $input['dis_price']
			|| $input['dis_pb']
			|| $input['dis_ekzamen']
			|| $input['dis_dop']) {
			$dis_triger = 'Дистанционная';
		}

		$tempEvents[6] = $dis_triger;
		$tempEvents[13] = $input['dis_start'];
		$tempEvents[18] = $input['dis_dur'];
		$tempEvents[23] = $input['dis_price'];
		$tempEvents[38] = $input['dis_pb'];
		$tempEvents[32] = $input['dis_ekzamen'];
		$tempEvents[33] = $input['dis_dop'];

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			$finalArray[] = explode('#', $itemEvent);
		}
		$finalArray[] = $tempEvents;
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "PROGRAMS", $finalArray);

	} elseif($input['type'] == 'corpus') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"DOP_ADRESS"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
                $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 11, '');

		$tempEvents[0] = $input['name'];
		$tempEvents[1] = $input['adress'];
		$tempEvents[2] = $input['phonecor'];
		$tempEvents[3] = $input['link'];
		$tempEvents[4] = $input['coord'];
		$tempEvents[5] = $input['metro'];
		$tempEvents[7] = $input['textcor'];
		$tempEvents[8] = date('d.m.Y H:i');
		$tempEvents[9] = '';
		$tempEvents[10] = uniqid('', true);

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			$finalArray[] = explode('#', $itemEvent);
		}
		$finalArray[] = $tempEvents;
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "DOP_ADRESS", $finalArray);

	} elseif($input['type'] == 'fillials') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"FILLIALS_VUZ"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
                $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 12, '');

        $idVuz = trim($input['id_main']);

        if(!is_numeric($idVuz)) {
            preg_match('#vuchebe.com/uchebnye-zavedeniya/universities/(.*)?/#', $idVuz, $matches);

            if($matches[1]) {
                $arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE");
                $arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "CODE" => $matches[1]);
                $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
                if($row = $res->GetNext())
                {
                    $idVuz = $row['ID'];
                } else {
                    $idVuz = '';
                }
            } else {
                $idVuz = '';
            }
        }

		$tempEvents[0] = $input['name'];
		$tempEvents[1] = $idVuz;
		$tempEvents[2] = $input['adress'];
		$tempEvents[3] = $input['coord'];
		$tempEvents[4] = $input['metro'];
		$tempEvents[5] = $input['phonefil'];
		$tempEvents[6] = $input['link'];
		$tempEvents[8] = $input['textfil'];

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			$finalArray[] = explode('#', $itemEvent);
		}
		$finalArray[] = $tempEvents;
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "FILLIALS_VUZ", $finalArray);

	} elseif($input['type'] == 'units') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"MORE_U"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
			    $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 17, '');

        $idV = '';
        $idK = '';
        $idS = '';

        $idVuz = '';

        if(trim($input['id_v'])) {
            $idVuz = trim($input['id_v']);
            $idV = trim($input['id_v']);
        } elseif(trim($input['id_k'])) {
            $idVuz = trim($input['id_k']);
            $idK = trim($input['id_k']);
        } elseif(trim($input['id_s'])) {
            $idVuz = trim($input['id_s']);
            $idS = trim($input['id_s']);
        }

        if($idVuz && !is_numeric($idVuz)) {
            preg_match('#vuchebe.com/uchebnye-zavedeniya/(.*)?/(.*)?/#', $idVuz, $matches);

            if($matches[2]) {
                $arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE");
                $arFilter = array("IBLOCK_ID" => array(2, 3, 4), "ACTIVE" => "Y", "CODE" => $matches[2]);
                $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
                if ($row = $res->GetNext()) {

                    if($row['IBLOCK_ID'] == 2) {
                        $idV = $row['ID'];
                        $idK = '';
                        $idS = '';
                    }

                    if($row['IBLOCK_ID'] == 3) {
                        $idK = $row['ID'];
                        $idV = '';
                        $idS = '';
                    }

                    if($row['IBLOCK_ID'] == 4) {
                        $idS = $row['ID'];
                        $idV = '';
                        $idK = '';
                    }
                }
            }
        }

		$tempEvents[0] = $input['name'];
        $tempEvents[1] = $idV;
        $tempEvents[2] = $idK;
        $tempEvents[3] = $idS;
		$tempEvents[4] = $input['adress'];
		$tempEvents[5] = $input['coord'];
		$tempEvents[6] = $input['metro'];
		$tempEvents[7] = $input['phoneun'];
		$tempEvents[8] = $input['link'];
		$tempEvents[9] = $input['e_mail'];
		$tempEvents[10] = $input['textun'];

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			$finalArray[] = explode('#', $itemEvent);
		}
		$finalArray[] = $tempEvents;
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "MORE_U", $finalArray);

	} elseif($input['type'] == 'obchegitie') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"OBG"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
			    $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 12, '');

		$tempEvents[0] = $input['adress'];
		$tempEvents[1] = $input['coord'];
		$tempEvents[2] = $input['metro'];
		$tempEvents[3] = $input['phoneobg'];
		$tempEvents[4] = $input['contact'];
		$tempEvents[5] = $input['link'];
		$tempEvents[7] = $input['textobg'];

		$finalArray = array();

        foreach ($arrEvents as $n => $itemEvent) {
            $finalArray[] = explode('#', $itemEvent);
        }

		$finalArray[] = $tempEvents;
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "OBG", $finalArray);

	} elseif($input['type'] == 'ring') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"TIME_RING"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
                $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 13, '');

		$tempEvents[0] = $input['name'];
		$tempEvents[1] = $input['z_1'];
		$tempEvents[2] = $input['z_2'];
		$tempEvents[3] = $input['z_3'];
		$tempEvents[4] = $input['z_4'];
		$tempEvents[5] = $input['z_5'];
		$tempEvents[6] = $input['z_6'];
		$tempEvents[7] = $input['z_7'];
		$tempEvents[8] = $input['z_8'];
		$tempEvents[9] = $input['z_9'];
		$tempEvents[10] = $input['z_10'];
		$tempEvents[11] = $input['z_11'];
		$tempEvents[12] = $input['z_12'];

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			$finalArray[] = explode('#', $itemEvent);
		}
		$finalArray[] = $tempEvents;
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "TIME_RING", $finalArray);

	} elseif($input['type'] == 'sections') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"SECTIONS_VUZ"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
                $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 10, '');

		$tempEvents[0] = $input['name'];
		$tempEvents[1] = $input['phonesec'];
		$tempEvents[2] = $input['contact'];
		$tempEvents[3] = $input['link'];
		$tempEvents[4] = $input['message'];

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			$finalArray[] = explode('#', $itemEvent);
		}
		$finalArray[] = $tempEvents;
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "SECTIONS_VUZ", $finalArray);

	} elseif($input['type'] == 'fakultets') {

		$arrEvents = array();

		$res = CIBlockElement::GetProperty($iblock, $input['id_vuz'], array("sort" => "asc"), array("CODE"=>"FAKULTETS"));
		while($ob = $res->GetNext()) {
            $temp = explode('#', $ob['VALUE']);
            if($temp[0])
                $arrEvents[] = $ob['VALUE'];
		}

		$tempEvents = array_fill(0, 17, '');

		$tempEvents[0] = $input['name'];
		$tempEvents[1] = $input['adress'];
		$tempEvents[2] = $input['coord'];
		$tempEvents[3] = $input['metro'];
		$tempEvents[4] = $input['phonefak'];
		$tempEvents[5] = $input['email'];
		$tempEvents[6] = $input['link'];
		$tempEvents[9] = $input['textfak'];
		$tempEvents[10] = $input['message'];
		$tempEvents[11] = $input['spec'];

		$finalArray = array();
		foreach($arrEvents as $n => $itemEvent) {
			$finalArray[] = explode('#', $itemEvent);
		}
		$finalArray[] = $tempEvents;
		CIBlockElement::SetPropertyValueCode($input['id_vuz'], "FAKULTETS", $finalArray);

	}
}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>