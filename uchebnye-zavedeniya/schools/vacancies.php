<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-vacancies-add" data-vuz-id="<?=$arResult["ID"]?>" data-id="0" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить вакансию</span>
</div>
<?php } ?>
<style>
.display-name {
	color: #000 !important;
	cursor: pointer;
	text-decoration-color: #ff471a;
}
.display-name span {
	color: #ff471a;
}
</style>
<div class="module st-news">
	<div class="line" id="box-line" data-type="vacancies">
		<?
		foreach($arResult["VACANCIES"] as $item) {
		?>
		<div class="news-item" style="position: relative;">
			<?if($pageAdmin):?>
			<div class="color-silver js-vacancies-edit" data-vuz-id="<?php echo $arResult["ID"]?>" data-id="<?php echo $item["ID"]; ?>" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
			<?endif?>
			<div class="news-name">
				<span><?=$item["NAME"]?></span>
			</div>
			<p>
			<?php if($item["PROPERTY_PHONE_VALUE"]) { ?>
				Телефон: <?php echo $item["PROPERTY_PHONE_VALUE"]?><br />
			<?php } ?>
			<?php if($item["PROPERTY_EMAIL_VALUE"]) { ?>
				Электронная почта: <a href="mailto:<?php echo $item["PROPERTY_EMAIL_VALUE"]?>"><?php echo $item["PROPERTY_EMAIL_VALUE"]?></a><br />
			<?php } ?>
			<?php if($item["PROPERTY_CONTACTS_VALUE"]) { ?>
				Контактное лицо: <?php echo $item["PROPERTY_CONTACTS_VALUE"]?><br />
			<?php } ?>
			<?php if($item["PROPERTY_FAKULTET_VALUE"]) { ?>
				Факультет: <?php echo $item["PROPERTY_FAKULTET_VALUE"]?><br />
			<?php } ?>
			<?php if($item["DETAIL_TEXT"]) { ?>
				<br /><?php echo $item["DETAIL_TEXT"]?><br />
			<?php } ?>
			</p>
		</div>
		<?
		}
		?>
	</div>
</div><!-- st-news -->