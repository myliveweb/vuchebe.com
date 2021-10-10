<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$id   = (int) $input['id'];
$chat = (int) $input['chat'];
$type = $input['type'];

$user_id = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
    $user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if(!$user_id)
  die(json_encode(array("status" => "error", 'message' => 'Необходима авторизация')));

$chatInfo = $dbh->query('SELECT group_owner from a_chat_support WHERE group_chat = ' . $chat . ' AND group_owner != 0 ORDER BY id ASC')->fetch();
$owner = $chatInfo['group_owner'];

if($user_id == $owner || isEdit()) {

    if($type == 'close') {

        CIBlockElement::SetPropertyValueCode($chat, "CLOSE", "Y");

        $stmt = $dbh->prepare("UPDATE a_chat_support SET del_owner = :del_owner WHERE group_chat = :group_chat");
        $stmt->bindParam(':del_owner', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':group_chat', $chat, PDO::PARAM_INT);
        $stmt->execute();

    } elseif($type == 'delete') {

        $stmt = $dbh->prepare("UPDATE a_chat_support SET del_to = :del_to WHERE group_chat = :group_chat");
        $stmt->bindParam(':del_to', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':group_chat', $chat, PDO::PARAM_INT);
        $stmt->execute();

    }

    $result['status'] = 'success';
    $result['return'] = $user_id;

} else {
    die(json_encode(array("status" => "error", 'message' => 'Недостаточно прав')));
}

$data = $result ? $result : array("status" => "error", 'message' => 'Ошибка закрытия тикета');
die(json_encode($data));
?>