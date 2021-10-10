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
?>
<div class="st-content-right">
    <div class="breadcrumbs">
        <a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/company/">Компания</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/company/law/">Правовая информация</a> <i class="fa fa-angle-double-right color-orange"></i> <?=$arResult["NAME"]?>
    </div>
    <div class="page-content">
        <div class="st-content-bottom clear">
            <div class="name-block text-left"> &nbsp;&nbsp;<span><?php echo $arResult["NAME"]?></span></div>

            <div class="news-detail">
                <?php echo $arResult["PREVIEW_TEXT"];?>
                <?php if($arResult["PROPERTIES"]["DOC"]["VALUE"]) { ?>
                <div style="text-align: center; margin-top: 50px;">
                    <a href="<?php echo CFile::GetPath($arResult["PROPERTIES"]["DOC"]["VALUE"]); ?>" download>
                        <button class="button right txt-up">
                            <span>Версия для печати (PDF)</span>
                        </button>
                    </a>
                </div>
                <?php } ?>
                <div style="clear:both"></div>
            </div>
        </div><!-- st-content-bottom -->
    </div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>