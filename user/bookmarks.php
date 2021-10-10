<?php
global $dbh;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$arResult["BOOKMARK"] = array();
if($user_id) {
	$arrBaookmark = $dbh->query('SELECT * from a_bookmark WHERE user_id = ' . $user_id . ' ORDER BY date_create DESC')->fetchAll();
}

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] > $b['sort']) ? -1 : 1;
}
$arrFinal = array();
$arrFilter = array();
foreach($arrBaookmark as $book) {
	if($book['type'] == 1 || $book['type'] == 2 || $book['type'] == 3 || $book['type'] == 4)
		$arrFilter['uz'] = 1;
	elseif($book['type'] == 5) {
		$arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $book["uz_id"])->fetch();
		if($arrTeacher['cnt'] > 0) {
			$book['teacher'] = 1;
			$arrFilter['teacher'] = 1;
		} else {
			$book['teacher'] = 0;
			$arrFilter['us'] = 1;
		}
	} elseif($book['type'] == 6) {
		$arrFilter['chat'] = 1;
	}
	$arrFinal[] = $book;
}
?>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<style>
#box-line .js-bookmark {
    padding: 0;
    width: 100%;
}
.js-bookmark.active span::before,
.js-bookmark:hover span::before {
    border-color: #ff471a;
}
.js-bookmark:active {
    color: #ff471a;
    background: #fff;
    box-shadow: none;
    box-shadow: 0 0 13px #999 inset;
}
.js-bookmark.active:hover {
    color: ffffff;
    box-shadow: none;
}
.js-bookmark.active:hover span {
    text-decoration: none;
    color: #ffffff;
}
.display-name {
	color: #000 !important;
	cursor: pointer;
	text-decoration-color: #ff471a;
}
.display-name span {
	color: #ff471a;
}
.m-header .filter {
	color: #ff471a;
}
.m-header .filter.color-silver {
	color: #9f9f9f;
	text-decoration: none;
	cursor: default;
}

.chat-left, #chat .chat-right {
	margin: 15px 0;
}
.chat-left {
	text-align: left;
}
.chat-right {
	text-align: right;
}
.chat-left .message_chat_wrapper img.avatar_chat,
.chat-right .message_chat_wrapper img.avatar_chat {
	border-radius: 50%;
    border: 1px solid #ff471a;
	display: inline-block;
    vertical-align: top;
	width: 22px;
    height: 22px;
}
.chat-left .message_chat_wrapper img.avatar_duz {
	position: relative;
    right: -6px;
    top: 12px;
    vertical-align: top;
}
.chat-right .message_chat_wrapper img.avatar_duz {
	position: relative;
    right: 6px;
    top: 12px;
    vertical-align: top;
}
.message_chat {
	border-radius: 4px;
	padding: 10px 15px;
	display: inline-block;
	max-width: 75%;
	text-align: left;
}
.message_chat_user {
	margin-bottom: 7px;
}
.chat-left .message_chat {
	border: 1px solid #4b4b4b;
	background-color: #fbfbfb;
	margin-left: 5px;
	cursor: pointer;
    word-wrap: break-word;
}
.chat-right .message_chat {
	border: 1px solid #ff471a;
	background-color: #fbfbfb;
	margin-right: 5px;
	cursor: pointer;
    word-wrap: break-word;
}
.chat-left .message_chat_user a {
	color: #000 !important;
	cursor: pointer;
	text-decoration-color: #ff471a;
}
.chat-left .message_chat_user span {
	color: #ff471a;
}
.chat-left .message_chat_user a {
	text-decoration: none;
    border-bottom: 1px dashed #fff;
    font-family: Verdana, "sans-serif";
    cursor: pointer;
    color: #4b4b4b;
    margin-left: 40px;
}
.chat-right .message_chat_user a {
	text-decoration: none;
    border-bottom: 1px dashed #fff;
    font-family: Verdana, "sans-serif";
    cursor: pointer;
    color: #ff471a;
}
.chat-left .message_chat_user a:hover {
    border-color: #4b4b4b;
}
.chat-right .message_chat_user a:hover {
    border-color: #ff471a;
}
.message_chat_user span {
	font-size: 11px;
	color: gray;
}
.chat-right .message_chat_user span {
    margin-right: 40px;
}

.del-mes-right,
.del-mes-left {
	position: absolute;
	font-size: 12px;
	border-bottom: 1px dotted gray;
	color: gray;
	cursor: pointer;
	display: none;
}

.del-mes-right:hover,
.del-mes-left:hover {
	border-bottom: 1px dotted #ff471a;
	color: #ff471a;
}
</style>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>

<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Мои закладки</span>
	</div><br>
	<div class="page-content" id="page">
		<div class="name-block text-center txt-up"><span>Закладки</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="m-header" style="padding-bottom: 10px;">
					<a href="#" data-filter="all" class="filter color-silver js-educations-list">Все</a> &nbsp;
					<?if($arrFilter['uz']):?>
					<a href="#" data-filter="uz" class="filter js-educations-list">Учебные заведения</a> &nbsp;
					<?endif?>
					<?if($arrFilter['us']):?>
					<a href="#" data-filter="us" class="filter js-educations-list">Пользователи</a> &nbsp;
					<?endif?>
					<?if($arrFilter['teacher']):?>
					<a href="#" data-filter="teacher" class="filter js-educations-list">Преподаватели</a> &nbsp;
					<?endif?>
					<?if($arrFilter['chat']):?>
					<a href="#" data-filter="chat" class="filter js-educations-list">Сообщения</a> &nbsp;
					<?endif?>
				</div>
				<div class="line" id="box-line" data-type="bookmarks">
				<?php
				CModule::IncludeModule('iblock');
				foreach($arrFinal as $bookmark) {
					$arrData = array();
					$filterType = '';
					if($bookmark["type"] == 1) {
						$arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_SITE", "PROPERTY_EMAIL", "PROPERTY_YEAR");
						$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ID" => $bookmark["uz_id"]);
						$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
						if($row = $res->GetNext())
						{
							$arrData = $row;

							$arrData["ADRESS"] = $row["PROPERTY_ADRESS_VALUE"];
                            $arrData["YEAR"] = preg_replace('~\D+~','', $row["PROPERTY_YEAR_VALUE"]);

							if($row["PROPERTY_LOGO_VALUE"]) {
								$arrData["PIC"] = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
							} elseif($row["PREVIEW_PICTURE"]) {
								$arrData["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);
							} else {
                                $arrData["PIC"] = '/local/templates/vuchebe/images/noimage-2.png';
                            }
						}
						$filterType = 'uz';
					} elseif($bookmark["type"] == 2) {
						$arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_SITE", "PROPERTY_EMAIL", "PROPERTY_LOGO", "PROPERTY_YEAR");
						$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "ID" => $bookmark["uz_id"]);
						$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
						if($row = $res->GetNext())
						{
							$arrData = $row;

							$arrData["ADRESS"] = $row["PROPERTY_ADRESS_VALUE"];
                            $arrData["YEAR"] = preg_replace('~\D+~','', $row["PROPERTY_YEAR_VALUE"]);

							if($row["PROPERTY_LOGO_VALUE"]) {
								$arrData["PIC"] = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
							} elseif($row["PREVIEW_PICTURE"]) {
								$arrData["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);
							} else {
                                $arrData["PIC"] = '/local/templates/vuchebe/images/noimage-2.png';
                            }
						}
						$filterType = 'uz';
					} elseif($bookmark["type"] == 3) {
						$arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_SITE", "PROPERTY_EMAIL", "PROPERTY_LOGO", "PROPERTY_YEAR");
						$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "ID" => $bookmark["uz_id"]);
						$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
						if($row = $res->GetNext())
						{
							$arrData = $row;

							$arrData["ADRESS"] = $row["PROPERTY_ADRESS_VALUE"]["TEXT"];
                            $arrData["YEAR"] = preg_replace('~\D+~','', $row["PROPERTY_YEAR_VALUE"]);

							if($row["PROPERTY_LOGO_VALUE"]) {
                                $arrData["PIC"] = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
                            } else {
                                $arrData["PIC"] = '/local/templates/vuchebe/images/noimage-2.png';
                            }

						}
						$filterType = 'uz';
					} elseif($bookmark["type"] == 4) {
						$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_ADRESS", "PROPERTY_PHONE", "PROPERTY_SITE", "PROPERTY_YEAR");
						$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "ID" => $bookmark["uz_id"]);
						$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
						if($row = $res->GetNext())
						{
							$arrData = $row;

							$arrAdress = explode('&', $row["PROPERTY_ADRESS_VALUE"]);
							$arrData["ADRESS"] = $arrAdress[0];
                            $arrData["YEAR"] = preg_replace('~\D+~','', $row["PROPERTY_YEAR_VALUE"]);

							$arrSite = explode('?', $row["PROPERTY_SITE_VALUE"]);
							$arrData["PROPERTY_SITE_VALUE"] = $arrSite[0];

							if($row["DETAIL_PICTURE"]) {
                                $arrData["PIC"] = CFile::GetPath($row["DETAIL_PICTURE"]);
                            } else {
                                $arrData["PIC"] = '/local/templates/vuchebe/images/noimage-2.png';
                            }
						}
						$filterType = 'uz';
					} elseif($bookmark["type"] == 5) {
						$rsUserData = CUser::GetByID($bookmark["uz_id"]);
						$userData = $rsUserData->Fetch();

						if($userData['PERSONAL_PHOTO']) {
							$avatar_url = CFile::GetPath($userData['PERSONAL_PHOTO']);
						} else {
							$avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
						}

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

						$month_1 = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
						if($userData['PERSONAL_BIRTHDAY']) {
							list($dayShow, $monthShow, $yearShow) = explode('.', $userData['PERSONAL_BIRTHDAY']);
							$showBd = (int) $dayShow . ' ' . $month_1[(int) $monthShow] . ' ' . (int) $yearShow . ' г.';
						}

						$userData['TEACHER'] = $bookmark["teacher"];
						if($bookmark["teacher"])
							$filterType = 'teacher';
						else
							$filterType = 'us';
					} elseif($bookmark["type"] == 6) {
						$item = $dbh->query('SELECT * from a_chat WHERE id = ' . $bookmark["uz_id"] . ' ORDER BY id ASC')->fetch();

						if(!$item)
							continue;

						if($item['owner_id'] == $user_id)
							$from = (int) $item['from_id'];
						else
							$from = (int) $item['owner_id'];

						$userChat = CUser::GetByID($from);
						$userChat  = $userChat->Fetch();

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

						if($_SESSION['USER_DATA']['PERSONAL_PHOTO']) {
							$avatar_url_my = CFile::GetPath($_SESSION['USER_DATA']['PERSONAL_PHOTO']);
						} else {
							$avatar_url_my = SITE_TEMPLATE_PATH . "/img/foto-user.png";
						}

						$owner_id = $_SESSION['USER_DATA']['ID'];
						$from_id = $userChat['ID'];

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

                        $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@i';
                        $replacement = '<a href="$1" target="_blank">$1</a>';
                        $item['message'] =  preg_replace($pattern, $replacement, $item['message']);

						$filterType = 'chat';

					}

					?>
					<div class="news-item <?php echo $filterType; ?>">
						<?php if($bookmark["type"] == 6) { ?>
						<div class="col-12" style="padding-right: 0;">
						<?php
							if($item['owner_id'] == $user_id) {
							?>
							<div data-res="<?=$item['id']?>" class="chat-right">
								<div class="message_chat_wrapper" style="position: relative;">
									<div class="message_chat_user">
										<a href="/user/<?=$item['owner_id']?>/">Я</a> <span><?php echo get_str_time($item['date_post'] + (($_SESSION['PANEL']['UTM'] - 3) * 60 * 60)); ?></span>
									</div>
									<?php if($item['success']) { ?>
<div class="message_chat" style="margin-right: -4px; position: relative;"><div class="del-mes-right js-del" style="bottom: -1px; left: -131px;" data-type="del-bookmark" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>">убрать из закладок</div><?=trim($item['message']);?></div>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3.png" alt="img">
									<?php } else { ?>
<div class="message_chat" style="margin-right: -4px; position: relative; background-color: #ff471a; color: #fff;"><div class="del-mes-right js-del" style="bottom: -1px; left: -131px;" data-type="del-bookmark" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $item['from_id']; ?>">убрать из закладок</div><?=trim($item['message']);?></div>
									<img style="right: 2px;" class="avatar_duz" src="/upload/main/ug_right_3_no.png" alt="img">
									<?php } ?>
									<img class="avatar_chat" src="<?=$avatar_url_my?>" alt="img" <?php if($_SESSION['USER_DATA']['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } else { echo 'style="border: 1px solid #ff5b32;"'; } ?>>
								</div>
							</div>
							<?
							} else {
								$arrIdChat[] = $item['id'];
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
<div class="message_chat" style="margin-left: -4px; position: relative;"><div class="del-mes-left js-del" style="bottom: -1px; right: -131px;" data-type="del-bookmark" data-id="<?php echo $item['id']; ?>" data-owner="<?php echo $item['owner_id']; ?>" data-from="<?php echo $_SESSION['USER_DATA']['ID']; ?>">убрать из закладок</div><?=trim($item['message']);?></div>
								</div>
							</div>
							<?
							}
						?>
						</div><!-- content-right -->
						<?php } elseif($bookmark["type"] == 5) { ?>
						<div class="col-3 width-sm content-left" style="padding-right: 0;">
							<div class="image brd rad-50" style="text-align: center; width: 100%;">
								<img src="<?=$avatar_url?>" alt="img" style="height: 111px; width: 111px;<?php if($userData['TEACHER']) { echo ' border: 3px solid #ff5b32;'; } ?>">
							</div>
						</div>
						<div class="col-9 width-sm content-right" style="padding: 0;">
							<div class="page-info">
								<h1 class="name-user">
									<span><a href="/user/<?=$userData['ID']?>/" class="display-name"><?=$format_name?></a></span>
									<?php if(CUser::IsOnLine($userData['ID'], 30) && $userData['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
									<div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
									<?php } ?>
								</h1>
								<div class="contact-info">
									<div class="btns" style="margin-top: 25px; width: 145px; display: inline-block;">
										<a style="height: 33px;" href="#" class="button js-bookmark active" data-state="1" data-type="<?php echo $bookmark["type"]?>" data-id="<?php echo $bookmark["uz_id"]?>">
											<span style="font-size: 16px; padding-top: 5px;">закладки</span>
										</a>
									</div>
									<div class="btns right" style="cursor: pointer; display: inline-block; float: none; position: relative; top: -2px;">
                  <?php
                    if($_SESSION['USER_DATA']['PRO'] === 'Y') {
                      $link = '';
                    } else {
                      $link = 'href="/user/chat/'. $userData['ID'] . '/"';
                    }
                  ?>
                  <a style="height: 31px;" <?php echo $link; ?> class="button small">сообщение</a>
									</div>
								</div><!-- contact-info -->
								<br>
							</div>
						</div><!-- content-right -->
						<?php
                        } else {
                            $year_digital = $arrData["YEAR"];
                        ?>
                        <?php if($year_digital) { ?>
                            <div class="year-mobile">
                                <div class="stick-year" style="margin: 5px auto;">
                                    <div class="text">
                                        год <br>основания
                                        <span><?php echo $year_digital; ?></span>
                                    </div>
                                </div><!-- stick-year -->
                            </div>
                        <?php } ?>
						<div class="col-3 width-sm content-left" style="padding: 0;">
							<?if($arrData["PIC"]):?>
								<div class="image left brd" style="width: 100%;">
									<img style="width: 100%;" src="<?=$arrData["PIC"]?>" alt="<?=$arrData["NAME"]?>" title="<?=$arrData["NAME"]?>" />
								</div>
							<?endif?>
							<div class="btns" style="margin-top: 10px;">
								<a href="#" class="button js-bookmark active" data-state="1" data-type="<?php echo $bookmark["type"]?>" data-id="<?php echo $bookmark["uz_id"]?>">
									<span style="font-size: 18px;">закладки</span>
								</a>
							</div>
						</div>
						<div class="col-9 width-sm content-right" style="margin: 0 0 10px 0;">
                            <div class="col-10" style="padding: 0; <?php if($year_digital) { ?>width: 78%<? } else { ?>width: 100%<? } ?>;">
                                <div class="news-name">
                                    <a href="<?php echo $arrData["DETAIL_PAGE_URL"]?>"><span><?php echo $arrData["NAME"]?></span></a>
                                </div>
                                <p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                <?if($arrData["ADRESS"]):?>
                                Адрес:&nbsp;<?php echo $arrData["ADRESS"]?><br>
                                <?endif?>
                                <?if($arrData["PROPERTY_SITE_VALUE"]):?>
                                Сайт:&nbsp;<a href="<?php echo $arrData["PROPERTY_SITE_VALUE"]?>"><?php echo $arrData["PROPERTY_SITE_VALUE"]?></a><br>
                                <?endif?>
                                <?if($arrData["PROPERTY_PHONE_VALUE"]):?>
                                Телефон:&nbsp;<?php echo $arrData["PROPERTY_PHONE_VALUE"]?><br>
                                <?endif?>
                                <?if($arrData["PROPERTY_EMAIL_VALUE"]):?>
                                Электронная почта:&nbsp;<a href="mailto:<?php echo $arrData["PROPERTY_EMAIL_VALUE"]?>"><?php echo $arrData["PROPERTY_EMAIL_VALUE"]?></a>
                                <?endif?>
                                </p>
                            </div>
                            <div class="col-2" style="padding: 0 0 0 15px; width: 20%;">
                                <? if($year_digital) { ?>
                                    <div class="stick-year year-desctop">
                                        <div class="text">
                                            год <br>основания
                                            <span><?=$year_digital?></span>
                                        </div>
                                    </div><!-- stick-year -->
                                <? } ?>
                            </div>
						</div>
					<?php } ?>
					</div>
					<?
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>