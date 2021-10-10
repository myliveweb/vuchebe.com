<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$owner_id 			= (int) $input['owner_id'];
$owner_display_name = trim($input['owner_display_name']);
$from_id 			= (int) $input['from_id'];
$from_display_name 	= trim($input['from_display_name']);
$date_post 			= (int) time();
$message 			= trim($input['message']);
$avatar 			= trim($input['avatar']);

$br = str_replace(array("\r\n", "\r", "\n"), '<br>', $message);

$rsUser = CUser::GetByID($from_id);
$userChat  = $rsUser->Fetch();

$arrBlock = array();
$arrBlock = $dbh->query('SELECT id from a_block_user WHERE id_user = ' . $userChat['ID'] . ' AND block_user = ' . $owner_id)->fetch();

if($_SESSION['USER_DATA']['WORK_PAGER'] || $userChat['WORK_PAGER'] || $arrBlock['id']) {

	$result['event'] = 'block';
	$data = $result ? array("status" => "error", 'add' => $result ) : array("status" => "error", 'message' => 'Ошибка добавления сообщения.');
	die(json_encode($data));
}

if(!$error && $owner_id && $owner_display_name && $from_id && $from_display_name && $message && $avatar) {
	$stmt = $dbh->prepare("INSERT INTO a_chat (owner_id, owner_display_name, from_id, from_display_name, date_post, message, del_owner, del_to, avatar, success, group_chat, group_owner) VALUES (:owner_id, :owner_display_name, :from_id, :from_display_name, :date_post, :message, 0, 0, :avatar, 0, 0, 0)");
	$stmt->bindParam(':owner_id', $owner_id);
	$stmt->bindParam(':owner_display_name', $owner_display_name);
	$stmt->bindParam(':from_id', $from_id);
	$stmt->bindParam(':from_display_name', $from_display_name);
	$stmt->bindParam(':date_post', $date_post);
	$stmt->bindParam(':message', $br);
	$stmt->bindParam(':avatar', $avatar);
	$stmt->execute();

	$result['id'] = $dbh->lastInsertId();
	$result['time'] = get_str_time($date_post + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60));
	$result['message'] = $br;
	$result['teacher'] = $_SESSION['USER_DATA']['TEACHER'];
	$result['color'] = $_SESSION['USER_DATA']['WORK_FAX'];

    $stmt = $dbh->prepare("INSERT INTO a_user_success (chat_id, user_id, post_id) VALUES (0, :user_id, :post_id)");
    $stmt->bindParam(':user_id', $_SESSION['USER_DATA']['ID'], PDO::PARAM_INT);
    $stmt->bindParam(':post_id', $result['id'], PDO::PARAM_INT);
    $stmt->execute();
}

$data = $result ? array("status" => "success", 'add' => $result ) : array("status" => "error", 'message' => 'Ошибка добавления сообщения.');
die(json_encode($data));
?>