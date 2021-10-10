<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Чат");

CModule::IncludeModule('iblock');

$from = 0;
$arrUri = explode('/', $_REQUEST['url']);
$from = (int) $arrUri[2];

if (!$USER->IsAuthorized() || !$from) {
    LocalRedirect('/users/');
}

$userChat = CUser::GetByID($from);
$userChat  = $userChat->Fetch();

$full_name_chat = trim($userChat['NAME']) . ' ' . trim($userChat['LAST_NAME']);
if (strlen($name) <= 0)
	$full_name_chat = trim($userChat['LOGIN']);

if (strlen(trim($userChat['NAME'])) && strlen(trim($userChat['LAST_NAME']))) {
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

if($userChat['PERSONAL_PHOTO']) {
	$avatar_url_opponent = CFile::GetPath($userChat['PERSONAL_PHOTO']);
} else {
	$avatar_url_opponent = SITE_TEMPLATE_PATH . "/img/foto-user.png";
}

if($_SESSION['USER_DATA']['PRO'] == 'Y') {
  require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_profile_pro.php');
} else {
  require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_profile.php');
}

?>
<script>
chatPage = 1;
</script>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>
<div class="st-content-right">
<div class="breadcrumbs">
<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?=$url?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?=$url?>/dialogs/">Мои сообщения</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?=$userChat['ID']?>/"><?=$format_name?></a>
</div>
<style>
#chat {
	width: 100%;
	height: 400px;
	background-color: #fff;
	border: 1px solid #ff471a;
	border-radius: 4px;
	padding: 15px 15px;
	overflow: scroll;
}
#chat .chat-left, #chat .chat-right {
	margin: 15px 0;
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
	max-width: 75%;
	text-align: left;
}
#chat .message_chat_user {
	margin-bottom: 7px;
}
#chat .chat-left .message_chat {
	border: 1px solid #4b4b4b;
	background-color: #fbfbfb;
	cursor: pointer;
	margin-left: -4px;
	position: relative;
}
#chat .chat-right .message_chat {
	border: 1px solid #ff471a;
	background-color: #fbfbfb;
	cursor: pointer;
	margin-right: -4px;
	position: relative;
}

#chat .chat-right .message_chat.no_show {
	background-color: #ff471a;
	color: #fff;
}

#chat .chat-right .message_chat.no_show_ajax {
	margin-right: 6px;
}

#chat .chat-left .message_chat_user a {
	color: #000 !important;
	cursor: pointer;
	text-decoration-color: #ff471a;
}
#chat .chat-left .message_chat_user span {
	color: #ff471a;
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
	display: none;
}

#chat .del-mes-right:hover,
#chat .del-mes-left:hover {
	border-bottom: 1px dotted #ff471a;
	color: #ff471a;
}
</style>
<div class="page-content">
	<div class="name-block text-left txt-up"> &nbsp;&nbsp;<span>ЧАТ</span></div>
    <?php
      $arGroups = CUser::GetUserGroup($from);

      if($_SESSION['USER_DATA']['PRO'] === 'Y' || in_array(6, $arGroups) || in_array(7, $arGroups)) {
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
			<div id="error-message" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;">Пользователь ограничил доступ к чату.</div>
			<div class="contact-form bg-silver" style="margin: 0px auto;">
				<div class="row-line">
					<div class="col-12">
						<div id="chat" style="overflow-x:hidden;">
						<?
						require_once($_SERVER["DOCUMENT_ROOT"].'/ajax/function.php');

						$error = 0;

						$owner_id = $_SESSION['USER_DATA']['ID'];
						$from_id = $userChat['ID'];

                        $arrSuccessPost = array();
                        $arrSuccess = $dbh->query('SELECT post_id from a_user_success WHERE user_id = ' . $owner_id)->fetchAll();
                        foreach($arrSuccess as $item)
                            $arrSuccessPost[] = $item['post_id'];

						$arrBlock = array();
						$arrBlock = $dbh->query('SELECT id from a_block_user WHERE id_user = ' . $from_id . ' AND block_user = ' . $owner_id)->fetch();

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

						if(!$error && $owner_id && $from_id) {
							$result = $dbh->query('SELECT * from a_chat WHERE (del_owner = 0 AND owner_id = ' . $owner_id . ' AND from_id = ' . $from_id . ') OR (del_to = 0 AND owner_id = ' . $from_id . ' AND from_id = ' . $owner_id . ') ORDER BY date_post ASC')->fetchAll();
						}
						$beep = 0;
						$arrIdChat = array();

                        list($day, $month, $year) = explode(',', date("j,n,Y"));
                        $curTime = mktime(0, 0, 0, $month, $day, $year);

                        $showLine = 0;

						foreach ($result as $item) {

							$bookType = 'bookmark';
							$bookName = 'в закладки';
							$bookCSS = '-79px';

							$PostBaookmark = $dbh->query('SELECT * from a_bookmark WHERE uz_id = ' . $item['id'] . ' AND user_id = ' . $owner_id . '  AND type = 6 ORDER BY id ASC')->fetch();

							if($PostBaookmark) {

								$bookType = 'del-chat-bookmark';
								$bookName = 'убрать из закладок';
								$bookCSS = '-132px';

							}

                            if(!in_array($item['id'], $arrSuccessPost)) {

                                //---- Отмечаем Post как прочитанный ---------------------------
                                $stmt = $dbh->prepare("INSERT INTO a_user_success (chat_id, user_id, post_id) VALUES (0, :user_id, :post_id)");
                                $stmt->bindParam(':user_id', $_SESSION['USER_DATA']['ID'], PDO::PARAM_INT);
                                $stmt->bindParam(':post_id', $item['id'], PDO::PARAM_INT);
                                $stmt->execute();

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

							if($item['owner_id'] == $owner_id) {
							?>
							<div data-res="<?=$item['id']?>" class="chat-right">
								<div class="message_chat_wrapper" style="position: relative;">
									<div class="message_chat_user">
										<a href="/user/<?=$item['owner_id']?>/">Я</a> <span><?php echo get_str_time($item['date_post'] + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60)); ?></span>
									</div>
									<?php if($item['success'] || $_SESSION['USER_DATA']['WORK_FAX'] || $userChat['WORK_FAX']) { ?>
<div class="message_chat"><div class="del-mes-right js-del" style="bottom: 12px; left: <?php echo $bookCSS; ?>;" data-type="<?php echo $bookType; ?>" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>" data-pos="right"><?php echo $bookName; ?></div><div class="del-mes-right js-del" style="bottom: -1px; left: -60px;" data-type="post" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>">удалить</div><?=trim($item['message']);?></div>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3.png">
									<?php } else { ?>
<div id="chat-id-<?php echo $item['id']; ?>" class="message_chat no_show" data-id="<?php echo $item['id']; ?>"><div class="del-mes-right js-del" style="bottom: 12px; left: <?php echo $bookCSS; ?>;" data-type="<?php echo $bookType; ?>" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>" data-pos="right"><?php echo $bookName; ?></div><div class="del-mes-right js-del" style="bottom: -1px; left: -60px;" data-type="post" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>">удалить</div><?=trim($item['message']);?></div>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3_no.png">
									<?php } ?>
									<img class="avatar_chat" src="<?=$avatar_url?>" <?php if($_SESSION['USER_DATA']['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
								</div>
							</div>
							<?
							} else {
								$arrIdChat[] = $item['id'];
								if(!$item['success'] && !$beep) {
									$beep = 1;
								?>
									<script>
										soundClick();
									</script>
								<?
								}

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
							<div data-res="<?=$item['id']?>" class="chat-left">
								<div class="message_chat_wrapper">
									<div class="message_chat_user">
										<a href="/user/<?=$item['owner_id']?>/"><?=$format_name?></a> <span style="color: gray;"><?php echo get_str_time($item['date_post'] + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60)); ?></span>
										<?php if(CUser::IsOnLine($item['owner_id'], 30) && $userChat['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
										<div style="display: inline-block; position: relative; top: -1px; margin-left: 2px; width: 8px; height: 8px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
										<?php } ?>
									</div>
									<img class="avatar_chat" src="<?=$avatar_url_opponent?>" <?php if($userChat['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
									<img style="right: -2px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png">
<div class="message_chat"><div class="del-mes-left js-del" style="bottom: 25px; right: <?php echo $bookCSS; ?>;" data-type="<?php echo $bookType; ?>" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>" data-pos="left"><?php echo $bookName; ?></div><div class="del-mes-left js-del" style="bottom: 12px; right: <?php echo $spamCSS; ?>;" data-type="<?php echo $spamType; ?>" data-spam="<?php echo $spamData; ?>" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>"><?php echo $spamName; ?></div><div class="del-mes-left js-del" style="bottom: -1px; right: -60px;" data-type="post" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>">удалить</div><?=trim($item['message']);?></div>
								</div>
							</div>
							<?
							}
						}
						if($arrIdChat) {
							$in  = implode(',', $arrIdChat);
							$stmt= $dbh->prepare("UPDATE a_chat SET success = 1 WHERE id IN (" . $in . ")");
							$stmt->execute();
						}
						?>
						</div>
					</div>
				</div>
				<form id="form-chat" method="post">
					<input type="hidden" name="owner_id" class="owner-id" value="<?=$_SESSION['USER_DATA']['ID']?>" />
					<input type="hidden" name="owner_display_name" class="owner-display-name" value="<?=$name?>" />
					<input type="hidden" name="from_id" class="from-id" value="<?=$userChat['ID']?>" />
					<input type="hidden" name="from_display_name" class="from-display-name" value="<?=$full_name_chat?>" />
					<input type="hidden" name="avatar" class="avatar" value="<?=$avatar_url?>" />
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
					</div>
				</form>
			</div><!-- contact-form -->
		</div><!-- st-content-bottom -->
    <?php } ?>
	</div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>