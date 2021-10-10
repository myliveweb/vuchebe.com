<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>
<link rel="stylesheet" href="/user/dialogs.css">
<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Мои сообщения</span>
	</div><br>
	<div class="page-content">
		<div class="name-block text-center txt-up"><span>Сообщения</span></div>
    <?php
      if($_SESSION['USER_DATA']['PRO'] === 'Y') {
    ?>
    <div class="st-content-bottom clear">
      <div class="row-line">
        <div class="col-12" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin: 80px auto; text-align: center;">
          Страница заблокирована.
        </div>
        </div>
    </div>
    <?php
      } else {

      $crop = 2;
      $userMain = $_SESSION['USER_DATA']['ID'];

      function cmp($a, $b) {
          if ($a['date_post'] == $b['date_post']) {
              return 0;
          }
          return ($a['date_post'] > $b['date_post']) ? -1 : 1;
      }

// ---- Получаем всех с кем были сообщения ----------
      $resultUser = array();
      $resultDialog = $dbh->query('SELECT owner_id, from_id from a_chat WHERE owner_id = ' . $userMain . ' OR from_id = ' . $userMain . ' ORDER BY date_post DESC')->fetchAll();
      foreach ($resultDialog as $item) {
          if($item['owner_id'] == $userMain) {
              if(!in_array($item['from_id'], $resultUser))
                  $resultUser[] = $item['from_id'];
          } elseif($item['from_id'] == $userMain) {
              if(!in_array($item['owner_id'], $resultUser))
                  $resultUser[] = $item['owner_id'];
          }
      }

// ---- Групповые чаты пользователя ----------------
      $arrGroupId = array();
      $groupId = $dbh->query('SELECT chat_id from a_group_user WHERE user_id = ' . $userMain . ' ORDER BY id ASC')->fetchAll();

// ---- Создаём список последних постов ------------
      $arrPost = array();
      foreach($resultUser as $itemUser) {
          $resPost = $dbh->query('SELECT * from a_chat WHERE ((owner_id = ' . $itemUser . ' AND from_id = ' . $userMain . ' AND  del_to = 0 AND group_owner = 0) OR (owner_id = ' . $userMain . ' AND from_id = ' . $itemUser . ' AND  del_owner = 0 AND group_owner = 0)) ORDER BY date_post DESC')->fetch();
          if($resPost)
              $arrPost[] = $resPost;
      }

      foreach($groupId as $groupItem) {
          $resPost = $dbh->query('SELECT * from a_chat WHERE del_to = 0 AND group_chat = ' . $groupItem['chat_id'] . ' ORDER BY date_post DESC')->fetch();
          if($resPost)
              $arrPost[] = $resPost;
      }

      usort($arrPost, "cmp");

      $arrPostId = array();
      $arrPostNewMessage = $dbh->query('SELECT post_id from a_user_success WHERE user_id = ' . $userMain)->fetchAll();
      foreach ($arrPostNewMessage as $item)
          $arrPostId[] = $item['post_id'];

      $countFilter = array();
      $countFilter['all']     = 0;
      $countFilter['chat']  = 0;
      $countFilter['group']   = 0;

      foreach($arrPost as $itemCount) {

          if($itemCount['group_chat'])
              $countFilter['group']++;
          else
              $countFilter['chat']++;

          $countFilter['all']++;
      }

    ?>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div style="text-align: center; margin: 5px;">
					<span data-id="0" class="color-silver add-group-chat" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: -14px;">Создать групповой чат</span>
				</div>
                <div id="group-filter" class="m-header" style="margin-bottom: 25px;">
                    <div class="filter-row">
                        <a href="#" data-filter="all" class="filter js-message-list color-silver">Все(<span class="js-all"><?php echo $countFilter['all']; ?></span>)</a>
                    </div>
                    <div class="filter-row">
                        <a href="#" data-filter="chat" class="filter js-message-list">Чаты(<span class="js-chat"><?php echo $countFilter['chat']; ?></span>)</a>
                    </div>
                    <div class="filter-row">
                        <a href="#" data-filter="group" class="filter js-message-list">Групповые чаты(<span class="js-group"><?php echo $countFilter['group']; ?></span>)</a>
                    </div>
                </div>
				<div class="line" id="chat" data-type="dialogs">
<?
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
		$idUser = $itemPost['from_id'];
	} else {
		$idUser = $itemPost['owner_id'];
	}
	// ---- Получаем Пользователя ----
	$rsUserData = CUser::GetByID($idUser);
	$userData = $rsUserData->Fetch();

    $group = 0;
    $admin = 0;
    $newMessage = 0;

    if($itemPost['group_chat'] > 0 && $itemPost['owner_id'] == 0) {

        $group = $itemPost['group_chat'];

        $groupChat = $dbh->query('SELECT * from a_group_chat WHERE id = ' . $group)->fetch();

        $userData['TEACHER'] = 0;

        $avatar_chat = $groupChat['avatar'];
        $avatar_url = $avatar_chat;

        $format_name = '<span>' . strtoupper(mb_substr(trim($groupChat['name']), 0, 1)) . '</span>' . mb_substr(trim($groupChat['name']), 1);

        $linkUser = '/user/groupchat/' . $group . '/';
        $linkChat = '/user/groupchat/' . $group . '/';

        $displayName = $groupChat['name'];

        if(!in_array($itemPost['id'], $arrPostId))
            $newMessage = 1;

        $filterClass = 'group';

    } elseif($itemPost['group_chat'] > 0 && $itemPost['owner_id'] > 0) {

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

        $avatar_chat = $groupChat['avatar'];
        // ---- Получаем Аватар ----------
        if($userData['PERSONAL_PHOTO']) {
            $avatar_url = CFile::GetPath($userData['PERSONAL_PHOTO']);
        } else {
            $avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
        }

        $format_name = '<span>' . strtoupper(mb_substr(trim($groupChat['name']), 0, 1)) . '</span>' . mb_substr(trim($groupChat['name']), 1);

        $linkUser = '/user/' . $userData['ID'] . '/';
        $linkChat = '/user/groupchat/' . $group . '/';

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

        $filterClass = 'group';

    } else {

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
        $avatar_chat = $avatar_url;

        // ---- Формируем полное имя -----
        if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
            $format_name = '<span>' . strtoupper(mb_substr(trim($userData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['NAME']), 1);
            if($userData['SECOND_NAME']) {
                $format_name .= ' ';
                $format_name .= '<span>' . strtoupper(mb_substr($userData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($userData['SECOND_NAME'], 1);
            }
            $format_name .= ' ';
            $format_name .= '<span>' . strtoupper(mb_substr(trim($userData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['LAST_NAME']), 1);
        } else {
            $format_name = '<span>' . strtoupper(mb_substr(trim($userData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($userData['LOGIN']), 1);
        }

        $linkUser = '/user/' . $userData['ID'] . '/';
        $linkChat = '/user/chat/' . $userData['ID'] . '/';

        $displayName = $userData['NAME'];

        if($userData['SECOND_NAME'])
            $displayName .= ' ' . $userData['SECOND_NAME'];

        $displayName .= ' ' . $userData['LAST_NAME'];

        $filterClass = 'chat';
    }

	// ---- обрезаем длинные сообщения -----
	//
	$itemPost['message'] = preg_replace('#(<br */?>\s*)+#i', '<br />', $itemPost['message']);

    $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@i';
    $replacement = '<a href="$1" target="_blank">$1</a>';
    $itemPost['message'] =  preg_replace($pattern, $replacement, $itemPost['message']);
	?>
	<div class="news-item all <?=$filterClass?>">
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
					<?php if(CUser::IsOnLine($userData['ID'], 30) && $userData['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1 && !$group) { ?>
					<div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
					<?php } if($newMessage) { ?>
                    <a id="new-chat" class="img-top" style="margin-left: 3px; display: inline-block; " href="<?php echo $linkChat; ?>">
                        <img style="width: 100%; height: 100%; border: 1px solid #ff471a; border-radius: 50%;" src="<?=SITE_TEMPLATE_PATH?>/img/new_chat_1.png" alt="У вас новое сообщение" title="У вас новое сообщение">
                    </a>
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
                                            <div class="message-chat-system" style="margin-left: -4px; position: relative;"><div class="del-mes-left js-del-user-chat-always" data-chat-id="<?php echo $group; ?>" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>">выйти из чата</div><a href="/user/<?php echo $userSys['ID']; ?>/"><?php echo $fullNameSys; ?></a> <?php echo trim($itemPost['message']); ?></div>
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
                                        <?php if($itemPost['success'] || $userData['WORK_FAX'] || $_SESSION['USER_DATA']['WORK_FAX'] || $group) { ?>
                                            <img style="right: -2px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png" alt="img">
                                            <?php if($group && $admin) { ?>
                                                <div class="message_chat" style="margin-left: -4px; position: relative;"><div class="del-mes-left js-del-user-chat-always" data-chat-id="<?php echo $group; ?>" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>">выйти из чата</div><?php echo trim($itemPost['message']); ?></div>
                                            <?php } elseif($group) { ?>
                                                <div class="message_chat" style="margin-left: -4px; position: relative;"><?=trim($itemPost['message']);?></div>
                                            <?php } else { ?>
                                                <div class="message_chat" style="margin-left: -4px; position: relative;"><div class="del-mes-left js-del" data-type="chat" data-id="<?php echo $itemPost['id']; ?>" data-owner="<?php echo $itemPost['owner_id']; ?>" data-from="<?php echo $itemPost['from_id']; ?>">удалить чат</div><?=trim($itemPost['message']);?></div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <img style="right: -2px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3_no.png" alt="img">
                                            <div class="message_chat" style="margin-left: -4px; position: relative; background-color: #4b4b4b; color: #fff;"><div class="del-mes-left js-del" data-type="chat" data-id="<?php echo $itemPost['id']; ?>" data-owner="<?php echo $itemPost['owner_id']; ?>" data-from="<?php echo $itemPost['from_id']; ?>">удалить чат</div><?=trim($itemPost['message']);?></div>
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
								<?php if($itemPost['success'] || $userData['WORK_FAX'] || $_SESSION['USER_DATA']['WORK_FAX'] || $group) { ?>
									<?php if($group) { ?>
                                        <div class="message_chat" style="margin-right: -4px; position: relative;"><div class="del-mes-right js-del-user-chat-always" data-chat-id="<?php echo $group; ?>" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>">выйти из чата</div><?=trim($itemPost['message']);?></div>
									<?php } else { ?>
                                        <div class="message_chat" style="margin-right: -4px; position: relative;"><div class="del-mes-right js-del" data-type="chat" data-id="<?php echo $itemPost['id']; ?>" data-owner="<?php echo $itemPost['owner_id']; ?>" data-from="<?php echo $itemPost['from_id']; ?>">удалить чат</div><?=trim($itemPost['message']);?></div>
                                    <?php } ?>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3.png" alt="img">
								<?php } else { ?>
									<div class="message_chat" style="margin-right: -4px; position: relative; background-color: #ff471a; color: #fff;"><div class="del-mes-right js-del" data-type="chat" data-id="<?php echo $itemPost['id']; ?>" data-owner="<?php echo $itemPost['owner_id']; ?>" data-from="<?php echo $itemPost['from_id']; ?>">удалить чат</div><?=trim($itemPost['message']);?></div>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3_no.png" alt="img">
								<?php } ?>
								<img class="avatar_chat" src="<?php echo $_SESSION['USER_DATA']['AVATAR']; ?>" alt="img" <?php if($_SESSION['USER_DATA']['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
                                </a>
                            </div>
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
    <?php } ?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>