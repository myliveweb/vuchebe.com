<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости образования");
$current_razdel = 'news-education';

$arrNewsObr = array();
$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_VUZ_ID");

if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
	$arFilter = array("IBLOCK_ID" => 31, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
	$arFilter = array("IBLOCK_ID" => 31, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
else
	$arFilter = array("IBLOCK_ID" => 31, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

$res = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
while($row = $res->Fetch())
{
	$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

	$row['URL']	= '/news/education/?s=' . $row["ID"];

	$arrNewsObr[] = $row;
}

if($_SESSION['PANEL']['CITY'] || $_SESSION['PANEL']['REGION']) {
	$arFilter = array("IBLOCK_ID" => 31, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY'], "PROPERTY_REGION" => false, "PROPERTY_CITY" => false);
	$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, array("nPageSize"=>20), $arSelect);
	while($row = $res->Fetch())
	{
		$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

		$row['URL']	= '/news/education/?s=' . $row["ID"];
		$arrNewsObr[] = $row;
	}
}

$obrMenu = sizeof($arrNewsObr);

require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_uchebnye-zavedeniya_news.php');
CModule::IncludeModule('iblock');
$arrType = array(1 => 'education', 2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');
?>
<script>
var curList = '<?php echo $current_razdel; ?>';
var startFromList = 1;
var cnt = '20';
</script>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<style>
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
.st-news .news-item .button {
    font-size: 11px;
    padding: 0 10px;
    width: 90px;
    line-height: 30px;
}
</style>
<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/news/">Новости учебных заведений</a> <i class="fa fa-angle-double-right color-orange"></i>  <span>Новости образования</span>
	</div>
	<?php
	if($_REQUEST['s']) {
		$id_news = (int) $_REQUEST['s'];
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE", "PROPERTY_MORE_PHOTO");
		$arFilter = array("IBLOCK_ID" => 31, "ACTIVE" => "Y", "ID" => $id_news, "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
		$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		$news_item = $res->Fetch();

		$news_item["FORMAT_DATE"] = get_str_time_post(strtotime($news_item['DATE_CREATE']));
		?>
		<div class="page-content" id="page">
			<div class="name-block text-center txt-up"><span>Новости образования</span></div>
			<div class="st-content-bottom clear">
				<div class="module st-news">
					<div class="line" id="box-line" data-url="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=news">
						<?if($news_item['ID']):?>
						<div class="news-item one" style="position: relative;">
							<?php if($news_item["PREVIEW_PICTURE"]) { ?>
							<div class="image brd left">
								<img src="<? echo CFile::GetPath($news_item["PREVIEW_PICTURE"]); ?>" alt="<?=$news_item["NAME"]?>" title="<?=$news_item["NAME"]?>" style="max-width: 230px;<?php if($numPic) { ?> margin-top: 15px;<?php } ?>">
							</div>
							<? } ?>
							<div class="date" style="margin-bottom: 7px;"><?php echo $news_item["FORMAT_DATE"]; ?></div>
							<div class="news-name" style="margin-bottom: 15px;"><span><?=$news_item["NAME"]?></span></div>
							<?php
							$br = str_replace(array("\r\n", "\r", "\n"), '<br>', $news_item["DETAIL_TEXT"]);
							echo $br;
							?>
						</div>
					<div class="btns text-right" style="float: right;">
						<a href="/news/education/" class="button" style="font-family: Verdana;"><i class="fa fa-angle-double-left"></i> назад к списку новостей</a>
					</div>
						<?endif?>
					</div>
				</div>
			</div><!-- st-news -->
		</div>
		<?php
	} else {
	?>
	<div class="page-content" id="page">
		<div class="st-content-bottom clear">
			<div class="module st-news">
					<?php
					$arrNews = array();
					$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_VUZ_ID");

					if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
						$arFilter = array("IBLOCK_ID" => 31, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
					elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
						$arFilter = array("IBLOCK_ID" => 31, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
					else
						$arFilter = array("IBLOCK_ID" => 31, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

					$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, array("nPageSize"=>20), $arSelect);
					while($row = $res->Fetch())
					{
						$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

						$row['URL']	= '/news/education/?s=' . $row["ID"];
						$arrNews[] = $row;
					}

					if($_SESSION['PANEL']['CITY'] || $_SESSION['PANEL']['REGION']) {
						$arFilter = array("IBLOCK_ID" => 31, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY'], "PROPERTY_REGION" => false, "PROPERTY_CITY" => false);
						$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, array("nPageSize"=>20), $arSelect);
						while($row = $res->Fetch())
						{
							$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

							$row['URL']	= '/news/education/?s=' . $row["ID"];
							$arrNews[] = $row;
						}
					}

					if($arrNews) {
						?>
						<div class="name-block text-center"><span>Новости образования</span></div>
						<div class="line hide">
						<?php
						$cur = 0;
						foreach($arrNews as $news_item) {
						?>
						<div class="news-item<?php if($cur == 9) { /*echo ' one';*/ } ?>">
						<?php if($news_item["PREVIEW_PICTURE"]) { ?>
						<div class="image brd left">
							<a href="<?php echo $news_item["URL"]; ?>">
								<img src="<? echo CFile::GetPath($news_item["PREVIEW_PICTURE"]); ?>" alt="<?=$news_item["NAME"]?>" title="<?=$news_item["NAME"]?>" style="max-width: 200px;">
							</a>
						</div>
						<?php } ?>
						<div class="date" style="margin-bottom: 7px;"><?php echo $news_item["FORMAT_DATE"]; ?></div>
						<div class="news-name" style="margin-bottom: 15px;">
							<a href="<?php echo $news_item["URL"]; ?>"><span><?=$news_item["NAME"]?></span></a>
						</div>
						<p>
						<?php
						$br = str_replace(array("\r\n", "\r", "\n"), '<br>', $news_item["DETAIL_TEXT"]);
						$out = substr($br, 0, 148);
						echo $out . '..';
						?>
						</p>
						</div>
						<?php
							$cur++;
						}
						?>
					</div>
					<!-- END News образования -->
				<?php } ?>
			</div>
		</div>
	</div><!-- st-news -->
<?php } ?>
</div><!-- st-content-right -->
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>