<?php
$arrPlan = array();
$arSelectPlan = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PLAN_ID", "PROPERTY_DESCRIPTION", "PROPERTY_PRICE", "PROPERTY_MAIN_CITY", "PROPERTY_CAPITAL");
$arFilterPlan = array("IBLOCK_ID" => 38, "ACTIVE" => "Y");
$resPlan = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilterPlan, false, false, $arSelectPlan);
while($rowPlan = $resPlan->GetNext()) {
    $arrPlan['top'][] = $rowPlan;
}

$arSelectPlan = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PLAN_ID", "PROPERTY_DESCRIPTION", "PROPERTY_PRICE", "PROPERTY_MAIN_CITY", "PROPERTY_CAPITAL");
$arFilterPlan = array("IBLOCK_ID" => 44, "ACTIVE" => "Y");
$resPlan = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilterPlan, false, false, $arSelectPlan);
while($rowPlan = $resPlan->GetNext()) {
    $arrPlan['side'][] = $rowPlan;
}
?>
<style>

</style>
<div class="hideForm-tarif tarif" style="display: none;">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-tarif" method="post" action="" style="width: 600px;">
			<div style="padding-bottom: 20px; max-height: 750px; overflow: auto;" id="box-line" class="line">
                <div id="top">
                    <div class="name_form text-center" style="margin-bottom: 30px;"><span>Тарифы на верхний баннер</span></div>
                    <?php
                    foreach($arrPlan['top'] as $itemPlan) {
                    ?>
                    <div class="row-line one-line <?php echo $itemPlan['PROPERTY_PLAN_ID_VALUE']; ?>" style="margin-bottom: 10px;" data-tarif="<?php echo $itemPlan['PROPERTY_PLAN_ID_VALUE']; ?>" data-open="0">
                        <div class="col-5">
                            <span class="color-silver js-open-tarif" style="margin-left: 20px; font-size: 20px; cursor: pointer;"><?php echo $itemPlan['NAME']; ?></span>
                        </div>
                        <div class="col-6">
                            <img class="js-open-tarif down-tarif" src="<?=SITE_TEMPLATE_PATH?>/img/tarif_down.png" style="width: 30px; height: 30px; position: relative; top: -3px; margin-left: 17px; cursor: pointer;">
                            <img class="js-open-tarif up-tarif" src="<?=SITE_TEMPLATE_PATH?>/img/tarif_up.png" style="width: 30px; height: 30px; position: relative; top: -2px; margin-left: 17px; cursor: pointer; display: none;">
                            <span class="color-silver js-open-tarif open-text" style="margin-left: 100px; font-size: 16px; line-height: 22px; border-bottom: 1px dotted; cursor: pointer; position: relative; top: -13px;">Развернуть</span>
                        </div>
                        <div class="js-desc" style="display: none; margin: 10px 40px; border-bottom: 1px solid #9f9f9f; padding-bottom: 10px;">
                            <div style="margin: 10px 0px; font-size: 17px;"><?php echo $itemPlan['PROPERTY_DESCRIPTION_VALUE']; ?></div>
                            <div style="margin: 5px 0px; font-size: 18px;">Стоимость показа: <?php echo $itemPlan['PROPERTY_PRICE_VALUE']; ?> руб.</div>
                            <?php if($itemPlan['PROPERTY_MAIN_CITY_VALUE']) { ?>
                            <div style="margin: 5px 0px; font-size: 18px;">Стоимость показа (главный город региона): <?php echo $itemPlan['PROPERTY_MAIN_CITY_VALUE']; ?> руб.</div>
                            <?php } ?>
                            <?php if($itemPlan['PROPERTY_CAPITAL_VALUE']) { ?>
                                <div style="margin: 5px 0px; font-size: 18px;">Стоимость показа (в столице): <?php echo $itemPlan['PROPERTY_CAPITAL_VALUE']; ?> руб.</div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <div id="side">
                    <div class="name_form text-center" style="margin: 15px 0px 30px 0px;"><span>Тарифы на квадратный баннер</span></div>
                    <?php
                    foreach($arrPlan['side'] as $itemPlan) {
                        ?>
                        <div class="row-line one-line <?php echo $itemPlan['PROPERTY_PLAN_ID_VALUE']; ?>" style="margin-bottom: 10px;" data-tarif="<?php echo $itemPlan['PROPERTY_PLAN_ID_VALUE']; ?>" data-open="0">
                            <div class="col-5">
                                <span class="color-silver js-open-tarif" style="margin-left: 20px; font-size: 20px; cursor: pointer;"><?php echo $itemPlan['NAME']; ?></span>
                            </div>
                            <div class="col-6">
                                <img class="js-open-tarif down-tarif" src="<?=SITE_TEMPLATE_PATH?>/img/tarif_down.png" style="width: 30px; height: 30px; position: relative; top: -3px; margin-left: 17px; cursor: pointer;">
                                <img class="js-open-tarif up-tarif" src="<?=SITE_TEMPLATE_PATH?>/img/tarif_up.png" style="width: 30px; height: 30px; position: relative; top: -2px; margin-left: 17px; cursor: pointer; display: none;">
                                <span class="color-silver js-open-tarif open-text" style="margin-left: 100px; font-size: 16px; line-height: 22px; border-bottom: 1px dotted; cursor: pointer; position: relative; top: -13px;">Развернуть</span>
                            </div>
                            <div class="js-desc" style="display: none; margin: 10px 40px; border-bottom: 1px solid #9f9f9f; padding-bottom: 10px;">
                                <div style="margin: 10px 0px; font-size: 17px;"><?php echo $itemPlan['PROPERTY_DESCRIPTION_VALUE']; ?></div>
                                <div style="margin: 5px 0px; font-size: 18px;">Стоимость показа: <?php echo $itemPlan['PROPERTY_PRICE_VALUE']; ?> руб.</div>
                                <?php if($itemPlan['PROPERTY_MAIN_CITY_VALUE']) { ?>
                                    <div style="margin: 5px 0px; font-size: 18px;">Стоимость показа (главный город региона): <?php echo $itemPlan['PROPERTY_MAIN_CITY_VALUE']; ?> руб.</div>
                                <?php } ?>
                                <?php if($itemPlan['PROPERTY_CAPITAL_VALUE']) { ?>
                                    <div style="margin: 5px 0px; font-size: 18px;">Стоимость показа (в столице): <?php echo $itemPlan['PROPERTY_CAPITAL_VALUE']; ?> руб.</div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
				<div class="row-line mb-10 mt-15 css-btn" style="margin-top: 35px;">
					<div class="col-12" style="text-align: center;">
						<button type="button" class="js-submit-tarif" onclick="close_form();"><span>Закрыть</span></button>
					</div>
				</div>
			</div>
			<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
	  	</form>
  	</div>
</div><!-- hideForm tarif -->