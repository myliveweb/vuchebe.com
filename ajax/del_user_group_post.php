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

$arrAdminGroupId = array();
$arrAdmin = $dbh->query('SELECT user_id from a_group_admin WHERE chat_id = ' . $chat)->fetchAll();
foreach($arrAdmin as $item)
    $arrAdminGroupId[] = $item['user_id'];

$admin = 0;
if(in_array($user_id, $arrAdminGroupId))
    $admin = 1;

if($owner == $user_id || $admin) {

    if($id) {

        $stmt = $dbh->prepare("INSERT INTO a_group_del_local (chat_id, user_id, post_id) VALUES (:chat_id, :user_id, :post_id)");
        $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_user_success WHERE chat_id = :chat_id AND id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result['status'] = 'success';
        $result['return'] = $id;

    } elseif(!$id && $admin) {

        $stmt= $dbh->prepare("UPDATE a_chat SET del_to = 1 WHERE group_chat = ? AND owner_id != 0");
        $stmt->execute(array($chat));

        $sql = "DELETE FROM a_group_del_local WHERE chat_id = :chat_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_user_success WHERE chat_id = :chat_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
        $stmt->execute();

        $result['status'] = 'success';
        $result['return'] = $chat;
    }

} else {
    die(json_encode(array("status" => "error", 'message' => 'Недостаточно прав')));
}

$data = $result ? $result : array("status" => "error", 'message' => 'Ошибка добавления чата');
die(json_encode($data));
?>