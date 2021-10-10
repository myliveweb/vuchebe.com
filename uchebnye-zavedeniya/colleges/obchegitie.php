<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="obchegitie" data-iblock="3" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить общежитие</span>
</div>
<?php } ?>
<?php
$placeholder = array('Адрес',
    'Координаты Яндекс',
    'Метро',
    'Телефон',
    'Контактное лицо',
    'Ссылка на страницу',
    'Ссылка',
    'Текст',
    'Комментарий',
    'Запасная строка',
    'Дополнительная строка',
    'Внутренний комментарий'); // 12
//print_r($placeholder);
?>
<div class="module st-news">
	<div class="line" id="box-line">
		<? foreach($arResult["PROPERTIES"]["OBG"]["VALUE"] as $item_id => $item) {
			$arrItem = explode('#', $item);
			//if(!$arrItem[0])
			//	continue;
			?>
		<div class="news-item">
			<div class="right">
			<?if($pageAdmin):?>
			<div style="position: relative; top: -10px; right: 5px; text-align: right;">
				<div class="color-silver js-news-edit" data-block="obchegitie" data-id="<?php echo $item_id; ?>" data-iblock="3" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
			</div>
			<?endif?>
            <? if($arrItem[1]) { ?>
                <div class="btns text-right"><a href="/map/?map=<?php echo $arResult["ID"]; ?>&obchegitie=<?php echo $arrItem[11]; ?>" class="button"><span style="font-family: Verdana;">на карте</span></a></div>
            <? } ?>
			</div>
			<div class="news-name">
				<span><?=$arrItem[0]?></span>
			</div>
			<p>
			<?php
			for($n = 1; $n < sizeof($placeholder); $n++) {
				if($n == 1 || $n > 8)
					continue;
				if(trim($arrItem[$n])) {
					if($n == 5 || $n == 6) {
						echo $placeholder[$n] . ': <a href="' . $arrItem[$n] . '" target="blank">' . $arrItem[$n] . '</a><br>';
					} else {
						echo $placeholder[$n] . ': ' . $arrItem[$n] . '<br>';
					}
				}
			}
			?>
			</p>
		</div>
		<? } ?>
	</div>
</div><!-- st-news -->