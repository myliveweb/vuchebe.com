<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$id 	 = (int) $input['id'];
$owner   = (int) $input['owner'];
$name    = trim($input['name']);
$avatar  = trim($input['avatar']);

$user_id = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
    $user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$arrAdminGroupId = array();
$arrAdmin = $dbh->query('SELECT user_id from a_group_admin WHERE chat_id = ' . $id)->fetchAll();
foreach($arrAdmin as $item)
    $arrAdminGroupId[] = $item['user_id'];

$admin = 0;
if(in_array($user_id, $arrAdminGroupId))
    $admin = 1;

if($owner != $user_id && !$admin)
  die(json_encode(array("status" => "error", 'message' => 'Необходима авторизация')));

if(!$id) {

    $checkName = $dbh->query('SELECT id from a_group_chat WHERE name = "' . $name . '" AND owner = ' . $owner)->fetch();
    if($checkName)
        die(json_encode(array("status" => "error", 'message' => 'Чат с таким названием уже занят')));

  if($name && $owner && $input['users'] && $input['admins']) {

    $stmt = $dbh->prepare("INSERT INTO a_group_chat (name, owner, avatar) VALUES (:name, :owner, :avatar)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':owner', $owner);
    $stmt->bindParam(':avatar', $avatar);
    $stmt->execute();

    $result['id'] = $dbh->lastInsertId();

    if(!$result['id'])
        die(json_encode(array("status" => "error", 'message' => 'Повторите создание чата')));

    $stmt = $dbh->prepare("INSERT INTO a_group_user (chat_id, user_id) VALUES (:chat_id, :user_id)");
    $stmt->bindParam(':chat_id', $result['id']);
    $stmt->bindParam(':user_id', $user_group);

    foreach($input['users'] as $user) {
      $user_group = (int) $user;
      $stmt->execute();
    }

    $stmt = $dbh->prepare("INSERT INTO a_group_admin (chat_id, user_id) VALUES (:chat_id, :user_id)");
    $stmt->bindParam(':chat_id', $result['id']);
    $stmt->bindParam(':user_id', $admin_group);

    foreach($input['admins'] as $admin) {
      $admin_group = (int) $admin;
      $stmt->execute();
    }

    /*
        Рассылаем уведомления участникам
    */
    $datePost = (int) time();

    $message = 'создал групповой чат "' . $name . '" и пригласил вас присоединиться.';
    $br = str_replace(array("\r\n", "\r", "\n"), '<br>', $message);

	$stmt = $dbh->prepare("INSERT INTO a_chat (owner_id, owner_display_name, from_id, from_display_name, date_post, message, del_owner, del_to, avatar, success, group_chat, group_owner) VALUES (0, '', 0, '', :date_post, :message, 0, 0, '', 1, :group_chat, :group_owner)");
	$stmt->bindParam(':date_post', $datePost);
	$stmt->bindParam(':message', $br);
	$stmt->bindParam(':group_chat', $result['id']);
	$stmt->bindParam(':group_owner', $owner);
	$stmt->execute();

  } else {

    die(json_encode(array("status" => "error", 'message' => 'Повторите создание чата')));
  }

} else {
    $stmt= $dbh->prepare('UPDATE a_group_chat SET name = "' . $name . '", avatar = "' . $avatar . '"  WHERE id = ' . $id);
    $stmt->execute();

    $sql = "DELETE FROM a_group_user WHERE chat_id = :chat_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':chat_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $dbh->prepare("INSERT INTO a_group_user (chat_id, user_id) VALUES (:chat_id, :user_id)");
    $stmt->bindParam(':chat_id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_group, PDO::PARAM_INT);

    foreach($input['users'] as $user) {
        $user_group = (int) $user;
        $stmt->execute();
    }

    $sql = "DELETE FROM a_group_admin WHERE chat_id = :chat_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':chat_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $dbh->prepare("INSERT INTO a_group_admin (chat_id, user_id) VALUES (:chat_id, :user_id)");
    $stmt->bindParam(':chat_id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $admin_group, PDO::PARAM_INT);

    foreach($input['admins'] as $admin) {
        $admin_group = (int) $admin;
        $stmt->execute();
    }

    $result['OK'] = 'OK';
}

$data = $result ? array("status" => "success", 'add' => $result ) : array("status" => "error", 'message' => 'Ошибка добавления чата');
die(json_encode($data));
?>