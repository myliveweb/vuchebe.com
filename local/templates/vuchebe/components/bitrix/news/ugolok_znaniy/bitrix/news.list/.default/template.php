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
if($arResult["SECTION"]):
	$current_section = $arResult["SECTION"]["PATH"][0]["CODE"];
else:
	$current_section = '';
endif;
require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_ugolok_znaniy.php');
//echo '<pre>';
//print_r($arResult["SECTION"]);
//echo '</pre>';
?>
<div class="st-content-right">
	<div class="breadcrumbs">
		<?if($arResult["SECTION"]):?>
 		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/ugolok-znaniy/">Уголок знаний</a> <i class="fa fa-angle-double-right color-orange"></i> <?=$arResult["SECTION"]["PATH"][0]["NAME"]?>
		<?else:?>
 		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> Уголок знаний
		<?endif;?>
	</div>
	<div class="page-content" id="page">
		<div class="name-block text-center txt-up"><span>Уголок знаний</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="line">
				<?if($arParams["DISPLAY_TOP_PAGER"]):?>
					<?=$arResult["NAV_STRING"]?><br />
				<?endif;?>
				<?foreach($arResult["ITEMS"] as $arItem):?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<div class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>" style="position: relative;">
                        <?if(isEditPlus()):?>
                            <div class="color-silver js-ugolok-edit" data-block="ugolok" data-id="<?php echo $arItem['ID']; ?>" data-iblock="5" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
                        <?endif?>
						<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
							<div style="width: 122px;" class="image left brd">
								<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>" />
							</div>
						<?endif?>
						<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
							<div class="news-name">
								<?if($current_section):?>
								<a href="/ugolok-znaniy/<?=$current_section?>/<?echo $arItem["CODE"]?>/"><span><?echo $arItem["NAME"]?></span></a>
								<?else:?>
								<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><span><?echo $arItem["NAME"]?></span></a>
								<?endif;?>
							</div>
						<?endif;?>
						<p>
							<? echo mb_substr($arItem['PREVIEW_TEXT'], 0, 160) . '..'; ?>
							<?if($arItem["DISPLAY_PROPERTIES"]["SIGN"]["DISPLAY_VALUE"]):?>
							<div style="text-align: right;">
								<? echo $arItem["DISPLAY_PROPERTIES"]["SIGN"]["DISPLAY_VALUE"]; ?>
							</div>
							<?endif;?>
						</p>
					</div>
				<?endforeach;?>
				<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
					<br /><?=$arResult["NAV_STRING"]?>
				<?endif;?>
				</div>
			</div>
			 <!-- st-news -->
		</div>
		 <!-- st-content-bottom -->
	</div>
	 <!-- page-item -->
</div>
