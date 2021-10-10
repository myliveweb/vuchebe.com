<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="fakultets" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить факультет или институт</span>
</div>
<?php } ?>
<?php
$placeholder = array('Название факультета или института',
    'Адрес',
    'Координаты Яндекс',
    'Метро',
    'Телефон',
    'Email',
    'Ссылка на страницу',
    'Бюджетные места',
    'ucheba.ru',
    'Текст',
    'Комментарий',
    'Специальность',
    'Облако тегов',
    'Тег',
    'Запасная строка',
    'Дополнительная строка',
    'Внутренний комментарий'); // 17
if($_REQUEST['s']) {
	$id_section = (int) $_REQUEST['s'] - 1;
	$section = explode('#', $arResult["PROPERTIES"]["FAKULTETS"]["VALUE"][$id_section]);
?>
<div class="st-content-bottom clear">
	<div class="module st-news">
		<div class="line" id="box-line">
			<div class="news-item one">
				<div class="right">
				<?if($pageAdmin):?>
				<div style="position: relative; top: -10px; right: 5px; text-align: right;">
					<div class="color-silver js-news-edit" data-block="fakultets" data-id="<?php echo $id_section; ?>" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
				</div>
				<?endif?>
				<? if($section[2]) { ?>
					<div class="btns text-right"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>
				<? } ?>
				</div>
				<div class="news-name"><span><?=$section[0]?></span></div>
				<?php
					for($n = 1; $n < sizeof($placeholder); $n++) {
						if($n == 2 || $n == 8 || $n > 13)
							continue;
						if($section[$n]) {
							if($n == 6) {
								echo '<p>' . $placeholder[$n] . ': <a href="' . $section[$n] . '" target="blank">' . $section[$n] . '</a></p>';
							} elseif($n == 9 || $n == 11 || $n == 10) {
								echo '<p>' . $section[$n] . '</p>';
							} else {
								echo '<p>' . $placeholder[$n] . ': ' . $section[$n] . '</p>';
							}
						}
					}
				?>
				<div class="btns text-right" style="margin-top: 15px;">
					<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=fakultets" class="button" style="font-family: Verdana;"><i class="fa fa-angle-double-left"></i> назад к списку факультетов и институтов</a>
				</div>
			</div>
		</div>
	</div><!-- st-news -->

	<div class="name-block text-center"><span>Другие факультеты и институты</span></div>
	<div class="st-carousel news-3">
		<div class="owl-carousel">
			<?php
			$arrSections = $arResult["PROPERTIES"]["FAKULTETS"]["VALUE"];
			unset($arrSections[$id_section]);
			$newArray = array();
			foreach($arrSections as $id_item => $val_item) {
				$newArray[]	= array('id' => $id_item, 'val' => $val_item);
			}
			shuffle($newArray);
			foreach($newArray as $val_item) {
				$arrItem = explode('#', $val_item['val']);
				if(!$arrItem[0])
					continue;
			?>
			<div class="st-item">
				<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=fakultets&s=<?php echo ($val_item['id'] + 1); ?>"><span><?=$arrItem[0]?></span></a>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
</div>
<?php
} else {
	echo '<div class="module st-news">';
	echo '<div class="line" id="box-line">';
	foreach($arResult["PROPERTIES"]["FAKULTETS"]["VALUE"] as $sections_id => $sections_item) {
		$arrItem = explode('#', $sections_item);
		if(!$arrItem[0])
			continue;
	?>
	<div class="news-item">
		<div class="right">
		<?if($pageAdmin):?>
		<div style="position: relative; top: -10px; right: 5px; text-align: right;">
			<div class="color-silver js-news-edit" data-block="fakultets" data-id="<?php echo $sections_id; ?>" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
		</div>
		<?endif?>
		<? if($arrItem[2]) { ?>
			<div class="btns text-right"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>
		<? } ?>
		</div>
		<div class="news-name">
			<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=fakultets&s=<?php echo ($sections_id + 1); ?>"><span><?=$arrItem[0]?></span></a>
		</div>
		<p>
		<?php
			for($n = 1; $n < sizeof($placeholder); $n++) {
				if($n == 2 || $n > 5)
					continue;
				if($arrItem[$n]) {
					if($n == 6) {
						echo $placeholder[$n] . ': <a href="' . $arrItem[$n] . '" target="blank">' . $arrItem[$n] . '</a><br>';
					} else {
						echo $placeholder[$n] . ': ' . $arrItem[$n] . '<br>';
					}
				}
			}
		?>
		</p>
	</div>
	<?php
	}
	echo '</div>';
	echo '</div>';
}
?>