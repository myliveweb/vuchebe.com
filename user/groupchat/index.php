<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Чат");

CModule::IncludeModule('iblock');

$groupObserver = 1;
$crop = 2;

$chatId = 0;
$arrUri = explode('/', $_REQUEST['url']);

if($arrUri[2])
    $chatId = (int) $arrUri[2];

$userMain = 0;
if($_SESSION['USER_DATA'])
    $userMain = $_SESSION['USER_DATA']['ID'];

if (!$USER->IsAuthorized() || !$chatId || !$userMain) {
    LocalRedirect('/users/');
}

require_once($_SERVER["DOCUMENT_ROOT"].'/ajax/function.php');

?>
<link rel="stylesheet" href="/user/groupchat/main.css">
<?php

$group = getGroup();

$resultChat = array();
if($group == 2) {
    $resultChat = $dbh->query('SELECT group_chat from a_chat_support WHERE group_owner = ' . $_SESSION['USER_DATA']['ID'] . ' AND del_to = 0 GROUP BY group_chat')->fetchAll();
}

$btnSupport = 0;
if($section === 'service' || $resultChat || isEdit()) {
    $btnSupport = 1;
}

if($_SESSION['USER_DATA']['PRO'] == 'Y') {
    require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_profile_pro.php');
} else {
    require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_profile.php');
}

$arrFilter = array();
$arrFilter['online'] = array();
$arrFilter['admin'] = array();
$arrFilter['user'] = array();
$arrFilter['teacher'] = array();

$chatInfo = $dbh->query('SELECT * from a_group_chat WHERE id = ' . $chatId)->fetch();
$chatName = '<span style="font-size: 14px;">' . strtoupper(mb_substr(trim($chatInfo['name']), 0, 1)) . '</span>' . mb_substr(trim($chatInfo['name']), 1);
$chatAvatar = SITE_TEMPLATE_PATH . "/img/foto-user.png";

$arrUserGroupId = array();
$arrUser  = $dbh->query('SELECT user_id from a_group_user WHERE chat_id = ' . $chatId)->fetchAll();
foreach($arrUser as $item)
    $arrUserGroupId[] = $item['user_id'];

$arrAdminGroupId = array();
$arrAdmin = $dbh->query('SELECT user_id from a_group_admin WHERE chat_id = ' . $chatId)->fetchAll();
foreach($arrAdmin as $item)
    $arrAdminGroupId[] = $item['user_id'];

$arrSuccessPost = array();
$arrSuccess = $dbh->query('SELECT post_id from a_user_success WHERE chat_id = ' . $chatId . ' AND user_id = ' . $userMain)->fetchAll();
foreach($arrSuccess as $item)
    $arrSuccessPost[] = $item['post_id'];

$arrDelPost = array();
$arrPost = $dbh->query('SELECT post_id from a_group_del_local WHERE chat_id = ' . $chatId . ' AND user_id = ' . $userMain)->fetchAll();
foreach($arrPost as $item)
    $arrDelPost[] = $item['post_id'];

if($arrDelPost) {
    $inPost = implode(', ', $arrDelPost);
    $arrChat = $dbh->query('SELECT * from a_chat WHERE group_chat = ' . $chatId . ' AND del_to = 0 AND id NOT IN(' . $inPost . ') ORDER BY date_post ASC')->fetchAll();
} else {
    $arrChat = $dbh->query('SELECT * from a_chat WHERE group_chat = ' . $chatId . ' AND del_to = 0 ORDER BY date_post ASC')->fetchAll();
}

$userInfo = array();
foreach($arrUserGroupId as $itemChat) {

    $userObj = CUser::GetByID($itemChat);
    $userChat = $userObj->Fetch();

    $full_name = trim($userChat['NAME']) . ' ' . trim($userChat['LAST_NAME']);
    if (strlen($full_name) <= 0)
        $full_name = trim($userChat['LOGIN']);

    $userChat['FULL_NAME'] = $full_name;

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

    $userChat['FORMAT_NAME'] = $format_name;

    if($userChat['PERSONAL_PHOTO']) {
        $avatar_url = CFile::GetPath($userChat['PERSONAL_PHOTO']);
    } else {
        $avatar_url = SITE_TEMPLATE_PATH . "/img/foto-user.png";
    }

    $userChat['AVATAR'] = $avatar_url;

    if($userChat['WORK_WWW']) {
        $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $userChat['ID'])->fetch();
        if($arrTeacher['cnt'] > 0) {
            $userChat['TEACHER'] = 1;
            $arrFilter['teacher'][] = $userChat;
        } else {
            $userChat['TEACHER'] = 0;
            $arrFilter['user'][] = $userChat;
        }
    } else {
        $userChat['TEACHER'] = 0;
        $arrFilter['user'][] = $userChat;
    }

    $userChat['ADMIN'] = 0;
    if(in_array($userChat['ID'], $arrAdminGroupId)) {
        $userChat['ADMIN'] = 1;
        $arrFilter['admin'][] = $userChat;
    }

    $userChat['ONLINE'] = 0;
    if(CUser::IsOnLine($userChat['ID'], 30)) {
        $userChat['ONLINE'] = 1;
        $arrFilter['online'][] = $userChat;
    }

    $userChat['CLASS'] = '';

    if($userChat['ONLINE'])
        $userChat['CLASS'] .= ' online';

    if($userChat['TEACHER'])
        $userChat['CLASS'] .= ' teacher';
    else
        $userChat['CLASS'] .= ' user';

    if($userChat['ADMIN'])
        $userChat['CLASS'] .= ' admin';

    $userInfo[$userChat['ID']] = $userChat;
}

$countFilter = array();
$countFilter['all']     = 0;
$countFilter['online']  = 0;
$countFilter['admin']   = 0;
$countFilter['user']    = 0;
$countFilter['teacher'] = 0;

foreach($arrChat as $itemCount) {

    if($itemCount['owner_id'] > 0) {

        $dataUser = $userInfo[$itemCount['owner_id']];

        if($dataUser['ONLINE'])
            $countFilter['online']++;

        if($dataUser['ADMIN'])
            $countFilter['admin']++;

        if(!$dataUser['TEACHER'])
            $countFilter['user']++;

        if($dataUser['TEACHER'])
            $countFilter['teacher']++;

        $countFilter['all']++;
    }

}

function createBaloon($users, $chatId, $type) {
    $html = '';
    if(sizeof($users)) {
        if(sizeof($users) > 4) {
            $showBaloon = 3;
        } else {
            $showBaloon = 4;
        }
        $html .= '<div class="st-baloon ' . $type . '-baloon" style="height: 52px; left: 0px; top: -60px;">';
        $en = 0;
        foreach($users as $item) {
            if($en >= $showBaloon) {
                $html .= '<div class="more-baloon"><span data-type="group-chat" data-chat="' . $chatId . '" data-type-user="' . $type . '" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
                break;
            } else {
                $en++;
            }

            $noAuth = '';
            if(!$_SESSION['USER_DATA'])
                $noAuth = ' class="js-noauth"';

            $html .= '<a href="/user/' . $item['ID'] . '/"' . $noAuth . '>';
            $html .= '<div class="image">';
            $html .= '<img style="height: 22px;" src="' . $item['AVATAR'] . '" alt="' . $item['FULL_NAME'] . '" title="' . $item['FULL_NAME'] . '">';
            $html .= '</div>';
            $html .= '</a>';
        }
        $html .= '</div>';
    }
    return $html;
}

//echo '<pre>';
//var_dump($userInfo);
//echo '<pre>';

?>
<script>
chatPage = 1;
</script>
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">

<style>
#group-filter.m-header .filter-row {
    display: inline-block;
    position: relative;
    margin-right: 10px;
}

#group-filter.m-header .filter-row:first-child .st-baloon::after {
    left: 10%;
}

#group-filter.m-header .filter-row:nth-child(2) .st-baloon::after {
    left: 20%;
}

#chat .message_chat_wrapper .message-chat-system {
    color: gray;
    font-size: 11px;
    cursor: default;
    text-align: center;
    margin-bottom: 25px;
}
#chat .message_chat_wrapper .message-chat-system a {
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
    text-decoration: none;
    padding-bottom: 1px;
    transition: all 0.5s linear;
}
#chat .message_chat_wrapper .message-chat-system a:hover {
    border-bottom: none;
}
</style>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>
<div class="st-content-right">
<div class="breadcrumbs">
<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?=$url?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?=$url?>/dialogs/">Мои сообщения</a> <i class="fa fa-angle-double-right color-orange"></i> <span><?php echo $chatInfo['name']; ?></span>
</div>
<div class="page-content" id="page">
	<div class="name-block text-left txt-up" style="margin-bottom: 15px;">&nbsp;&nbsp;<span>ЧАТ</span>&nbsp;&nbsp;</div>
    <?php

    // ---- Проверка на доступность группового чата пользователю ----------------
    $groupCheck = $dbh->query('SELECT id from a_group_user WHERE chat_id = ' . $chatId . ' AND user_id = ' . $userMain . ' ORDER BY id ASC')->fetch();

    if($_SESSION['USER_DATA']['PRO'] === 'Y' || !$groupCheck) {
    ?>
    <div class="st-content-bottom clear">
      <div class="row-line">
        <div class="col-12" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin: 80px auto; text-align: center;">
          Чат заблокирован.
        </div>
        </div>
    </div>
    <?php
      } else {
    ?>
		<div class="st-content-bottom clear">
            <div class="module st-news" id="box-line">
                <div id="group-filter" class="m-header" style="margin-bottom: 15px;">
                    <div class="filter-row">
                        <?php echo createBaloon($userInfo, $chatId, 'all'); ?>
                        <a href="#" data-filter="all" class="filter js-group-list color-silver">все(<span class="js-all"><?php echo $countFilter['all']; ?></span>)</a>
                    </div>
                    <div class="filter-row">
                        <?php echo createBaloon($arrFilter['online'], $chatId, 'online'); ?>
                        <a href="#" data-filter="online" class="filter js-group-list">в сети(<span class="js-online"><?php echo $countFilter['online']; ?></span>)</a>
                    </div>
                    <div class="filter-row">
                        <?php echo createBaloon($arrFilter['admin'], $chatId, 'admin'); ?>
                        <a href="#" data-filter="admin" class="filter js-group-list">администраторы(<span class="js-admin"><?php echo $countFilter['admin']; ?></span>)</a>
                    </div>
                    <div class="filter-row">
                        <?php echo createBaloon($arrFilter['user'], $chatId, 'user'); ?>
                        <a href="#" data-filter="user" class="filter js-group-list">пользователи(<span class="js-user"><?php echo $countFilter['user']; ?></span>)</a>
                    </div>
                    <div class="filter-row">
                        <?php echo createBaloon($arrFilter['teacher'], $chatId, 'teacher'); ?>
                        <a href="#" data-filter="teacher" class="filter js-group-list">преподаватели(<span class="js-teacher"><?php echo $countFilter['teacher']; ?></span>)</a>
                    </div>
                </div>
            </div>
			<div id="error-message" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;">Пользователь ограничил доступ к чату.</div>
			<div class="contact-form bg-silver" style="margin: 0px auto;">
				<div class="row-line">
					<div class="col-12">
						<div id="chat" style="overflow-x:hidden;">
						<?
						$error = 0;
						$beep = 0;

                        list($day, $month, $year) = explode(',', date("j,n,Y"));
                        $curTime = mktime(0, 0, 0, $month, $day, $year);

                        $showLine = 0;

						foreach ($arrChat as $item) {

							$bookType = 'bookmark';
							$bookName = 'в закладки';
							$bookCSS = '-79px';

							$PostBaookmark = $dbh->query('SELECT * from a_bookmark WHERE uz_id = ' . $item['id'] . ' AND user_id = ' . $_SESSION['USER_DATA']['ID'] . '  AND type = 6 ORDER BY id ASC')->fetch();

							if($PostBaookmark) {

								$bookType = 'del-chat-bookmark';
								$bookName = 'убрать из закладок';
								$bookCSS = '-132px';

							}

                            $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@i';
                            $replacement = '<a href="$1" target="_blank">$1</a>';
                            $item['message'] =  preg_replace($pattern, $replacement, $item['message']);

                            if($curTime < $item['date_post'] && !$showLine) {
                                $showLine = 1;
                                ?>
                                    <div class="line-today" id="time-line" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: 0px; text-align: center; margin-top: 35px;">
                                        <div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
                                    </div>
                                <?php
                            }

                            if(!in_array($item['id'], $arrSuccessPost)) {

                                //---- Отмечаем Post как прочитанный ---------------------------
                                $stmt = $dbh->prepare("INSERT INTO a_user_success (chat_id, user_id, post_id) VALUES (:chat_id, :user_id, :post_id)");
                                $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_INT);
                                $stmt->bindParam(':user_id', $userMain, PDO::PARAM_INT);
                                $stmt->bindParam(':post_id', $item['id'], PDO::PARAM_INT);
                                $stmt->execute();

                            }

							if($item['owner_id'] == $_SESSION['USER_DATA']['ID']) {
                            //---- Мои сообщения -----------------
							?>
							<div data-res="<?=$item['id']?>" class="chat-right online-user-<?=$item['owner_id']?> all<?php echo $userInfo[$item['owner_id']]['CLASS']; ?>">
								<div class="message_chat_wrapper" style="position: relative;">
									<div class="message_chat_user">
										<a href="/user/<?=$item['owner_id']?>/">Я</a> <span><?php echo get_str_time($item['date_post'] + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60)); ?></span>
									</div>
									<?php if($item['success'] || $_SESSION['USER_DATA']['WORK_FAX'] || $userChat['WORK_FAX'] || 1) { ?>
<div class="message_chat"><div class="del-mes-right js-del" style="bottom: 12px; left: <?php echo $bookCSS; ?>;" data-type="<?php echo $bookType; ?>" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['owner_id']; ?>" data-pos="right"><?php echo $bookName; ?></div><div class="del-mes-right js-del-group-post" style="bottom: -1px; left: -60px;" data-type="post" data-id-post="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-chat="<?php echo $chatId; ?>">удалить</div><?=trim($item['message']);?></div>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3.png">
									<?php } else { ?>
<div id="chat-id-<?php echo $item['id']; ?>" class="message_chat no_show" data-id="<?php echo $item['id']; ?>"><div class="del-mes-right js-del" style="bottom: 12px; left: <?php echo $bookCSS; ?>;" data-type="<?php echo $bookType; ?>" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['owner_id']; ?>" data-pos="right"><?php echo $bookName; ?></div><div class="del-mes-right js-del-group-post" style="bottom: -1px; left: -60px;" data-type="post" data-id-post="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-chat="<?php echo $chatId; ?>">удалить</div><?=trim($item['message']);?></div>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3_no.png">
									<?php } ?>
									<img class="avatar_chat" src="<?php echo $userInfo[$item['owner_id']]['AVATAR']; ?>" <?php if($_SESSION['USER_DATA']['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
								</div>
							</div>
							<?
							} else {
                            //---- Не мои сообщения -----------------
                                //---- Подача звукового сигнала о новых сообщениях -----------------
								if(!in_array($item['id'], $arrSuccessPost)) {

                                    //---- Звуковой сигнал, 1 раз ----------------------------------
									if(!$beep) {
                                        $beep = 1;
                                        ?>
                                        <script>
                                            var audio = new Audio(); // Создаём новый элемент Audio
                                            audio.src = '/note.mp3'; // Указываем путь к звуку "клика"
                                            audio.autoplay = true; // Автоматически запускаем
                                        </script>
                                        <?
                                    }
								}

								if($item['owner_id']) {
                                //---- Сообщения от других участников -----------------
								$spamType = 'spam';
								$spamData = 0;
								$spamName = 'спам';
								$spamCSS = '-40px';


								$arSelect = array("ID", "NAME", "IBLOCK_ID");
								$arFilter = array("IBLOCK_ID" => 25, "ACTIVE" => "Y", "PROPERTY_POST_ID" => $item['id']);
								$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
								if($row = $res->Fetch())
								{
									$spamType = 'no-spam';
									$spamData = $row["ID"];
									$spamName = 'не спам';
									$spamCSS = '-58px';
								}

							?>
							<div data-res="<?=$item['id']?>" class="chat-left online-user-<?=$item['owner_id']?> all<?php echo $userInfo[$item['owner_id']]['CLASS']; ?>">
								<div class="message_chat_wrapper">
									<div class="message_chat_user">
										<a href="/user/<?=$item['owner_id']?>/"><?php echo $userInfo[$item['owner_id']]['FORMAT_NAME']; ?></a> <span style="color: gray;"><?php echo get_str_time($item['date_post'] + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60)); ?></span>
										<?php if(CUser::IsOnLine($item['owner_id'], 30) && $userChat['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
										<div style="display: inline-block; position: relative; top: -1px; margin-left: 2px; width: 8px; height: 8px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
										<?php } ?>
									</div>
									<img class="avatar_chat" src="<?php echo $userInfo[$item['owner_id']]['AVATAR']; ?>" <?php if($userInfo[$item['owner_id']]['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
									<img style="right: -2px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png">
<div class="message_chat"><div class="del-mes-left js-del" style="bottom: 25px; right: <?php echo $bookCSS; ?>;" data-type="<?php echo $bookType; ?>" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $_SESSION['USER_DATA']['ID']; ?>" data-pos="left"><?php echo $bookName; ?></div><div class="del-mes-left js-del" style="bottom: 12px; right: <?php echo $spamCSS; ?>;" data-type="<?php echo $spamType; ?>" data-spam="<?php echo $spamData; ?>" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>"><?php echo $spamName; ?></div><div class="del-mes-left js-del-group-post" style="bottom: -1px; right: -60px;" data-type="post" data-id-post="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-chat="<?php echo $chatId; ?>">удалить</div><?=trim($item['message']);?></div>
								</div>
							</div>
							<?
                            } else {
                                //---- Системные сообщения -----------------

                                $userSys = CUser::GetByID($item['group_owner']);
                                $userSys = $userSys->Fetch();

                                $fullNameSys = trim($userSys['NAME']);
                                if(trim($userSys['SECOND_NAME']))
                                    $fullNameSys .= ' ' . trim($userSys['SECOND_NAME']);
                                if(trim($userSys['LAST_NAME']))
                                    $fullNameSys .= ' ' . trim($userSys['LAST_NAME']);

                                if (strlen($fullNameSys) <= 0)
                                    $fullNameSys = trim($userSys['LOGIN']);
                                ?>
                                <div data-res="<?=$item['id']?>" class="chat-left all system">
                                    <div class="message_chat_wrapper">
                                        <div class="message-chat-system"><a href="/user/<?php echo $userSys['ID']; ?>/"><?php echo $fullNameSys; ?></a> <?=trim($item['message']);?></div>
                                    </div>
                                </div>
                                <?
                            }
                            }
						}
						?>
						</div>
					</div>
				</div>
				<form id="form-group-chat-post" method="post">
					<input type="hidden" name="chat_id" class="chat-id" value="<?php echo $chatId; ?>" />
                    <input type="hidden" name="owner_id" class="owner-id" value="<?php echo $chatInfo['owner']; ?>" />
                    <input type="hidden" name="user_id" class="user-id" value="<?php echo $userMain; ?>" />
					<div class="row-line" style="margin-top: 15px;">
						<div class="col-12">
							<span class="label">Введите сообщение</span>
							<textarea style="height: 35px; padding: 10px 12px 8px 12px;" id="textarea-input" class="message" name="message"><?=$_POST['message']?></textarea>
						</div>
					</div>
					<div class="contact-form-footer">
						<button class="button right txt-up" type="submit">
							<span>отправить</span>
						</button>
                        <?php if(in_array($_SESSION['USER_DATA']['ID'], $arrAdminGroupId)) { ?>
                        <div style="float: right; margin-right: 25px;">
                            <span data-id="<?php echo $chatId; ?>" class="color-silver add-group-chat" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: 16px;">Настройки чата</span>
                        </div>
                        <?php } else { ?>
                            <div style="float: right; margin-right: 25px;">
                                <span data-chat-id="<?php echo $chatId; ?>" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>" class="color-silver js-del-user-chat-always" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: 16px;">Выйти из чата</span>
                            </div>
                        <?php } ?>
					</div>
				</form>
			</div><!-- contact-form -->
		</div><!-- st-content-bottom -->
    <?php } ?>
	</div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>