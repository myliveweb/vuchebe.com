<style>
.popup .list-city a.active,
.popup .list-abc a.active,
.popup .result-city a.active {
	font-weight: bold;
}
#panel-line {
	height: 290px;
	overflow: auto;
}

#panel-carousel .owl-controls .owl-nav .owl-prev {
	background: url(/local/templates/vuchebe/images/prev_panel.png) no-repeat;
    background-size: cover;
    left: 0px;
	width: 4px;
    height: 30px;
    bottom: 2px;
    top: 0px;
    margin-top: 0px;
}

#panel-carousel .owl-controls .owl-nav .owl-next {
	background: url(/local/templates/vuchebe/images/next_panel.png) no-repeat;
    background-size: cover;
    right: 0px;
	width: 4px;
    height: 30px;
    bottom: 2px;
    top: 0px;
    margin-top: 0px;
}

#panel-carousel .owl-item.cloned {
	//width: 100px !important;
}
</style>
<?php


$arFilter = Array("IBLOCK_ID"=>32, "ACTIVE"=>"Y", "PROPERTY_NALICHIE" => "Y", "SECTION_ID" => $cookie_country);
$cnt_city = CIBlockElement::GetList(false, $arFilter, array('IBLOCK_ID'))->Fetch()['CNT'];

?>
<div class="st-popup-container">
	<div class="st-select-city popup" id="popup">
					<div class="list">
						<div class="st-tabs">
							<div class="container owl-carousel" id="panel-carousel">
								<?php
								$arrCountry = array();
							    $arSelectCountry = array("ID", "NAME", "IBLOCK_ID");
							    $arFilterCountry = array("IBLOCK_ID" => 32, "ACTIVE" => "Y");
							    $resCountry = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilterCountry, false, $arSelectCountry);
							    while($rowCountry = $resCountry->GetNext()) {
								    $arrCountry[] = $rowCountry;
								}
								for($n = 0; $n < 10; $n++) {
									foreach($arrCountry as $num_country => $valCountry) {
									?>
									    <div class="item" style="width: 100px;">
										<a href="#" style="width: 100px; text-align: center; padding: 0;" data-id="<?php echo $valCountry['ID']; ?>" class="js-counry-top<?php if($cookie_country == $valCountry['ID']) { echo ' active'; } ?>"><span><?php echo $valCountry['NAME']; ?></span></a>
										</div>
										<?php
									}
								}
								?>
							</div>
						</div>

						<div class="st-search-city container"<?php if(!$cnt_city) { echo ' style="display: none;"'; } ?>>
							<div class="row-line">
								<div class="col-4">
									<div class="st-search">
										<input type="search" id="search-location" value="" placeholder="Москва" name="st-search" style="color: black;">
										<button type="reset" style="background: none !important;"><img src="<?=SITE_TEMPLATE_PATH?>/images/close-3.png" alt="reset"></button>
									</div>
								</div>
								<div class="col-8">
									<span class="st-search-title">или выберите из списка:</span>
								</div>
							</div>
						</div>

						<div class="list-city container"<?php if(!$cnt_city) { echo ' style="display: none;"'; } ?>>
						<?php
						$num_city = 0;
					    $arSelectCity = array("ID", "NAME", "IBLOCK_ID");
					    $arFilterCity = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_TOP" => "Y", "SECTION_ID" => $cookie_country);
					    $resCity = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterCity, false, false, $arSelectCity);
						while($rowCity = $resCity->GetNext()) {
						?>
						<a href="#" data-id="<?php echo $rowCity['ID']; ?>" class="js-city-top js-city-top-<?php echo $rowCity['ID']; ?><?php if($cookie_city == $rowCity['ID'] || (!$cookie_country && !$cookie_city && !$num_city)) { echo ' active'; } ?>"><?php echo $rowCity['NAME']; ?></a>
						<?php
							$num_city++;
						}
					    ?>
						</div>
						<?php
						$arrLiter = array();
					    $arSelectLiter = array("ID", "NAME", "IBLOCK_ID");
					    $arFilterLiter = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_NALICHIE" => "Y", "SECTION_ID" => $cookie_country);
					    $resLiter = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterLiter, false, false, $arSelectLiter);
						while($rowLiter = $resLiter->GetNext()) {
							$liter = mb_strtoupper(mb_substr($rowLiter['NAME'], 0, 1));
							if(!in_array($liter, $arrLiter))
								$arrLiter[] = $liter;
						}
						asort($arrLiter);
					    ?>
						<div class="list-abc container"<?php if(!$cnt_city) { echo ' style="display: none;"'; } ?>>
							<?php
							foreach($arrLiter as $item) {
							?>
							<a href="#" data-id="<?php echo $item; ?>" class="js-abc-top<?php if($cookie_abc == $item) { echo ' active'; } ?>"><?php echo $item; ?></a>
							<?php
							}
							?>
						</div>

						<div class="result-city container"<?php if(!$cnt_city) { echo ' style="display: none;"'; } ?>>
							<div class="title" id="title-abc"><?php if($cookie_abc) { echo $cookie_abc; } else { echo 'М'; } ?></div>
							<div class="line" id="panel-line">
								<div class="item-city">
									<?php
									$nStolb = 0;
								    $arSelectList = array("ID", "NAME", "IBLOCK_ID");
								    $arFilterList = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "PROPERTY_NALICHIE" => "Y", "SECTION_ID" => $cookie_country, "NAME" => $cookie_abc . "%");
								    $resList = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilterList, false, false, $arSelectList);
									while($rowList = $resList->GetNext()) {
									?>
									<a href="#" data-id="<?php echo $rowList['ID']; ?>" class="js-list-top js-list-top-<?php echo $rowList['ID']; ?><?php if($cookie_city == $rowList['ID']) { echo ' active'; } ?>"><span><?php echo $rowList['NAME']; ?></span></a>
									<?php
										$nStolb++;
										if($nStolb == 12) {
											?>
											</div>
											<div class="item-city">
											<?php
											$nStolb = 0;
										}

									}
									?>
								</div>
							</div>
						</div>

						<span class="close"><img src="<?=SITE_TEMPLATE_PATH?>/images/close-2.png" alt="close"></span>
					</div><!-- list -->
					<?php
					$strTop = $_SESSION['PANEL']['COUNTRY_NAME'];
					if($_SESSION['PANEL']['CITY_NAME'])
						$strTop = $_SESSION['PANEL']['CITY_NAME'];
					?>
					<div class="container">
						<div class="name-city" id="name-city">
							<span><i class="fa fa-map-marker" aria-hidden="true"></i> <a href="#"><?php echo $strTop; ?></a></span>
						</div>
					</div>
	</div>
</div><!-- st-popup-container -->