<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$id = (int) $input['id'];

$user_id   = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id   = (int) $_SESSION['USER_DATA']['ID'];
    $user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if(!$user_id)
  die(json_encode(array("status" => "error", 'message' => 'Необходима авторизация')));

if($id) {

    $arrAdminGroupId = array();
    $arrAdmin = $dbh->query('SELECT user_id from a_group_admin WHERE chat_id = ' . $id)->fetchAll();
    foreach($arrAdmin as $item)
        $arrAdminGroupId[] = $item['user_id'];

    if(!in_array($user_id, $arrAdminGroupId))
        die(json_encode(array("status" => "error", 'message' => 'Недостаточно прав')));

    $result['info'] = $dbh->query('SELECT * from a_group_chat WHERE id = ' . $id)->fetch();

    $arrUserGroup = array();
    $arrUser  = $dbh->query('SELECT user_id from a_group_user WHERE chat_id = ' . $id)->fetchAll();
    foreach($arrUser as $item) {

        $userObj = CUser::GetByID($item['user_id']);
        $userChat = $userObj->Fetch();

        $nameDisplay = trim($userChat['NAME']);
        if(trim($userChat['SECOND_NAME']))
            $nameDisplay .= ' ' . trim($userChat['SECOND_NAME']);
        if(trim($userChat['LAST_NAME']))
            $nameDisplay .= ' ' . trim($userChat['LAST_NAME']);

        if (strlen($nameDisplay) <= 0)
            $nameDisplay = $USER->GetLogin();

        $userChat['NAME_DISPLAY'] = $nameDisplay;

        if($userChat['PERSONAL_PHOTO']) {
            $avatar_url = CFile::GetPath($userChat['PERSONAL_PHOTO']);
        } else {
            $avatar_url = SITE_TEMPLATE_PATH . "/img/foto-user.png";
        }

        $userChat['AVATAR'] = $avatar_url;

        $userChat['ADMIN'] = 0;
        if(in_array($userChat['ID'], $arrAdminGroupId))
            $userChat['ADMIN'] = 1;

        if($userChat['WORK_WWW']) {
            $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $userChat['ID'])->fetch();
            if($arrTeacher['cnt'] > 0) {
                $userChat['TEACHER'] = 1;
            } else {
                $userChat['TEACHER'] = 0;
            }
        } else {
            $userChat['TEACHER'] = 0;
        }

        $result['user'][] = $userChat;
    }

    $result['status'] = 'success';
}

$data = $result ? $result : array("status" => "error", 'message' => 'Ошибка добавления чата');
die(json_encode($data));
?>