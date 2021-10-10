<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 25px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="opendoor" data-iblock="4" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Новый день открытых дверей</span>
</div>
<?php } ?>
<script>
var curPage = 'schools';
var id_vuz = <?php echo $arResult["ID"]; ?>;
var startFrom = 10;
</script>
<div class="page-content">
	<div class="name-block text-center txt-up"><span>Дни открытых дверей</span></div>
	<div class="st-content-bottom clear">
	<? if($arResult["PROPERTIES"]["OPENDOOR"]["VALUE"]) { ?>
		<div class="module st-news">
			<div class="line" id="box-line" data-type="opendoor">
				<?
				foreach($arResult["PROPERTIES"]["OPENDOOR"]["VALUE"] as $idOd => $item) {
					$arrTemp = array();
					$arrItem = explode('#', $item);
					if(!$arrItem[0])
						continue;

					if(sizeof($arrItem) < sizeof($placeholder)) {
						$arrItem[9]	= $arrItem[5];
						$arrItem[5] = '';
					}

					$arrTemp['ID'] = $arrItem[12];
					$arrTemp['DATA'] = $arrItem;

					$fullTime = $arrItem[1] . ' ' . $arrItem[2];

					$strDate = get_str_time_post(strtotime($fullTime));
					$arrTemp["FORMAT_DATE"] = $strDate;

					$curDate = explode(' ', $strDate);
					$arrTemp["DAY"] = $curDate[0];
					$arrTemp["MONTH"] = $curDate[1];

					$arrTemp['sort'] = strtotime($fullTime);
					$newsList[] = $arrTemp;
				}

		        $placeholder = array('Название',
		            'Дата',
		            'Время',
		            'Адрес',
		            'Координаты Яндекс',
		            'Телефон',
		            'Ссылка на страницу',
		            'Комментарий',
		            'Текст',
		            'ucheba.ru',
		            'Дата создания',
		            'Дополнительная строка',
		            'Уникальный ключ');

				usort($newsList, "cmp_uz");
				$go = 1;
				$cur_time = time();
				$tuday = 1;
				foreach($newsList as $ts => $itemList) {
					$arrItem = $itemList['DATA'];
					if(!$arrItem[0])
						continue;

					if(sizeof($arrItem) < sizeof($placeholder)) {
						$arrItem[9]	= $arrItem[5];
						$arrItem[5] = '';
					}
				?>
				<?php
				if($cur_time > $itemList['sort'] && $tuday) {
					$tuday = 0;
					if($ts) {
				?>
					<div style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: -21px; text-align: center;">
						<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
					</div>
				<?php
					}
				}
				?>
				<div class="news-item opendoor">
					<div class="right">
					<?if($pageAdmin):?>
					<div style="position: relative; top: -10px; right: 5px; text-align: right;">
						<div class="color-silver js-news-edit" data-block="opendoor" data-id="<?php echo $itemList["ID"]; ?>" data-iblock="4" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
					</div>
					<?endif?>
					<? if($arrItem[1]) {
						$arrDate = explode(' ', $arrItem[1]);
						$fullTime = $arrDate[0] . ' ' . $arrItem[2];
						$strDate = get_str_time_post(strtotime($fullTime));
						$curDate = explode(' ', $strDate);
					?>
					<div class="date-ico" style="margin-bottom: 10px;"><span><?=$curDate[0]?></span><?=$curDate[1]?></div>
					<? } ?>
					<? if($arrItem[4]) { ?>
					<div class="btns text-right">
						<a href="/map/?map=<?php echo $arResult["ID"]; ?>&opendoor=<?php echo $itemList["ID"]; ?>" class="button">
							<span style="font-family: Verdana;">на карте</span>
						</a>
					</div>					<? } ?>
					</div>
					<div class="date" style="margin-bottom: 7px;"><?php echo $strDate; if($cur_time > $itemList['sort']) { echo ' (событие уже прошло)'; } ?></div>
					<div class="news-name">
						<? if($arrItem[6]) { ?>
						<a href="<?=$arrItem[6]?>"><span><?=$arrItem[0]?></span></a>
						<? } else { ?>
						<span><?=$arrItem[0]?></span>
						<? } ?>
					</div>
					<p>
					<?php
					for($n = 1; $n < sizeof($placeholder); $n++) {
						if($n == 1 || $n == 2 || $n == 4 || $n == 9 || $n == 10 || $n == 11 || $n == 12)
							continue;

						if(trim($arrItem[$n])) {
							if($n == 6) {
								echo $placeholder[$n] . ': <a href="' . $arrItem[$n] . '" target="blank">' . trim($arrItem[$n]) . '</a><br>';
							} elseif($n == 8) {
								echo trim($arrItem[$n]) . '<br>';
							} else {
								echo $placeholder[$n] . ': ' . trim($arrItem[$n]) . '<br>';
							}
						}
					}
					?>
					</p>
				</div>
				<?
					$go++;
					if($go > 10)
						break;
				}
				?>
			</div>
		</div><!-- st-news -->
	<? } ?>
	</div><!-- st-content-bottom -->
</div>