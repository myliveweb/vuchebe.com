<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = 0;

$input = filter_input_array(INPUT_POST);

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if($user_id) {

	$sql = "DELETE FROM a_user_uz WHERE user_id = :user_id AND id = :id";
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$stmt->bindParam(':id', $input['id'], PDO::PARAM_INT);
	$stmt->execute();
}
$data = array("status" => "success");
die(json_encode($data));
?>