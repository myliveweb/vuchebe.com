<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

if(!empty($input['name']) and $input['name']
	and !empty($input['last_name']) and $input['last_name']
	and !empty($input['first_name']) and $input['first_name']
	and !empty($input['second_name']) and $input['second_name']
	and !empty($input['email']) and $input['email']
	and !empty($input['phone']) and $input['phone']
	and !empty($input['message']) and $input['message']
	and !empty($input['links']) and sizeof($input['links'])) {

	if($APPLICATION->CaptchaCheckCode($input["captcha_word"], $input["captcha_sid"])) {

		global $USER;
		global $DB;

		$firstname = strip_tags($_REQUEST['firstname']);
		$email = strip_tags($_REQUEST['email']);
		$message = strip_tags($_REQUEST['message_fb']);

		$el = new CIBlockElement;

		$PROP = array();
		$PROP['FIRST_NAME']  = $input['first_name'];
		$PROP['LAST_NAME']   = $input['last_name'];
		$PROP['SECOND_NAME'] = $input['second_name'];
		$PROP['EMAIL'] 		 = $input['email'];
		$PROP['PHONE'] 		 = $input['phone'];
		$PROP['LINKS'] 		 = $input['links'];

		$arLoadProductArray = Array(
		  "MODIFIED_BY"    		=> $USER->GetID(),   // элемент добавлен текущим пользователем
		  "IBLOCK_SECTION_ID" 	=> false,            // элемент лежит в корне раздела
		  "IBLOCK_ID"      		=> 37,
		  "PROPERTY_VALUES"		=> $PROP,
		  "NAME"           		=> $input['name'],
		  "ACTIVE"         		=> "Y",              // активен
		  "DETAIL_TEXT"   		=> $input['message']
		  );


		if($PRODUCT_ID = $el->Add($arLoadProductArray)) {

			$result['status'] = 'success';
			$result['message'] = 'Ваша заявка успешно отправлена';

		} else {
			$result['status'] = 'error';
			$result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
		}

	} else {
		$result['status'] = 'error';
		$result['message'] = 'Не правильный код картинки';
	}

} else {
    $result['status'] = 'error';
    $result['message'] = 'Все поля обязательны для заполнения';
}

die(json_encode($result));
?>