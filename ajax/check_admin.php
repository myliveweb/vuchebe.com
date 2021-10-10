<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

if($input['type'] == 'email' || $input['type'] == 'all') {

    $filter = array("ACTIVE" => "Y", "EMAIL" => $input['email']);
    $rsUsers = CUser::GetList($userBy, $userOrder, $filter);
    if($res = $rsUsers->Fetch()) {
        $error['email'] = 'Такой Email уже занят';
    }
}

if($error) {
    $data = array("status" => "error", 'error' => $error);
} else {
    $data = array("status" => "success");
}

die(json_encode($data));
?>