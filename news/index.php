<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости учебных заведений");

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
$arrType = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');
?>
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
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i>  <span>Новости учебных заведений</span>
	</div>
	<div class="page-content" id="page">
		<div class="st-content-bottom clear">
			<div class="module st-news">

					<?php
					if($arrNewsObr) {
						?>
						<div class="name-block text-center"><span>Новости образования</span></div>
						<div class="line hide">
						<?php
						$cur = 0;
						foreach($arrNewsObr as $news_item) {
						?>
						<div class="news-item<?php if(!$cur || $cur == 2) { echo ' one'; } ?><?php if($cur) { echo ' hide-block'; } ?>">
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
						<div class="btns text-center">
							<span class="btn-silver js-hide-block"><i class="fa fa-caret-down"></i></span>
							<a href="/news/education/">
								<span class="btn-silver dec">все ноости образования</span>
							</a>
						</div>
					</div>
					<!-- END News Образование -->
				<?php } ?>

					<?php

					$arrNews = array();

					$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_VUZ_ID");

					if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
						$arFilter = array("IBLOCK_ID" => 22, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
					elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
						$arFilter = array("IBLOCK_ID" => 22, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
					else
						$arFilter = array("IBLOCK_ID" => 22, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

					$res = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
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
						<div class="name-block text-center"><span>Новости ВУЗов</span></div>
						<div class="line hide">
						<?php
						$cur = 0;
						foreach($arrNews as $news_item) {
						?>
						<div class="news-item<?php if(!$cur || $cur == 2) { echo ' one'; } ?><?php if($cur) { echo ' hide-block'; } ?>">
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
						<div class="btns text-center">
							<span class="btn-silver js-hide-block"><i class="fa fa-caret-down"></i></span>
							<a href="/news/universities/">
								<span class="btn-silver dec">все ноости ВУЗов</span>
							</a>
						</div>
					</div>
					<!-- END News ВУЗ -->
				<?php } ?>

					<?php
					$arrNews = array();
					$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_VUZ_ID");

					if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
						$arFilter = array("IBLOCK_ID" => 28, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
					elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
						$arFilter = array("IBLOCK_ID" => 28, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
					else
						$arFilter = array("IBLOCK_ID" => 28, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

					$res = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
					while($row = $res->Fetch())
					{
						$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

						$arSelectUrl = array("ID", "NAME", "IBLOCK_ID", "CODE");
						$arFilterUrl = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "ID" => $row["PROPERTY_VUZ_ID_VALUE"]);
						$resUrl = CIBlockElement::GetList(array("ID" => "DESC"), $arFilterUrl, false, false, $arSelectUrl);
						if($rowUrl = $resUrl->Fetch())
						{
							$row['URL']	= '/uchebnye-zavedeniya/colleges/' . $rowUrl['CODE'] . '/?sect=news&s=' . $row["ID"];
						}
						$arrNews[] = $row;
					}
					if($arrNews) {
						?>
						<div class="name-block text-center"><span>Новости колледжей</span></div>
						<div class="line hide">
						<?php
						$cur = 0;
						foreach($arrNews as $news_item) {
						?>
						<div class="news-item<?php if(!$cur || $cur == 2) { echo ' one'; } ?><?php if($cur) { echo ' hide-block'; } ?>">
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
						<div class="btns text-center">
							<span class="btn-silver js-hide-block"><i class="fa fa-caret-down"></i></span>
							<a href="/news/colleges/">
								<span class="btn-silver dec">все ноости колледжей</span>
							</a>
						</div>
					</div>
					<!-- END News Колледжи -->
				<?php } ?>

					<?php
					$arrNews = array();
					$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_VUZ_ID");

					if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
						$arFilter = array("IBLOCK_ID" => 29, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
					elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
						$arFilter = array("IBLOCK_ID" => 29, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
					else
						$arFilter = array("IBLOCK_ID" => 29, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

					$res = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
					while($row = $res->Fetch())
					{
						$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

						$arSelectUrl = array("ID", "NAME", "IBLOCK_ID", "CODE");
						$arFilterUrl = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "ID" => $row["PROPERTY_VUZ_ID_VALUE"]);
						$resUrl = CIBlockElement::GetList(array("ID" => "DESC"), $arFilterUrl, false, false, $arSelectUrl);
						if($rowUrl = $resUrl->Fetch())
						{
							$row['URL']	= '/uchebnye-zavedeniya/schools/' . $rowUrl['CODE'] . '/?sect=news&s=' . $row["ID"];
						}
						$arrNews[] = $row;
					}
					if($arrNews) {
						?>
						<div class="name-block text-center"><span>Новости школ</span></div>
						<div class="line hide">
						<?php
						$cur = 0;
						foreach($arrNews as $news_item) {
						?>
						<div class="news-item<?php if(!$cur || $cur == 2) { echo ' one'; } ?><?php if($cur) { echo ' hide-block'; } ?>">
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
						<div class="btns text-center">
							<span class="btn-silver js-hide-block"><i class="fa fa-caret-down"></i></span>
							<a href="/news/schools/">
								<span class="btn-silver dec">все ноости школ</span>
							</a>
						</div>
					</div>
					<!-- END News Школы -->
				<?php } ?>

					<?php
					$arrNews = array();
					$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_VUZ_ID");

					if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
						$arFilter = array("IBLOCK_ID" => 30, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
					elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
						$arFilter = array("IBLOCK_ID" => 30, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
					else
						$arFilter = array("IBLOCK_ID" => 30, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

					$res = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
					while($row = $res->Fetch())
					{
						$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

						$arSelectUrl = array("ID", "NAME", "IBLOCK_ID", "CODE");
						$arFilterUrl = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "ID" => $row["PROPERTY_VUZ_ID_VALUE"]);
						$resUrl = CIBlockElement::GetList(array("ID" => "DESC"), $arFilterUrl, false, false, $arSelectUrl);
						if($rowUrl = $resUrl->Fetch())
						{
							$row['URL']	= '/uchebnye-zavedeniya/language-class/' . $rowUrl['CODE'] . '/?sect=news&s=' . $row["ID"];
						}
						$arrNews[] = $row;
					}
					if($arrNews) {
						?>
						<div class="name-block text-center"><span>Новости языковых курсов</span></div>
						<div class="line hide">
						<?php
						$cur = 0;
						foreach($arrNews as $news_item) {
						?>
						<div class="news-item<?php if(!$cur || $cur == 2) { echo ' one'; } ?><?php if($cur) { echo ' hide-block'; } ?>">
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
						<div class="btns text-center">
							<span class="btn-silver js-hide-block"><i class="fa fa-caret-down"></i></span>
							<a href="/news/language-class/">
								<span class="btn-silver dec">все ноости языковых курсов</span>
							</a>
						</div>
					</div>
					<!-- END News Языковые курсы -->
				<?php } ?>
				<?php
				$arrNews = array();
				$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE");

				if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
					$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
				elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
					$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
				else
					$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

				$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, false, $arSelect);
				while($rowCarusel = $resCarusel->Fetch())
				{

					$resEvents = CIBlockElement::GetProperty($rowCarusel["IBLOCK_ID"], $rowCarusel["ID"], "sort", "asc", array("CODE" => "OPENDOOR"));
					while ($obEvents = $resEvents->GetNext()) {
						$rowCarusel["TYPE"]  = $arrType[$rowCarusel["IBLOCK_ID"]];

						$tempEvents = explode('#', $obEvents['VALUE']);

						$rowCarusel["NAME_OPENDOOR"]  = $tempEvents[0];

						$fullTime = $tempEvents[1] . ' ' . $tempEvents[2];

						if(!$tempEvents[0] || strtotime($fullTime) > time())
							continue;

						$strDate = get_str_time_post(strtotime($fullTime));
						$curDate = explode(' ', $strDate);
						$rowCarusel["DAY"] = $curDate[0];
						$rowCarusel["MONTH"] = $curDate[1];

						$arrNews[] = $rowCarusel;
					}
				}
				if($arrNews) {
				shuffle($arrNews);
				?>
				<div class="name-block text-center"><span>Дни открытых дверей</span></div>
				<div class="line">
					<div class="st-carousel news-3 theme-1">
						<div class="owl-carousel">
							<?php
							for($n = 0; $n < 12; $n++) {
								$val_item = $arrNews[$n];
							?>
							<div class="st-item">
								<div style="text-align: center;">
								<div class="date-ico theme-2" style="margin-bottom: 10px; width: 100%; background-position: center;"><span><?=$val_item['DAY']?></span><?=$val_item['MONTH']?></div>
								<a style="margin: 15px 0 10px;" href="/uchebnye-zavedeniya/<?=$val_item["TYPE"]?>/<?=$val_item["CODE"]?>/?sect=opendoor" class="display-name" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>"><span class="crop-height"><?php echo $val_item['NAME_OPENDOOR']; ?></span></a>
								</div>
							</div>
							<?php
							}
							?>
						</div>
					</div>
				</div><!-- st-carousel -->

				<div class="btns text-center">
					<a href="/uchebnye-zavedeniya/open-days/">
						<span class="btn-silver dec">все Дни открытых дверей</span>
					</a>
				</div>
				<?php } ?>
			</div>
		</div>
	</div><!-- st-news -->
</div><!-- st-content-right -->
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>