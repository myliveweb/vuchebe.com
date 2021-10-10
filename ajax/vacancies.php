<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = 0;

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$isAdmin = 0;
$arrAdmins = array();
$newArrAdmins = array();

$resAdmins = CIBlockElement::GetProperty(2, $input['vuz_id'], "sort", "asc", array("CODE" => "ADMINS"));
while ($obAdmins = $resAdmins->GetNext()) {
    $arrAdmins[] = $obAdmins['VALUE'];
}

if(in_array($user_id, $arrAdmins) || isEdit())
	$isAdmin = 1;

if($isAdmin) {

	if($input['name']) {

		$el = new CIBlockElement;

		$PROP = array();

		$PROP['VUZ_ID']   = $input['vuz_id'];
		$PROP['PHONE']    = $input['phone'];
		$PROP['EMAIL']    = $input['email'];
		$PROP['CONTACTS'] = $input['contacts'];
		$PROP['FAKULTET'] = $input['spec'];
		$PROP['USER_ID']  = $user_id;

		$arLoadProductArray = Array(
		  "MODIFIED_BY"    => $USER->GetID(),
		  "IBLOCK_SECTION_ID" => false,
		  "IBLOCK_ID"      => 24,
		  "PROPERTY_VALUES"=> $PROP,
		  "NAME"           => $input['name'],
		  "DETAIL_TEXT"    => html_entity_decode($input['message']),
		  "ACTIVE"         => "Y"
		  );

		if($input['vac_id']) {
			$result = $el->Update($input['vac_id'], $arLoadProductArray);
		} else {
			if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
				$result = $PRODUCT_ID;
			} else {
			  	$result = "Error: ".$el->LAST_ERROR;
			}
		}
	}
}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>