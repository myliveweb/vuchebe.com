<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ВУЗы");?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news",
	"universities",
	array(
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"CHECK_DATES" => "Y",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "N",
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "PREVIEW_PICTURE",
			3 => "IBLOCK_ID",
			4 => "",
		),
		"DETAIL_PAGER_SHOW_ALL" => "N",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "FB",
			1 => "ID_UCHEBA",
			2 => "ID_HEAD_VUZ",
			3 => "INSTA",
			4 => "TWITTER",
			5 => "WIFI",
			6 => "YOUTUBE",
			7 => "ABBR",
			8 => "ADMINS",
			9 => "ADRESS",
			10 => "AKK_NUM",
			11 => "AKK_END",
			12 => "AKK_START",
			13 => "AKT_ZAL",
			14 => "WATER",
			15 => "BOOK",
			16 => "MEST",
			17 => "VK",
			18 => "WAR",
			19 => "YEAR",
			20 => "CITY",
			21 => "GA_NUM",
			22 => "GA_END",
			23 => "GA_START",
			24 => "OPENDOOR",
			25 => "RUKOVODSTVO",
			26 => "DOP_LINK",
			27 => "DOP_ADRESS",
			28 => "MORE_U",
			29 => "HISTORY_VUZ",
			30 => "LICESE_NUM",
			31 => "LICESE_END",
			32 => "LICESE_START",
			33 => "MEDPUNKT",
			34 => "MUSEUM",
			35 => "DESLIKE",
			36 => "LIKE",
			37 => "OBG",
			38 => "OK",
			39 => "PARKING",
			40 => "FULL_NAME",
			41 => "PROGRAMS",
			42 => "BALL",
			43 => "TIME_RING",
			44 => "SITE",
			45 => "SITE_CLEAN",
			46 => "GA_SVID",
			47 => "SECTIONS_VUZ",
			48 => "ADD_EVENTS",
			49 => "SPORT",
			50 => "LICESE_LINK",
			51 => "GA_LINK",
			52 => "WIKI",
			53 => "URL",
			54 => "PL",
			55 => "PRICE",
			56 => "STOLOVAYA",
			57 => "COUNTRY",
			58 => "PHONE",
			59 => "PHONE_PK",
			60 => "GOV",
			61 => "UCHREDITEL",
			62 => "FIO_RUKOVODSTVO",
			63 => "FAKULTETS",
			64 => "FILLIALS_VUZ",
			65 => "EMAIL",
			66 => "EMAIL_PK",
			67 => "ELECTRON_PR",
			68 => "LONGITUDE",
			69 => "LATITUDE",
			70 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "2",
		"IBLOCK_TYPE" => "catalogs",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "ID",
			1 => "CODE",
			2 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "ADRESS",
			1 => "SITE",
			2 => "PHONE",
			3 => "EMAIL",
			4 => "",
		),
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PREVIEW_TRUNCATE_LEN" => "",
		"SEF_FOLDER" => "/uchebnye-zavedeniya/universities/",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SORT_BY1" => "ID",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_RATING" => "N",
		"USE_REVIEW" => "N",
		"USE_RSS" => "N",
		"USE_SEARCH" => "N",
		"USE_SHARE" => "N",
		"COMPONENT_TEMPLATE" => "universities",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_CODE#/",
		)
	),
	false
);?>
<!-- st-content-right --><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>