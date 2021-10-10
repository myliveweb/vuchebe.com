<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$userBy = "id";
$userOrder = "asc";


$filter = array("EMAIL" => $input['email']);
$rsUsers = CUser::GetList($userBy, $userOrder, $filter);
while($res = $rsUsers->Fetch()) {
	$result[] = $res['ID'];
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>