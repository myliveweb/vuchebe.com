<?php
//$supportObserver = 1;
$btnSupportActive = 1;

$userMain = $_SESSION['USER_DATA']['ID'];

$input = filter_input_array(INPUT_POST);

if(isEdit()) {
    $filter = 'new';
} else {
    $filter = 'all';
}

if($input['filter']) {
    $filter = $input['filter'];
}

$search = '';
if($input['s']) {
    $search = $input['s'];
}

?>
<link rel="stylesheet" href="/user/dialogs.css">
<div class="st-content-right st-content-users" id="box-line">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $_SESSION['USER_DATA']['ID']; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Техническая поддержка</span>
	</div>
    <div class="page-content" id="page">
        <?php if(isEdit()) { ?>
        <div class="structure-cat bg-silver text-center" style="margin: 15px 0 20px;">
            <div class="row-line">
                <form action="/user/<?php echo $userMain; ?>/service/" id="support-search" method="post" accept-charset="utf-8">
                    <div class="col-10 search-filed" style="padding: 0 0 0 15px;">
                        <input type="text" name="s" id="support-text" value="<?php echo $search; ?>" />
                        <input type="hidden" name="p" value="1" />
                        <input type="hidden" name="filter" id="filterinput" value="<?php echo $filter; ?>" />
                    </div>
                    <div class="col-2 button-filed">
                        <button type="submit" style="line-height: 30px; width: 100%;">
                            <span class="short"><i class="fa fa-search"></i></span>
                            <span class="full">найти</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php } else { ?>
            <div style="height: 1px;"></div>
        <?php } ?>
        <div class="name-block text-center txt-up" style="margin: 10px 0 12px 0;"><span>Техническая поддержка</span></div>
        <div class="st-content-bottom clear">
			<div class="module st-news">
                <div style="text-align: center; margin-top: 15px;">
                    <?php if(isEdit()) { ?>
                        <a href="#" class="add-support-chat" style="text-decoration: none;"><span class="color-silver" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: -14px;">Создать новую заявку</span></a>
                    <?php } else { ?>
                        <a href="/user/support/" style="text-decoration: none;"><span data-id="0" class="color-silver" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: -14px;">Создать новую заявку</span></a>
                    <?php } ?>
                </div>
                <?php if(isEdit()) { ?>
                <div class="m-header" id="group-filter" style="margin: 0 0 27px;">
                    <a href="#" data-filter="new" class="filter-service color-silver">Новые</a>
                    <a href="#" data-filter="open" class="filter-service">Открытые</a>
                    <a href="#" data-filter="all" data-sort="ticket" class="filter-service sort">№</a>
                    <a href="#" data-filter="pro" class="filter-service">Бизнес-аккаунты</a>
                    <a href="#" data-filter="user" class="filter-service">Пользователи</a>
                    <a href="#" data-filter="close" class="filter-service">Закрытые</a>
                    <a href="#" data-filter="del" class="filter-service">Удаленные</a>
                    <a href="#" data-filter="all" data-sort="time" class="filter-service sort">Все</a>
                    <div style="float: right;<?php if(!$search || 1) echo ' display: none;'; ?>">
                        <a href="/user/<?php echo $userMain; ?>/service/" class="filter-service">вернуться к списку тикетов</a> &nbsp;
                    </div>
                </div>
                <?php } else { ?>
                    <div style="height: 10px;"></div>
                <?php } ?>
				<div class="line" id="all">
<?php

function cmp($a, $b) {
    if ($a['date_post'] == $b['date_post']) {
        return 0;
    }
    return ($a['date_post'] > $b['date_post']) ? -1 : 1;
}

// ---- Получаем список всех тикетов ----------
$resultUser = array();
$arrPost = array();

$arrDelPost = array();
$arrPost = $dbh->query('SELECT post_id from a_support_del_local WHERE user_id = ' . $userMain)->fetchAll();
foreach($arrPost as $item)
    $arrDelPost[] = $item['post_id'];

$arrPostId = array();
$arrPostNewMessage = $dbh->query('SELECT post_id from a_user_success WHERE user_id = ' . $userMain . ' AND chat_id > 342400')->fetchAll();
foreach ($arrPostNewMessage as $item)
    $arrPostId[] = $item['post_id'];

if($search) {

    if(is_numeric($search)) {

        $arrTempId = array();
        $arrPostIdNum = $dbh->query('SELECT * FROM a_chat_support WHERE group_chat LIKE "%' . $search . '%" ORDER BY id DESC')->fetchAll();
        foreach ($arrPostIdNum as $itemNum) {
            if(!in_array($itemNum['group_chat'], $arrTempId)) {
                $arrTempId[] = $itemNum['group_chat'];
                $arrPost[] = $itemNum;
            }
        }
    } else {

        $arrUniqPost = array();
        $arrUniqPostMessage = $dbh->query('SELECT * FROM a_chat_support GROUP BY owner_id, group_chat ORDER BY id DESC')->fetchAll();
        foreach ($arrUniqPostMessage as $item)
            $arrUniqPost[] = $item;

        $arrUserId = array();
        $dataUser = CUser::GetList($by = "ID", $order = "ASC", array('NAME' => $search));
        while ($arUser = $dataUser->Fetch()) {
            $arrUserId[] = $arUser['ID'];
        }

        foreach ($arrUniqPost as $itemUniqPost) {
            if (in_array($itemUniqPost['owner_id'], $arrUserId))
                $arrPost[] = $itemUniqPost;
        }
    }

} else {

    if (isEdit()) {
        $resultDialog = $dbh->query('SELECT group_chat from a_chat_support GROUP BY group_chat ORDER BY date_post ASC')->fetchAll();
    } else {
        $resultDialog = $dbh->query('SELECT group_chat from a_chat_support WHERE group_owner = ' . $userMain . ' AND del_to = 0 GROUP BY group_chat ORDER BY date_post ASC')->fetchAll();
    }

    foreach ($resultDialog as $groupItem) {
        if ($arrDelPost) {
            $inPost = implode(', ', $arrDelPost);
            $resPost = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $groupItem['group_chat'] . ' AND id NOT IN(' . $inPost . ') ORDER BY date_post DESC')->fetch();
        } else {
            $resPost = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $groupItem['group_chat'] . ' ORDER BY date_post DESC')->fetch();
        }
        if ($resPost) {
            //$actualGroup = $dbh->query('SELECT user_group from a_chat_support WHERE group_chat = ' . $resPost['group_chat'] . ' ORDER BY id ASC')->fetch();
            //$resPost['group_chat'] = $actualGroup['group_chat'];
            $arrPost[] = $resPost;
        }
    }
}

usort($arrPost, "cmp");

// ---- Запускаем главный цикл формирования ленты ---
foreach($arrPost as $itemPost) {

	if(!$itemPost['id'])
		continue;

	// ---- Кто кому писал ----
	// 1 - мне писали
	// 0 - я писал

	$road = 1;
	if($itemPost['owner_id'] == $userMain) {
		$road = 0;
		$idUser = $itemPost['owner_id'];
	} else {
		$idUser = $itemPost['owner_id'];
	}
	// ---- Получаем Пользователя ----
	$rsUserData = CUser::GetByID($idUser);
	$userData = $rsUserData->Fetch();

    $group = 0;
    $admin = 0;
    $newMessage = 0;

    $group = $itemPost['group_chat'];

    $groupChat = $dbh->query('SELECT * from a_group_chat WHERE id = ' . $group)->fetch();

    // ---- Получаем Пользователя ----
    $rsUserData = CUser::GetByID($itemPost['owner_id']);
    $userData = $rsUserData->Fetch();

    if($userData['WORK_WWW']) {
        $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $userData['ID'])->fetch();
        if($arrTeacher['cnt'] > 0) {
            $userData['TEACHER'] = 1;
        } else {
            $userData['TEACHER'] = 0;
        }
    } else {
        $userData['TEACHER'] = 0;
    }

    $avatar_chat = SITE_TEMPLATE_PATH . "/img/adminava.png";
    // ---- Получаем Аватар ----------
    if($userData['PERSONAL_PHOTO']) {
        $avatar_url = CFile::GetPath($userData['PERSONAL_PHOTO']);
    } else {
        $avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
    }

    $groupChat['name'] = 'Тикет №' . $itemPost['group_chat'];

    $css = '';

    if(!in_array($itemPost['id'], $arrPostId)) {
        $newMessage = 1;
        $css .= ' new';
    }

    if($itemPost['del_owner'] == 0) {
        $css .= ' open';
    } elseif($itemPost['del_owner'] > 0) {
        $css .= ' close';
        if($itemPost['del_to'] > 0) {
            $css .= ' del';
        }
    }

    $userTo = 0;
    if($itemPost['mark'] > 0) {
        $userTo = $itemPost['mark'];
    } else {
        $userTo = $itemPost['user_group'];
    }

    if($userTo == 6 || $userTo == 7) {
        $css .= ' pro';
    } else {
        $css .= ' user';
    }

    $format_name = '<span>' . strtoupper(mb_substr(trim($groupChat['name']), 0, 1)) . '</span>' . mb_substr(trim($groupChat['name']), 1);

    $linkUser = '/user/' . $userData['ID'] . '/';
    $linkChat = '/user/support/' . $group . '/';

    $displayNameTop = $groupChat['name'];

    $displayName = $userData['NAME'];

    if($userData['SECOND_NAME'])
        $displayName .= ' ' . $userData['SECOND_NAME'];

    $displayName .= ' ' . $userData['LAST_NAME'];

    $arrAdminGroupId = array();
    $arrAdmin = $dbh->query('SELECT user_id from a_group_admin WHERE chat_id = ' . $group)->fetchAll();
    foreach($arrAdmin as $item)
        $arrAdminGroupId[] = $item['user_id'];

    if(in_array($userData['ID'], $arrAdminGroupId))
        $admin = 1;

	// ---- обрезаем длинные сообщения -----
	//
	$itemPost['message'] = preg_replace('#(<br */?>\s*)+#i', '<br />', $itemPost['message']);

    $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@i';
    $replacement = '<a href="$1" target="_blank">$1</a>';
    $itemPost['message'] =  preg_replace($pattern, $replacement, $itemPost['message']);
	?>
	<div class="news-item all<?php echo $css; ?>" data-ticket="<?php echo $itemPost['group_chat']; ?>" data-time="<?php echo $itemPost['date_post']; ?>" <?php if(!$newMessage && $filter == 'new') { echo 'style="display: none;"'; } ?>>
		<div class="col-3 content-left">
			<div class="image brd rad-50">
				<a href="<?php echo $linkChat; ?>">
					<img class="profile-avatar" style="height: 109px;" src="<?=$avatar_chat?>" alt="img">
				</a>
			</div>
		</div>
		<div class="col-9 content-right">
			<div class="page-info">
				<h1 class="name-user">
					<span><a href="<?php echo $linkChat; ?>" class="display-name"><?=$format_name?></a></span>
                    <?php if($newMessage) { ?>
                        <a id="new-chat" class="img-top" style="margin-left: 8px; display: inline-block; " href="<?php echo $linkChat; ?>">
                            <img style="width: 100%; height: 100%; border: 1px solid #ff471a; border-radius: 50%;" src="<?=SITE_TEMPLATE_PATH?>/img/new_chat_1.png" alt="У вас новое сообщение" title="У вас новое сообщение">
                        </a>
					<?php } ?>
                    <?php if($itemPost['del_owner']) { ?>
                        <div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; color: #ff471a; font-size: 14px;">
                            тикет закрыт
                            <?php if($itemPost['del_to']) { ?>
                                <div style="display: inline-block; position: relative; top: -1px; margin-left: 0px; color: #ff471a; font-size: 14px;">(удалён)</div>
                            <?php } ?>
                        </div>
                    <?php } ?>
				</h1>
				<div class="contact-info">
				<?php if($road) {
				        if($group && !$itemPost['owner_id']) {

                            $userSys = CUser::GetByID($itemPost['group_owner']);
                            $userSys = $userSys->Fetch();

                            $fullNameSys = trim($userSys['NAME']);
                            if(trim($userSys['SECOND_NAME']))
                                $fullNameSys .= ' ' . trim($userSys['SECOND_NAME']);
                            if(trim($userSys['LAST_NAME']))
                                $fullNameSys .= ' ' . trim($userSys['LAST_NAME']);

                            if (strlen($fullNameSys) <= 0)
                                $fullNameSys = trim($userSys['LOGIN']);
				            ?>
                            <div class="chat-left">
                                <div class="message_chat_wrapper">
                                    <a href="<?php echo $linkChat; ?>" style="text-decoration: none; color: #000;">
                                        <?php if($admin) { ?>
                                            <div class="message-chat-system" style="margin-left: -4px; position: relative;"><div class="del-mes-left js-support-chat-close" data-chat-id="<?php echo $group; ?>" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>">выйти из чата</div><a href="/user/<?php echo $userSys['ID']; ?>/"><?php echo $fullNameSys; ?></a> <?php echo trim($itemPost['message']); ?></div>
                                        <?php } else { ?>
                                            <div class="message-chat-system" style="margin-left: -4px; position: relative;"><a href="/user/<?php echo $userSys['ID']; ?>/"><?php echo $fullNameSys; ?></a> <?=trim($itemPost['message']);?></div>
                                        <?php } ?>
                                    </a>
                                </div>

                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="chat-left">
                                <div class="message_chat_wrapper">
                                    <div class="message_chat_user">
                                        <a href="<?php echo $linkUser; ?>">
                                            <?php echo $displayName; ?> <span style="display: inline-block; margin-bottom: 0px;"><?php echo get_str_time($itemPost['date_post'] + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60)); ?></span>
                                        </a>
                                    </div>
                                    <a href="<?php echo $linkChat; ?>" style="text-decoration: none; color: #000;">
                                        <img class="avatar_chat" src="<?=$avatar_url?>" alt="img" <?php if($userData['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
                                        <img style="right: -2px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png" alt="img">
                                        <?php if(!$itemPost['del_owner']) { ?>
                                            <div class="message_chat" style="margin-left: -4px; position: relative;"><div class="del-mes-left js-up-down js-support-chat-close" data-chat-id="<?php echo $group; ?>" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>">закрыть тикет</div><?php echo trim($itemPost['message']); ?></div>
                                        <?php } elseif($itemPost['del_owner'] && !$itemPost['del_to']) { ?>
                                            <div class="message_chat" style="margin-left: -4px; position: relative;"><div class="del-mes-left js-up-down js-support-chat-delete" data-chat-id="<?php echo $group; ?>" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>">удалить тикет</div><?php echo trim($itemPost['message']); ?></div>
                                        <?php } else { ?>
                                            <div class="message_chat" style="margin-left: -4px; position: relative;"><?php echo trim($itemPost['message']); ?></div>
                                        <?php } ?>
                                    </a>
                                </div>

                            </div>
                        <?php
                        }
				    } else { ?>
					<div class="chat-right">
							<div class="message_chat_wrapper" style="position: relative;">
								<div class="message_chat_user" style="color: #ff471a;">
                                    <a href="/user/<?php echo $userMain; ?>/">
									Я <span style="display: inline-block; margin-bottom: 0px;"><?php echo get_str_time($itemPost['date_post'] + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60)); ?></span>
                                    </a>
                                </div>
                                <a href="<?php echo $linkChat; ?>" style="text-decoration: none; color: #000;">
                                <?php if(!$itemPost['del_owner']) { ?>
                                    <div class="message_chat" style="margin-right: -4px; position: relative;"><div class="del-mes-right js-up-down js-support-chat-close" data-chat-id="<?php echo $group; ?>" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>">закрыть тикет</div><?=trim($itemPost['message']);?></div>
                                <?php } elseif($itemPost['del_owner'] && !$itemPost['del_to']) { ?>
                                    <div class="message_chat" style="margin-right: -4px; position: relative;"><div class="del-mes-right js-up-down js-support-chat-delete" data-chat-id="<?php echo $group; ?>" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>">удалить тикет</div><?=trim($itemPost['message']);?></div>
                                <?php } else { ?>
                                    <div class="message_chat" style="margin-right: -4px; position: relative;"><?=trim($itemPost['message']);?></div>
                                <?php } ?>
                                <img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3.png" alt="img">
								<img class="avatar_chat" src="<?php echo $_SESSION['USER_DATA']['AVATAR']; ?>" alt="img" <?php if($_SESSION['USER_DATA']['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
                                </a>
                            </div>
					</div>
				<?php } ?>
				</div><!-- contact-info -->
			</div>
		</div><!-- content-right -->
	</div>
	<?php } ?>
				</div>
                <div class="line" id="new" style="display: none;"></div>
                <div class="line" id="open" style="display: none;"></div>
                <div class="line" id="pro" style="display: none;"></div>
                <div class="line" id="user" style="display: none;"></div>
                <div class="line" id="close" style="display: none;"></div>
                <div class="line" id="del" style="display: none;"></div>
			</div>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>