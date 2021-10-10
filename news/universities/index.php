<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости ВУЗов");
$current_razdel = 'news-universities';

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

if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
	$arFilterEvent = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false, "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
	$arFilterEvent = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false, "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
else
	$arFilterEvent = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false, "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

$cntEvent = CIBlockElement::GetList(false, $arFilterEvent, array('IBLOCK_ID'))->Fetch()['CNT'];
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
</style>
<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/news/">Новости учебных заведений</a> <i class="fa fa-angle-double-right color-orange"></i>  <span>Новости ВУЗов</span>
	</div>
	<div class="page-content" id="page">
		<div class="name-block text-center"><span>Новости ВУЗов</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news" id="box-line">
				<div class="m-header">
					<a href="#" data-filter="news" class="filter color-silver js-vuz-list">новости</a> &nbsp;
					<?php if($cntEvent) { ?>
					<a href="#" data-filter="events" class="filter js-vuz-list">события</a> &nbsp;
					<?php } ?>
				</div>
					<?php
					$arrNews = array();
					$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_VUZ_ID");

					if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
						$arFilter = array("IBLOCK_ID" => 22, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
					elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
						$arFilter = array("IBLOCK_ID" => 22, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
					else
						$arFilter = array("IBLOCK_ID" => 22, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

					$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, array("nPageSize"=>10), $arSelect);
					while($row = $res->Fetch())
					{
						$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

						$arSelectUrl = array("ID", "NAME", "IBLOCK_ID", "CODE");
						$arFilterUrl = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ID" => $row["PROPERTY_VUZ_ID_VALUE"]);
						$resUrl = CIBlockElement::GetList(array("ID" => "DESC"), $arFilterUrl, false, false, $arSelectUrl);
						if($rowUrl = $resUrl->Fetch())
						{
							$row['URL']	= '/uchebnye-zavedeniya/universities/' . $rowUrl['CODE'] . '/?sect=news&s=' . $row["ID"];
						}
						$arrNews[] = $row;
					}
					if($arrNews) {
						?>
						<div class="line" id="news">
						<?php
						$cur = 0;
						foreach($arrNews as $news_item) {
						?>
						<div class="news-item<?php if($cur == 9) { /*echo ' one';*/ } ?>" data-id="<?php echo $news_item['ID']; ?>">
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
					<!-- END News ВУЗ -->
				<?php } ?>
				<div class="line" id="events" style="display: none;"></div>
			</div>
		</div>
	</div><!-- st-news -->
</div><!-- st-content-right -->
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>