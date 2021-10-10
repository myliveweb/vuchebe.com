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
$current_section = '';
$arr_request = explode('/', $_SERVER['REQUEST_URI']);
$SectList = CIBlockSection::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>5, "ACTIVE"=>"Y", "=CODE"=>$arr_request[2]) ,false, array("ID","IBLOCK_ID","CODE","NAME"));
if($SectListGet = $SectList->GetNext())
{
    $cur_section['NAME'] = $SectListGet["NAME"];
    $cur_section['CODE'] = $SectListGet["CODE"];
    $current_section = $SectListGet["CODE"];
}
require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_ugolok_znaniy.php');
//echo '<pre>';
//print_r($cur_section);
//echo '</pre>';
?>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/ugolok-znaniy/">Уголок знаний</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/ugolok-znaniy/<?=$cur_section['CODE']?>/"><?=$cur_section['NAME']?></a> <i class="fa fa-angle-double-right color-orange"></i> <?=$arResult["NAME"]?>
	</div>
	<div class="page-content" id="page">
		<div class="name-block text-center txt-up">
			<span>Уголок знаний</span>
		</div>
		<div class="page-item clearfix">
			<?if(is_array($arResult["DETAIL_PICTURE"])):?>
			<div class="col-3 content-left">
				<div class="image brd">
					<img
						src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
						alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
						title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
						/>
				</div>
				<?if($arResult["PROPERTIES"]["WIKI"]["VALUE"]):?>
				<div class="btns links" style="margin: 15px 0;">
					<i class="ico wik"></i> <a href="<?=$arResult["PROPERTIES"]["WIKI"]["VALUE"]?>" target="blank">подробнее</a>
				</div>
				<?endif?>
			</div>
			<?endif?>

			<div class="<?if(is_array($arResult["DETAIL_PICTURE"])):?>col-9 content-right<?else:?>col-12 content-center<?endif?>">
                <div class="page-info">
                    <?if(isEditPlus()):?>
                        <div class="color-silver js-ugolok-edit" data-block="ugolok" data-id="<?php echo $arResult['ID']; ?>" data-iblock="5" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
                    <?endif?>
					<h1 class="name-item">
						<span><?=$arResult["NAME"]?></span>
					</h1>
				</div>
				<div>
					<?=$arResult["DETAIL_TEXT"]?>
				</div>
				<?if(!is_array($arResult["DETAIL_PICTURE"])):?>
					<?if($arResult["PROPERTIES"]["WIKI"]["VALUE"]):?>
					<div class="btns links" style="margin: 15px 0;">
						<i class="ico wik"></i> <a href="<?=$arResult["PROPERTIES"]["WIKI"]["VALUE"]?>" target="blank">подробнее</a>
					</div>
					<?endif?>
				<?endif?>
			</div><!-- content-right -->
			<div class="st-content-bottom clear">
				<div class="st-tags-block">
				<?
				$arr_section = array();
				$ElementId = $arResult['ID'];
				$db_groups = CIBlockElement::GetElementGroups($ElementId, true);
				while($ar_group = $db_groups->Fetch()) {
					$arr_section[] = $ar_group["ID"];
				}
				$SectList = CIBlockSection::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>5, "ACTIVE"=>"Y", "ID"=>$arr_section) ,false, array("ID","IBLOCK_ID","CODE","NAME","SECTION_PAGE_URL"));
				while($SectListGet = $SectList->GetNext())
				{
				?>
					<a href="<?=$SectListGet['SECTION_PAGE_URL']?>" class="tag"><?=$SectListGet['NAME']?></a>
				<?
				}
				?>
				</div>
			<div class="name-block text-center" style="margin-top: 40px;"><span><?=$cur_section['NAME']?></span></div>
			<div class="st-carousel news-2">
				<div class="owl-carousel">
				<?
				$arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "PREVIEW_TEXT");
				$arFilter = Array("IBLOCK_ID"=>5, "ACTIVE"=>"Y", "SECTION_ID"=>$arr_section);
				$res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nPageSize"=>4), $arSelect);
				while($ob = $res->GetNextElement())
				{
					$arFields = $ob->GetFields();
					?>
					<div class="st-item">
						<a style="text-decoration: none; color: #000;" href="<?=$arFields['DETAIL_PAGE_URL']?>"><p><? echo mb_substr($arFields['PREVIEW_TEXT'], 0, 140) . '..'; ?></p></a>
					</div>
					<?
				}
				?>
				</div>
			</div><!-- st-carousel -->
			<div class="name-block text-center"><span>Случайные темы</span></div>
			<div class="st-carousel news-2">
				<div class="owl-carousel">
				<?
				$arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "PREVIEW_TEXT");
				$arFilter = Array("IBLOCK_ID"=>5, "ACTIVE"=>"Y");
				$res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nPageSize"=>4), $arSelect);
				while($ob = $res->GetNextElement())
				{
					$arFields = $ob->GetFields();
					?>
					<div class="st-item">
						<a style="text-decoration: none; color: #000;" href="<?=$arFields['DETAIL_PAGE_URL']?>"><p><? echo mb_substr($arFields['PREVIEW_TEXT'], 0, 140) . '..'; ?></p></a>
					</div>
					<?
				}
				?>
				</div>
			</div><!-- st-carousel -->
			</div><!-- st-content-bottom -->
		</div><!-- st-content-bottom -->
	</div>
</div><!-- page-item -->