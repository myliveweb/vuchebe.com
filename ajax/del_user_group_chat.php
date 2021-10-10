<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$id 	 = (int) $input['id'];
$chat = (int) $input['chat'];

$user_id = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
    $user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if(!$user_id)
  die(json_encode(array("status" => "error", 'message' => 'Необходима авторизация')));

if($id == $user_id) {

    $sql = "DELETE FROM a_group_user WHERE chat_id = :chat_id AND user_id = :user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $sql = "DELETE FROM a_group_admin WHERE chat_id = :chat_id AND user_id = :user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $result['status'] = 'success';
    $result['return'] = $user_id;

} else {
    die(json_encode(array("status" => "error", 'message' => 'Недостаточно прав')));
}

$data = $result ? $result : array("status" => "error", 'message' => 'Ошибка добавления чата');
die(json_encode($data));
?>