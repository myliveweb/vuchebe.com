<!DOCTYPE html>
<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$arrUri = explode('/', $_SERVER['REQUEST_URI']);
$razdel = $arrUri[1];
$crop = 0;
?>
<html>
	<head>
<?$APPLICATION->ShowHead();?>
<title><?$APPLICATION->ShowTitle('В учёбе');?></title>
	<link rel="icon" href="https://vuchebe.com/favicon.ico" type="image/x-icon">
<link rel="icon" href="https://vuchebe.com/favicon.png" sizes="16x16" type="image/png">
<link rel="apple-touch-icon" sizes="16x16" href="https://vuchebe.com/favicon.png">
<link rel="apple-touch-icon" sizes="32x32" href="https://vuchebe.com/favicon.png">
<link rel="apple-touch-icon" sizes="72x72" href="https://vuchebe.com/favicon.png">
<link rel="apple-touch-icon" sizes="76x76" href="https://vuchebe.com/favicon.png">
<link rel="apple-touch-icon" sizes="114x114" href="https://vuchebe.com/favicon.png">
<link rel="apple-touch-icon" sizes="120x120" href="https://vuchebe.com/favicon.png">
<link rel="apple-touch-icon" sizes="144x144" href="https://vuchebe.com/favicon.png">
<link rel="apple-touch-icon" sizes="152x152" href="https://vuchebe.com/favicon.png">
<link rel="apple-touch-icon" sizes="180x180" href="https://vuchebe.com/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/style.css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/template_style.css">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="keywords" content="">

<title>В учёбе</title>

	</head>
	<body>
<div id="panel">
<?$APPLICATION->ShowPanel();?>
</div>
<body id="home">
<script>
var startFrom = 0;
var startFromSearch = 0;
var startFromList = 0;
var startFromListOrders = 0;
var startFromListAdmin = 0;
var search = '';
var curList = '';
var cnt = 0;
var frontPage = 0;
var chatPage = 0;
var pro = 0;
</script>
<style>
@media screen and (-webkit-min-device-pixel-ratio:0) {
	/* Safari and Chrome */
	.myClass {
		top: -15px;
	}

	/* Safari only override */
	::i-block-chrome,.myClass {
		top: -18px;
	}
}
</style>
<div class="wrapper">

<header class="st-header">
	<div class="container">
		<div class="line-top" style="min-height: 45px;">
			<div class="st-select-city">
				<div class="name-city" id="name-city-top">
					<span><i class="fa fa-map-marker" aria-hidden="true"></i> <a href="#"></a></span>
				</div>
			</div>
			<?if (!$USER->IsAuthorized()):
				if($_SESSION['USER_DATA']['FIRST_VIZIT'] == 1) {
					$USER->Authorize($_SESSION['USER_DATA']['ID']);

					$rsUser = CUser::GetByID($USER->GetId());
					$_SESSION['USER_DATA'] = $rsUser->Fetch();

					$_SESSION['USER_DATA']['FIRST_VIZIT'] = 0;

					$_SESSION['USER_DATA']['PRO'] = 'N';
					$_SESSION['USER_DATA']['PRO_TYPE'] = '';

					$arGroups = CUser::GetUserGroup($_SESSION['USER_DATA']['ID']);
					if(in_array(6, $arGroups)) {
						$_SESSION['USER_DATA']['PRO'] = 'Y';
						$_SESSION['USER_DATA']['PRO_TYPE'] = 'U';
					} elseif(in_array(7, $arGroups)) {
						$_SESSION['USER_DATA']['PRO'] = 'Y';
						$_SESSION['USER_DATA']['PRO_TYPE'] = 'F';
					}

					if (strlen($_SESSION['USER_PASS']) > 0)
						$_SESSION['USER_DATA']['USER_PASS'] = $_SESSION['USER_PASS'];

					$name = trim($USER->GetFirstName()) . ' ' . trim($USER->GetLastName());

					if (strlen($name) <= 0)
						$name = $USER->GetLogin();

					$_SESSION['USER_DATA']['FULL_NAME'] = $name;

					if($_SESSION['USER_DATA']['PERSONAL_PHOTO']) {
						$avatar_url = CFile::GetPath($_SESSION['USER_DATA']['PERSONAL_PHOTO']);
					} else {
						$avatar_url = SITE_TEMPLATE_PATH . "/img/foto-user.png";
					}
					$_SESSION['USER_DATA']['AVATAR'] = $avatar_url;

					if($_SESSION['USER_DATA']['WORK_WWW']) {
						$arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $_SESSION['USER_DATA']['ID'])->fetch();
						if($arrTeacher['cnt'] > 0) {
							$_SESSION['USER_DATA']['TEACHER'] = 1;
						} else {
							$_SESSION['USER_DATA']['TEACHER'] = 0;
						}
					} else {
						$_SESSION['USER_DATA']['TEACHER'] = 0;
					}
				} else {
					$_SESSION['USER_DATA'] = array();
					$_SESSION['USER_PASS'] = '';
				}
			?>
			<?else:
				$rsUser = CUser::GetByID($USER->GetId());
				$_SESSION['USER_DATA'] = $rsUser->Fetch();

				$_SESSION['USER_DATA']['FIRST_VIZIT'] = 0;

				$_SESSION['USER_DATA']['PRO'] = 'N';
				$_SESSION['USER_DATA']['PRO_TYPE'] = '';

				$arGroups = CUser::GetUserGroup($_SESSION['USER_DATA']['ID']);
				if(in_array(6, $arGroups)) {
					$_SESSION['USER_DATA']['PRO'] = 'Y';
					$_SESSION['USER_DATA']['PRO_TYPE'] = 'U';
				} elseif(in_array(7, $arGroups)) {
					$_SESSION['USER_DATA']['PRO'] = 'Y';
					$_SESSION['USER_DATA']['PRO_TYPE'] = 'F';
				}

				if (strlen($_SESSION['USER_PASS']) > 0)
					$_SESSION['USER_DATA']['USER_PASS'] = $_SESSION['USER_PASS'];

				if($_SESSION['USER_DATA']['PRO'] == 'Y' && $_SESSION['USER_DATA']['PRO_TYPE'] == 'U') {
					$name = trim($_SESSION['USER_DATA']['WORK_COMPANY']);
				} else {
					$name = trim($USER->GetFirstName()) . ' ' . trim($USER->GetLastName());
				}

				if (strlen($name) <= 0)
					$name = $USER->GetLogin();

				$_SESSION['USER_DATA']['FULL_NAME'] = $name;

				if($_SESSION['USER_DATA']['PERSONAL_PHOTO']) {
					$avatar_url = CFile::GetPath($_SESSION['USER_DATA']['PERSONAL_PHOTO']);
				} else {
					$avatar_url = SITE_TEMPLATE_PATH . "/img/foto-user.png";
				}
				$_SESSION['USER_DATA']['AVATAR'] = $avatar_url;

				if($_SESSION['USER_DATA']['WORK_WWW']) {
					$arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $_SESSION['USER_DATA']['ID'])->fetch();
					if($arrTeacher['cnt'] > 0) {
						$_SESSION['USER_DATA']['TEACHER'] = 1;
					} else {
						$_SESSION['USER_DATA']['TEACHER'] = 0;
					}
				} else {
					$_SESSION['USER_DATA']['TEACHER'] = 0;
				}
			endif;

			require($_SERVER["DOCUMENT_ROOT"].'/include/get_cookie.php');

            $url = getUserUrl($_SESSION['USER_DATA']);

			if($_SESSION['USER_DATA']['PRO'] == 'Y') {
			?>
			<script>
				pro = 1;
			</script>
			<?php
			}
			?>
			<div class="st-setting right text-right">
				<div class="user-name myClass" style="min-width: 0px; max-width: 350px; position: relative;">
					<?if (!$USER->IsAuthorized()): ?>
						<a class="popup-login img-top" href="#">
							<img src="<?=SITE_TEMPLATE_PATH?>/img/foto-user.png" alt="img">
						</a>
						<a class="popup-login name-text" href="#">Авторизация</a>
					<?else:?>
                        <?php if($_SESSION['USER_DATA']['PRO'] == 'Y') {
                            if($_SESSION['USER_DATA']["WORK_FAX"]) {
                                $proBalance = $_SESSION['USER_DATA']["WORK_FAX"];
                            } else {
                                $proBalance = 0;
                            }
                            ?>
                            <a href="/user/<?=$url?>/balance/" style="margin-right: 5px;"><span class="js-top-balance" style="margin: 0 3px;"><?php echo $proBalance; ?></span>руб.</a>
                        <?php } ?>
                        <a id="new-support" class="img-top" style="margin-left: 5px; display: none;" href="/user/<?php echo $url; ?>/service/">
                            <img class="blink" style="width: 100%; height: 100%; border: none;" src="<?=SITE_TEMPLATE_PATH?>/img/mail-support.png" alt="Новое сообщение от технической поддержки" title="Новое сообщение от технической поддержки">
                        </a>
						<a id="new-chat" class="img-top" style="margin-left: 5px; display: none;" href="/user/<?php echo $url; ?>/dialogs/">
							<img style="width: 100%; height: 100%;" src="<?=SITE_TEMPLATE_PATH?>/img/new_chat_1.png" alt="У вас новое сообщение" title="У вас новое сообщение">
						</a>
						<a class="img-top" style="margin-left: 5px;" href="/user/<?=$url?>/">
							<img class="ava" src="<?=$avatar_url?>" alt="img"<?php if($_SESSION['USER_DATA']['TEACHER']) { echo 'style="border: 2px solid #ff5b32;"'; } ?>>
						</a><a class="name-text" href="/user/<?=$url?>/"><?=$name?></a>
					<?endif?>
				</div>
				<div class="st-right">
				<span class="setting-options">
					<div><i class="fa fa-cog" aria-hidden="true"></i> <span class="name-options">Настройки</span></div>
				</span>
				<div class="list">
						<?php
							$smeschenie = $cookie_utm - 3;
							$today = date("H:i", time() + $smeschenie * 60 * 60);
						?>
						<span class="time"><?php echo $today; ?></span>
						<?if (!$USER->IsAuthorized()):?>
						<a href="/reg/"><span>Регистрация</span></a>
						<?else:?>
						<a href="/settings/" class="js-setting" data-teacher="<?=$_SESSION['USER_DATA']['WORK_WWW']?>" data-color="<?=$_SESSION['USER_DATA']['WORK_FAX']?>" data-chat="<?=$_SESSION['USER_DATA']['WORK_PAGER']?>" data-offline="<?=$_SESSION['USER_DATA']['PERSONAL_PAGER']?>" data-url="<?=$_SESSION['USER_DATA']['WORK_PHONE']?>"><span>Настройки</span></a>
						<a href="/?logout=yes"><span>Выйти</span></a>
						<?endif?>
					</div>
				</div>
			</div>
		</div><!-- line-top -->

		<div class="row-line" style="margin: 25px -15px 0 -15px;">
			<div class="col-6"><a class="logo left" href="/"><img src="<?=SITE_TEMPLATE_PATH?>/images/logo.png" alt="logo"></a></div>
			<div class="col-6">
				<div class="st-header-banner text-right" id="top-banner-list">
					<div class="st-banner" style="position: relative;">
						<div class="hide-top-banner js-hide-banner" style="position: absolute; right: 0px; top: -13px; font-size: 11px; color: #9f9f9f; cursor: pointer;">реклама</div>
						<div class="image brd">
							<?php
							list($idBanner, $srcBanner, $hrefBanner, $targetBanner, $clickBanner, $nameBanner) = getRandomBanner(34, 428, 60);

							if($clickBanner) {
								$stmt = $dbh->prepare("INSERT INTO a_banner_click (user_agent, banner_id, date_time) VALUES ('" . $_SERVER['HTTP_USER_AGENT'] . "', :banner_id, :date_time)");
								$stmt->bindParam(':banner_id', $idBanner, PDO::PARAM_INT);
								$stmt->bindParam(':date_time', date("Y-m-d H:i:s"));
								$stmt->execute();
							}

							?>
							<?php if($idBanner) { ?>
							<a class="js-click-banner" href="#" data-id="<?php echo $idBanner; ?>"<?php echo $targetBanner; ?>><img src="<?php echo $srcBanner; ?>" style="margin: 0; float: right; padding: 1px;" title="<?php echo $nameBanner; ?>" alt="<?php echo $nameBanner; ?>"></a>
							<?php } else { ?>
							<a href="<?php echo $hrefBanner; ?>"<?php echo $targetBanner; ?>><img src="<?php echo $srcBanner; ?>" style="margin: 0; float: right; padding: 1px;" title="<?php echo $nameBanner; ?>" alt="<?php echo $nameBanner; ?>"></a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>

  </div>
</header>

<div class="section st-body-site">
	<div class="container">
		<div class="st-top-menu">
			<div class="st-menu-search right">
				<div class="search">
					<form action="/search/" method="post" accept-charset="utf-8">
						<input type="search" placeholder="" name="s" value="" />
						<input type="hidden" name="p" value="1" />
						<input type="hidden" name="filter" value="all" />
						<button class="button" type="submit"><i class="fa fa-search"></i></button>
					</form>
				</div>
			</div><!-- st-menu-search -->

			<div class="st-menu-li<?php if($razdel == 'uchebnye-zavedeniya') { ?> current<?php } ?>">
				<a<?php if($razdel == 'uchebnye-zavedeniya') { ?> class="active"<?php } ?> href="/uchebnye-zavedeniya/"><span>учебные заведения</span></a>
			</div>
			<div class="st-menu-li<?php if($razdel == 'map') { ?> current<?php } ?>">
				<a<?php if($razdel == 'map') { ?> class="active"<?php } ?> href="/map/" ><span>карта</span></a>
			</div>
			<div class="st-menu-li<?php if($razdel == 'news') { ?> current<?php } ?>">
				<a<?php if($razdel == 'news') { ?> class="active"<?php } ?> href="/news/"><span>новости и события</span></a>
			</div>
</div><!-- st-top-menu -->

<div class="st-content clear"<?php if($razdel == 'login') { ?> style="padding: 30px;"<?php } ?><?php if($razdel == 'map') { ?> style="height: 560px;"<?php } ?>>
