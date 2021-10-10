<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = array();

$input = filter_input_array(INPUT_POST);

if($input['old_pass'] !== $_SESSION['USER_DATA']['USER_PASS']) {
	$data = array("status" => "error", 'res' => 'Неверно введён старый пароль');
} elseif($input['new_pass'] !== $input['confirm_pass']) {
	$data = array("status" => "error", 'res' => 'Новый пароль и подтверждение не совпадают');
} else {
	$myUser = new CUser;

	$fields = Array(
	  "PASSWORD"          => $input['new_pass'],
	  "CONFIRM_PASSWORD"  => $input['confirm_pass']
	);

	$test = $myUser->Update($_SESSION['USER_DATA']['ID'], $fields);

	if($test) {

		$_SESSION['USER_PASS'] = $input['new_pass'];
		$_SESSION['USER_DATA']['USER_PASS'] = $input['new_pass'];

		$data = array("status" => "success", 'res' => 'ok');
	} else {
		echo '<pre>'; print_r($myUser->LAST_ERROR); echo '</pre>';
		$data = array("status" => "error", 'res' => 'Не удалось изменить пароль');
	}
}

die(json_encode($data));
?>