<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="units" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить подразделение</span>
</div>
<?php } ?>
<?php
$placeholder = array('Название подразделения',
    'ID вуза',
    'ID колледжа',
    'ID школы',
    'Адрес',
    'Координаты Яндекс',
    'Метро',
    'Телефон',
    'Ссылка на страницу',
    'Email',
    'Текст',
    'Облако тегов',
    'Тег',
    'ucheba.ru',
    'Запасная строка',
    'Дополнительная строка',
    'Внутренний комментарий'); // 17
if($_REQUEST['s']) {
	$id_section = (int) $_REQUEST['s'] - 1;
	$section = explode('#', $arResult["PROPERTIES"]["MORE_U"]["VALUE"][$id_section]);
?>
<div class="st-content-bottom clear">
	<div class="module st-news">
		<div class="line" id="box-line">
			<div class="news-item one">
				<div class="right">
				<?if($pageAdmin):?>
				<div style="position: relative; top: -10px; right: 5px; text-align: right;">
					<div class="color-silver js-news-edit" data-block="units" data-id="<?php echo $id_section; ?>" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
				</div>
				<?endif?>
				<? if($section[5]) { ?>
					<div class="btns text-right"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>
				<? } ?>
				</div>
				<div class="news-name"><span><?=$section[0]?></span></div>
				<?php
					for($n = 1; $n < sizeof($placeholder); $n++) {
						if($n == 5 || $n > 12)
							continue;
						if($section[$n]) {
							if($n == 1) {
								$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE");
								$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ID" => $section[$n]);
								$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
								$row = $res->Fetch();
							?>
							<p><a href="/uchebnye-zavedeniya/universities/<?=$row['CODE']?>/" style="color: #ff471a;">Страница ВУЗа</a></p>
							<?php
							} elseif($n == 2) {
								$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE");
								$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "ID" => $section[$n]);
								$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
								$row = $res->Fetch();
							?>
							<p><a href="/uchebnye-zavedeniya/colleges/<?=$row['CODE']?>/" style="color: #ff471a;">Страница колледжа</a></p>
							<?php
							} elseif($n == 3) {
								$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE");
								$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "ID" => $section[$n]);
								$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
								$row = $res->Fetch();
							?>
							<p><a href="/uchebnye-zavedeniya/schools/<?=$row['CODE']?>/" style="color: #ff471a;">Страница школы</a></p>
							<?php
							} elseif($n == 8) {
								echo '<p>' . $placeholder[$n] . ': <a href="' . $section[$n] . '" target="blank">' . $section[$n] . '</a></p>';
							} else {
								echo '<p>' . $placeholder[$n] . ': ' . $section[$n] . '</p>';
							}
						}
					}
				?>
				<div class="btns text-right">
					<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=units" class="button" style="font-family: Verdana;"><i class="fa fa-angle-double-left"></i> назад к списку подразделений</a>
				</div>
			</div>
		</div>
	</div><!-- st-news -->

	<div class="name-block text-center"><span>Другие подразделения</span></div>
	<div class="st-carousel news-3">
		<div class="owl-carousel">
			<?php
			$arrSections = $arResult["PROPERTIES"]["MORE_U"]["VALUE"];
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
				<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=units&s=<?php echo ($val_item['id'] + 1); ?>"><span><?=$arrItem[0]?></span></a>
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
	foreach($arResult["PROPERTIES"]["MORE_U"]["VALUE"] as $sections_id => $sections_item) {
		$arrItem = explode('#', $sections_item);
		if(!$arrItem[0])
			continue;

		$idUZ = 0;
		$code = '';
		$type = '';
		$map = 0;

		if($arrItem[3]) {
            $idUZ = $arrItem[3];
            $type = 'schools';
        }

        if($arrItem[2]) {
            $idUZ = $arrItem[2];
            $type = 'colleges';
        }

        if($arrItem[1]) {
            $idUZ = $arrItem[1];
            $type = 'universities';
        }

        if($idUZ) {
            $arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PROPERTY_LONGITUDE", "PROPERTY_LATITUDE");
            $arFilter = array("IBLOCK_ID" => array(2, 3, 4), "ACTIVE" => "Y", "ID" => $idUZ);
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
		<div style="position: relative; top: -10px; right: 5px; text-align: right;">
			<div class="color-silver js-news-edit" data-block="units" data-id="<?php echo $sections_id; ?>" data-iblock="2" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
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
			<a href="/uchebnye-zavedeniya/<?php echo $type; ?>/<?php echo $code; ?>/"><span><?=$arrItem[0]?></span></a>
        <?php } else { ?>
            <span><?=$arrItem[0]?></span>
        <?php } ?>
        </div>
		<p>
		<?php
			for($n = 1; $n < sizeof($placeholder); $n++) {
				if($n == 1 || $n == 2 || $n == 3 || $n == 5 || $n > 12)
					continue;
				if($arrItem[$n]) {
					if($n == 8) {
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