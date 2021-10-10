<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$new = 0;
$newGroup = 0;
$sound = 0;
$soundSupport = 0;

if($_SESSION['USER_DATA']['ID']) {

    $_SESSION['NEW_CHAT'] = 0;

	$new = $dbh->query('SELECT COUNT(id) as cnt from a_chat WHERE del_owner = 0 AND from_id = ' . $_SESSION['USER_DATA']['ID'] . ' AND success = 0')->fetch();
	if($new['cnt']) {
        //echo 'add group private ';
		$_SESSION['NEW_CHAT'] += $new['cnt'];
		$sound += $new['cnt'];
	}

    $arrGroupId = array();
    $arrGrou  = $dbh->query('SELECT chat_id from a_group_user WHERE user_id = ' . $_SESSION['USER_DATA']['ID'])->fetchAll();
    //---- Если у пользователя есть группы то проверяем какие посты он уже просмотрел --------
    if($arrGrou) {
        foreach ($arrGrou as $item)
            $arrGroupId[] = $item['chat_id'];

        $inGroup = implode(', ', $arrGroupId);

        $arrPostId = array();
        $arrPost = $dbh->query('SELECT post_id from a_user_success WHERE chat_id IN(' . $inGroup . ') AND user_id = ' . $_SESSION['USER_DATA']['ID'])->fetchAll();
        foreach ($arrPost as $item)
            $arrPostId[] = $item['post_id'];

        $arrDelPost = array();
        $arrPost = $dbh->query('SELECT post_id from a_group_del_local WHERE chat_id IN(' . $inGroup . ') AND user_id = ' . $_SESSION['USER_DATA']['ID'])->fetchAll();
        foreach($arrPost as $item)
            $arrDelPost[] = $item['post_id'];

        $mergeArr = array_merge($arrPostId, $arrDelPost);
        //---- Если у пользователя есть просмотренные посты или заблокированные то выбираем все кроме их --------
        if($mergeArr) {

            $inPost = implode(', ', $mergeArr);

            $newGroup = $dbh->query('SELECT COUNT(id) as cnt from a_chat WHERE del_to = 0 AND group_chat IN(' . $inGroup . ') AND id NOT IN(' . $inPost . ') AND owner_id != ' . $_SESSION['USER_DATA']['ID'])->fetch();
        } else {
        //---- Иначе просто выбираем --------------------------------------------------------
            $newGroup = $dbh->query('SELECT COUNT(id) as cnt from a_chat WHERE del_to = 0 AND group_chat IN(' . $inGroup . ') AND owner_id != ' . $_SESSION['USER_DATA']['ID'])->fetch();
        }

        if ($newGroup['cnt']) {
            $_SESSION['NEW_CHAT'] += $newGroup['cnt'];
            $sound += $newGroup['cnt'];
        }
    }

    $arrGroupSupportId = array();
    if(isEdit()) {
        $newSupport = $dbh->query('SELECT group_chat from a_chat_support WHERE del_owner = 0 GROUP BY group_chat ORDER BY id ASC')->fetchAll();
    } else {
        $newSupport = $dbh->query('SELECT group_chat from a_chat_support WHERE del_owner = 0 AND group_owner = ' . $_SESSION['USER_DATA']['ID'] . ' GROUP BY group_chat ORDER BY id ASC')->fetchAll();
    }

    foreach ($newSupport as $itemSupport)
        $arrGroupSupportId[] = $itemSupport['group_chat'];

    if($arrGroupSupportId) {

        $inPostIdSupport = implode(', ', $arrGroupSupportId);

        $arrPostId = array();
        $arrPost = $dbh->query('SELECT post_id from a_user_success WHERE chat_id IN(' . $inPostIdSupport . ') AND user_id = ' . $_SESSION['USER_DATA']['ID'])->fetchAll();
        foreach ($arrPost as $item)
            $arrPostId[] = $item['post_id'];

        $arrDelPost = array();
        $arrPost = $dbh->query('SELECT post_id from a_group_del_local WHERE chat_id IN(' . $inPostIdSupport . ') AND user_id = ' . $_SESSION['USER_DATA']['ID'])->fetchAll();
        foreach ($arrPost as $item)
            $arrDelPost[] = $item['post_id'];

        $mergeArr = array_merge($arrPostId, $arrDelPost);
        if($mergeArr) {
            $inPostSupport = implode(', ', $mergeArr);
            $newPostSupport = $dbh->query('SELECT COUNT(id) as cnt from a_chat_support WHERE del_owner = 0 AND group_chat IN(' . $inPostIdSupport . ') AND id NOT IN(' . $inPostSupport . ') AND owner_id != ' . $_SESSION['USER_DATA']['ID'])->fetch();
            //var_dump($newPostSupport);
            //$newPostSupportDump = $dbh->query('SELECT id from a_chat_support WHERE del_owner = 0 AND group_chat IN(' . $inPostIdSupport . ') AND id NOT IN(' . $inPostSupport . ') AND owner_id != ' . $_SESSION['USER_DATA']['ID'])->fetchAll();
            //var_dump($newPostSupportDump);
        } else {
            $newPostSupport = $dbh->query('SELECT COUNT(id) as cnt from a_chat_support WHERE del_owner = 0 AND group_chat IN(' . $inPostIdSupport . ') AND owner_id != ' . $_SESSION['USER_DATA']['ID'])->fetch();
        }

        if ($newPostSupport['cnt']) {
            $_SESSION['NEW_CHAT_SUPPORT'] = $newPostSupport['cnt'];
            $soundSupport = $newPostSupport['cnt'];
        }

    }
}

$data = array("status" => "success", 'new' => $sound, 'sound' => $sound, 'newSupport' => $soundSupport, 'soundSupport' => $soundSupport);
die(json_encode($data));
?>