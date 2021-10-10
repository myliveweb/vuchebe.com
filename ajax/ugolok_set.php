<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error  = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$input['id'] = (int) $input['id'];

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

if(isEditPlus()) {
    if($input['name']) {

        $el = new CIBlockElement;

        $PROP = array();
        $PROP['WIKI'] = $input['wiki'];  // свойству с кодом 12 присваиваем значение "Белый"
        $PROP['SIGN'] = $input['sign'];

        $dataArray = Array(
            "MODIFIED_BY"       => $USER->GetID(),
            "IBLOCK_SECTION"    => $input['sections'],
            "IBLOCK_SECTION_ID" => $input['sections'][0],
            "PROPERTY_VALUES"   => $PROP,
            "NAME"              => $input['name'],
            "ACTIVE"            => "Y",
            "PREVIEW_TEXT"      => $input['preview'],
            "DETAIL_TEXT"       => $input['detail']
          );

        $result = $el->Update($input['id'], $dataArray);
    }
}

$data = array("status" => "success", 'res' => $result);

die(json_encode($data));
?>