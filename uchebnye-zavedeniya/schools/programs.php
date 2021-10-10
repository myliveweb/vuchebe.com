<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="programs" data-iblock="4" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Новая программа обучения</span>
</div>
<?php } ?>
<?php
$placeholder = array('Название программы обучения',
    'Обучение на базе',
    'Очная',
    'Очно-заочная',
    'Заочная',
    'Группа выходного дня',
    'Дистанционная',
    'Учёная степень',
    'ucheba.ru',
    'Начало обучения',
    'Начало обучения',
    'Начало обучения',
    'Начало обучения',
    'Начало обучения',
    'Срок обучения',
    'Срок обучения',
    'Срок обучения',
    'Срок обучения',
    'Срок обучения',
    'Стоимость',
    'Стоимость',
    'Стоимость',
    'Стоимость',
    'Стоимость',
    'Вступительные экзамены',
    'Дополнительно',
    'Вступительные экзамены',
    'Дополнительно',
    'Вступительные экзамены',
    'Дополнительно',
    'Вступительные экзамены',
    'Дополнительно',
    'Вступительные экзамены',
    'Дополнительно',
    'Проходной балл',
    'Проходной балл',
    'Проходной балл',
    'Проходной балл',
    'Проходной балл',
    'Бюджетные места',
    'Комментарий',
    'Текст',
    'Факультет или подразделения',
    'Облако тегов',
    'Тег',
    'Код специальности',
    'Ссылка',
    'Внутренний комментарий', //48
    '<br><br>');

$arrPoryadok = array(
7,45,1,39,
2,14,9,24,25,34,19,
3,15,10,26,27,35,20,
4,16,11,28,29,36,21,
5,17,12,30,31,37,22,
6,18,13,32,33,38,23,
0,42,43,41,40);

$arrBlock['och'] = array(2,14,9,24,25,34,19);
$arrBlock['zoch'] = array(3,15,10,26,27,35,20);
$arrBlock['ochzoch'] = array(4,16,11,28,29,36,21);
$arrBlock['gvd'] = array(5,17,12,30,31,37,22);
$arrBlock['dis'] = array(6,18,13,32,33,38,23);

$arrHead = array(2,3,4,5,6);
$arrCost = array(19,20,21,22,23);
?>
<style>
.m-header .filter {
	color: #ff471a;
}
.m-header .filter.color-silver {
	color: #9f9f9f;
	text-decoration: none;
	cursor: default;
}
.st-tags-block .tag {
	color: #ff471a;
}
.st-tags-block .tag.active {
	border-color: #ff471a;
	text-decoration: none;
}
.news-item.one p {
	margin-bottom: 0px;
	margin-top: 5px;
}
</style>
<?php
if($_REQUEST['s']) {
	$id_section = (int) $_REQUEST['s'] - 1;
	$section = explode('#', $arResult["PROPERTIES"]["PROGRAMS"]["VALUE"][$id_section]);

	$arrFilter = array();

	if($section[2])
		$arrFilter['och'] += 1;
	if($section[4])
		$arrFilter['zoch'] += 1;
	if($section[3])
		$arrFilter['ochzoch'] += 1;
	if($section[5])
		$arrFilter['gvd'] += 1;
	if($section[6])
		$arrFilter['dis'] += 1;

?>
<div class="st-content-bottom clear">
	<div class="module st-news">
		<div class="m-header">
			<a href="#" data-filter="all" class="filter color-silver js-filter-one">Все</a> &nbsp;
			<?if($arrFilter['och']):?>
			<a href="#" data-filter="och" class="filter js-filter-one">Очная</a> &nbsp;
			<?endif?>
			<?if($arrFilter['zoch']):?>
			<a href="#" data-filter="zoch" class="filter js-filter-one">Заочная</a> &nbsp;
			<?endif?>
			<?if($arrFilter['ochzoch']):?>
			<a href="#" data-filter="ochzoch" class="filter js-filter-one">Очно-заочная</a> &nbsp;
			<?endif?>
			<?if($arrFilter['gvd']):?>
			<a href="#" data-filter="gvd" class="filter js-filter-one">Группа выходного дня</a> &nbsp;
			<?endif?>
			<?if($arrFilter['dis']):?>
			<a href="#" data-filter="dis" class="filter js-filter-one">Дистанционная</a> &nbsp;
			<?endif?>
		</div>
		<div class="line" id="box-line">
			<div class="news-item one" style="position: relative;">
				<?if($pageAdmin):?>
				<div class="color-silver js-news-edit" data-block="programs" data-id="<?php echo $id_section; ?>" data-iblock="4" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
				<?endif?>
				<div class="news-name" style="margin-bottom: 15px;"><span><?=$section[0]?></span></div>
				<?php
				foreach($arrPoryadok as $idPlaceholder) {
					if(!$idPlaceholder) {
						echo '<br>';
					} else {
						if($section[$idPlaceholder]) {
							$slassName = '';
							if(in_array($idPlaceholder, $arrBlock['och']))
								$slassName = 'och';
							if(in_array($idPlaceholder, $arrBlock['zoch']))
								$slassName = 'zoch';
							if(in_array($idPlaceholder, $arrBlock['ochzoch']))
								$slassName = 'ochzoch';
							if(in_array($idPlaceholder, $arrBlock['gvd']))
								$slassName = 'gvd';
							if(in_array($idPlaceholder, $arrBlock['dis']))
								$slassName = 'dis';
							if($slassName)
								$slassName .= ' see';

							$rub = '';
							if(in_array($idPlaceholder, $arrCost))
								$rub = ' руб.';

							if(in_array($idPlaceholder, $arrHead)) {
								echo '<p class="' . $slassName . '" style="margin-top: 15px;"><b>' . $section[$idPlaceholder] . '</b></p>';
							} elseif($idPlaceholder == 41 || $idPlaceholder == 40) {
								echo '<p class="' . $slassName . '">' . $section[$idPlaceholder] . '</p>';
							} else {
								echo '<p class="' . $slassName . '">' . trim($placeholder[$idPlaceholder]) . ': ' . $section[$idPlaceholder] . $rub . '</p>';
							}
						}
					}
				}
				?>
				<div class="btns text-right" style="margin-top: 30px;">
					<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=programs" class="button" style="font-family: Verdana;"><i class="fa fa-angle-double-left"></i> назад к списку программ обучения</a>
				</div>
			</div>
		</div>
	</div><!-- st-news -->

	<div class="name-block text-center"><span>Другие программы обучения</span></div>
	<div class="st-carousel news-3">
		<div class="owl-carousel">
			<?php
			$arrSections = $arResult["PROPERTIES"]["PROGRAMS"]["VALUE"];
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
				<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=programs&s=<?php echo ($val_item['id'] + 1); ?>"><span><?=$arrItem[0]?></span></a>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
</div>
<?php
} else {
	$arrFilter = array();
	$arrUS = array();
	foreach($arResult["PROPERTIES"]["PROGRAMS"]["VALUE"] as $sections_id => $sections_item) {
		$arrItem = explode('#', $sections_item);
		if($arrItem[2])
			$arrFilter['och'] += 1;
		if($arrItem[4])
			$arrFilter['zoch'] += 1;
		if($arrItem[3])
			$arrFilter['ochzoch'] += 1;
		if($arrItem[5])
			$arrFilter['gvd'] += 1;
		if($arrItem[6])
			$arrFilter['dis'] += 1;

		if(!in_array($arrItem[7], $arrUS))
			$arrUS[] = $arrItem[7];
	}

	echo '<div class="module st-news">';
	?>
	<div class="m-header">
		<a href="#" data-filter="all" class="filter color-silver js-filter">Все</a> &nbsp;
		<?if($arrFilter['och']):?>
		<a href="#" data-filter="och" class="filter js-filter">Очная</a> &nbsp;
		<?endif?>
		<?if($arrFilter['zoch']):?>
		<a href="#" data-filter="zoch" class="filter js-filter">Заочная</a> &nbsp;
		<?endif?>
		<?if($arrFilter['ochzoch']):?>
		<a href="#" data-filter="ochzoch" class="filter js-filter">Очно-заочная</a> &nbsp;
		<?endif?>
		<?if($arrFilter['gvd']):?>
		<a href="#" data-filter="gvd" class="filter js-filter">Группа выходного дня</a> &nbsp;
		<?endif?>
		<?if($arrFilter['dis']):?>
		<a href="#" data-filter="dis" class="filter js-filter">Дистанционная</a> &nbsp;
		<?endif?>
	</div>
	<?php if($arrUS) { ?>
	<div class="st-tags-block" style="margin-bottom: 25px;">
		<a href="#" data-filter="all" class="tag active">Все</a>
		<?php foreach($arrUS as $usItem) { ?>
		<a href="#" data-filter="<?=$usItem?>" class="tag"><?=$usItem?></a>
		<?php } ?>
	</div>
	<?php } ?>

	<?php
	echo '<div class="line" id="box-line">';
	foreach($arResult["PROPERTIES"]["PROGRAMS"]["VALUE"] as $sections_id => $sections_item) {
		$arrItem = explode('#', $sections_item);
		if(!$arrItem[0])
			continue;

		$strClass = '';

		if($arrItem[2])
			$strClass .= ' och';
		if($arrItem[4])
			$strClass .= ' zoch';
		if($arrItem[3])
			$strClass .= ' ochzoch';
		if($arrItem[5])
			$strClass .= ' gvd';
		if($arrItem[6])
			$strClass .= ' dis';
		?>
		<div class="news-item<?=$strClass?>" data-filter="<?=$arrItem[7]?>" style="position: relative;">
			<?if($pageAdmin):?>
			<div class="color-silver js-news-edit" data-block="programs" data-id="<?php echo $sections_id; ?>" data-iblock="4" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
			<?endif?>
			<div class="news-name">
				<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=programs&s=<?php echo ($sections_id + 1); ?>"><span><?=$arrItem[0]?></span></a>
			</div>
		</div>
	<?php
	}
	echo '</div>';
	echo '</div>';
}
?>