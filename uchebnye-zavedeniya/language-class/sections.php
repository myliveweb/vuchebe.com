<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="sections" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить секцию</span>
</div>
<?php } ?>
<?php
function shuffle_assoc($in) {
    $result = array();
    while ($key = array_rand($in)) {
        $result[$key] = $in[$key];
    }
    return $result;
}
$placeholder = array('Название',
    'Телефон',
    'Контактное лицо',
    'Ссылка на страницу',
    'Комментарий',
    'Облако тегов',
    'Тег',
    'Запасная строка',
    'Дополнительная строка',
    'Внутренний комментарий'); // 10
if($_REQUEST['s']) {
	//echo $_REQUEST['s'] - 1;
	$id_section = (int) $_REQUEST['s'] - 1;
	$section = explode('#', $arResult["PROPERTIES"]["SECTIONS_VUZ"]["VALUE"][$id_section]);
?>
<div class="st-content-bottom clear">
	<div class="module st-news">
		<div class="line" id="box-line">
			<div class="news-item one" style="position: relative;">
				<?if($pageAdmin):?>
				<div class="color-silver js-news-edit" data-block="sections" data-id="<?php echo $id_section; ?>" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
				<?endif?>
				<div class="news-name"><span><?=$section[0]?></span></div>
				<?php
					for($n = 1; $n < sizeof($placeholder); $n++) {
						if($section[$n]) {
							if($n == 3) {
								echo '<p>' . $placeholder[$n] . ': <a href="' . $section[$n] . '" target="blank">' . $section[$n] . '</a></p>';
							} else {
								echo '<p>' . $placeholder[$n] . ': ' . $section[$n] . '</p>';
							}
						}
					}
				?>
				<div class="btns text-right">
					<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=sections" class="button" style="font-family: Verdana;"><i class="fa fa-angle-double-left"></i> назад к списку секций</a>
				</div>
			</div>
		</div>
	</div><!-- st-news -->

	<div class="name-block text-center"><span>Другие секции</span></div>
	<div class="st-carousel news-3">
		<div class="owl-carousel">
			<?php
			$arrSections = $arResult["PROPERTIES"]["SECTIONS_VUZ"]["VALUE"];
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
				<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=sections&s=<?php echo ($val_item['id'] + 1); ?>"><span><?=$arrItem[0]?></span></a>
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
	foreach($arResult["PROPERTIES"]["SECTIONS_VUZ"]["VALUE"] as $sections_id => $sections_item) {
		$arrItem = explode('#', $sections_item);
		if(!$arrItem[0])
			continue;
	?>
	<div class="news-item" style="position: relative;">
		<?if($pageAdmin):?>
		<div class="color-silver js-news-edit" data-block="sections" data-id="<?php echo $sections_id; ?>" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
		<?endif?>
		<div class="news-name">
			<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=sections&s=<?php echo ($sections_id + 1); ?>"><span><?=$arrItem[0]?></span></a>
		</div>
		<p>
		<?php
			for($n = 1; $n < sizeof($placeholder); $n++) {
				if($arrItem[$n]) {
					if($n == 3) {
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