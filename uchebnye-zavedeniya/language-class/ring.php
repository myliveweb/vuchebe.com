<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="ring" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить расписание</span>
</div>
<?php } ?>
<div class="line" id="box-line">
<?php
foreach($arResult["PROPERTIES"]["TIME_RING"]["VALUE"] as $ring_id => $ring_item) {
	echo '<div style="margin: 0px 0px 30px 30px; position: relative;">';
	if($pageAdmin):?>
		<div class="color-silver js-news-edit" data-block="ring" data-id="<?php echo $ring_id; ?>" style="position: absolute; top: -6px; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
	<?endif;
	$arrItem = explode('#', $ring_item);
	if($arrItem[0])
		echo '<p><b>' . $arrItem[0] . '</b></p>';
	echo '<ul>';
	for($n = 1; $n < sizeof($arrItem); $n++) {
		if($arrItem[$n])
			echo '<li>' . $arrItem[$n] . '</li>';
	}
	echo '</ul>';
	echo '</div>';
}
?>
</div>