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
<style>

.st-content-bottom .news-name {
    font-size: 20px;
    color: #000;
    margin-left: 20px;
}
.st-content-bottom .news-name a span {
	color: #000;
}
.st-content-bottom .news-hr {
    padding: 0 0 25px;
    border-bottom: 1px solid #ccc;
    margin-bottom: 20px;
}
.st-content-bottom .jobs-list {
    margin-top: 15px;
	font-size: 14px;
    line-height: 1.1;
	display: -webkit-box;
	-webkit-line-clamp: 4;
	-webkit-box-orient: vertical;
	overflow: hidden;
}
</style>
<div class="st-content-right">

<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/company/">Компания</a> <i class="fa fa-angle-double-right color-orange"></i> Вакансии
</div>

<div class="page-content">
<div class="st-content-bottom clear">
<div class="name-block text-left"> &nbsp;&nbsp;<span>Вакансии</span></div>

<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="news-hr" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="news-name">
			<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
				<span><?echo $arItem["NAME"]?></span>
			</a>
		</div>
		<div class="jobs-list"><?=$arItem["FIELDS"]["DETAIL_TEXT"];?></div>
	</div>
<?endforeach;?>

</div><!-- st-content-bottom -->
</div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>
