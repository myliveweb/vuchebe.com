<?php
CModule::IncludeModule('iblock');

$getCookie = filter_input_array(INPUT_COOKIE);

$cookie_country = 0;
$cookie_abc = '';
$cookie_city = 0;
$cookie_utm = 0;

$city_name = '';
$region = '';
$topcity = 0;

$cnt_city = 0;

$hide_cookies = 0;

if(isset($getCookie['PANEL_COUNTRY']) && $getCookie['PANEL_COUNTRY'] && !$cookie_country)
	$cookie_country = $getCookie['PANEL_COUNTRY'];

if(isset($getCookie['PANEL_ABC']) && $getCookie['PANEL_ABC'] && !$cookie_abc)
	$cookie_abc = $getCookie['PANEL_ABC'];

if(isset($getCookie['PANEL_CITY']) && $getCookie['PANEL_CITY'] && !$cookie_city) {
	$cookie_city = $getCookie['PANEL_CITY'];

	$arSelectCity = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION", "PROPERTY_TOPCITY");
	$arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $cookie_city);
	$resCity = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterCity, false, false, $arSelectCity);
	if($rowCity = $resCity->GetNext()) {
		$region = $rowCity['PROPERTY_REGION_VALUE'];

		if($rowCity['PROPERTY_TOPCITY_VALUE'] == "Y") {
			$topcity = 1;
		}
	}
}

if(!$cookie_country) {
	$cookie_country = 79;
}

if(isset($getCookie['PANEL_CITY_NAME']) && $getCookie['PANEL_CITY_NAME'] && !$city_name) {
	$city_name = $getCookie['PANEL_CITY_NAME'];
}

$arSelectCountry = array("ID", "NAME", "IBLOCK_ID");
$arFilterCountry = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $cookie_country);
$resCountry = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilterCountry, false, $arSelectCountry);
if($rowCountry = $resCountry->GetNext()) {
    $country_name = $rowCountry['NAME'];
}

if(isset($getCookie['PANEL_UTM']) && $getCookie['PANEL_UTM'])
	$cookie_utm = $getCookie['PANEL_UTM'];
else
	$cookie_utm = 3;

if(isset($getCookie['PANEL_HIDE_COOKIES']) && $getCookie['PANEL_HIDE_COOKIES']) {
	$hide_cookies = $getCookie['PANEL_HIDE_COOKIES'];
}

$_SESSION['PANEL']['COUNTRY'] 		= $cookie_country;
$_SESSION['PANEL']['COUNTRY_NAME'] 	= $country_name;
$_SESSION['PANEL']['ABC'] 			= $cookie_abc;
$_SESSION['PANEL']['CITY'] 			= $cookie_city;
$_SESSION['PANEL']['CITY_NAME'] 	= $city_name;
$_SESSION['PANEL']['UTM'] 			= $cookie_utm;
$_SESSION['PANEL']['REGION'] 		= $region;
$_SESSION['PANEL']['TOPCITY'] 		= $topcity;
$_SESSION['PANEL']['HIDE_COOKIES'] 	= $hide_cookies;
?>