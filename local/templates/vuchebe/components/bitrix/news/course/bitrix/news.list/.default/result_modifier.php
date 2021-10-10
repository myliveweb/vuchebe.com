<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

global $dbh;
CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$cnt = 20;

$arrNews = array();
$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL", "PROPERTY_YEAR");

if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
	$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
	$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
	$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
else
	$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y");

$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize" => $cnt), $arSelect);
while($rowCarusel = $resCarusel->Fetch())
{
	$arrTemp = array();

	$arrTemp['ID'] = $rowCarusel['ID'];
	$arrTemp['IBLOCK_ID'] = $rowCarusel['IBLOCK_ID'];
	$arrTemp['NAME'] = $rowCarusel['NAME'];

	$arrTemp['URL'] = '/uchebnye-zavedeniya/language-class/' . $rowCarusel["CODE"] . '/';

	if($rowCarusel["PROPERTY_LOGO_VALUE"]):
		$srcLogo = CFile::GetPath($rowCarusel["PROPERTY_LOGO_VALUE"]);
	elseif($rowCarusel["DETAIL_PICTURE"]):
		$srcLogo = CFile::GetPath($rowCarusel["DETAIL_PICTURE"]);
	else:
		$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
	endif;

	$arrTemp['IMG']	= $srcLogo;

	$arrAdress = explode('&', $rowCarusel["PROPERTY_ADRESS_VALUE"]);
	$arrTemp['ADRESS']	= $arrAdress[0];

	$arrUrl = explode('?', $rowCarusel["PROPERTY_SITE_VALUE"]);
	$arrTemp['SITE']	= $arrUrl[0];

	$arrTemp['PHONE']	= $rowCarusel["PROPERTY_PHONE_VALUE"];
	$arrTemp['EMAIL']	= $rowCarusel["PROPERTY_EMAIL_VALUE"];
    $arrTemp['YEAR']	= $rowCarusel["PROPERTY_YEAR_VALUE"];

	$arrNews[] = $arrTemp;
}

$arResult = array();

$arResult['DATA'] = $arrNews;
$arResult["CNT"] = $cnt;
?>