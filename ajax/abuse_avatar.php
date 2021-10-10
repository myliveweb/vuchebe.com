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

	$testAbuse = 1;
	$arSelect = array("ID", "NAME", "IBLOCK_ID");
	$arFilter = array("IBLOCK_ID" => 27, "ACTIVE" => "Y", "PROPERTY_AUTHOR" => $input['user'], "PROPERTY_ABUSE" => $user_id);
	$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
	if($row = $res->GetNext())
	{
		$testAbuse = 0;
	}
	if($testAbuse || 1) {

		if (strlen(trim($input['fname'])) && strlen(trim($input['lname']))) {
			$format_name = trim($input['fname']);
			if($input['sname']) {
				$format_name .= ' ';
				$format_name .= $input['sname'];
			}
			$format_name .= ' ';
			$format_name .= trim($input['lname']);
		}

		$el = new CIBlockElement;

		$PROP = array();
		$PROP['AUTHOR']   = $input['user'];
		$PROP['ABUSE']    = $user_id;

		$arLoadProductArray = Array(
		  "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
		  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
		  "IBLOCK_ID"         => 27,
		  "PROPERTY_VALUES"   => $PROP,
		  "NAME"              => $format_name,
		  "ACTIVE"            => "Y"
		  );

		  $arLoadProductArray['DETAIL_PICTURE'] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . $input['url']);

		if ($PRODUCT_ID = $el->Add($arLoadProductArray)){

			$result['status'] = 'success';
			$result['message'] = 'Ваша жалоба отправлена';
		} else {
			$result['status'] = 'error';
			$result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
		}
	} else {
		$result['status'] = 'success';
	}
}
$result['status'] = 'success';
echo json_encode($result);
?>