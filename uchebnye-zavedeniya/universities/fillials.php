<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="fillials" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить филиал</span>
</div>
<?php } ?>
<?php
$placeholder = array('Название филиала',
    'ID филиала',
    'Адрес',
    'Координаты Яндекс',
    'Метро',
    'Телефон',
    'Ссылка на страницу',
    'ucheba.ru',
    'Текст',
    'Запасная строка',
    'Дополнительная строка',
    'Внутренний комментарий'); // 12
?>
<div class="module st-news">
	<div class="line" id="box-line">
		<? foreach($arResult["PROPERTIES"]["FILLIALS_VUZ"]["VALUE"] as $id_filials => $item) {
			$arrItem = explode('#', $item);
			if(!$arrItem[0])
				continue;

			    $code = '';
			    $map = 0;

                if($arrItem[1]) {
                    $arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PROPERTY_LONGITUDE", "PROPERTY_LATITUDE");
                    $arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ID" => $arrItem[1]);
                    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
                    if($row = $res->Fetch()) {
                        $code = $row['CODE'];
                        if($row['PROPERTY_LONGITUDE_VALUE'] && $row['PROPERTY_LATITUDE_VALUE']) {
                            $map = $row['ID'];
                        }
                    }
                }

			?>
		<div class="news-item">
			<div class="right">
			<?if($pageAdmin):?>
			<div style="position: relative; right: 5px; text-align: right;">
				<div class="color-silver js-news-edit" data-block="fillials" data-id="<?php echo $id_filials; ?>" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
			</div>
			<?endif?>
			<? if($map) { ?>
				<div class="btns text-right"><a href="/map/?map=<?php echo $map; ?>" class="button"><span style="font-family: Verdana;">на карте</span></a></div>
			<? } ?>
			</div>
			<div class="news-name">
				<?php
				if($code) {
				?>
				<a href="/uchebnye-zavedeniya/universities/<?=$code?>/" style="color: #ff471a;"><span><?=$arrItem[0]?></span></a>
				<?php } else { ?>
				<span><?=$arrItem[0]?></span>
				<?php } ?>
			</div>
			<p>
			<?php
			for($n = 1; $n < sizeof($placeholder); $n++) {
				if($n == 1 || $n == 3 || $n > 5)
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