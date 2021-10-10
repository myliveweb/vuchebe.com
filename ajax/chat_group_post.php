<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$chat      = (int) $input['chat_id'];
$owner     = (int) $input['owner_id'];
$user 	   = (int) $input['user_id'];
$message   = trim($input['message']);

$user_id = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
    $user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if($user != $user_id)
    die(json_encode(array("status" => "error", 'message' => 'Необходима авторизация')));

$arrUserGroupId = array();
$arrUser = $dbh->query('SELECT user_id from a_group_user WHERE chat_id = ' . $chat)->fetchAll();
foreach($arrUser as $item)
    $arrUserGroupId[] = $item['user_id'];

if(!in_array($user, $arrUserGroupId))
    die(json_encode(array("status" => "error", 'message' => 'Недостаточно прав')));

if($chat && $owner && $message && $user) {

    $datePost = (int) time();
    $br = str_replace(array("\r\n", "\r", "\n"), '<br>', $message);

    $stmt = $dbh->prepare("INSERT INTO a_chat (owner_id, owner_display_name, from_id, from_display_name, date_post, message, del_owner, del_to, avatar, success, group_chat, group_owner) VALUES (:owner_id, '', 0, '', :date_post, :message, 0, 0, '', 1, :group_chat, :group_owner)");
    $stmt->bindParam(':owner_id', $user, PDO::PARAM_INT);
    $stmt->bindParam(':date_post', $datePost, PDO::PARAM_INT);
    $stmt->bindParam(':message', $br);
    $stmt->bindParam(':group_chat', $chat, PDO::PARAM_INT);
    $stmt->bindParam(':group_owner', $owner, PDO::PARAM_INT);
    $stmt->execute();

	$result['id'] = $dbh->lastInsertId();
	$result['time'] = get_str_time($datePost + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60));
    $result['create'] = $datePost;

    $stmt = $dbh->prepare("INSERT INTO a_user_success (chat_id, user_id, post_id) VALUES (:chat_id, :user_id, :post_id)");
    $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['USER_DATA']['ID'], PDO::PARAM_INT);
    $stmt->bindParam(':post_id', $result['id'], PDO::PARAM_INT);
    $stmt->execute();

    $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@i';
    $replacement = '<a href="$1" target="_blank">$1</a>';
    $result['message'] =  preg_replace($pattern, $replacement, $br);

	$result['teacher'] = $_SESSION['USER_DATA']['TEACHER'];
    $result['user']    = !$_SESSION['USER_DATA']['TEACHER'];
    $result['avatar']  = $_SESSION['USER_DATA']['AVATAR'];
    $result['color']  = 1;

    $result['chat']  = $chat;
    $result['owner']  = $owner;
    $result['user_id']  = $user_id;

    $arrAdminGroupId = array();
    $arrAdmin = $dbh->query('SELECT user_id from a_group_admin WHERE chat_id = ' . $chat)->fetchAll();
    foreach($arrAdmin as $item)
        $arrAdminGroupId[] = $item['user_id'];

    $result['admin'] = 0;
    if(in_array($user, $arrUserGroupId))
        $result['admin'] = 1;

    $result['online'] = 1;

    $result['class'] = ' online';
    if($result['teacher'])
        $result['class'] .= ' teacher';
    else
        $result['class'] .= ' user';

    if($result['admin'])
        $result['class'] .= ' admin';
}

$data = $result ? array("status" => "success", 'add' => $result ) : array("status" => "error", 'message' => 'Ошибка добавления сообщения.');
die(json_encode($data));
?>