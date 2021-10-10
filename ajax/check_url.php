<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$user_id = 0;
if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
}


$filter = array("ACTIVE" => "Y", "WORK_PHONE" => $input['url']);
$rsUsers = CUser::GetList($userBy, $userOrder, $filter);
if($res = $rsUsers->Fetch()) {
    if($res['ID'] != $user_id) {
        $error['url'] = 'Такой URL уже занят';
    }
}

if($error) {
    $data = array("status" => "error", 'error' => $error);
} else {
    $data = array("status" => "success");
}

die(json_encode($data));
?>