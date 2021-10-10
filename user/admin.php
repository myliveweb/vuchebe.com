<?php
global $dbh;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] > $b['sort']) ? -1 : 1;
}

$arAdmins = array();
$arrFilter = array();

$arSelect = array("ID", "NAME", "IBLOCK_ID");
$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_ADMINS" => false, "PROPERTY_ADMINS" => $user_id);
$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
while ($row = $res->Fetch()) {
    $arAdmins[] = $row;

    if($row['IBLOCK_ID'] == 2)
        $arrFilter['vuz'] = 1;
    elseif($row['IBLOCK_ID'] == 3)
        $arrFilter['suz'] = 1;
    elseif($row['IBLOCK_ID'] == 4)
        $arrFilter['nuz'] = 1;
    elseif($row['IBLOCK_ID'] == 6)
        $arrFilter['yk'] = 1;
}
?>
<style>
#box-line .js-bookmark {
	padding: 0;
	width: 100%;
}
.js-bookmark.active {
	color: #ff471a;
	background: #fff;
	border: 1px solid #ff471a;
}
.js-bookmark.active span {
	font-family: Verdana;
	text-decoration: none;
    color: #ff471a;
}
.js-bookmark.active span::before {
    border: 1px solid #ff471a;
}
.m-header .filter {
	color: #ff471a;
}
.m-header .filter.color-silver {
	color: #9f9f9f;
	text-decoration: none;
	cursor: default;
}
.hide-block {
	display: none;
}
.crop-height {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow-y: hidden;
}
.crop-height-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow-y: hidden;
}
</style>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>

<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Администратор</span>
	</div><br>
	<div class="page-content">
		<div class="name-block text-center txt-up" style="font-size: 18px;"><span>Администрирование</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="m-header" style="padding-bottom: 10px;">
					<a href="#" data-filter="all" class="filter color-silver js-educations-list">Все</a> &nbsp;
					<?if($arrFilter['nuz']):?>
					<a href="#" data-filter="nuz" class="filter js-educations-list">Начальное</a> &nbsp;
					<?endif?>
					<?if($arrFilter['suz']):?>
					<a href="#" data-filter="suz" class="filter js-educations-list">Среднее</a> &nbsp;
					<?endif?>
					<?if($arrFilter['vuz']):?>
					<a href="#" data-filter="vuz" class="filter js-educations-list">Высшее</a> &nbsp;
					<?endif?>
					<?if($arrFilter['yk']):?>
					<a href="#" data-filter="yk" class="filter js-educations-list">Языковые курсы</a> &nbsp;
					<?endif?>
				</div>
				<!--<div style="text-align: center;">
					<span class="color-silver js-uz-add" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: -14px;">Добавить учебное заведение</span>
					<span class="color-silver js-uz-add teacher" style="margin-left: 15px; cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: -14px;<?php if(!$_SESSION['USER_DATA']['WORK_WWW']) { echo ' display: none;'; } ?>">Я преподавал(а)</span>
				</div>-->
				<?php
				if(sizeof($arAdmins) > 0) {
					CModule::IncludeModule('iblock');
				?>
				<div class="line" id="box-line">
					<?php
					foreach($arAdmins as $uz) {
						$filterType = '';
						if($uz['IBLOCK_ID'] == 2) {
							$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL");
							$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ID" => $uz['ID']);
							$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
							if($row = $res->GetNext()) {

							if($row["PROPERTY_LOGO_VALUE"]):
								$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
							elseif($row["PREVIEW_PICTURE"]):
								$srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
							else:
								$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
							endif;

							if($uz['teacher'])
								$filterType = 'teacher';
							else
								$filterType = 'vuz';
							?>
							<div class="news-item <?php echo $filterType; ?>">
								<div class="image brd left">
									<img style="width: 122px;" src="<?php echo $srcLogo; ?>" alt = "<?php echo $row['NAME']; ?>" title = "<?php echo $row['NAME']; ?>">
								</div>
								<div class="news-name">
									<a href="/uchebnye-zavedeniya/universities/<?php echo $row["CODE"]; ?>/"><span class="crop-height-2"><?php echo $row['NAME']; ?></span></a>
								</div>
								<p>
								<?php if($row["PROPERTY_ADRESS_VALUE"]) { ?>
									Адрес:&nbsp;<?php echo $row["PROPERTY_ADRESS_VALUE"]; ?><br>
								<?php } ?>
								<?php if($row["PROPERTY_SITE_VALUE"]) { ?>
									Сайт:&nbsp;<a href="<?php echo $row["PROPERTY_SITE_VALUE"]; ?>"><?php echo $row["PROPERTY_SITE_VALUE"]; ?></a><br>
								<?php } ?>
								<?php if($row["PROPERTY_PHONE_VALUE"]) { ?>
									Телефон:&nbsp;<?php echo $row["PROPERTY_PHONE_VALUE"]; ?><br>
								<?php } ?>
								<?php if($row["PROPERTY_EMAIL_VALUE"]) { ?>
									Электронная почта:&nbsp;<a href="mailto:<?php echo $row["PROPERTY_EMAIL_VALUE"]; ?>"><?php echo $row["PROPERTY_EMAIL_VALUE"]; ?></a><br>
								<?php } ?>
								</p>
							</div>
							<?php
							}
						} elseif($uz['IBLOCK_ID'] == 3) {
							$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL");
							$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "ID" => $uz['ID']);
							$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
							if($row = $res->GetNext()) {
								if($row["PROPERTY_LOGO_VALUE"]):
									$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
								elseif($row["PREVIEW_PICTURE"]):
									$srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
								else:
									$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
								endif;

								if($uz['teacher'])
									$filterType = 'teacher';
								else
									$filterType = 'suz';
								?>
								<div class="news-item <?php echo $filterType; ?>">
									<div class="image brd left"><img style="width: 122px;" src="<?php echo $srcLogo; ?>" alt = "<?php echo $row['NAME']; ?>" title = "<?php echo $row['NAME']; ?>"></div>
									<div class="news-name">
										<a href="/uchebnye-zavedeniya/colleges/<?php echo $row["CODE"]; ?>/"><span class="crop-height-2"><?php echo $row['NAME']; ?></span></a>
									</div>
									<p>
									<?php if($row["PROPERTY_ADRESS_VALUE"]) { ?>
										Адрес:&nbsp;<?php echo $row["PROPERTY_ADRESS_VALUE"]; ?><br>
									<?php } ?>
									<?php if($row["PROPERTY_SITE_VALUE"]) { ?>
										Сайт:&nbsp;<a href="<?php echo $row["PROPERTY_SITE_VALUE"]; ?>"><?php echo $row["PROPERTY_SITE_VALUE"]; ?></a><br>
									<?php } ?>
									<?php if($row["PROPERTY_PHONE_VALUE"]) { ?>
										Телефон:&nbsp;<?php echo $row["PROPERTY_PHONE_VALUE"]; ?><br>
									<?php } ?>
									<?php if($row["PROPERTY_EMAIL_VALUE"]) { ?>
										Электронная почта:&nbsp;<a href="mailto:<?php echo $row["PROPERTY_EMAIL_VALUE"]; ?>"><?php echo $row["PROPERTY_EMAIL_VALUE"]; ?></a><br>
									<?php } ?>
									</p>
								</div>
								<?php
							}
						} elseif($uz['IBLOCK_ID'] == 4) {
							$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL");
							$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "ID" => $uz['ID']);
							$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
							if($row = $res->GetNext()) {
								if($row["PROPERTY_LOGO_VALUE"]):
									$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
								elseif($row["PREVIEW_PICTURE"]):
									$srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
								else:
									$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
								endif;

								if($uz['teacher'])
									$filterType = 'teacher';
								else
									$filterType = 'nuz';
								?>
								<div class="news-item <?php echo $filterType; ?>">
									<div class="image brd left"><img style="width: 122px;" src="<?php echo $srcLogo; ?>" alt = "<?php echo $row['NAME']; ?>" title = "<?php echo $row['NAME']; ?>"></div>
									<div class="news-name">
										<a href="/uchebnye-zavedeniya/schools/<?php echo $row["CODE"]; ?>/"><span class="crop-height-2"><?php echo $row['NAME']; ?></span></a>
									</div>
									<p>
									<?php if($row["PROPERTY_ADRESS_VALUE"]["TEXT"]) {
											$adress = $row["PROPERTY_ADRESS_VALUE"]["TEXT"];
									?>
										Адрес:&nbsp;<?php echo $adress; ?><br>
									<?php } ?>
									<?php if($row["PROPERTY_SITE_VALUE"]) { ?>
										Сайт:&nbsp;<a href="<?php echo $row["PROPERTY_SITE_VALUE"]; ?>"><?php echo $row["PROPERTY_SITE_VALUE"]; ?></a><br>
									<?php } ?>
									<?php if($row["PROPERTY_PHONE_VALUE"]) { ?>
										Телефон:&nbsp;<?php echo $row["PROPERTY_PHONE_VALUE"]; ?><br>
									<?php } ?>
									<?php if($row["PROPERTY_EMAIL_VALUE"]) { ?>
										Электронная почта:&nbsp;<a href="mailto:<?php echo $row["PROPERTY_EMAIL_VALUE"]; ?>"><?php echo $row["PROPERTY_EMAIL_VALUE"]; ?></a><br>
									<?php } ?>
									</p>
								</div>
								<?php
							}
						} elseif($uz['IBLOCK_ID'] == 6) {
							$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL");
							$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "ID" => $uz['ID']);
							$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
							if($row = $res->GetNext()) {
								if($row["PROPERTY_LOGO_VALUE"]):
									$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
								elseif($row["DETAIL_PICTURE"]):
									$srcLogo = CFile::GetPath($row["DETAIL_PICTURE"]);
								else:
									$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
								endif;

								if($uz['teacher'])
									$filterType = 'teacher';
								else
									$filterType = 'yk';
								?>
								<div class="news-item <?php echo $filterType; ?>">
									<div class="image brd left"><img style="width: 122px;" src="<?php echo $srcLogo; ?>" alt = "<?php echo $row['NAME']; ?>" title = "<?php echo $row['NAME']; ?>"></div>
									<div class="news-name">
										<a href="/uchebnye-zavedeniya/language-class/<?php echo $row["CODE"]; ?>/"><span class="crop-height-2"><?php echo $row['NAME']; ?></span></a>
									</div>
									<p>
									<?php if($row["PROPERTY_ADRESS_VALUE"]) {
										$arrAdress = explode('&', $row["PROPERTY_ADRESS_VALUE"]);
									?>
										Адрес:&nbsp;<?php echo $arrAdress[0]; ?><br>
									<?php } ?>
									<?php if($row["PROPERTY_SITE_VALUE"]) {
										$arrUrl = explode('?', $row["PROPERTY_SITE_VALUE"]);
									?>
										Сайт:&nbsp;<a href="<?php echo $arrUrl[0]; ?>"><? if(strlen($arrUrl[0]) > 52) { echo mb_substr($arrUrl[0], 0, 50) . '..'; } else { echo $arrUrl[0]; }?></a><br>
									<?php } ?>
									<?php if($row["PROPERTY_PHONE_VALUE"]) { ?>
										Телефон:&nbsp;<?php echo $row["PROPERTY_PHONE_VALUE"];?><br>
									<?php } ?>
									<?php if($row["PROPERTY_EMAIL_VALUE"]) { ?>
										Электронная почта:&nbsp;<a href="mailto:<?php echo $row["PROPERTY_EMAIL_VALUE"]; ?>"><?php echo $row["PROPERTY_EMAIL_VALUE"]; ?></a><br>
									<?php } ?>
									</p>
								</div>
								<?php
							}
						}
					}
					?>
				</div>
				<?php
				}
				?>
			</div><!-- st-news -->

		</div><!-- st-content-bottom -->
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>