<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if($user_id) {
	if($input['obr'] == 1) {
		if($input['type'] == 'fack') {
			$res = CIBlockElement::GetProperty(2, $input['uz'], array("sort" => "asc"), array("CODE"=>"FAKULTETS"));
			while($ob = $res->GetNext()) {
				if($ob['VALUE']) {
					$arrFackEx = explode('#', $ob['VALUE']);
					$result[] = $arrFackEx[0];
				}
			}
		}
	}
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>