<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта учебных заведений");

CModule::IncludeModule('iblock');

$current_razdel = 'language-class';
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

$geo = array();

$arrIco = array(2 => 'bal-2.png', 3 => 'bal-6.png', 4 => 'bal-3.png', 6 => 'bal-4.png');
$arrTypeUrl = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');

$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO", "PROPERTY_ADRESS", "PROPERTY_LONGITUDE", "PROPERTY_LATITUDE", "PROPERTY_PHONE", "PROPERTY_CITY");
$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 6);
$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
while($row = $res->Fetch()) {

	if($row["PROPERTY_LOGO_VALUE"]):
		$srcLogo = CFile::GetPath($row["PROPERTY_LOGO_VALUE"]);
	elseif($row["PREVIEW_PICTURE"]):
		$srcLogo = CFile::GetPath($row["PREVIEW_PICTURE"]);
	elseif($row["DETAIL_PICTURE"]):
		$srcLogo = CFile::GetPath($row["DETAIL_PICTURE"]);
	else:
		$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage.png';
	endif;

	$url = 'http://vuchebe.com/uchebnye-zavedeniya/' . $arrTypeUrl[$row["IBLOCK_ID"]] . '/' . $row["CODE"] . '/';

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
					$srcLogo
				);
			}
    	}
    }
}
//echo '<pre>';
//var_dump($geo);
//echo '</pre>';
?>
<div class="map-block general-map">
<div id="map" class="map"></div><!-- map -->
</div><!-- map-block -->
<script type="text/javascript">
ymaps.ready(init);
function init(){
    var myMap = new ymaps.Map("map", {
        center: [55.76, 37.61],
        controls: [],
        // Уровень масштабирования. Допустимые значения:
        // от 0 (весь мир) до 19.
        zoom: 2
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
        // Эта опция отвечает за размеры кластеров.
        // В данном случае для кластеров, содержащих до 100 элементов,
        // будет показываться маленькая иконка. Для остальных - большая.
        clusterNumbers: [10]
	});
	geoObjects = [];
	<?php foreach($geo as $idObj => $item) { ?>
		geoObjects[<?php echo $idObj; ?>] = new ymaps.Placemark([<?php echo $item[2]; ?>, <?php echo $item[1]; ?>], {
		balloonContent: '<div class="image" style="width: 59px; float: left; border: 1px solid #ff471a;"><img style="margin: 3px; width: 51px;" src="<?php echo $item[6]; ?>" alt="img"></div>' +
		'<div class="text" style="width: calc(100% - 59px); float: left; padding-left: 10px; font-size: 12px; color: #2a2929;">' +
		'<a style="color: #ff471a; margin-bottom: 5px;" class="title" href="<?php echo $item[4]; ?>"><?php echo $item[3]; ?></a>' +
		'Адрес: <?php echo $item[0]; ?>' +
		<?php if($item[5]) { ?>
		'<br>Телефон: <?php echo $item[5]; ?>' +
		<?php } ?>
		'</div>'
	} , {
	    iconLayout: 'default#image',
	    iconImageHref: '<?php echo SITE_TEMPLATE_PATH; ?>/images/bal-4.png',
	    iconImageSize: [29, 39],
	    iconImageOffset: [-14, -40]
	});
	<?php } ?>
	clusterer.add(geoObjects);
	myMap.geoObjects.add(clusterer);

    /*myMap.setBounds(clusterer.getBounds(), {
        checkZoomRange: true
    });*/
}
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>