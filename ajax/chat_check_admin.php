<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

CModule::IncludeModule('iblock');

$error = 0;
$result = array();

$user_id = 0;

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
}

$input = filter_input_array(INPUT_POST);

$avatar = (int) $input['avatar'];

if($avatar) {

    $arSelect = array("ID", "NAME", "IBLOCK_ID");
    $arFilter = array("IBLOCK_ID" => 47, "ACTIVE" => "Y", "ID" => $avatar);
    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
    if($obRes = $res->GetNextElement()) {

        $row = $obRes->GetFields();
        $props = $obRes->GetProperties();

        $chat    = 0;
        $ownerId = $props['USER']['VALUE'];
        $owner   = $user_id;
        $user    = $user_id;
    }

}

$originChatId = $chat;

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

$message = $user_name . ' создал новый тикет для ' . $displayName . '(ID: ' . $userData['ID'] . ')';

$el = new CIBlockElement;

$PROP = array();
$PROP['ACCOUNT']   = $user;
$PROP['CLOSE']     = "N";
$PROP['GROUP']     = getGroupName();
$PROP['BANNER']    = $avatar;

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

CIBlockElement::SetPropertyValueCode($avatar, "TICKET", $ticket);
CIBlockElement::SetPropertyValueCode($avatar, "MODERATOR", $user_id);
CIBlockElement::SetPropertyValueCode($avatar, "MODERATE_TIME", date('d.m.Y H:i:s'));



if($chat && $message && $user) {

    $result['chat']  = $chat;
    $result['owner']  = $owner;
    $result['user_id']  = $user_id;
    $result['user']  = $user;
    $result['message']  = $message;

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

    }

    $arFilterCntTicket = Array("IBLOCK_ID" => 47, "ACTIVE" => "Y", "!PROPERTY_TICKET" => false, "PROPERTY_USER" => $userData['ID']);
    $resCntTicket = CIBlockElement::GetList(array(), $arFilterCntTicket, Array(), false, Array());
    $result['TICKET_CNT'] = $resCntTicket ? $resCntTicket : 0;

}

$data = $result ? array("status" => "success", 'add' => $result ) : array("status" => "error", 'message' => 'Ошибка добавления сообщения.');
die(json_encode($data));
?>