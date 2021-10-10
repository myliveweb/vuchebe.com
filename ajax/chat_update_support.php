<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$load      = $input['load'];
$groupChat = (int) $input['group'];

$user_id = 0;
$user_name = '';

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
    $user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if(!$user_id)
    die(json_encode(array("status" => "error", 'message' => 'Необходима авторизация')));

$arrDelPost = array();
$arrPost = $dbh->query('SELECT post_id from a_support_del_local WHERE chat_id = ' . $groupChat . ' AND user_id = ' . $user_id)->fetchAll();
foreach($arrPost as $item)
    $arrDelPost[] = $item['post_id'];

$mergeArr = array_merge($load, $arrDelPost);

if($mergeArr) {
    $inPost = implode(', ', $mergeArr);
    $newPost = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $groupChat . ' AND id NOT IN(' . $inPost . ') AND del_to = 0 AND owner_id != ' . $user_id)->fetchAll();
} else {
    $newPost = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $groupChat . ' AND del_to = 0 AND owner_id != ' . $user_id)->fetchAll();
}
foreach($newPost as $item) {
    $arrTemp = array();
    if($item['owner_id']) {

        $userObj = CUser::GetByID($item['owner_id']);
        $userChat = $userObj->Fetch();

        $arrTemp['id'] = $item['id'];
        $arrTemp['userid'] = $userChat['ID'];
        $arrTemp['usermain'] = $user_id;
        $arrTemp['time'] = get_str_time($item['date_post'] + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60));

        $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@i';
        $replacement = '<a href="$1" target="_blank">$1</a>';
        $arrTemp['message'] =  preg_replace($pattern, $replacement, $item['message']);

        if(strlen(trim($userChat['NAME'])) && strlen(trim($userChat['LAST_NAME']))) {
            $format_name = '<span style="font-size: 14px;">' . strtoupper(mb_substr(trim($userChat['NAME']), 0, 1)) . '</span>' . mb_substr(trim($userChat['NAME']), 1);
            if($userChat['SECOND_NAME']) {
                $format_name .= ' ';
                $format_name .= '<span style="font-size: 14px;">' . strtoupper(mb_substr($userChat['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($userChat['SECOND_NAME'], 1);
            }
            $format_name .= ' ';
            $format_name .= '<span style="font-size: 14px;">' . strtoupper(mb_substr(trim($userChat['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($userChat['LAST_NAME']), 1);
        } else {
            $format_name = '<span style="font-size: 14px;">' . strtoupper(mb_substr(trim($userChat['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($userChat['LOGIN']), 1);
        }

        $arrTemp['displayname'] = $format_name;

        if($userChat['PERSONAL_PHOTO']) {
            $arrTemp['avatar'] = CFile::GetPath($userChat['PERSONAL_PHOTO']);
        } else {
            $arrTemp['avatar'] = SITE_TEMPLATE_PATH . "/img/foto-user.png";
        }

        if($userChat['WORK_WWW']) {
            $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $userChat['ID'])->fetch();
            if ($arrTeacher['cnt'] > 0) {
                $arrTemp['teacher'] = 1;
                $arrTemp['user'] = 0;
            } else {
                $arrTemp['teacher'] = 0;
                $arrTemp['user'] = 1;
            }
        } else {
            $arrTemp['teacher'] = 0;
            $arrTemp['user'] = 1;
        }

        if(CUser::IsOnLine($userChat['ID'], 30) && $userChat['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) {
            $arrTemp['online'] = 1;
        } else {
            $arrTemp['online'] = 0;
        }

        $arrTemp['admin'] = 0;

        $arrTemp['class'] = '';
        if($arrTemp['teacher'])
            $arrTemp['class'] .= ' teacher';
        else
            $arrTemp['class'] .= ' user';

        if($arrTemp['admin'])
            $arrTemp['class'] .= ' admin';

        $stmt = $dbh->prepare("INSERT INTO a_user_success (chat_id, user_id, post_id) VALUES (:chat_id, :user_id, :post_id)");
        $stmt->bindParam(':chat_id', $groupChat, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $item['id'], PDO::PARAM_INT);
        $stmt->execute();

    } else {

    }

    $result[] = $arrTemp;
}

die(json_encode(array("status" => "success", 'update' => $result)));
?>