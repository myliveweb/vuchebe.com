<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$id    = (int) $input['id'];
$chat  = (int) $input['chat'];
$owner = (int) $input['owner'];

$user_id = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
    $user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if(!$user_id)
  die(json_encode(array("status" => "error", 'message' => 'Необходима авторизация')));

if($_SESSION['USER_DATA']['PRO'] === 'Y' || isEdit()) {

    $stmt = $dbh->prepare("INSERT INTO a_support_del_local (chat_id, user_id, post_id) VALUES (:chat_id, :user_id, :post_id)");
    $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':post_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $result['status'] = 'success';
    $result['return'] = $id;

} else {
    die(json_encode(array("status" => "error", 'message' => 'Недостаточно прав')));
}

$data = $result ? $result : array("status" => "error", 'message' => 'Ошибка добавления чата');
die(json_encode($data));
?>