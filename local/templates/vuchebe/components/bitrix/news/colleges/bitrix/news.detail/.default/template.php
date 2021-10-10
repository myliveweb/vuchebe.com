<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

global $USER;

$user_id = 0;
$user_name = '';
$user_avatar = '';
$pageAdmin = 0;

if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
	$user_avatar = $_SESSION['USER_DATA']['AVATAR'];

	If(in_array($user_id, $arResult["PROPERTIES"]["ADMINS"]["VALUE"]) || isEdit())
		$pageAdmin = 1;
}

require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_colleges.php');
//echo '<pre>';
//print_r($arResult["PROPERTIES"]["GA_PDF"]);
//echo '</pre>';
global $dbh;
CModule::IncludeModule('iblock');

function cmp_uz($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] > $b['sort']) ? -1 : 1;
}

?>
<script>
<?php
	echo "var id_vuz = " . $arResult["ID"] . ";\n";
	echo "var id_user = " . $user_id . ";\n";
	echo "var user_name = '" . $user_name . "';\n";
	echo "var user_avatar = '" . $user_avatar . "';\n";
	echo "var curPage = 'colleges';\n";
?>
</script>

<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">

<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/uchebnye-zavedeniya/">Учебные зведения</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/uchebnye-zavedeniya/colleges/">Колледжи</a> <i class="fa fa-angle-double-right color-orange"></i> <?php echo mb_ucfirst($arResult["NAME"]); ?>
	</div>
	<?
	if($structure_menu):
		require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/colleges/structure.php');
	elseif($_REQUEST['sect'] == 'history'):
		require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/colleges/history.php');
	elseif($_REQUEST['sect'] == 'teacher'):
		require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/colleges/teacher.php');
	elseif($_REQUEST['sect'] == 'students'):
		require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/colleges/students.php');
	elseif($_REQUEST['sect'] == 'opendoor'):
		require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/colleges/opendoor.php');
	elseif($_REQUEST['sect'] == 'events'):
		require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/colleges/events.php');
	elseif($_REQUEST['sect'] == 'news'):
		require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/colleges/news.php');
	elseif($_REQUEST['sect'] == 'reviews'):
		require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/colleges/reviews.php');
	else:
	?>
	<div class="page-content" id="page">
		<div class="name-block text-center txt-up">
			<? if($arResult["PROPERTIES"]["ABBR"]["VALUE"]) { ?>
			<span><?=$arResult["PROPERTIES"]["ABBR"]["VALUE"]?></span>
			<? } else { ?>
			<span>Колледж</span>
			<? } ?>
		</div>
		<div class="page-item clearfix">
			<div class="col-3 content-left">
				<div class="image brd">
				<?
				if($arResult["PROPERTIES"]["LOGO"]["VALUE"]):
					$srcLogo = CFile::GetPath($arResult["PROPERTIES"]["LOGO"]["VALUE"]);
					$altLogo = 'Логотип колледжа';
					$titleLogo = 'Логотип колледжа';
				elseif(is_array($arResult["PREVIEW_PICTURE"])):
					$srcLogo = $arResult["PREVIEW_PICTURE"]["SRC"];
					$altLogo = $arResult["PREVIEW_PICTURE"]["ALT"];
					$titleLogo = $arResult["PREVIEW_PICTURE"]["TITLE"];
				else:
					$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
					$altLogo = 'Логотип колледжа';
					$titleLogo = 'Логотип колледжа';
				endif;

				if($pageAdmin):?>
					<input type="file" id="file" data-type="logo" data-id="<?php echo $arResult["ID"]; ?>" data-iblock="3" accept="image/*">
					<label for="file">
						<img class="profile-avatar" style="cursor: pointer;" src="<?php echo $srcLogo; ?>" alt="<?php echo $altLogo; ?>" title="<?php echo $titleLogo; ?>" />
					</label>
				<?else:?>
					<img src="<?php echo $srcLogo; ?>" alt="<?php echo $altLogo; ?>" title="<?php echo $titleLogo; ?>" />
				<?endif;?>

				</div>
				<div class="btns" style="margin-top: 10px;">
					<?php if($arResult["BOOKMARK"]) { ?>
					<a href="#" class="button js-bookmark active" data-state="1" data-type="2" data-id="<?php echo $arResult["ID"]; ?>">
						<span>закладки</span>
					</a>
					<?php } else { ?>
					<a href="#" class="button js-bookmark" data-state="0" data-type="2" data-id="<?php echo $arResult["ID"]; ?>">
						<span>закладки</span>
					</a>
					<?php } ?>
					<?php if($arResult["PROPERTIES"]["LONGITUDE"]["VALUE"] && $arResult["PROPERTIES"]["LATITUDE"]["VALUE"]) { ?>
					<a href="/map/?map=<?php echo $arResult["ID"]; ?>" class="button js-map" style="margin-top: 10px;">
						<span>на карте</span>
					</a>
					<?php } ?>
				</div>
			</div>

			<div class="col-9 content-right">
				<div class="page-info">
                    <h1 class="name-item">
                        <span style="min-height: 110px;" title="<?php echo mb_ucfirst($arResult["PROPERTIES"]["FULL_NAME"]["VALUE"]["TEXT"]); ?>"><?php echo mb_ucfirst($arResult["NAME"]); ?></span>
                    </h1>

					<div class="contact-info" style="position: relative;">
						<?if($pageAdmin):?>
						<div class="color-silver js-vuz-edit" data-block="first" data-iblock="3" style="position: absolute; top: 0px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
						<?endif?>
						<?
						if($arResult["PROPERTIES"]["CITY"]["VALUE"]):
						    $arSelectCity = array("ID", "NAME", "IBLOCK_ID");
						    $arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $arResult["PROPERTIES"]["CITY"]["VALUE"]);
						    $resCity = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterCity, false, false, $arSelectCity);
						    if($rowCity = $resCity->GetNext()) {
							    $cityName = $rowCity["NAME"];
							}
						?>
						<span><?=$arResult["PROPERTIES"]["CITY"]["NAME"]?>: <?=$cityName?></span>
						<?endif?>
						<?if($arResult["PROPERTIES"]["ADRESS"]["VALUE"]):?>
						<span><?=$arResult["PROPERTIES"]["ADRESS"]["NAME"]?>: <?=$arResult["PROPERTIES"]["ADRESS"]["VALUE"]?></span>
						<?endif?>
						<?if($arResult["PROPERTIES"]["PHONE"]["VALUE"]):?>
						<span><?=$arResult["PROPERTIES"]["PHONE"]["NAME"]?>: <?=$arResult["PROPERTIES"]["PHONE"]["VALUE"]?></span>
						<?endif?>
						<?if($arResult["PROPERTIES"]["PHONE_PK"]["VALUE"]):?>
						<span><?=$arResult["PROPERTIES"]["PHONE_PK"]["NAME"]?>: <?=$arResult["PROPERTIES"]["PHONE_PK"]["VALUE"]?></span>
						<?endif?>
						<?if($arResult["PROPERTIES"]["EMAIL"]["VALUE"]):?>
						<span><?=$arResult["PROPERTIES"]["EMAIL"]["NAME"]?>: <a href="mailto:<?=$arResult["PROPERTIES"]["EMAIL"]["VALUE"]?>" target="_blank"><?=$arResult["PROPERTIES"]["EMAIL"]["VALUE"]?></a></span>
						<?endif?>
						<?if($arResult["PROPERTIES"]["EMAIL_PK"]["VALUE"]):?>
						<span><?=$arResult["PROPERTIES"]["EMAIL_PK"]["NAME"]?>: <a href="mailto:<?=$arResult["PROPERTIES"]["EMAIL_PK"]["VALUE"]?>" target="_blank"><?=$arResult["PROPERTIES"]["EMAIL_PK"]["VALUE"]?></a></span>
						<?endif?>
						<?if($arResult["PROPERTIES"]["SITE"]["VALUE"]):?>
						<span><?=$arResult["PROPERTIES"]["SITE"]["NAME"]?>: <a href="<?=$arResult["PROPERTIES"]["SITE"]["VALUE"]?>" target="_blank"><?=$arResult["PROPERTIES"]["SITE"]["VALUE"]?></a></span>
						<?endif?>

						<? if($arResult["PROPERTIES"]["VK"]["VALUE"]
							|| $arResult["PROPERTIES"]["FB"]["VALUE"]
							|| $arResult["PROPERTIES"]["OK"]["VALUE"]
							|| $arResult["PROPERTIES"]["TWITTER"]["VALUE"]
							|| $arResult["PROPERTIES"]["WIKI"]["VALUE"]
							|| $arResult["PROPERTIES"]["INSTA"]["VALUE"]
							|| $arResult["PROPERTIES"]["YOUTUBE"]["VALUE"]
							|| $arResult["PROPERTIES"]["DOP_LINK"]["VALUE"]) { ?>
						<div class="title"><b>Ссылки:</b></div>
						<div class="links" style="position: relative;">
							<?if($pageAdmin):?>
							<div class="color-silver js-vuz-edit" data-block="soc" data-iblock="3" style="position: absolute; top: 0px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
							<?endif?>
							<?if($arResult["PROPERTIES"]["VK"]["VALUE"]):?>
							<p><i class="ico vk"></i> <a href="<?=$arResult["PROPERTIES"]["VK"]["VALUE"]?>" target="_blank">Вконтакте</a></p>
							<?endif?>
							<?if($arResult["PROPERTIES"]["FB"]["VALUE"]):?>
							<p><i class="ico fc"></i> <a href="<?=$arResult["PROPERTIES"]["FB"]["VALUE"]?>" target="_blank">Facebook</a></p>
							<?endif?>
							<?if($arResult["PROPERTIES"]["OK"]["VALUE"]):?>
							<p><i class="ico ok"></i> <a href="<?=$arResult["PROPERTIES"]["OK"]["VALUE"]?>" target="_blank">Одноклассники</a></p>
							<?endif?>
							<?if($arResult["PROPERTIES"]["TWITTER"]["VALUE"]):?>
							<p><i class="ico tw"></i> <a href="<?=$arResult["PROPERTIES"]["TWITTER"]["VALUE"]?>" target="_blank">Twitter</a></p>
							<?endif?>
							<?if($arResult["PROPERTIES"]["WIKI"]["VALUE"]):?>
							<p><i class="ico wik"></i> <a href="<?=$arResult["PROPERTIES"]["WIKI"]["VALUE"]?>" target="_blank">Wikipedia</a></p>
							<?endif?>
							<?if($arResult["PROPERTIES"]["INSTA"]["VALUE"]):?>
							<p><i class="ico inst"></i> <a href="<?=$arResult["PROPERTIES"]["INSTA"]["VALUE"]?>" target="_blank">Instagram</a></p>
							<?endif?>
							<?if($arResult["PROPERTIES"]["YOUTUBE"]["VALUE"]):?>
							<p><i class="ico you"></i> <a href="<?=$arResult["PROPERTIES"]["YOUTUBE"]["VALUE"]?>" target="_blank">Youtube</a></p>
							<?endif?>
							<?if($arResult["PROPERTIES"]["DOP_LINK"]["VALUE"]):
								foreach($arResult["PROPERTIES"]["DOP_LINK"]["VALUE"] as $dop_link) {
									?>
									<p><i class="ico"></i> <a href="<?=$dop_link?>" target="_blank">Дополнительные ссылки</a></p>
									<?
								}
							endif?>
						</div>
						<? } elseif($pageAdmin) { ?>
						<div class="links" style="position: relative; margin-bottom: 60px;">
							<div class="color-silver js-vuz-edit" data-block="soc" data-iblock="3" style="position: absolute; top: 0px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">добавить ссылки</div>
						</div>
						<? } ?>
						<div style="position: relative;">
							<?if($pageAdmin):?>
							<div class="color-silver js-vuz-edit" data-block="license" data-iblock="3" style="position: absolute; top: -5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
							<?endif?>

							<?if($arResult["PROPERTIES"]["GOV"]["VALUE"]):?>
							<div class="title"><b><?=$arResult["PROPERTIES"]["GOV"]["NAME"]?>:</b></div>
							<span><?=$arResult["PROPERTIES"]["GOV"]["VALUE"]?></span>
							<?endif?>

							<?if($arResult["PROPERTIES"]["GA_NUM"]["VALUE"]):?>
							<div class="title"><b>Государственная аккредитация:</b></div>
								<?if($arResult["PROPERTIES"]["GA_PDF"]["VALUE"]):?>
								<span>Приказ Рособрнадзора № <a href="<?=CFile::GetPath($arResult["PROPERTIES"]["GA_PDF"]["VALUE"]);?>" target="_blank"><?=$arResult["PROPERTIES"]["GA_NUM"]["VALUE"]?></a> от <?=$arResult["PROPERTIES"]["GA_START"]["VALUE"]?> на срок до: <?=$arResult["PROPERTIES"]["GA_END"]["VALUE"]?></span>
								<?else:?>
								<span>Приказ Рособрнадзора № <?=$arResult["PROPERTIES"]["GA_NUM"]["VALUE"]?> от <?=$arResult["PROPERTIES"]["GA_START"]["VALUE"]?> на срок до: <?=$arResult["PROPERTIES"]["GA_END"]["VALUE"]?></span>
								<?endif?>
							<?endif?>

							<?if($arResult["PROPERTIES"]["GA_SVID"]["VALUE"]["TEXT"]):?>
								<span><a href="<?=$arResult["PROPERTIES"]["GA_SVID"]["VALUE"]["TEXT"];?>" target="_blank">Свидетельство о государственной аккредитации</a></span>
							<?endif?>

							<?if($arResult["PROPERTIES"]["LICESE_NUM"]["VALUE"]):?>
							<div class="title"><b>Лицензия:</b></div>
								<span>№ <?=$arResult["PROPERTIES"]["LICESE_NUM"]["VALUE"]?> от <?=$arResult["PROPERTIES"]["LICESE_START"]["VALUE"]?> на срок до: <?=$arResult["PROPERTIES"]["LICESE_END"]["VALUE"]?></span>
							<?endif?>

							<?if($arResult["PROPERTIES"]["LICESE_LINK"]["VALUE"]):?>
								<span><a href="<?=$arResult["PROPERTIES"]["LICESE_LINK"]["VALUE"];?>" target="_blank">Ссылка на Лицензию</a></span>
							<?endif?>

							<?if($arResult["PROPERTIES"]["AKK_NUM"]["VALUE"]):?>
							<div class="title"><b>Аккредитация:</b></div>
								<span>Свидетельство № <?=$arResult["PROPERTIES"]["AKK_NUM"]["VALUE"]?> от <?=$arResult["PROPERTIES"]["AKK_START"]["VALUE"]?> на срок до: <?=$arResult["PROPERTIES"]["AKK_END"]["VALUE"]?></span>
							<?endif?>

							<?if($arResult["PROPERTIES"]["GA_LINK"]["VALUE"]):?>
								<span><a href="<?=$arResult["PROPERTIES"]["GA_LINK"]["VALUE"];?>" target="_blank">Ссылка на Аккредитацию</a></span>
							<?endif?>

							<?if($arResult["PROPERTIES"]["UCHREDITEL"]["VALUE"]):?>
							<div class="title"><b><?=$arResult["PROPERTIES"]["UCHREDITEL"]["NAME"]?>:</b></div>
							<span><?=$arResult["PROPERTIES"]["UCHREDITEL"]["VALUE"]?></span>
							<?endif?>

							<?if($arResult["PROPERTIES"]["RUKOVODSTVO"]["VALUE"]):?>
							<div class="title"><b><?=$arResult["PROPERTIES"]["RUKOVODSTVO"]["VALUE"]?>:</b></div>
							<span><?=$arResult["PROPERTIES"]["FIO_RUKOVODSTVO"]["VALUE"]?></span>
							<?endif?>
						</div>
					</div><!-- contact-info -->

					<? if($arResult["PROPERTIES"]["YEAR"]["VALUE"]) {
							$year_digital = preg_replace('~\D+~','', $arResult["PROPERTIES"]["YEAR"]["VALUE"]);
					?>
					<div class="stick-year">
						<div class="text">
							год <br>основания
							<span><?=$year_digital?></span>
						</div>
					</div><!-- stick-year -->
					<? } ?>
				</div>
			</div><!-- content-right -->

			<div class="st-content-bottom clear" style="margin-top: 15px;">
				<div class="page-rating" id="page-rating-vuz">
					<div class="center text-center" style="padding: 20px 0px;">
						<span class="title"><?php if($arResult["OTZIV_NUM"]) { echo 'Отзывов: '; } ?><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=reviews"><?php if($arResult["OTZIV_NUM"]) { echo $arResult["OTZIV_NUM"]; } else { echo 'Оставить отзыв'; }?></a></span>
						<?php if($arResult["OTZIV_LAST_TIME"]) { ?>
						<span class="date">Последний отзыв <?=$arResult["OTZIV_LAST_TIME"]?></span>
						<?php } ?>
					</div>
					<?php
					if(sizeof($arResult["LIKE_ALL"])) {
						if(sizeof($arResult["LIKE_ALL"]) > 4) {
							$showBaloon = 3;
						} else {
							$showBaloon = 4;
						}
					?>
					<div class="st-baloon my-baloon js-baloon">
					<?php
					$en = 0;
					foreach($arResult["LIKE_ALL"] as $events_item) {

						if($en >= $showBaloon) {
							echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="vuz" data-id="' . $arResult["ID"] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
							break;
						} else {
							$en++;
							$rsUserData = CUser::GetByID($events_item["id_user"]);
							$userData = $rsUserData->Fetch();

							if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
								$format_name = $userData['NAME'];
								if($userData['SECOND_NAME']) {
									$format_name .= ' ';
									$format_name .= $userData['SECOND_NAME'];
								}
								$format_name .= ' ';
								$format_name .= $userData['LAST_NAME'];
							} else {
								$format_name = $userData['LOGIN'];
							}

							if($userData['PERSONAL_PHOTO']) {
								$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
							} else {
								$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
							}
						}
						?>
						<a class="vuz-like-<?php echo $events_item["id_user"]; ?><?php if(!$_SESSION['USER_DATA']) { echo ' js-noauth'; } ?>" href="/user/<?php echo $userData['ID']; ?>/">
							<div class="image">
								<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
							</div>
						</a>
					<?php } ?>
					</div>
					<?php } ?>
					<a href="#" class="button js-vuz-left <?php if($_SESSION['USER_DATA']) { echo 'js-b-left'; } else { echo 'js-noauth'; } ?> b-left<?php if($arResult["LIKE_MY"]) { echo " active"; } ?>"<?php if(!$arResult["OTZIV_NUM"]) { echo ' style="top: 7px;"'; }?>><span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if($arResult["LIKE_ALL"]) { echo sizeof($arResult["LIKE_ALL"]); } else { echo '0'; } ?></span></a>
					<?php
					if(sizeof($arResult["DESLIKE_ALL"])) {
						if(sizeof($arResult["DESLIKE_ALL"]) > 4) {
							$showBaloon = 3;
						} else {
							$showBaloon = 4;
						}
					?>
					<div class="st-baloon my-baloon js-baloon" style="right: 0px;">
					<?php
					$en = 0;
					foreach($arResult["DESLIKE_ALL"] as $events_item) {

						if($en >= $showBaloon) {
							echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="vuz" data-id="' . $arResult["ID"] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
							break;
						} else {
							$en++;
							$rsUserData = CUser::GetByID($events_item["id_user"]);
							$userData = $rsUserData->Fetch();

							if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
								$format_name = $userData['NAME'];
								if($userData['SECOND_NAME']) {
									$format_name .= ' ';
									$format_name .= $userData['SECOND_NAME'];
								}
								$format_name .= ' ';
								$format_name .= $userData['LAST_NAME'];
							} else {
								$format_name = $userData['LOGIN'];
							}

							if($userData['PERSONAL_PHOTO']) {
								$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
							} else {
								$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
							}
						}
						?>
						<a class="vuz-deslike-<?php echo $events_item["id_user"]; ?><?php if(!$_SESSION['USER_DATA']) { echo ' js-noauth'; } ?>" href="/user/<?php echo $userData['ID']; ?>/">
							<div class="image">
								<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
							</div>
						</a>
					<?php } ?>
					</div>
					<?php } ?>
					<a href="#" class="button js-vuz-right <?php if($_SESSION['USER_DATA']) { echo 'js-b-right'; } else { echo 'js-noauth'; } ?> b-right<?php if($arResult["DESLIKE_MY"]) { echo " active"; } ?>"<?php if(!$arResult["OTZIV_NUM"]) { echo ' style="top: 7px;"'; }?>><span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if($arResult["DESLIKE_ALL"]) { echo sizeof($arResult["DESLIKE_ALL"]); } else { echo '0'; } ?></span></a>
				</div><!-- page-rating -->

				<script>
				<?php
					if($arResult["LIKE_MY"]) {
						echo 'var like = 1; ';
					} else {
						echo 'var like = 0; ';
					}
					if($arResult["DESLIKE_MY"]) {
						echo 'var deslike = 1; ';
					} else {
						echo 'var deslike = 0; ';
					}
				?>
				</script>

				<?
				$categoryIco = 0;
				if($arResult["PROPERTIES"]["OBG"]["VALUE"]
					|| $arResult["PROPERTIES"]["PARKING"]["VALUE"]
					|| $arResult["PROPERTIES"]["WIFI"]["VALUE"]
					|| $arResult["PROPERTIES"]["STOLOVAYA"]["VALUE"]
					|| $arResult["PROPERTIES"]["MEDPUNKT"]["VALUE"]
					|| $arResult["PROPERTIES"]["SPORT"]["VALUE"]
					|| $arResult["PROPERTIES"]["BOOK"]["VALUE"]
					|| $arResult["PROPERTIES"]["WAR"]["VALUE"]
					|| $arResult["PROPERTIES"]["MUSEUM"]["VALUE"]
					|| $arResult["PROPERTIES"]["WATER"]["VALUE"]
					|| $arResult["PROPERTIES"]["AKT_ZAL"]["VALUE"]) {

					$categoryIco = 1;

				}
				if($categoryIco) { ?>
				<div class="category-ico" style="position: relative;">
					<?if($pageAdmin):?>
						<div class="color-silver js-vuz-edit" data-block="service" data-iblock="3" style="position: absolute; top: -28px; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
					<?endif?>
					<?if($arResult["PROPERTIES"]["PARKING"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-1.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["PARKING"]["VALUE"]?></span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["WIFI"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-2.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["WIFI"]["VALUE"]?></span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["OBG"]["VALUE"]):?>
					<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=obchegitie"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-3.png" alt="ico"><span class="title">Общежитие</span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["STOLOVAYA"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-4.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["STOLOVAYA"]["VALUE"]?></span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["MEDPUNKT"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-5.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["MEDPUNKT"]["VALUE"]?></span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["SPORT"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-6.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["SPORT"]["VALUE"]?></span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["BOOK"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-7.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["BOOK"]["VALUE"]?></span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["WAR"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-8.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["WAR"]["VALUE"]?></span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["MUSEUM"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-9.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["MUSEUM"]["VALUE"]?></span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["WATER"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-11.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["WATER"]["VALUE"]?></span></a>
					<?endif?>
					<?if($arResult["PROPERTIES"]["AKT_ZAL"]["VALUE"]):?>
					<a href="javascript:void(0)"><img src="<?=SITE_TEMPLATE_PATH?>/images/cat-12.png" alt="ico"><span class="title"><?=$arResult["PROPERTIES"]["AKT_ZAL"]["VALUE"]?></span></a>
					<?endif?>
				</div>
				<?
				} elseif($pageAdmin) { ?>
				<div class="category-ico" style="position: relative;">
					<div class="color-silver js-vuz-edit" data-block="service" data-iblock="3" style="position: absolute; top: -28px; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">добавить сервисы</div>
				</div>
				<? }

				$newsList = array();
				$arrFilter = array();

				if($arResult["NEWS"]) {
					$arrFilter['news'] = 1;

					$like = array();
					$like_sql = $dbh->query('SELECT id_news from a_like_news WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"] . ' ORDER BY id DESC')->fetchAll();
					foreach($like_sql as $like_item) {
						$like[] = $like_item['id_news'];
					}

					$deslike = array();
					$deslike_sql = $dbh->query('SELECT id_news from a_deslike_news WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"] . ' ORDER BY id DESC')->fetchAll();
					foreach($deslike_sql as $deslike_item) {
						$deslike[] = $deslike_item['id_news'];
					}

					$like_news_cnt = array();
					$like_news_cnt_sql = $dbh->query('SELECT * from a_like_news WHERE id_vuz = ' . $arResult["ID"] . ' ORDER BY id DESC')->fetchAll();
					foreach($like_news_cnt_sql as $like_news_cnt_item) {
						$like_news_cnt[$like_news_cnt_item['id_news']][] = $like_news_cnt_item;
					}

					$deslike_news_cnt = array();
					$deslike_news_cnt_sql = $dbh->query('SELECT * from a_deslike_news WHERE id_vuz = ' . $arResult["ID"] . ' ORDER BY id DESC')->fetchAll();
					foreach($deslike_news_cnt_sql as $deslike_news_cnt_item) {
						$deslike_news_cnt[$deslike_news_cnt_item['id_news']][] = $deslike_news_cnt_item;
					}

					$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PREVIEW_TEXT", "PREVIEW_PICTURE", "PROPERTY_LIKE", "PROPERTY_DESLIKE");
					$arFilter = array("IBLOCK_ID" => 28, "ACTIVE" => "Y", "PROPERTY_VUZ_ID" => $arResult["ID"]);
					$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
					while($row = $res->Fetch())
					{
						$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

						$row["TYPE"] = 'news';
						$row['sort'] = strtotime($row['DATE_CREATE']);
						$newsList[] = $row;
					}
				}

				if($arResult["PROPERTIES"]["ADD_EVENTS"]["VALUE"]) {
					$arrFilter['events'] = 1;

					$like_events = array();
					$like_events_sql = $dbh->query('SELECT key_event from a_like_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"] . ' ORDER BY id DESC')->fetchAll();
					foreach($like_events_sql as $like_events_item) {
						$like_events[] = $like_events_item['key_event'];
					}

					$deslike_events = array();
					$deslike_events_sql = $dbh->query('SELECT key_event from a_deslike_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"] . ' ORDER BY id DESC')->fetchAll();
					foreach($deslike_events_sql as $deslike_events_item) {
						$deslike_events[] = $deslike_events_item['key_event'];
					}

					$like_events_cnt = array();
					$like_events_cnt_sql = $dbh->query('SELECT * from a_like_events WHERE id_vuz = ' . $arResult["ID"] . ' ORDER BY id DESC')->fetchAll();
					foreach($like_events_cnt_sql as $like_events_cnt_item) {
						$like_events_cnt[$like_events_cnt_item['key_event']][] = $like_events_cnt_item;
					}

					$deslike_events_cnt = array();
					$deslike_events_cnt_sql = $dbh->query('SELECT * from a_deslike_events WHERE id_vuz = ' . $arResult["ID"] . ' ORDER BY id DESC')->fetchAll();
					foreach($deslike_events_cnt_sql as $deslike_events_cnt_item) {
						$deslike_events_cnt[$deslike_events_cnt_item['key_event']][] = $deslike_events_cnt_item;
					}

					$deslike_events_go = array();
					$deslike_events_go_sql = $dbh->query('SELECT key_event from a_events_go WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetchAll();
					foreach($deslike_events_go_sql as $deslike_events_go_item) {
						$deslike_events_go[] = $deslike_events_go_item['key_event'];
					}

					$deslike_events_go_cnt = array();
					$deslike_events_go_cnt_sql = $dbh->query('SELECT * from a_events_go WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
					foreach($deslike_events_go_cnt_sql as $deslike_events_go_cnt_item) {
						$deslike_events_go_cnt[$deslike_events_go_cnt_item['key_event']][] = $deslike_events_go_cnt_item;
					}

			        $placeholderEvents = array('Название',
			            'Дата',
			            'Время',
			            'Адрес',
			            'Координаты Яндекс',
			            'Телефон',
			            'Контактное лицо',
			            'Ссылка на страницу',
			            'Комментарий',
			            'Текст',
			            'Облако тегов',
			            'Тег',
			            'Дата создания',
			            'Дополнительная строка',
			            'Уникальный ключ'); // 15

					foreach($arResult["PROPERTIES"]["ADD_EVENTS"]["VALUE"] as $idEvent => $item) {
						$arrTemp = array();
						$arrItem = explode('#', $item);
						if(!$arrItem[0])
							continue;

						$arrTemp["TYPE"] = 'events';
						$arrTemp['ID'] = $arrItem[14];
						$arrTemp['DATA'] = $arrItem;

						$fullTime = $arrItem[1] . ' ' . $arrItem[2];

						$strDate = get_str_time_post(strtotime($fullTime));
						$arrTemp["FORMAT_DATE"] = $strDate;

						$curDate = explode(' ', $strDate);
						$arrTemp["DAY"] = $curDate[0];
						$arrTemp["MONTH"] = $curDate[1];

						$arrTemp['sort'] = strtotime($fullTime);
						$newsList[] = $arrTemp;
					}
				}

				if($arResult["PROPERTIES"]["OPENDOOR"]["VALUE"]) {
					$arrFilter['opendoor'] = 1;

			        $placeholderOpendoor = array('Название',
			            'Дата',
			            'Время',
			            'Адрес',
			            'Координаты Яндекс',
			            'Телефон',
			            'Ссылка на страницу',
			            'Комментарий',
			            'Текст',
			            'ucheba.ru',
			            'Дата создания',
			            'Дополнительная строка',
			            'Уникальный ключ');

					foreach($arResult["PROPERTIES"]["OPENDOOR"]["VALUE"] as $idOd => $item) {
						$arrTemp = array();
						$arrItem = explode('#', $item);
						if(!$arrItem[0])
							continue;

						if(sizeof($arrItem) < sizeof($placeholder)) {
							$arrItem[9]	= $arrItem[5];
							$arrItem[5] = '';
						}

						$arrTemp["TYPE"] = 'opendoor';
						$arrTemp['ID'] = $arrItem[12];
						$arrTemp['DATA'] = $arrItem;

						$fullTime = $arrItem[1] . ' ' . $arrItem[2];

						$strDate = get_str_time_post(strtotime($fullTime));
						$arrTemp["FORMAT_DATE"] = $strDate;

						$curDate = explode(' ', $strDate);
						$arrTemp["DAY"] = $curDate[0];
						$arrTemp["MONTH"] = $curDate[1];

						$arrTemp = strtotime($fullTime);
						$newsList[] = $arrTemp;
					}
				}

				if($newsList) {
				?>
				<div class="module st-news">
					<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Новости и события</span></div>
					<div class="m-header">
						<?if($arrFilter['news']):?>
						<a href="#" data-filter="news" class="filter js-news-list color-silver">Новости</a> &nbsp;
						<?endif?>
						<?if($arrFilter['events']):?>
						<a href="#" data-filter="events" class="filter js-news-list<?php if(!$arrFilter['news']) { echo ' color-silver'; } ?>">События</a> &nbsp;
						<?endif?>
						<?if($arrFilter['opendoor']):?>
						<a href="#" data-filter="opendoor" class="filter js-news-list<?php if(!$arrFilter['news'] && !$arrFilter['events']) { echo ' color-silver'; } ?>">Дни открытых дверей</a> &nbsp;
						<?endif?>
					</div>
					<script>
					<?php
					if(sizeof($newsList) > 10) {
						echo 'var startFrom = 10;' . "\n";
					}

					echo 'var arrLikeNews = new Array();' . "\n";
					foreach($like as $itemLike) {
						echo 'arrLikeNews.push(' . $itemLike . ');' . "\n";
					}
					echo 'var arrDeslikeNews = new Array();' . "\n";
					foreach($deslike as $itemDeslike) {
						echo 'arrDeslikeNews.push(' . $itemDeslike . ');' . "\n";
					}
					echo 'var arrLikeNewsCnt = new Array();' . "\n";
					foreach($like_news_cnt as $idNews => $arrCnt) {
						echo 'arrLikeNewsCnt[' . $idNews . '] = ' . sizeof($arrCnt) . ';' . "\n";
					}
					echo 'var arrDeslikeNewsCnt = new Array();' . "\n";
					foreach($deslike_news_cnt as $idNews => $arrCnt) {
						echo 'arrDeslikeNewsCnt[' . $idNews . '] = ' . sizeof($arrCnt) . ';' . "\n";
					}

					echo 'var arrLikeEvents = new Array();' . "\n";
					foreach($like_events as $itemLikeEvents) {
						echo 'arrLikeEvents.push(' . $itemLikeEvents . ');' . "\n";
					}
					echo 'var arrDeslikeEvents = new Array();' . "\n";
					foreach($deslike_events as $itemDeslikeEvents) {
						echo 'arrDeslikeEvents.push(' . $itemDeslikeEvents . ');' . "\n";
					}
					echo 'var arrLikeEventsCnt = new Array();' . "\n";
					foreach($like_events_cnt as $idEvent => $arrCnt) {
						echo 'arrLikeEventsCnt[' . $idEvent . '] = ' . sizeof($arrCnt) . ';' . "\n";
					}
					echo 'var arrDeslikeEventsCnt = new Array();' . "\n";
					foreach($deslike_events_cnt as $idEvent => $arrCnt) {
						echo 'arrDeslikeEventsCnt[' . $idEvent . '] = ' . sizeof($arrCnt) . ';' . "\n";
					}

					echo 'var arrGoEvents = new Array();' . "\n";
					foreach($deslike_events_go as $itemDeslikeEvents) {
						echo 'arrGoEvents.push(' . $itemDeslikeEvents . ');' . "\n";
					}
					echo 'var arrGoEventsCnt = new Array();' . "\n";
					foreach($deslike_events_go_cnt as $idEvent => $arrCnt) {
						echo 'arrGoEventsCnt[' . $idEvent . '] = ' . sizeof($arrCnt) . ';' . "\n";
					}

					echo "var detailPageUrl = '" . $arResult["DETAIL_PAGE_URL"] . "';" . "\n";
					?>
					</script>
					<div class="line" id="box-line" data-vuz="<?=$arResult['ID']?>" data-type="all">
					<?php
					usort($newsList, "cmp_uz");
					//krsort($newsList);
					$go = 1;
					$news_cnt = 0;
					$events_cnt = 0;
					$opendoor_cnt = 0;
					$cur_time = time();
					$tuday_events = 1;
					$tuday_opendoor = 1;
					foreach($newsList as $ts => $itemList) {
						if($itemList['TYPE'] == 'news') {
							$news_cnt++;
							if($news_cnt > 10)
								continue;
						?>
						<div class="news-item news" style="position: relative;">
							<?if($pageAdmin):?>
							<div class="color-silver js-news-edit" data-block="news" data-id="<?php echo $itemList["ID"]; ?>" data-iblock="3" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
							<?endif?>
							<?php if($itemList["PREVIEW_PICTURE"]) { ?>
							<div class="image brd left"><img src="<? echo CFile::GetPath($itemList["PREVIEW_PICTURE"]); ?>" alt="<?=$itemList["NAME"]?>" title="<?=$itemList["NAME"]?>" style="max-width: 200px;"></div>
							<?php } ?>
							<div class="date" style="margin-bottom: 7px;"><?php echo $itemList["FORMAT_DATE"]; ?></div>
							<div class="news-name">
								<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=news&s=<?php echo $itemList["ID"]; ?>"><span><?=$itemList["NAME"]?></span></a>
							</div>
							<p>
							<?php
							$br = str_replace(array("\r\n", "\r", "\n"), '<br>', $itemList["DETAIL_TEXT"]);
							$out = substr($br, 0, 148);
							echo $out . '..';
							?>
							</p>
							<div class="page-rating" data-news="<?=$itemList['ID']?>" data-vuz="<?=$arResult["ID"]?>" data-name="<?=$itemList["NAME"]?>" style="margin: 0px 0px 5px 0px; text-align: right;">
								<?php
								if(sizeof($like_news_cnt[$itemList['ID']])) {
									if(sizeof($like_news_cnt[$itemList['ID']]) > 4) {
										$showBaloon = 3;
									} else {
										$showBaloon = 4;
									}
								?>
								<div class="st-baloon" style="right: 100px; height: 52px;">
								<?php
								$en = 0;
								foreach($like_news_cnt[$itemList['ID']] as $news_item) {
									if($en >= $showBaloon) {
										echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="news" data-id="' . $itemList["ID"] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
									} else {
										$en++;
										$rsUserData = CUser::GetByID($news_item["id_user"]);
										$userData = $rsUserData->Fetch();

										if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
											$format_name = $userData['NAME'];
											if($userData['SECOND_NAME']) {
												$format_name .= ' ';
												$format_name .= $userData['SECOND_NAME'];
											}
											$format_name .= ' ';
											$format_name .= $userData['LAST_NAME'];
										} else {
											$format_name = $userData['LOGIN'];
										}

										if($userData['PERSONAL_PHOTO']) {
											$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
										} else {
											$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
										}
									}
									?>
									<a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
										<div class="image" style="height: 42px;">
											<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
										</div>
									</a>
								<?php } ?>
								</div>
								<?php } ?>
								<a href="#" data-my="<?php if(in_array($itemList['ID'], $like)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($like_news_cnt[$itemList['ID']])) { echo sizeof($like_news_cnt[$itemList['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-news-left'; } else { echo 'js-noauth'; } ?> b-left<?php if(in_array($itemList['ID'], $like)) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px;">
									<span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($like_news_cnt[$itemList['ID']])) { echo sizeof($like_news_cnt[$itemList['ID']]); } else { echo '0'; } ?></span>
								</a>
								<?php
								if(sizeof($deslike_news_cnt[$itemList['ID']])) {
									if(sizeof($deslike_news_cnt[$itemList['ID']]) > 4) {
										$showBaloon = 3;
									} else {
										$showBaloon = 4;
									}
								?>
								<div class="st-baloon" style="right: 0px; height: 52px;">
								<?php
								$en = 0;
								foreach($deslike_news_cnt[$itemList['ID']] as $news_item) {
									if($en >= $showBaloon) {
										echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="news" data-id="' . $itemList["ID"] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
									} else {
										$en++;
										$rsUserData = CUser::GetByID($news_item["id_user"]);
										$userData = $rsUserData->Fetch();

										if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
											$format_name = $userData['NAME'];
											if($userData['SECOND_NAME']) {
												$format_name .= ' ';
												$format_name .= $userData['SECOND_NAME'];
											}
											$format_name .= ' ';
											$format_name .= $userData['LAST_NAME'];
										} else {
											$format_name = $userData['LOGIN'];
										}

										if($userData['PERSONAL_PHOTO']) {
											$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
										} else {
											$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
										}
									}
									?>
									<a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
										<div class="image" style="height: 42px;">
											<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
										</div>
									</a>
								<?php } ?>
								</div>
								<?php } ?>
								<a href="#" data-my="<?php if(in_array($itemList['ID'], $deslike)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($deslike_news_cnt[$itemList['ID']])) { echo sizeof($deslike_news_cnt[$itemList['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-news-right'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($itemList['ID'], $deslike)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px; margin-left: 5px;">
									<span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($deslike_news_cnt[$itemList['ID']])) { echo sizeof($deslike_news_cnt[$itemList['ID']]); } else { echo '0'; } ?></span>
								</a>
							</div>
						</div>
						<?php
						} elseif($itemList['TYPE'] == 'events') {
							$events_cnt++;
							if($events_cnt > 10)
								continue;
							$arrItem = $itemList['DATA'];
						?>
						<?php
						if($cur_time > $itemList['sort'] && $tuday_events) {
							$tuday_events = 0;
							if($events_cnt > 1) {
						?>
							<div class="line-today today-events" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: -21px; text-align: center; display: none;">
								<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
							</div>
						<?php
							}
						}
						?>
						<div class="news-item events" data-id="<?=$arResult['ID']?>" data-ukey="<?=$itemList['ID']?>" style="display: none;">
							<div class="right" data-vuz="<?=$arResult['ID']?>" data-event="<?=$itemList['ID']?>" style="position: relative;">
							<?if($pageAdmin):?>
							<div style="position: relative; top: -10px; right: 5px; text-align: right;">
								<div class="color-silver js-news-edit" data-block="events" data-id="<?php echo $itemList["ID"]; ?>" data-iblock="3" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
							</div>
							<?endif?>
							<? if($arrItem[1]) { ?>
							<div class="date-ico" style="margin-bottom: 10px;"><span><?=$itemList['DAY']?></span><?=$itemList['MONTH']?></div>
							<? } ?>
							<? if($arrItem[4]) { ?>
								<div class="btns text-right" style="text-align: left;">
									<a href="/map/?map=<?php echo $arResult["ID"]; ?>&event=<?php echo ($itemList["ID"] + 1); ?>" class="button">
										<span style="font-family: Verdana;">на карте</span>
									</a>
								</div>							<? } ?>
								<div class="btns text-right" style="margin-top: 15px; text-align: left; position: relative;">
									<?php
									if(sizeof($like_events_cnt[$itemList['ID']])) {
										if(sizeof($like_events_cnt[$itemList['ID']]) > 4) {
											$showBaloon = 3;
										} else {
											$showBaloon = 4;
										}
										?>
										<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
											<?php
											$en = 0;
											foreach($like_events_cnt[$itemList['ID']] as $events_item) {
												if($en >= $showBaloon) {
													echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="events" data-id="' . $itemList["ID"] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
												} else {
													$en++;
													$rsUserData = CUser::GetByID($events_item["id_user"]);
													$userData = $rsUserData->Fetch();

													if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
														$format_name = $userData['NAME'];
														if($userData['SECOND_NAME']) {
															$format_name .= ' ';
															$format_name .= $userData['SECOND_NAME'];
														}
														$format_name .= ' ';
														$format_name .= $userData['LAST_NAME'];
													} else {
														$format_name = $userData['LOGIN'];
													}

													if($userData['PERSONAL_PHOTO']) {
														$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
													} else {
														$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
													}
												}
												?>
												<a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
													<div class="image">
														<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
													</div>
												</a>
										<?php } ?>
										</div>
									<?php } ?>
									<a href="#" data-my="<?php if(in_array($itemList['ID'], $like_events)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($like_events_cnt[$itemList['ID']])) { echo sizeof($like_events_cnt[$itemList['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-event-left'; } else { echo 'js-noauth'; } ?> b-left<?php if(in_array($itemList['ID'], $like_events)) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px;">
										<span style="text-decoration: none;"><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($like_events_cnt[$itemList['ID']])) { echo sizeof($like_events_cnt[$itemList['ID']]); } else { echo '0'; } ?></span>
									</a>
								</div>
								<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
									<?php
									if(sizeof($deslike_events_cnt[$itemList['ID']])) {
										if(sizeof($deslike_events_cnt[$itemList['ID']]) > 4) {
											$showBaloon = 3;
										} else {
											$showBaloon = 4;
										}
										?>
										<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
											<?php
											$en = 0;
											foreach($deslike_events_cnt[$itemList['ID']] as $events_item) {
												if($en >= $showBaloon) {
													echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="events" data-id="' . $itemList["ID"] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
													break;
												} else {
													$en++;
													$rsUserData = CUser::GetByID($events_item["id_user"]);
													$userData = $rsUserData->Fetch();

													if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
														$format_name = $userData['NAME'];
														if($userData['SECOND_NAME']) {
															$format_name .= ' ';
															$format_name .= $userData['SECOND_NAME'];
														}
														$format_name .= ' ';
														$format_name .= $userData['LAST_NAME'];
													} else {
														$format_name = $userData['LOGIN'];
													}

													if($userData['PERSONAL_PHOTO']) {
														$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
													} else {
														$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
													}
												}
												?>
												<a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
													<div class="image">
														<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
													</div>
												</a>
										<?php } ?>
										</div>
									<?php } ?>
									<a href="#" data-my="<?php if(in_array($itemList['ID'], $deslike_events)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($deslike_events_cnt[$itemList['ID']])) { echo sizeof($deslike_events_cnt[$itemList['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-event-right'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($itemList['ID'], $deslike_events)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px;">
										<span style="text-decoration: none;"><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($deslike_events_cnt[$itemList['ID']])) { echo sizeof($deslike_events_cnt[$itemList['ID']]); } else { echo '0'; } ?></span>
									</a>
								</div>
								<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
									<?php
									if(sizeof($deslike_events_go_cnt[$itemList['ID']])) {
										if(sizeof($deslike_events_go_cnt[$itemList['ID']]) > 4) {
											$showBaloon = 3;
										} else {
											$showBaloon = 4;
										}
										?>
										<div class="st-baloon" style="height: 52px; right: 0px;">
										<?php
										$en = 0;
										foreach($deslike_events_go_cnt[$itemList['ID']] as $events_item) {
												if($en >= $showBaloon) {
													echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="events" data-id="' . $itemList["ID"] . '" data-hash="go" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
													break;
												} else {
													$en++;
													$rsUserData = CUser::GetByID($events_item["id_user"]);
													$userData = $rsUserData->Fetch();

													if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
														$format_name = $userData['NAME'];
														if($userData['SECOND_NAME']) {
															$format_name .= ' ';
															$format_name .= $userData['SECOND_NAME'];
														}
														$format_name .= ' ';
														$format_name .= $userData['LAST_NAME'];
													} else {
														$format_name = $userData['LOGIN'];
													}

													if($userData['PERSONAL_PHOTO']) {
														$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
													} else {
														$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
													}
												}
												?>
												<a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
													<div class="image">
														<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
													</div>
												</a>
										<?php } ?>
										</div>
									<?php } ?>
									<a href="#" data-lk="0" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-event-go'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($itemList['ID'], $deslike_events_go)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px;"><span style="text-decoration: none;">Я пойду (<?php echo sizeof($deslike_events_go_cnt[$itemList['ID']]); ?>)</span></a>
								</div>
							</div>
							<div class="date" style="margin-bottom: 7px;"><?php echo $itemList["FORMAT_DATE"]; if($cur_time > $itemList['sort']) { echo ' (событие уже прошло)'; } ?></div>
							<div class="news-name">
								<span><?=$arrItem[0]?></span>
							</div>
							<p style="margin-right: 100px;">
							<?php
							for($n = 1; $n < sizeof($placeholderEvents); $n++) {
								if($n == 1 || $n == 2 || $n == 4 || $n == 9 || $n == 12 || $n == 13 || $n == 14)
									continue;
								if(trim($arrItem[$n])) {
									if($n == 7) {
										echo $placeholderEvents[$n] . ': <a href="' . $arrItem[7] . '" target="blank">' . trim($arrItem[$n]) . '</a><br>';
									} elseif($n == 8) {
										echo trim($arrItem[$n]) . '<br>';
									} else {
										echo $placeholderEvents[$n] . ': ' . trim($arrItem[$n]) . '<br>';
									}
								}
							}
							?>
							</p>
						</div>
						<?php
						} elseif($itemList['TYPE'] == 'opendoor') {
							$opendoor_cnt++;
							if($opendoor_cnt > 10)
								continue;
							$arrItem = $itemList['DATA'];
						?>
						<?php
						if($cur_time > $itemList['sort'] && $tuday_opendoor) {
							$tuday_opendoor = 0;
							if($opendoor_cnt > 1) {
						?>
							<div class="line-today today-opendoor" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: -21px; text-align: center; display: none;">
								<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
							</div>
						<?php
							}
						}
						?>
						<div class="news-item opendoor" style="display: none;">
							<div class="right" style="position: relative;">
							<?if($pageAdmin):?>
							<div style="position: relative; top: -10px; right: 5px; text-align: right;">
								<div class="color-silver js-news-edit" data-block="opendoor" data-id="<?php echo $itemList["ID"]; ?>" data-iblock="3" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
							</div>
							<?endif?>
							<? if($arrItem[1]) { ?>
							<div class="date-ico" style="margin-bottom: 10px;"><span><?=$itemList['DAY']?></span><?=$itemList['MONTH']?></div>
							<? } ?>
							<? if($arrItem[4]) { ?>
							<div class="btns text-right">
								<a href="/map/?map=<?php echo $arResult["ID"]; ?>&opendoor=<?php echo $itemList["ID"]; ?>" class="button">
									<span style="font-family: Verdana;">на карте</span>
								</a>
							</div>							<? } ?>
							</div>
							<div class="date" style="margin-bottom: 7px;"><?php echo $itemList["FORMAT_DATE"]; if($cur_time > $itemList['sort']) { echo ' (событие уже прошло)'; } ?></div>
							<div class="news-name">
								<? if($arrItem[6]) { ?>
								<a href="<?=$arrItem[6]?>"><span><?=$arrItem[0]?></span></a>
								<? } else { ?>
								<span><?=$arrItem[0]?></span>
								<? } ?>
							</div>
							<p>
							<?php
							for($n = 1; $n < sizeof($placeholderOpendoor); $n++) {
								if($n == 1 || $n == 2 || $n == 4 || $n == 9 || $n == 10 || $n == 11 || $n == 12)
									continue;
								if(trim($arrItem[$n])) {
									if($n == 6) {
										echo $placeholderOpendoor[$n] . ': <a href="' . $arrItem[$n] . '" target="blank">' . trim($arrItem[$n]) . '</a><br>';
									} elseif($n == 8) {
										echo trim($arrItem[$n]) . '<br>';
									} else {
										echo $placeholderOpendoor[$n] . ': ' . trim($arrItem[$n]) . '<br>';
									}
								}
							}
							?>
							</p>
						</div>
						<?php
						}
						//$go++;
						if($go > 10)
							break;
					}
					?>
					</div>
				</div><!-- st-news -->
				<? } ?>
			</div><!-- st-content-bottom -->
		</div><!-- st-content-bottom -->
	</div>
	<?endif?>
</div><!-- page-item -->