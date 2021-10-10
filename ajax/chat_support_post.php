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

$originChatId = $chat;

$user_id = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
}

if($user != $user_id)
    die(json_encode(array("status" => "error", 'message' => 'Необходима авторизация')));

if(!$chat) {

    //if(isEdit())
    //    die(json_encode(array("status" => "error", 'message' => 'Администраторы не могут создавать заявки')));

    $group = getGroup();

    if($group == 6 || $group == 7) {
        $user_name = trim($_SESSION['USER_DATA']['WORK_COMPANY']);
    } else {
        $user_name = trim($_SESSION['USER_DATA']['NAME']) . ' ' . trim($_SESSION['USER_DATA']['LAST_NAME']);

        if (strlen($user_name) <= 0) {
            $user_name = trim($_SESSION['USER_DATA']['LOGIN']);
        }
    }

    CModule::IncludeModule('iblock');
    $el = new CIBlockElement;

    $PROP = array();
    $PROP['ACCOUNT']   = $user;
    $PROP['CLOSE']     = "N";
    $PROP['GROUP']     = getGroupName();

    $arLoadProductArray = Array(
        "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
        "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
        "IBLOCK_ID"         => 40,
        "PROPERTY_VALUES"   => $PROP,
        "NAME"              => $user_name,
        "ACTIVE"            => "Y",            // активен
        "PREVIEW_TEXT"      => $mesagse
    );

    if (!$ticket = $el->Add($arLoadProductArray)) {
        die(json_encode(array("status" => "error", 'message' => html_entity_decode("Error: ".$el->LAST_ERROR))));
    } else {
        $chat = $ticket;
        $owner = $user;
        $mark = $group;
    }
}

if($chat && $message && $user) {

    if(!$owner) {
        $chatInfo = $dbh->query('SELECT group_owner, mark from a_chat_support WHERE group_chat = ' . $chat . ' AND group_owner != 0 ORDER BY id ASC')->fetch();
        $owner = $chatInfo['group_owner'];
        $mark  = $chatInfo['mark'];
    }

    $group    = (int) getGroup();
    $datePost = (int) time();
    $br       = str_replace(array("\r\n", "\r", "\n"), '<br>', $message);

    $stmt = $dbh->prepare("INSERT INTO a_chat_support (owner_id, from_id, user_group, date_post, message, del_owner, del_to, success, group_chat, group_owner, mark) VALUES (:owner_id, 0, :user_group, :date_post, :message, 0, 0, 1, :group_chat, :group_owner, :mark)");
    $stmt->bindParam(':owner_id', $user, PDO::PARAM_INT);
    $stmt->bindParam(':user_group', $group, PDO::PARAM_INT);
    $stmt->bindParam(':date_post', $datePost, PDO::PARAM_INT);
    $stmt->bindParam(':message', $br);
    $stmt->bindParam(':group_chat', $chat, PDO::PARAM_INT);
    $stmt->bindParam(':group_owner', $owner, PDO::PARAM_INT);
    $stmt->bindParam(':mark', $mark, PDO::PARAM_INT);
    $stmt->execute();

	$result['id'] = $dbh->lastInsertId();

	$result['time'] = get_str_time($datePost + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60));
    $result['create'] = $datePost;

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

    $result['admin'] = 0;
    if($_SESSION['USER_DATA']['PRO'] !== 'Y')
        $result['admin'] = 1;

    $result['class'] = '';
    if($result['teacher'])
        $result['class'] .= ' teacher';
    else
        $result['class'] .= ' user';

    if($result['admin'])
        $result['class'] .= ' admin';

    if(isEdit()) {
        $filter = Array("GROUPS_ID" => array(1, 8));
        $rsUsers = CUser::GetList(($by = "ID"), ($order = "asc"), $filter);
        while ($arUser = $rsUsers->Fetch()) {

            $stmt = $dbh->prepare("INSERT INTO a_user_success (chat_id, user_id, post_id) VALUES (:chat_id, :user_id, :post_id)");
            $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $arUser['ID'], PDO::PARAM_INT);
            $stmt->bindParam(':post_id', $result['id'], PDO::PARAM_INT);
            $stmt->execute();
        }

    } else {
        $stmt = $dbh->prepare("INSERT INTO a_user_success (chat_id, user_id, post_id) VALUES (:chat_id, :user_id, :post_id)");
        $stmt->bindParam(':chat_id', $chat, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $result['id'], PDO::PARAM_INT);
        $stmt->execute();
    }
}

$data = $result ? array("status" => "success", 'add' => $result ) : array("status" => "error", 'message' => 'Ошибка добавления сообщения.');
die(json_encode($data));
?>