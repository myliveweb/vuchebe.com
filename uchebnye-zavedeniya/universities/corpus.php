<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="corpus" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить корпус</span>
</div>
<?php } ?>
<?php
$placeholder = array('Название корпуса',
    'Адрес',
    'Телефон',
    'Ссылка на страницу',
    'Координаты Яндекс',
    'Метро',
    'ucheba.ru',
    'Текст',
    'Дата создания',
    'Дополнительная строка',
    'Уникальный ключ'); // 11
?>
<div class="module st-news">
	<div class="line" id="box-line">
		<? foreach($arResult["PROPERTIES"]["DOP_ADRESS"]["VALUE"] as $id_corpus => $item) {
			$arrItem = explode('#', $item);
			if(!$arrItem[0])
				continue;
			?>
		<div class="news-item corpus" data-id="<?=$arResult['ID']?>" data-ukey="<?=$arrItem[10]?>" style="position: relative;">
			<div class="right">
			<?if($pageAdmin):?>
			<div style="position: relative; top: -10px; right: 5px; text-align: right;">
				<div class="color-silver js-news-edit" data-block="corpus" data-id="<?php echo $id_corpus; ?>" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
			</div>
			<?endif?>
			<? if($arrItem[4]) { ?>
				<div class="btns text-right">
					<a href="/map/?map=<?php echo $arResult["ID"]; ?>&corpus=<?php echo $arrItem[10]; ?>" class="button"><span style="font-family: Verdana;">на карте</span></a>
				</div>
			<? } ?>
			</div>
			<div class="news-name">
				<span><?=$arrItem[0]?></span>
			</div>
			<p>
			<?php
			for($n = 1; $n < sizeof($placeholder); $n++) {
				if($n == 4 || $n == 7 || $n == 8 || $n == 9 || $n == 10)
					continue;
				if(trim($arrItem[$n]))
					echo $placeholder[$n] . ': ' . trim($arrItem[$n]) . '<br>';
			}
			?>
			</p>
		</div>
		<? } ?>
	</div>
</div><!-- st-news -->