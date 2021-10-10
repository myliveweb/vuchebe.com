<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$input = filter_input_array(INPUT_POST);

global $USER;
global $DB;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

if($user_id) {

	CModule::IncludeModule('iblock');
	$el = new CIBlockElement;

	$PROP = array();
	$PROP['AUTHOR']   = $input['user_post'];
	$PROP['ABUSE']    = $user_id;
	$PROP['URL']      = $input['url_post'];
	$PROP['URL_POST'] = $input['url_post'];
	$PROP['POST_ID']  = $input['id_post'];
	$PROP['VUZ_ID']   = $input['vuz_post'];

	$arLoadProductArray = Array(
	  "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
	  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
	  "IBLOCK_ID"         => 23,
	  "PROPERTY_VALUES"   => $PROP,
	  "NAME"              => $input['name_post'],
	  "ACTIVE"            => "Y",            // активен
	  "DETAIL_TEXT"       => $input['text_post']
	  );


	if ($PRODUCT_ID = $el->Add($arLoadProductArray)){

		$result['status'] = 'success';
		$result['message'] = 'Ваша жалоба отправлена';
	} else {
		$result['status'] = 'error';
		$result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
	}
}
$result['status'] = 'success';
echo json_encode($result);
?>