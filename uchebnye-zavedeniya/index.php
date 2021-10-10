<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Учебные заведения");
require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_uchebnye-zavedeniya.php');
CModule::IncludeModule('iblock');
$arrType = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');
?>
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
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i>  <span>Учебные зведения</span>
	</div>
	<div class="page-content" id="page">
		<div class="st-content-bottom clear">
			<div class="module st-news">
			<?php
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL", "PROPERTY_YEAR");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y");

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{
				$arrNews[] = $rowCarusel;
			}
			if(sizeof($arrNews) > 0) {
			?>
			<div class="name-block text-center"><span>ВУЗы</span></div>
			<div class="line hide">
				<?php
				$cur = 0;
				foreach($arrNews as $val_item) {
					if($val_item["PROPERTY_LOGO_VALUE"]):
						$srcLogo = CFile::GetPath($val_item["PROPERTY_LOGO_VALUE"]);
					elseif($val_item["PREVIEW_PICTURE"]):
						$srcLogo = CFile::GetPath($val_item["PREVIEW_PICTURE"]);
					else:
						$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
					endif;

                    $year_digital = preg_replace('~\D+~','', $val_item["PROPERTY_YEAR_VALUE"]);
				?>
				<div class="news-item<?php if(!$cur || $cur == 2) { echo ' one'; } ?><?php if($cur) { echo ' hide-block'; } ?>">
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
                            <img style="width: 122px;" src="<?=$srcLogo?>" alt="img">
                        </div>
                    </div>
                    <div class="col-8" style="padding: 0 0 0 15px; <?php if($year_digital) { ?>width: 60%<? } else { ?>width: 80%<? } ?>;">
                        <div class="news-name">
                            <a href="/uchebnye-zavedeniya/universities/<?echo $val_item["CODE"]?>/"><span class="crop-height-2"><?echo $val_item["NAME"]?></span></a>
                        </div>
                        <p>
                        <?php if($val_item["PROPERTY_ADRESS_VALUE"]) { ?>
                            Адрес:&nbsp;<?php echo $val_item["PROPERTY_ADRESS_VALUE"]; ?><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_SITE_VALUE"]) { ?>
                            Сайт:&nbsp;<a href="<?=$val_item["PROPERTY_SITE_VALUE"];?>"><?=$val_item["PROPERTY_SITE_VALUE"];?></a><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_PHONE_VALUE"]) { ?>
                            Телефон:&nbsp;<?=$val_item["PROPERTY_PHONE_VALUE"];?><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_EMAIL_VALUE"]) { ?>
                            Электронная почта:&nbsp;<a href="mailto:<?=$val_item["PROPERTY_EMAIL_VALUE"];?>"><?=$val_item["PROPERTY_EMAIL_VALUE"];?></a><br>
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
					$cur++;
				}
				?>
				<div class="btns text-center">
					<span class="btn-silver js-hide-block"><i class="fa fa-caret-down"></i></span>
					<a href="/uchebnye-zavedeniya/universities/">
						<span class="btn-silver dec">все ВУЗы</span>
					</a>
				</div>
			</div>
			<?php
			}
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL", "PROPERTY_YEAR");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y");

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{
				$arrNews[] = $rowCarusel;
			}
			if(sizeof($arrNews) > 0) {
			?>
			<div class="name-block text-center"><span>Колледжи</span></div>
			<div class="line hide">
				<?php
				$cur = 0;
				foreach($arrNews as $val_item) {
					if($val_item["PROPERTY_LOGO_VALUE"]):
						$srcLogo = CFile::GetPath($val_item["PROPERTY_LOGO_VALUE"]);
					elseif($val_item["PREVIEW_PICTURE"]):
						$srcLogo = CFile::GetPath($val_item["PREVIEW_PICTURE"]);
					else:
						$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
					endif;

                    $year_digital = preg_replace('~\D+~','', $val_item["PROPERTY_YEAR_VALUE"]);
				?>
				<div class="news-item<?php if(!$cur || $cur == 2) { echo ' one'; } ?><?php if($cur) { echo ' hide-block'; } ?>">
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
                            <img style="width: 122px;" src="<?=$srcLogo?>" alt="img">
                        </div>
                    </div>
                    <div class="col-8" style="padding: 0 0 0 15px; <?php if($year_digital) { ?>width: 60%<? } else { ?>width: 80%<? } ?>;">
                        <div class="news-name">
                            <a href="/uchebnye-zavedeniya/colleges/<?echo $val_item["CODE"]?>/"><span class="crop-height-2"><?echo $val_item["NAME"]?></span></a>
                        </div>
                        <p>
                        <?php if($val_item["PROPERTY_ADRESS_VALUE"]) { ?>
                            Адрес:&nbsp;<?php echo $val_item["PROPERTY_ADRESS_VALUE"]; ?><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_SITE_VALUE"]) { ?>
                            Сайт:&nbsp;<a href="<?=$val_item["PROPERTY_SITE_VALUE"];?>"><?=$val_item["PROPERTY_SITE_VALUE"];?></a><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_PHONE_VALUE"]) { ?>
                            Телефон:&nbsp;<?=$val_item["PROPERTY_PHONE_VALUE"];?><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_EMAIL_VALUE"]) { ?>
                            Электронная почта:&nbsp;<a href="mailto:<?=$val_item["PROPERTY_EMAIL_VALUE"];?>"><?=$val_item["PROPERTY_EMAIL_VALUE"];?></a><br>
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
					$cur++;
				}
				?>
				<div class="btns text-center">
					<span class="btn-silver js-hide-block"><i class="fa fa-caret-down"></i></span>
					<a href="/uchebnye-zavedeniya/colleges/">
						<span class="btn-silver dec">все Колледжи</span>
					</a>
				</div>
			</div>
			<?php
			}
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL", "PROPERTY_YEAR");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y");

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{
				$arrNews[] = $rowCarusel;
			}
			if(sizeof($arrNews) > 0) {
			?>
			<div class="name-block text-center"><span>Школы</span></div>
			<div class="line hide">
				<?php
				$cur = 0;
				foreach($arrNews as $val_item) {
					if($val_item["PROPERTY_LOGO_VALUE"]):
						$srcLogo = CFile::GetPath($val_item["PROPERTY_LOGO_VALUE"]);
					elseif($val_item["PREVIEW_PICTURE"]):
						$srcLogo = CFile::GetPath($val_item["PREVIEW_PICTURE"]);
					else:
						$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
					endif;

                    $year_digital = preg_replace('~\D+~','', $val_item["PROPERTY_YEAR_VALUE"]);
				?>
				<div class="news-item<?php if(!$cur || $cur == 2) { echo ' one'; } ?><?php if($cur) { echo ' hide-block'; } ?>">
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
                            <img style="width: 122px;" src="<?=$srcLogo?>" alt="img">
                        </div>
                    </div>
                    <div class="col-8" style="padding: 0 0 0 15px; <?php if($year_digital) { ?>width: 60%<? } else { ?>width: 80%<? } ?>;">
                        <div class="news-name">
                            <a href="/uchebnye-zavedeniya/schools/<?echo $val_item["CODE"]?>/"><span class="crop-height-2"><?echo $val_item["NAME"]?></span></a>
                        </div>
                        <p>
                        <?php if($val_item["PROPERTY_ADRESS_VALUE"]["TEXT"]) {
                                $adress = $val_item["PROPERTY_ADRESS_VALUE"]["TEXT"];
                        ?>
                            Адрес:&nbsp;<?php echo $adress; ?><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_SITE_VALUE"]) { ?>
                            Сайт:&nbsp;<a href="<?=$val_item["PROPERTY_SITE_VALUE"];?>"><?=$val_item["PROPERTY_SITE_VALUE"];?></a><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_PHONE_VALUE"]) { ?>
                            Телефон:&nbsp;<?=$val_item["PROPERTY_PHONE_VALUE"];?><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_EMAIL_VALUE"]) { ?>
                            Электронная почта:&nbsp;<a href="mailto:<?=$val_item["PROPERTY_EMAIL_VALUE"];?>"><?=$val_item["PROPERTY_EMAIL_VALUE"];?></a><br>
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
					$cur++;
				}
				?>
				<div class="btns text-center">
					<span class="btn-silver js-hide-block"><i class="fa fa-caret-down"></i></span>
					<a href="/uchebnye-zavedeniya/schools/">
						<span class="btn-silver dec">все Школы</span>
					</a>
				</div>
			</div>
			<?php
			}
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_SITE", "PROPERTY_PHONE", "PROPERTY_EMAIL", "PROPERTY_YEAR");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y");

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>3), $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{
				$arrNews[] = $rowCarusel;
			}
			if(sizeof($arrNews) > 0) {
			?>
			<div class="name-block text-center"><span>Языковые курсы</span></div>
			<div class="line hide">
				<?php
				$cur = 0;
				foreach($arrNews as $val_item) {
					if($val_item["PROPERTY_LOGO_VALUE"]):
						$srcLogo = CFile::GetPath($val_item["PROPERTY_LOGO_VALUE"]);
					elseif($val_item["DETAIL_PICTURE"]):
						$srcLogo = CFile::GetPath($val_item["DETAIL_PICTURE"]);
					else:
						$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
					endif;

                    $year_digital = preg_replace('~\D+~','', $val_item["PROPERTY_YEAR_VALUE"]);
				?>
				<div class="news-item<?php if(!$cur || $cur == 2) { echo ' one'; } ?><?php if($cur) { echo ' hide-block'; } ?>">
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
                            <img style="width: 122px;" src="<?=$srcLogo?>" alt="img">
                        </div>
                    </div>
                    <div class="col-8" style="padding: 0 0 0 15px; <?php if($year_digital) { ?>width: 60%<? } else { ?>width: 80%<? } ?>;">
                        <div class="news-name">
                            <a href="/uchebnye-zavedeniya/language-class/<?echo $val_item["CODE"]?>/"><span class="crop-height-2"><?echo $val_item["NAME"]?></span></a>
                        </div>
                        <p>
                        <?php if($val_item["PROPERTY_ADRESS_VALUE"]) {
                            $arrAdress = explode('&', $val_item["PROPERTY_ADRESS_VALUE"]);
                        ?>
                            Адрес:&nbsp;<?php echo $arrAdress[0]; ?><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_SITE_VALUE"]) {
                            $arrUrl = explode('?', $val_item["PROPERTY_SITE_VALUE"]);
                        ?>
                            Сайт:&nbsp;<a href="<?=$arrUrl[0];?>"><? if(strlen($arrUrl[0]) > 52) { echo substr($arrUrl[0], 0, 50) . '..'; } else { echo $arrUrl[0]; }?></a><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_PHONE_VALUE"]) { ?>
                            Телефон:&nbsp;<?=$val_item["PROPERTY_PHONE_VALUE"];?><br>
                        <?php } ?>
                        <?php if($val_item["PROPERTY_EMAIL_VALUE"]) { ?>
                            Электронная почта:&nbsp;<a href="mailto:<?=$val_item["PROPERTY_EMAIL_VALUE"];?>"><?=$val_item["PROPERTY_EMAIL_VALUE"];?></a><br>
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
					$cur++;
				}
				?>
				<div class="btns text-center">
					<span class="btn-silver js-hide-block"><i class="fa fa-caret-down"></i></span>
					<a href="/uchebnye-zavedeniya/language-class/">
						<span class="btn-silver dec">все Языковые курсы</span>
					</a>
				</div>
			</div>
			<?php
			}
			?>
			<div class="name-block text-center"><span>Дни открытых дверей</span></div>
			<div class="line">
				<div class="st-carousel news-3 theme-1">
					<div class="owl-carousel">
						<?php
						$arrNews = array();
						$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE");

						if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
							$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
						elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
							$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
						elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
							$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
						else
							$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false);

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
						shuffle($arrNews);
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
			</div>
		</div>
	</div><!-- st-news -->
</div><!-- st-content-right -->
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>