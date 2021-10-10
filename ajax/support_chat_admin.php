<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = array();

$user_id = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
    $user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$input = filter_input_array(INPUT_POST);

$banner      = (int) $input['banner'];

if($banner) {

    $arSelect = array("ID", "NAME", "IBLOCK_ID");
    $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "ID" => $banner);
    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
    if($obRes = $res->GetNextElement()) {

        $row = $obRes->GetFields();
        $props = $obRes->GetProperties();

        $chat    = 0;
        $ownerId = $props['OWNER']['VALUE'];
        $owner   = $user_id;
        $user    = $user_id;
    }

} else {
    $chat      = (int) $input['id']; // 0 - новый чат
    $owner     = (int) $input['owner']; // Организатор чата
    $user 	   = (int) $input['owner'];

    $arrOwnerId = $input['users'];
    $ownerId    = (int) $arrOwnerId[0]; // Бизнес Аккаунт которому адресован чат (массив)
}

$originChatId = $chat;

if($user != $user_id)
    die(json_encode(array("status" => "error", 'message' => 'Необходима авторизация')));

$group = getGroup();

if($group == 6 || $group == 7) {
    $user_name = trim($_SESSION['USER_DATA']['WORK_COMPANY']);
} else {
    $user_name = trim($_SESSION['USER_DATA']['NAME']) . ' ' . trim($_SESSION['USER_DATA']['LAST_NAME']);

    if (strlen($user_name) <= 0) {
        $user_name = trim($_SESSION['USER_DATA']['LOGIN']);
    }
}

$rsUserData = CUser::GetByID($ownerId);
$userData = $rsUserData->Fetch();

$displayName = $userData['NAME'];

if($userData['SECOND_NAME'])
    $displayName .= ' ' . $userData['SECOND_NAME'];

$displayName .= ' ' . $userData['LAST_NAME'];

$message = $user_name. ' создал новый тикет для ' . $displayName . '(ID: ' . $userData['ID'] . ')';

if(!$chat) {

    CModule::IncludeModule('iblock');
    $el = new CIBlockElement;

    $PROP = array();
    $PROP['ACCOUNT']   = $user;
    $PROP['CLOSE']     = "N";
    $PROP['GROUP']     = getGroupName();
    $PROP['BANNER']    = $banner;

    $arLoadProductArray = Array(
        "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
        "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
        "IBLOCK_ID"         => 40,
        "PROPERTY_VALUES"   => $PROP,
        "NAME"              => $user_name,
        "ACTIVE"            => "Y",            // активен
        "PREVIEW_TEXT"      => $message
    );

    if (!$ticket = $el->Add($arLoadProductArray)) {
        die(json_encode(array("status" => "error", 'message' => html_entity_decode("Error: ".$el->LAST_ERROR))));
    } else {
        $chat = $ticket;
        $owner = $user;
    }

    if($banner) {
        CIBlockElement::SetPropertyValueCode($banner, "TICKET", $ticket);
    }
}

if($chat && $message && $user) {

    if(!$owner) {
        $chatInfo = $dbh->query('SELECT group_owner from a_chat_support WHERE group_chat = ' . $chat . ' AND group_owner != 0 ORDER BY id ASC')->fetch();
        $owner = $chatInfo['group_owner'];
    }

    $group    = (int) getGroup();
    $datePost = (int) time();
    $br       = str_replace(array("\r\n", "\r", "\n"), '<br>', $message);
    $groupTo  = (int) getGroupById($userData['ID']);

    $stmt = $dbh->prepare("INSERT INTO a_chat_support (owner_id, from_id, user_group, date_post, message, del_owner, del_to, success, group_chat, group_owner, mark) VALUES (:owner_id, 0, :user_group, :date_post, :message, 0, 0, 1, :group_chat, :group_owner, :mark)");
    $stmt->bindParam(':owner_id', $user, PDO::PARAM_INT);
    $stmt->bindParam(':user_group', $group, PDO::PARAM_INT);
    $stmt->bindParam(':date_post', $datePost, PDO::PARAM_INT);
    $stmt->bindParam(':message', $br);
    $stmt->bindParam(':group_chat', $chat, PDO::PARAM_INT);
    $stmt->bindParam(':group_owner', $userData['ID'], PDO::PARAM_INT);
    $stmt->bindParam(':mark', $groupTo, PDO::PARAM_INT);
    $stmt->execute();

	$result['id'] = $dbh->lastInsertId();

	$result['time'] = get_str_time($datePost + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60));
    $result['create'] = $datePost;

    $result['chat']  = $chat;
    $result['owner']  = $owner;
    $result['user_id']  = $user_id;

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