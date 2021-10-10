<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$result = array();

$input = filter_input_array(INPUT_POST);

global $USER;
global $DB;

CModule::IncludeModule('iblock');
$el = new CIBlockElement;

$PROP = array();
$PROP['COUNTRY'] = $input['country'];
$PROP['REGION']  = $input['region'];
$PROP['CITY']    = $input['city'];
$PROP['ADRESS']  = $input['adress'];
$PROP['PHONE']   = $input['tel'];
$PROP['SITE']    = $input['site'];
$PROP['EMAIL']   = $input['email'];
$PROP['TICKET']  = '';
$PROP['AUTHOR']  = (int) $USER->GetID();

$type = '';

if($input['obr'] == 2) {
	$type = 'Высшее';
} elseif($input['obr'] == 3) {
	$type = 'Среднее';
} elseif($input['obr'] == 4) {
	$type = 'Начальное';
} elseif($input['obr'] == 6) {
	$type = 'Языковые курсы';
}

$PROP['TYPE']   = $type;
$PROP['IBLOCK'] = (int) $input['obr'];

$PROP['PENDING'] = 'N';
$PROP['ADD']     = 'N';
$PROP['DEL']     = 'N';

$PROP['COUNTRY_ID'] = 0;
$PROP['REGION_ID']  = 0;
$PROP['CITY_ID']    = 0;

$PROP['UZ_ID']    = 0;

if(!$input['country_id']) {
    $PROP['COUNTRY_ID'] = array_shift(CIBlockSection::GetList(array(), array(
        'IBLOCK_ID' => 32,
        'NAME' => $PROP['COUNTRY']
    ), false, array('ID'))->fetch());
} else {
    $PROP['COUNTRY_ID'] = $input['country_id'];
}

if(!$input['region_id']) {
    $PROP['REGION_ID'] = array_shift(CIBlockElement::GetList(array(), array(
        'IBLOCK_ID' => 32,
        'PROPERTY_REGION' => $PROP['REGION']
    ), false, array('ID'))->fetch());
} else {
    $PROP['REGION_ID'] = $input['region_id'];
}

if(!$input['city_id']) {
    $PROP['CITY_ID'] = array_shift(CIBlockElement::GetList(array(), array(
        'IBLOCK_ID' => 32,
        'NAME' => $PROP['CITY']
    ), false, array('ID'))->fetch());
} else {
    $PROP['CITY_ID'] = $input['city_id'];
}

$arLoadProductArray = Array(
  "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
  "IBLOCK_ID"         => 50,
  "PROPERTY_VALUES"   => $PROP,
  "NAME"              => $input['name'],
  "ACTIVE"            => "Y"
  );


if ($PRODUCT_ID = $el->Add($arLoadProductArray)){

	$result['status'] = 'success';
	$result['message'] = 'Ваша заявка отправлена';
} else {
	$result['status'] = 'error';
	$result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
}

$result['status'] = 'success';
echo json_encode($result);
?>