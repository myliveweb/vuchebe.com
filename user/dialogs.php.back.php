<style>
.display-name {
	color: #000 !important;
	cursor: pointer;
	text-decoration-color: #ff471a;
}
.display-name span {
	color: #ff471a;
}
/*#chat {
	width: 100%;
	height: 400px;
	background-color: #fff;
	border: 1px solid #ff471a;
	border-radius: 4px;
	padding: 15px 15px;
	overflow: scroll;
}*/
#chat .chat-left, #chat .chat-right {
	margin: 15px 0 20px 0;
}
#chat .chat-left {
	text-align: left;
}
#chat .chat-right {
	text-align: right;
}
#chat .chat-left .message_chat_wrapper img.avatar_chat,
#chat .chat-right .message_chat_wrapper img.avatar_chat {
	border-radius: 50%;
    border: 1px solid #ff471a;
	display: inline-block;
    vertical-align: top;
	width: 22px;
    height: 22px;
}
#chat .chat-left .message_chat_wrapper img.avatar_duz {
	position: relative;
    right: -6px;
    top: 12px;
    vertical-align: top;
}
#chat .chat-right .message_chat_wrapper img.avatar_duz {
	position: relative;
    right: 6px;
    top: 12px;
    vertical-align: top;
}
#chat .message_chat {
	border-radius: 4px;
	padding: 10px 15px;
	display: inline-block;
	max-width: 80%;
	text-align: left;
}
#chat .message_chat_user {
	margin-bottom: 7px;
}
#chat .chat-left .message_chat {
	border: 1px solid #4b4b4b;
	background-color: #fbfbfb;
	margin-left: 5px;
}
#chat .chat-right .message_chat {
	border: 1px solid #ff471a;
	background-color: #fbfbfb;
	margin-right: 5px;
}
#chat .chat-left .message_chat_user a {
	text-decoration: none;
    border-bottom: 1px dashed #fff;
    font-family: Verdana, "sans-serif";
    cursor: pointer;
    color: #4b4b4b;
    margin-left: 40px;
}
#chat .chat-right .message_chat_user a {
	text-decoration: none;
    border-bottom: 1px dashed #fff;
    font-family: Verdana, "sans-serif";
    cursor: pointer;
    color: #ff471a;
}
#chat .chat-left .message_chat_user a:hover {
    border-color: #4b4b4b;
}
#chat .chat-right .message_chat_user a:hover {
    border-color: #ff471a;
}
#chat .message_chat_user span {
	font-size: 11px;
	color: gray;
}
#chat .chat-right .message_chat_user span {
    margin-right: 40px;
}
#chat .del-mes-right,
#chat .del-mes-left {
	position: absolute;
	font-size: 12px;
	border-bottom: 1px dotted gray;
	color: gray;
	cursor: pointer;
	bottom: -22px;
	display: none;
}

#chat .del-mes-right {
	right: 0px;
}

#chat .del-mes-left {
	left: 0px;
}
</style>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>

<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Мои сообщения</span>
	</div><br>
	<div class="page-content">
		<div class="name-block text-center txt-up"><span>Сообщения</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="line" id="chat" data-type="dialogs">
<?
$userMain = $_SESSION['USER_DATA']['ID'];

// ---- Получаем всех с кем были сообщения ----------
$resultUser = array();
$resultDialog = $dbh->query('SELECT owner_id, from_id from a_chat WHERE del = 0 AND del_all = 0 AND (owner_id = ' . $userMain . ' OR from_id = ' . $userMain . ') ORDER BY date_post DESC')->fetchAll();
foreach ($resultDialog as $item) {
	if($item['owner_id'] == $userMain) {
		if(!in_array($item['from_id'], $resultUser))
			$resultUser[] = $item['from_id'];
	} elseif($item['from_id'] == $userMain) {
		if(!in_array($item['owner_id'], $resultUser))
			$resultUser[] = $item['owner_id'];
	}
}
// ---- Создаём список последних постов ------------
$arrPost = array();
foreach($resultUser as $itemUser) {
	$resPost = $dbh->query('SELECT * from a_chat WHERE del = 0 AND del_all = 0 AND ((owner_id = ' . $itemUser . ' AND from_id = ' . $userMain . ') OR (owner_id = ' . $userMain . ' AND from_id = ' . $itemUser . ')) ORDER BY date_post DESC')->fetch();
	$arrPost[] = $resPost;
}

// ---- Запускаем главный цикл формирования ленты ---
foreach($arrPost as $itemPost) {
	// ---- Кто кому писал ----
	// 1 - мне писали
	// 0 - я писал
	$road = 1;
	if($itemPost['owner_id'] == $userMain) {
		$road = 0;
		$idUser = $itemPost['from_id'];
	} else {
		$idUser = $itemPost['owner_id'];
	}
	// ---- Получаем Пользователя ----
	$rsUserData = CUser::GetByID($idUser);
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

	// ---- Получаем Аватар ----------
	if($userData['PERSONAL_PHOTO']) {
		$avatar_url = CFile::GetPath($userData['PERSONAL_PHOTO']);
	} else {
		$avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
	}
	// ---- Формируем полное имя -----
	if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
		$format_name = '<span>' . strtoupper(substr(trim($userData['NAME']), 0, 1)) . '</span>' . substr(trim($userData['NAME']), 1);
		if($userData['SECOND_NAME']) {
			$format_name .= ' ';
			$format_name .= '<span>' . strtoupper(substr($userData['SECOND_NAME'], 0, 1)) . '</span>' . substr($userData['SECOND_NAME'], 1);
		}
		$format_name .= ' ';
		$format_name .= '<span>' . strtoupper(substr(trim($userData['LAST_NAME']), 0, 1)) . '</span>' . substr(trim($userData['LAST_NAME']), 1);
	} else {
		$format_name = '<span>' . strtoupper(substr(trim($userData['LOGIN']), 0, 1)) . '</span>' . substr(trim($userData['LOGIN']), 1);
	}

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
	// ---- обрезаем длинные сообщения -----
	if(strlen($itemPost['message']) > 130) {
		$itemPost['message'] = substr(trim($itemPost['message']), 0, 128) . '..';
	}
	?>
	<div class="news-item">
		<div class="col-3 content-left">
			<div class="image brd rad-50">
				<a href="/user/<?php echo $userData['ID']; ?>/">
					<img class="profile-avatar" style="height: 109px;<?php if($userData['TEACHER']) { echo ' border: 3px solid #ff5b32;'; } ?>" src="<?=$avatar_url?>" alt="img">
				</a>
			</div>
		</div>
		<div class="col-9 content-right">
			<div class="page-info" style="/*position: absolute;*/">
				<h1 class="name-user">
					<span><a href="/user/<?php echo $userData['ID']; ?>/" class="display-name"><?=$format_name?></a></span>
					<?php if(CUser::IsOnLine($userData['ID'], 30) && $userData['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
					<div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
					<?php } ?>
				</h1>
				<div class="contact-info">
				<?php if($road) { ?>
					<div class="chat-left">
						<a href="/user/chat/<?php echo $userData['ID']; ?>/" style="text-decoration: none; color: #000;">
							<div class="message_chat_wrapper">
								<div class="message_chat_user">
									<?=trim($itemPost['owner_display_name']);?> <span style="display: inline-block; margin-bottom: 0px;"><?php echo get_str_time($itemPost['date_post']); ?></span>
								</div>
								<img class="avatar_chat" src="<?=$avatar_url?>" alt="img" <?php if($userData['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
								<?php if($itemPost['success'] || $userData['WORK_FAX'] || $_SESSION['USER_DATA']['WORK_FAX']) { ?>
								<img style="right: -2px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png" alt="img">
								<div class="message_chat" style="margin-left: -4px; position: relative;"><div class="del-mes-left js-del" data-type="chat" data-id="<?php echo $itemPost['id']; ?>" data-owner="<?php echo $itemPost['owner_id']; ?>" data-from="<?php echo $itemPost['from_id']; ?>">удалить чат</div><?=trim($itemPost['message']);?></div></div>
								<?php } else { ?>
								<img style="right: -2px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3_no.png" alt="img">
								<div class="message_chat" style="margin-left: -4px; position: relative; background-color: #4b4b4b; color: #fff;"><div class="del-mes-left js-del" data-type="chat" data-id="<?php echo $itemPost['id']; ?>" data-owner="<?php echo $itemPost['owner_id']; ?>" data-from="<?php echo $itemPost['from_id']; ?>">удалить чат</div><?=trim($itemPost['message']);?></div></div>
								<?php } ?>
							</div>
						</a>
					</div>
				<?php } else { ?>
					<div class="chat-right">
						<a href="/user/chat/<?php echo $userData['ID']; ?>/" style="text-decoration: none; color: #000;">
							<div class="message_chat_wrapper" style="position: relative;">
								<div class="message_chat_user" style="color: #ff471a;">
									Я <span style="display: inline-block; margin-bottom: 0px;"><?php echo get_str_time($itemPost['date_post']); ?></span>
								</div>
								<?php if($itemPost['success'] || $userData['WORK_FAX'] || $_SESSION['USER_DATA']['WORK_FAX']) { ?>
									<div class="message_chat" style="margin-right: -4px; position: relative;"><div class="del-mes-right js-del" data-type="chat" data-id="<?php echo $itemPost['id']; ?>" data-owner="<?php echo $itemPost['owner_id']; ?>" data-from="<?php echo $itemPost['from_id']; ?>">удалить чат</div><?=trim($itemPost['message']);?></div></div>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3.png" alt="img">
								<?php } else { ?>
									<div class="message_chat" style="margin-right: -4px; position: relative; background-color: #ff471a; color: #fff;"><div class="del-mes-right js-del" data-type="chat" data-id="<?php echo $itemPost['id']; ?>" data-owner="<?php echo $itemPost['owner_id']; ?>" data-from="<?php echo $itemPost['from_id']; ?>">удалить чат</div><?=trim($itemPost['message']);?></div></div>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3_no.png" alt="img">
								<?php } ?>
								<img class="avatar_chat" src="<?php echo $_SESSION['USER_DATA']['AVATAR']; ?>" alt="img" <?php if($_SESSION['USER_DATA']['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
							</div>
						</a>
					</div>
				<?php } ?>
				</div><!-- contact-info -->
			</div>
		</div><!-- content-right -->
	</div>
	<?php
}
//echo '<pre>';
//var_dump($_SESSION['USER_DATA']);
//echo '</pre>';
?>
				</div>
			</div>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>