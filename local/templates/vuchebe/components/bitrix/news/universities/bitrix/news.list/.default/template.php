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
$current_razdel = 'universities';
require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_uchebnye-zavedeniya.php');
?>
<script>
var curList = '<?php echo $current_razdel; ?>';
var startFromList = 1;
var cnt = '<?php echo $arResult["CNT"]; ?>';
</script>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<div class="st-content-right">
	<div class="breadcrumbs">
 		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/uchebnye-zavedeniya/">Учебные зведения</a> <i class="fa fa-angle-double-right color-orange"></i> ВУЗы
	</div>
	<div class="page-content" id="page">
		<div class="name-block text-center txt-up"><span>Вузы</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="m-header">
					<a href="#" data-filter="rand" class="filter color-silver js-vuz-list">случайно</a> &nbsp;
					<a href="#" data-filter="abc" class="filter js-vuz-list">по алфавиту</a> &nbsp;
					<a href="#" data-filter="gov" class="filter js-vuz-list">государственные</a> &nbsp;
					<a href="#" data-filter="chast" class="filter js-vuz-list">частные</a> &nbsp;
                    <a href="#" data-filter="date" class="filter js-vuz-list">по дате основания</a> &nbsp;
				</div>
				<div class="line" id="rand">
				<?php
				foreach($arResult['DATA'] as $item) {
                    $year_digital = preg_replace('~\D+~','', $item["YEAR"]);
                ?>
				<div class="news-item" data-id="<?php echo $item['ID']; ?>">
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
                    <div class="col-2 img-mobile">
                        <div class="image brd left" style="margin: 0 0 10px 0;">
                            <img style="width: 122px;" src="<?php echo $item['IMG']; ?>" alt="<?php echo $item['NAME']; ?>" title="<?php echo $item['NAME']; ?>" />
                        </div>
                    </div>
                    <div class="col-8" style="padding: 0 0 0 15px; <?php if($item["YEAR"]) { ?>width: 60%<? } else { ?>width: 80%<? } ?>;">
                        <div class="news-name">
                            <a href="<?php echo $item["URL"]; ?>"><span class="crop-height"><?php echo $item["NAME"]; ?></span></a>
                        </div>
                        <p>
                        <?php if($item["ADRESS"]) { ?>
                            Адрес:&nbsp;<?php echo $item["ADRESS"]; ?><br>
                        <?php } ?>
                        <?php if($item["SITE"]) { ?>
                            Сайт:&nbsp;<a href="<?php echo $item["SITE"]; ?>"><?php echo $item["SITE"]; ?></a><br>
                        <?php } ?>
                        <?php if($item["PHONE"]) { ?>
                            Телефон:&nbsp;<?php echo $item["PHONE"]; ?><br>
                        <?php } ?>
                        <?php if($item["EMAIL"]) { ?>
                            Электронная почта:&nbsp;<a href="mailto:<?php echo $item["EMAIL"]; ?>"><?php echo $item["EMAIL"]; ?></a><br>
                        <?php } ?>
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
				<?php
				}
				?>
				</div>
				<div class="line" id="abc" style="display: none;"></div>
				<div class="line" id="gov" style="display: none;"></div>
				<div class="line" id="chast" style="display: none;"></div>
                <div class="line" id="date" style="display: none;"></div>
			</div>
			 <!-- st-news -->
		</div>
		 <!-- st-content-bottom -->
	</div>
	 <!-- page-item -->
</div>
