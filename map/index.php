<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта учебных заведений");

CModule::IncludeModule('iblock');

require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_map.php');
?>
<script src="https://api-maps.yandex.ru/2.1/?apikey=5880f79b-e2fe-4870-8818-fbd7c7dacfde&lang=ru_RU" type="text/javascript"></script>
<style>
.map {
	max-width: 100%;
    position: relative;
    height: 558px;
    margin: 1px;
}
</style>
<?php
$input = filter_input_array(INPUT_GET);
$idMap = (int) $input['map'];

$idEventInc     = $input['event'];
$idOpendoorInc  = $input['opendoor'];
$idCorpusInc    = $input['corpus'];
$idObgInc       = $input['obchegitie'];

$uzKey = '';
$idObj = '';

if($idEventInc) {
	$uzKey = 'ADD_EVENTS';
	$idObj = $idEventInc;
} elseif($idOpendoorInc) {
	$uzKey = 'OPENDOOR';
	$idObj = $idOpendoorInc;
} elseif($idCorpusInc) {
	$uzKey = 'DOP_ADRESS';
	$idObj = $idCorpusInc;
} elseif($idObgInc) {
    $uzKey = 'OBG';
    $idObj = $idObgInc;
}

$arrIco = array(2 => 'bal-2.png', 3 => 'bal-6.png', 4 => 'bal-3.png', 6 => 'bal-4.png');
$arrTypeUrl = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');

$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_LONGITUDE", "PROPERTY_LATITUDE", "PROPERTY_PHONE", "PROPERTY_CITY", "PROPERTY_ABBR");
$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => array(2,3,4,6), "ID" => $idMap);
$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
if($row = $res->Fetch()) {
	$geo = array();

	if($row["PROPERTY_LOGO_VALUE"]):
		$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
	elseif($row["PREVIEW_PICTURE"]):
		$srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
	elseif($row["DETAIL_PICTURE"]):
		$srcLogo = CFile::GetPath($row["DETAIL_PICTURE"]);
	else:
		$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage.png';
	endif;

	if($row["PROPERTY_ABBR_VALUE"]) {
	    $obgName = $row["PROPERTY_ABBR_VALUE"];
    } else {
        $obgName = $row["NAME"];
    }

    $resA = CIBlockElement::GetProperty($row["IBLOCK_ID"], $row["ID"], "sort", "asc", array("CODE" => "ADRESS"));
    if($obA = $resA->GetNext()) {
    	$adress = $obA['VALUE'];
		if($row["IBLOCK_ID"] == 4) {
			$adress = $obA['VALUE']['TEXT'];
		}
    }

	if($row["PROPERTY_CITY_VALUE"]) {
		if(stristr($adress, $row["PROPERTY_CITY_VALUE"]) === false) {
			$adress = $row["PROPERTY_CITY_VALUE"] . ', ' . $adress;
		}
	}

	$url = 'http://vuchebe.com/uchebnye-zavedeniya/' . $arrTypeUrl[$row["IBLOCK_ID"]] . '/' . $row["CODE"] . '/';
	if($row["IBLOCK_ID"] == 6 && $uzKey == '') {
	    $resGeo = CIBlockElement::GetProperty($row["IBLOCK_ID"], $row["ID"], "sort", "asc", array("CODE" => "YANDEX"));
	    while($obGeo = $resGeo->GetNext())
	    {
	    	if($obGeo['VALUE']) {
	    		$arrGeo = explode('#', $obGeo['VALUE']);

	    		if($row["PROPERTY_CITY_VALUE"]) {
	    			if(stristr($arrGeo[0], $row["PROPERTY_CITY_VALUE"]) === false) {
	    				$adress = $row["PROPERTY_CITY_VALUE"] . ', ' . $arrGeo[0];
	    			} else {
	    				$adress = $arrGeo[0];
	    			}
	    		}

	    		if(is_numeric($arrGeo[2]) && is_numeric($arrGeo[1])) {

					$geo[] = array(preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $adress),
						$arrGeo[2],
						$arrGeo[1],
						preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $row["NAME"]),
						$url,
						preg_replace('/[^0-9()+ -]/', '', $row["PROPERTY_PHONE_VALUE"]),
						$srcLogo,
						$arrIco[$row["IBLOCK_ID"]]
					);
				}
	    	}
	    }
	} else {
		if($uzKey != '') {
			$n = 0;
			$t = 0;
		    $resGeo = CIBlockElement::GetProperty($row["IBLOCK_ID"], $row["ID"], "sort", "asc", array("CODE" => $uzKey));
		    while($obGeo = $resGeo->GetNext()) {

		    	if($t)
		    		break;

		    	$arrGeo = explode('#', $obGeo['VALUE']);

	    		if($uzKey === 'ADD_EVENTS') {
	    			$matchKey = $arrGeo[14];
	    		} elseif($uzKey === 'OPENDOOR') {
	    			$matchKey = $arrGeo[12];
	    		} elseif($uzKey === 'DOP_ADRESS') {
	    			$matchKey = $arrGeo[10];
	    		} elseif($uzKey === 'OBG') {
                    $matchKey = $arrGeo[11];
                }

		    	if($arrGeo && $matchKey === $idObj) {
		    		$t = 1;

		    		if($uzKey === 'ADD_EVENTS') {
		    			$url .= '?sect=events';
		    		} elseif($uzKey === 'OPENDOOR') {
		    			$url .= '?sect=opendoor';
		    		} elseif($uzKey === 'DOP_ADRESS') {
		    			$url .= '?sect=corpus';
		    		} elseif($uzKey === 'OBG') {
                        $url .= '?sect=obchegitie';
                    }

					if($uzKey === 'DOP_ADRESS') {
		    			$adressIndex = $arrGeo[1];
		    			$phoneIndex = $arrGeo[2];
		    		} elseif($uzKey === 'OBG') {
                        $adressIndex = $arrGeo[0];
                        $phoneIndex = $arrGeo[3];
                    } else {
		    			$adressIndex = $arrGeo[3];
		    			$phoneIndex = $arrGeo[5];
		    		}

		    		if($row["PROPERTY_CITY_VALUE"]) {
		    			if(stristr($adressIndex, $row["PROPERTY_CITY_VALUE"]) === false && $uzKey !== 'OBG') {
		    				$adress = $row["PROPERTY_CITY_VALUE"] . ', ' . $adressIndex;
		    			} else {
		    				$adress = $adressIndex;
		    			}
		    		}

		    		if($uzKey === 'OBG') {
                        list($lon, $lat) = explode(',', $arrGeo[1]);
                        $arrGeo[0] = 'Общежитие - ' . $obgName;
                    }
		    		else
                        list($lon, $lat) = explode(',', $arrGeo[4]);

		    		if(is_numeric($lat) && is_numeric($lon)) {

						$fullTime = $arrGeo[1] . ' ' . $arrGeo[2];

						$strDate = get_str_time_post(strtotime($fullTime));
						$curDate = explode(' ', $strDate);

						$cur_time = time();
						if($cur_time > strtotime($fullTime))
							$strDate .= ' (событие уже прошло)';

						if($uzKey === 'DOP_ADRESS') {
							$strDate = $arrGeo[5];
			    		}

						$geo[] = array(preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $adress),
							$lat,
							$lon,
							preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $arrGeo[0]),
							$url,
							preg_replace('/[^0-9()+ -]/', '', $phoneIndex),
							$srcLogo,
							'baloon-ico.png',
							$strDate,
							$curDate[0],
							$curDate[1]
						);
					}
		    	}
		    	$n++;
		    }
		} else {
			$geo[] = array(preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $adress),
				$row["PROPERTY_LONGITUDE_VALUE"],
				$row["PROPERTY_LATITUDE_VALUE"],
				preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $row["NAME"]),
				$url,
				preg_replace('/[^0-9()+ -]/', '', $row["PROPERTY_PHONE_VALUE"]),
				$srcLogo,
				$arrIco[$row["IBLOCK_ID"]]
			);

			$uzKey = 'DOP_ADRESS';
			if($uzKey != '') {
				$n = 0;
				$t = 0;
			    $resGeo = CIBlockElement::GetProperty($row["IBLOCK_ID"], $row["ID"], "sort", "asc", array("CODE" => $uzKey));
			    while($obGeo = $resGeo->GetNext()) {

			    	$arrGeo = explode('#', $obGeo['VALUE']);

		    		if($uzKey === 'ADD_EVENTS') {
		    			$matchKey = $arrGeo[14];
		    		} elseif($uzKey === 'OPENDOOR') {
		    			$matchKey = $arrGeo[12];
		    		} elseif($uzKey === 'DOP_ADRESS') {
		    			$matchKey = $arrGeo[10];
		    		} elseif($uzKey === 'OBG') {
                        $matchKey = $arrGeo[11];
                    }

			    	if($arrGeo) {

			    	    $goUrl = '';

			    		if($uzKey === 'ADD_EVENTS') {
                            $goUrl = $url . '?sect=events';
			    		} elseif($uzKey === 'OPENDOOR') {
                            $goUrl = $url . '?sect=opendoor';
			    		} elseif($uzKey === 'DOP_ADRESS') {
                            $goUrl = $url . '?sect=corpus';
			    		} elseif($uzKey === 'OBG') {
                            $goUrl = $url . '?sect=obchegitie';
                        }

						if($uzKey === 'DOP_ADRESS') {
			    			$adressIndex = $arrGeo[1];
			    			$phoneIndex = $arrGeo[2];
                        } elseif($uzKey === 'OBG') {
                            $adressIndex = $arrGeo[0];
                            $phoneIndex = $arrGeo[3];
                        } else {
			    			$adressIndex = $arrGeo[3];
			    			$phoneIndex = $arrGeo[5];
			    		}

			    		if($row["PROPERTY_CITY_VALUE"]) {
			    			if(stristr($adressIndex, $row["PROPERTY_CITY_VALUE"]) === false && $uzKey !== 'OBG') {
			    				$adress = $row["PROPERTY_CITY_VALUE"] . ', ' . $adressIndex;
			    			} else {
			    				$adress = $adressIndex;
			    			}
			    		}

			    		list($lon, $lat) = explode(',', $arrGeo[4]);

			    		if(is_numeric($lat) && is_numeric($lon)) {

							$fullTime = $arrGeo[1] . ' ' . $arrGeo[2];

							$strDate = get_str_time_post(strtotime($fullTime));
							$curDate = explode(' ', $strDate);

							$cur_time = time();
							if($cur_time > strtotime($fullTime))
								$strDate .= ' (событие уже прошло)';

							if($uzKey === 'DOP_ADRESS') {
								$strDate = $arrGeo[5];
				    		}

							$geo[] = array(preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $adress),
								$lat,
								$lon,
								preg_replace('/[^a-zA-Zа-яА-Я0-9()+.,\/ -]/ui', '', $arrGeo[0]),
                                $goUrl,
								preg_replace('/[^0-9()+ -]/', '', $phoneIndex),
								$srcLogo,
								'baloon-ico.png',
								$strDate,
								$curDate[0],
								$curDate[1]
							);
						}
			    	}
			    	$n++;
			    }
			}

		}
	}
}
?>
<div class="map-block general-map">
<div id="map" class="map"></div><!-- map -->
</div><!-- map-block -->
<script type="text/javascript">
var myMap;
ymaps.ready(init);
function init(){
    myMap = new ymaps.Map("map", {
        center: [55.76, 37.61],
        controls: [],
        // Уровень масштабирования. Допустимые значения:
        // от 0 (весь мир) до 19.
        zoom: 10
    });

	myMap.controls.add('zoomControl', {
	    float: 'none',
	    position: {
	        right: 15,
	        bottom: 35
	   }
	});

	clusterer = new ymaps.Clusterer({
        // Зададим массив, описывающий иконки кластеров разного размера.
        clusterIcons: [
            {
                href: '<?php echo SITE_TEMPLATE_PATH; ?>/images/b.png',
                size: [40, 40],
                offset: [-20, -20]
            },
            {
                href: '<?php echo SITE_TEMPLATE_PATH; ?>/images/b.png',
                size: [60, 60],
                offset: [-30, -30]
            }],

        clusterNumbers: [15]
	});

	geoObjects = [];
	<?php foreach($geo as $idObj => $item) {
		if($uzKey === 'DOP_ADRESS') {
		?>
		geoObjects[<?php echo $idObj; ?>] = new ymaps.Placemark([<?php echo $item[2]; ?>, <?php echo $item[1]; ?>], {
		balloonContent: `
		<div class="image" style="width: 59px; float: left; border: 1px solid #ff471a;">
			<img style="margin: 3px; width: 51px;" src="<?php echo $item[6]; ?>" alt="<?php echo $item[4]; ?>" title="<?php echo $item[4]; ?>">
		</div>
		<div class="text" style="width: calc(100% - 59px); float: left; padding-left: 10px; font-size: 12px; color: #2a2929;">
			<a style="color: #ff471a; margin-bottom: 5px;" class="title" href="<?php echo $item[4]; ?>"><?php echo $item[3]; ?></a>
			Адрес: <?php echo $item[0]; ?>
			<?php if($item[8]) { ?>
			<br>Метро: <?php echo $item[8]; ?>
			<?php } ?>
			<?php if($item[5]) { ?>
			<br>Телефон: <?php echo $item[5]; ?>
			<?php } ?>
		</div>`
	} , {
	    iconLayout: 'default#image',
	    iconImageHref: '<?php echo SITE_TEMPLATE_PATH; ?>/images/<?php echo $item[7]; ?>',
	    iconImageSize: [29, 39],
	    iconImageOffset: [-14, -40]
	});
		<?php
		} elseif($uzKey === 'ADD_EVENTS' || $uzKey === 'OPENDOOR') {
		?>
		geoObjects[<?php echo $idObj; ?>] = new ymaps.Placemark([<?php echo $item[2]; ?>, <?php echo $item[1]; ?>], {
		balloonContent: `
		<div class="image" style="width: 80px; float: left;">
			<div class="date-ico" style="margin-bottom: 10px; width: 80px; padding: 26px 4px 0; background-size: 80px 80px; height: 80px;"><span><?php echo $item[9]; ?></span><?php echo $item[10]; ?></div>
		</div>
		<div class="text" style="width: calc(100% - 80px); float: left; padding-left: 10px; font-size: 12px; color: #2a2929;">
			<a style="color: #ff471a; margin-bottom: 0px;" class="title" href="<?php echo $item[4]; ?>"><?php echo $item[3]; ?></a>
			<div style="font-size: 11px; color: #9f9f9f;"><?php echo $item[8]; ?></div>
			Адрес: <?php echo $item[0]; ?>
			<?php if($item[5]) { ?>
			<br>Телефон: <?php echo $item[5]; ?>
			<?php } ?>
		</div>`
	} , {
	    iconLayout: 'default#image',
	    iconImageHref: '<?php echo SITE_TEMPLATE_PATH; ?>/images/<?php echo $item[7]; ?>',
	    iconImageSize: [29, 39],
	    iconImageOffset: [-14, -40]
	});
		<?php
		} else {
	?>
		geoObjects[<?php echo $idObj; ?>] = new ymaps.Placemark([<?php echo $item[2]; ?>, <?php echo $item[1]; ?>], {
		balloonContent: `
		<div class="image" style="width: 59px; float: left; border: 1px solid #ff471a;">
			<img style="margin: 3px; width: 51px;" src="<?php echo $item[6]; ?>" alt="<?php echo $item[4]; ?>" title="<?php echo $item[4]; ?>">
		</div>
		<div class="text" style="width: calc(100% - 59px); float: left; padding-left: 10px; font-size: 12px; color: #2a2929;">
			<a style="color: #ff471a; margin-bottom: 5px;" class="title" href="<?php echo $item[4]; ?>"><?php echo $item[3]; ?></a>
			Адрес: <?php echo $item[0]; ?>
			<?php if($item[5]) { ?>
			<br>Телефон: <?php echo $item[5]; ?>
			<?php } ?>
		</div>`
	} , {
	    iconLayout: 'default#image',
	    iconImageHref: '<?php echo SITE_TEMPLATE_PATH; ?>/images/<?php echo $item[7]; ?>',
	    iconImageSize: [29, 39],
	    iconImageOffset: [-14, -40]
	});
	<?php
		}
	}
	?>
	clusterer.add(geoObjects);
	myMap.geoObjects.add(clusterer);

    myMap.setBounds(clusterer.getBounds(), {
        checkZoomRange: true
    });
}
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>