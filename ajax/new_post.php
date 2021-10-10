<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$input = filter_input_array(INPUT_POST);

global $USER;
global $DB;

CModule::IncludeModule('iblock');
$el = new CIBlockElement;

if($input['type_post'] == 'edit') {

	$user_id = 0;
	if($_SESSION['USER_DATA'])
		$user_id = $_SESSION['USER_DATA']['ID'];

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_USER_ID");
	$arFilter = array("IBLOCK_ID" => 21, "ACTIVE" => "Y", "ID" => $input['id_post']);
	$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
	if($row = $res->Fetch())
	{
		if($row["PROPERTY_USER_ID_VALUE"] == $user_id) {

			$el = new CIBlockElement;

			$arLoadProductArray = Array(
			  "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
			  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
			  "IBLOCK_ID"         => 21,
			  "DETAIL_TEXT"       => $input['message_post']
			);

			$res = $el->Update($input['id_post'], $arLoadProductArray);

			if($res) {
				$result['status'] = 'success';
				$result['message'] = 'Успешно изменено.';
			} else {
				$result['status'] = 'error';
				$result['message'] = 'Не удалось изменить.';
			}
		}
	}
} else {

	$PROP = array();
	$PROP['VUZ_ID'] = $input['vuz_post'];
	$PROP['USER_ID'] = $input['user_post'];
	$PROP['PARENT_ID'] = 0;
	$PROP['LIKE'] = 0;
	$PROP['DESLIKE'] = 0;

	if($input['type_post'] == 'comment') {
		$PROP['PARENT_ID'] = $input['id_post'];
	}

	$arLoadProductArray = Array(
	  "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
	  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
	  "IBLOCK_ID"         => 21,
	  "PROPERTY_VALUES"   => $PROP,
	  "NAME"              => $input['name_post'],
	  "ACTIVE"            => "Y",            // активен
	  "DETAIL_TEXT"       => $input['message_post']
	  );


	if ($PRODUCT_ID = $el->Add($arLoadProductArray)){

		$result['status'] = 'success';
		$result['message'] = 'Ваше сообщение успешно отправлено';
		$result['id'] = $PRODUCT_ID;
		$result['user_avatar'] = $_SESSION['USER_DATA']['AVATAR'];
		$result['user_name'] = $_SESSION['USER_DATA']['FULL_NAME'];
		$result['format_time'] = get_str_time_post(time());

	} else {
		$result['status'] = 'error';
		$result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
		$result['id'] = 0;
	}

}
echo json_encode($result);
?>