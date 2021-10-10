<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = 0;
$result = array();
$region = 0;

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

if($input['cityId']) {
	$arSelectCity = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION");
	$arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $input['cityId']);
	$resCity = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterCity, false, false, $arSelectCity);
	if($rowCity = $resCity->GetNext()) {

		$region = $rowCity['PROPERTY_REGION_VALUE'];
	}
}

$result['country'] = $input['countryId'];
$result['region'] = $region;
$result['city'] = $input['cityId'];

if(in_array(2, $input['vuzId'])) {

	$geo = array();

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_LONGITUDE", "PROPERTY_LATITUDE", "PROPERTY_PHONE", "PROPERTY_CITY");

	if($region) {
		$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 2, "PROPERTY_REGION" => $region, "!PROPERTY_LONGITUDE" => false, "!PROPERTY_LATITUDE" => false);
	} else {
		$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 2, "PROPERTY_COUNTRY" => $input['countryId'], "!PROPERTY_LONGITUDE" => false, "!PROPERTY_LATITUDE" => false);
	}

	$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
	while($row = $res->Fetch()) {

		if($row["PROPERTY_LOGO_VALUE"]):
			$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
		elseif($row["PREVIEW_PICTURE"]):
			$srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
		elseif($row["DETAIL_PICTURE"]):
			$srcLogo = CFile::GetPath($row["DETAIL_PICTURE"]);
		else:
			$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage.png';
		endif;

	    $resA = CIBlockElement::GetProperty($row["IBLOCK_ID"], $row["ID"], "sort", "asc", array("CODE" => "ADRESS"));
	    if($obA = $resA->GetNext()) {
	    	$adress = $obA['VALUE'];
	    }

		if($row["PROPERTY_CITY_VALUE"]) {
			if(stristr($adress, $row["PROPERTY_CITY_VALUE"]) === false) {
				$adress = $row["PROPERTY_CITY_VALUE"] . ', ' . $adress;
			}
		}

		$url = 'http://vuchebe.com/uchebnye-zavedeniya/universities/' . $row["CODE"] . '/';

		$geo[] = array(preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $adress),
			$row["PROPERTY_LONGITUDE_VALUE"],
			$row["PROPERTY_LATITUDE_VALUE"],
			str_replace(array("'", "«", "»", "&"), '"', $row["NAME"]),
			$url,
			preg_replace('/[^0-9()+ -]/', '', $row["PROPERTY_PHONE_VALUE"]),
			$srcLogo,
			'bal-2.png'
		);
	}

	$result['universities'] = $geo;
}

if(in_array(3, $input['vuzId'])) {

	$geo = array();

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_LONGITUDE", "PROPERTY_LATITUDE", "PROPERTY_PHONE", "PROPERTY_CITY");

	if($region) {
		$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 3, "PROPERTY_REGION" => $region, "!PROPERTY_LONGITUDE" => false, "!PROPERTY_LATITUDE" => false);
	} else {
		$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 3, "PROPERTY_COUNTRY" => $input['countryId'], "!PROPERTY_LONGITUDE" => false, "!PROPERTY_LATITUDE" => false);
	}

	$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
	while($row = $res->Fetch()) {

		if($row["PROPERTY_LOGO_VALUE"]):
			$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
		elseif($row["PREVIEW_PICTURE"]):
			$srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
		elseif($row["DETAIL_PICTURE"]):
			$srcLogo = CFile::GetPath($row["DETAIL_PICTURE"]);
		else:
			$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage.png';
		endif;

	    $resA = CIBlockElement::GetProperty($row["IBLOCK_ID"], $row["ID"], "sort", "asc", array("CODE" => "ADRESS"));
	    if($obA = $resA->GetNext()) {
	    	$adress = $obA['VALUE'];
	    }

		if($row["PROPERTY_CITY_VALUE"]) {
			if(stristr($adress, $row["PROPERTY_CITY_VALUE"]) === false) {
				$adress = $row["PROPERTY_CITY_VALUE"] . ', ' . $adress;
			}
		}

		$url = 'http://vuchebe.com/uchebnye-zavedeniya/colleges/' . $row["CODE"] . '/';

		$geo[] = array(preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $adress),
			$row["PROPERTY_LONGITUDE_VALUE"],
			$row["PROPERTY_LATITUDE_VALUE"],
			str_replace(array("'", "«", "»", "&"), '"', $row["NAME"]),
			$url,
			preg_replace('/[^0-9()+ -]/', '', $row["PROPERTY_PHONE_VALUE"]),
			$srcLogo,
			'bal-6.png'
		);
	}

	$result['colleges'] = $geo;
}

if(in_array(4, $input['vuzId'])) {

	$geo = array();

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_LONGITUDE", "PROPERTY_LATITUDE", "PROPERTY_PHONE", "PROPERTY_CITY");

	if($region) {
		$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 4, "PROPERTY_REGION" => $region, "!PROPERTY_LONGITUDE" => false, "!PROPERTY_LATITUDE" => false);
	} else {
		$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 4, "PROPERTY_COUNTRY" => $input['countryId'], "!PROPERTY_LONGITUDE" => false, "!PROPERTY_LATITUDE" => false);
	}

	$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
	while($row = $res->Fetch()) {

		if($row["PROPERTY_LOGO_VALUE"]):
			$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
		elseif($row["PREVIEW_PICTURE"]):
			$srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
		elseif($row["DETAIL_PICTURE"]):
			$srcLogo = CFile::GetPath($row["DETAIL_PICTURE"]);
		else:
			$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage.png';
		endif;

	    $resA = CIBlockElement::GetProperty($row["IBLOCK_ID"], $row["ID"], "sort", "asc", array("CODE" => "ADRESS"));
	    if($obA = $resA->GetNext()) {
	    	$adress = $obA['VALUE'];
			if($row["IBLOCK_ID"] == 4) {
				$adress = $obA['~VALUE']['TEXT'];
			}
	    }

		if($row["PROPERTY_CITY_VALUE"]) {
			if(stristr($adress, $row["PROPERTY_CITY_VALUE"]) === false) {
				$adress = $row["PROPERTY_CITY_VALUE"] . ', ' . $adress;
			}
		}

		$url = 'http://vuchebe.com/uchebnye-zavedeniya/schools/' . $row["CODE"] . '/';

		$geo[] = array(preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $adress),
			$row["PROPERTY_LONGITUDE_VALUE"],
			$row["PROPERTY_LATITUDE_VALUE"],
			str_replace(array("'", "«", "»", "&"), '"', $row["NAME"]),
			$url,
			preg_replace('/[^0-9()+ -]/', '', $row["PROPERTY_PHONE_VALUE"]),
			$srcLogo,
			'bal-3.png'
		);
	}

	$result['schools'] = $geo;
}

if(in_array(6, $input['vuzId'])) {

	$geo = array();

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_LONGITUDE", "PROPERTY_LATITUDE", "PROPERTY_PHONE", "PROPERTY_CITY");

	if($region) {
		$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 6, "!PROPERTY_YANDEX" => false, "PROPERTY_REGION" => $region);
	} else {
		$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 6, "!PROPERTY_YANDEX" => false, "PROPERTY_COUNTRY" => $input['countryId']);
	}

	$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
	while($row = $res->Fetch()) {

		if($row["PROPERTY_LOGO_VALUE"]):
			$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
		elseif($row["PREVIEW_PICTURE"]):
			$srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
		elseif($row["DETAIL_PICTURE"]):
			$srcLogo = CFile::GetPath($row["DETAIL_PICTURE"]);
		else:
			$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage.png';
		endif;

		$url = 'http://vuchebe.com/uchebnye-zavedeniya/language-class/' . $row["CODE"] . '/';

	    $resGeo = CIBlockElement::GetProperty($row["IBLOCK_ID"], $row["ID"], "sort", "asc", array("CODE" => "YANDEX"));
	    while($obGeo = $resGeo->GetNext())
	    {
	    	if($obGeo['VALUE']) {
	    		$arrGeo = explode('#', $obGeo['VALUE']);

	    		if($row["PROPERTY_CITY_VALUE"]) {
	    			if(stristr($arrGeo[0], $row["PROPERTY_CITY_VALUE"]) === false) {
	    				$adress = $row["PROPERTY_CITY_VALUE"] . ', ' . $arrGeo[0];
	    			} else {
	    				$adress = $arrGeo[0];
	    			}
	    		}

	    		if(is_numeric($arrGeo[2]) && is_numeric($arrGeo[1])) {

					$geo[] = array(preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $adress),
						$arrGeo[2],
						$arrGeo[1],
						preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $row["NAME"]),
						$url,
						preg_replace('/[^0-9()+ -]/', '', $row["PROPERTY_PHONE_VALUE"]),
						$srcLogo,
						'bal-4.png'
					);
				}
	    	}
	    }
	}

	$result['languageClass'] = $geo;

}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>