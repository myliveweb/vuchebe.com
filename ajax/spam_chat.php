<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$error = array();
$result = array();

$user_id = 0;

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
}

$input = filter_input_array(INPUT_POST);

$id          = (int) $input['id'];
$type        = $input['type'];
$userCurrent = (int) $input['user'];
$postId        = (int) $input['post'];

if($postId && isEdit()) {

    $current = $dbh->query('SELECT * from a_chat WHERE id = ' . $postId . ' ORDER BY id ASC')->fetch();

    if($current['group_chat']) {
        $stmt = $dbh->prepare("SELECT * from a_chat WHERE group_chat = :group_chat ORDER BY id ASC");
        $stmt->execute(array(':group_chat' => $current['group_chat']));
        $newPost = $stmt->fetchAll();
    } else {
        $stmt = $dbh->prepare("SELECT * from a_chat WHERE (owner_id = :owner_id AND from_id = :from_id) OR (owner_id = :from_id AND from_id = :owner_id) ORDER BY id ASC");
        $stmt->execute(array(':owner_id' => $current['owner_id'], ':from_id' => $current['from_id']));
        $newPost = $stmt->fetchAll();
    }

    $useUser = array();
    $userInfo = array();

    foreach($newPost as $itemChat) {
        $post = array();

        $post['id'] = $itemChat['id'];
        $post['owner'] = $itemChat['owner_id'];
        $post['from'] = $itemChat['from_id'];
        if($post['owner'] == $user_id) {
            $post['side'] = 'right';
        } elseif(!$post['owner']) {
            $post['side'] = 'sys';
        } else {
            $post['side'] = 'left';
        }
        $post['date'] = $itemChat['date_post'];
        $post['dateFormat'] = get_str_time($itemChat['date_post'] + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60));

        if($useUser[$post['owner']]) {
            $userChat = $useUser[$post['owner']];
        } else {
            $userObj = CUser::GetByID($post['owner']);
            $userChat = $userObj->Fetch();
            $useUser[$post['owner']] = $userChat;
        }

        $full_name = $userChat['NAME'];
        if($userChat['SECOND_NAME']) {
            $full_name .= ' ';
            $full_name .= $userChat['SECOND_NAME'];
        }
        $full_name .= ' ';
        $full_name .= $userChat['LAST_NAME'];

        $post['fullName'] = $full_name;

        $format_name = '<span style="font-size: 14px;">' . strtoupper(mb_substr(trim($userChat['NAME']), 0, 1)) . '</span>' . mb_substr(trim($userChat['NAME']), 1);
        if($userChat['SECOND_NAME']) {
            $format_name .= ' ';
            $format_name .= '<span style="font-size: 14px;">' . strtoupper(mb_substr($userChat['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($userChat['SECOND_NAME'], 1);
        }
        $format_name .= ' ';
        $format_name .= '<span style="font-size: 14px;">' . strtoupper(mb_substr(trim($userChat['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($userChat['LAST_NAME']), 1);

        $post['formatName'] = $format_name;

        $post['spam'] = 0;
        if($post['id'] == $postId)
            $post['spam'] = 1;

        if($userChat['PERSONAL_PHOTO']) {
            $post['pic'] = CFile::GetPath($userChat['PERSONAL_PHOTO']);
        } else {
            $post['pic'] = SITE_TEMPLATE_PATH . "/img/foto-user.png";
        }

        $post['teacher'] = 0;
        $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $post['id'])->fetch();
        if($arrTeacher['cnt'] > 0) {
            $post['teacher'] = 1;
        }

        $post['online'] = 0;
        if(CUser::IsOnLine($post['id'], 30)) {
            $post['online'] = 1;
        }

        $post['url'] = getUserUrl($userChat);

        $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@i';
        $replacement = '<a href="$1" target="_blank">$1</a>';
        $post['message'] = preg_replace($pattern, $replacement, $itemChat['message']);

        if($post['side'] == 'sys') {
            $userObjSys = CUser::GetByID($itemChat['group_owner']);
            $userChatSys = $userObjSys->Fetch();

            $post['urlSys'] = getUserUrl($userChatSys);

            $full_name_sys = $userChatSys['NAME'];
            if($userChatSys['SECOND_NAME']) {
                $full_name_sys .= ' ';
                $full_name_sys .= $userChatSys['SECOND_NAME'];
            }
            $full_name_sys .= ' ';
            $full_name_sys .= $userChatSys['LAST_NAME'];

            $post['fullNameSys'] = $full_name_sys;
        }

        $result[] = $post;
    }
}

list($day, $month, $year) = explode(',', date("j,n,Y"));
$curTime = mktime(0, 0, 0, $month, $day, $year);

$data = $result ? array("status" => "success", "res" => $result, "line" => $curTime) : array("status" => "error", 'message' => 'Ошибка обработки запроса.');
die(json_encode($data));
?>