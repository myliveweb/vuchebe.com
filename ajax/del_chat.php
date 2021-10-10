<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

if($input['type'] == 'post') {

    $chatInfo = $dbh->query('SELECT * from a_chat WHERE id = ' . $input['id'] . ' ORDER BY id ASC')->fetch();

    if($user_id == $chatInfo['owner_id']) { // Удаляю свою запись
        if($chatInfo['del_to'] == 1) {
            $sql = "DELETE FROM a_chat WHERE id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id', $chatInfo['id'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt= $dbh->prepare("UPDATE a_chat SET del_owner = 1 WHERE id = " . $chatInfo['id']);
            $stmt->execute();
        }
    } elseif($user_id == $chatInfo['from_id']) {
        if($chatInfo['del_owner'] == 1) {
            $sql = "DELETE FROM a_chat WHERE id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id', $chatInfo['id'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt= $dbh->prepare("UPDATE a_chat SET del_to = 1 WHERE id = " . $chatInfo['id']);
            $stmt->execute();
        }
    }

    $result['status'] = 'success';
    $result['id'] = $input['id'];

} elseif($input['type'] == 'chat') {
    if($user_id == $input['owner']) { // Удаляю свою запись
        $sql = "DELETE FROM a_chat WHERE owner_id = :owner_id AND from_id = :from_id AND del_to = 1";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':owner_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':from_id', $input['from'], PDO::PARAM_INT);
        $stmt->execute();

        $stmt= $dbh->prepare("UPDATE a_chat SET del_owner = 1 WHERE owner_id = :owner_id AND  from_id = :from_id");
        $stmt->bindParam(':owner_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':from_id', $input['from'], PDO::PARAM_INT);
        $stmt->execute();

        $stmt= $dbh->prepare("UPDATE a_chat SET del_to = 1 WHERE owner_id = :owner_id AND  from_id = :from_id");
        $stmt->bindParam(':owner_id', $input['from'], PDO::PARAM_INT);
        $stmt->bindParam(':from_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

    } elseif($user_id == $input['from']) {
        $sql = "DELETE FROM a_chat WHERE owner_id = :owner_id AND from_id = :from_id AND del_owner = 1";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':owner_id', $input['owner'], PDO::PARAM_INT);
        $stmt->bindParam(':from_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt= $dbh->prepare("UPDATE a_chat SET del_to = 1 WHERE owner_id = :owner_id AND  from_id = :from_id");
        $stmt->bindParam(':owner_id', $input['owner'], PDO::PARAM_INT);
        $stmt->bindParam(':from_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt= $dbh->prepare("UPDATE a_chat SET del_owner = 1 WHERE owner_id = :owner_id AND  from_id = :from_id");
        $stmt->bindParam(':owner_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':from_id', $input['owner'], PDO::PARAM_INT);
        $stmt->execute();

    }

    $result['status'] = 'success';
    $result['id'] = $input['id'];

} elseif($input['type'] == 'spam') {

    $post = $dbh->query('SELECT * from a_chat WHERE id = ' . $input['id'] . ' ORDER BY id ASC')->fetch();

    if(!$post['from_id'])
        $post['from_id'] = $user_id;

    $ownerId = (int) $input['owner'];

    $rsAuthorData = CUser::GetByID($ownerId);
    $authorData = $rsAuthorData->Fetch();

    $format_name = $authorData['NAME'];
    if($authorData['SECOND_NAME']) {
        $format_name .= ' ';
        $format_name .= $authorData['SECOND_NAME'];
    }
    if($authorData['LAST_NAME']) {
        $format_name .= ' ';
        $format_name .= $authorData['LAST_NAME'];
    }

    CModule::IncludeModule('iblock');

    $el = new CIBlockElement;

    $PROP = array();

    $PROP['OWNER']     = $post['owner_id'];
    $PROP['FROM']      = $post['from_id'];
    $PROP['POST_ID']   = $post['id'];
    $PROP['DATE_POST'] = $post['date_post'];

    $arLoadProductArray = Array(
      "MODIFIED_BY"    => $USER->GetID(),
      "IBLOCK_SECTION_ID" => false,
      "IBLOCK_ID"      => 25,
      "PROPERTY_VALUES"=> $PROP,
      "NAME"           => $format_name,
      "DETAIL_TEXT"    => html_entity_decode($post['message']),
      "ACTIVE"         => "Y"
      );

    if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        $result['status'] = 'success';
        $result['id'] = $PRODUCT_ID;
    } else {
        $result['status'] = 'error';
        $result['message'] = "Error: ".$el->LAST_ERROR;
    }

} elseif($input['type'] == 'no-spam') {

    CModule::IncludeModule('iblock');

    $res = CIBlockElement::Delete($input['spam']);

    $result['status'] = 'success';

} elseif($input['type'] == 'bookmark') {

    $date_add = (int) time();

    $stmt = $dbh->prepare("INSERT INTO a_bookmark (user_id, type, uz_id, date_create) VALUES (:user_id, 6, :uz_id, :date_create)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':uz_id', $input['id'], PDO::PARAM_INT);
    $stmt->bindParam(':date_create', $date_add, PDO::PARAM_INT);
    $stmt->execute();
    $result['id'] = $dbh->lastInsertId();

    $result['status'] = 'success';

} elseif($input['type'] == 'del-bookmark' || $input['type'] == 'del-chat-bookmark') {

    $result['res'] = $dbh->exec('DELETE FROM a_bookmark WHERE user_id = ' . $user_id . ' AND uz_id = ' . $input['id'] . ' AND type = 6');

    $result['status'] = 'success';

} else {
    $result['status'] = 'error';
    $result['message'] = html_entity_decode("Error: Не задан режим.");
}

$data = $result ? $result : array('error' => 'Ошибка удаления.');

die(json_encode($data));
?>