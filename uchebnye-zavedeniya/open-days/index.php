<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Дни открытых дверей");
$current_razdel = 'open-days';
require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_uchebnye-zavedeniya.php');

CModule::IncludeModule('iblock');
$arrType = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] > $b['sort']) ? -1 : 1;
}
?>

<script>
var curList = '<?php echo $current_razdel; ?>';
var startFromList = 1;
var cnt = 20;
</script>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<style>
.hide-block {
	display: none;
}
.crop-height {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow-y: hidden;
}
.crop-height-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow-y: hidden;
}
</style>
<?php
$arrNews = array();
$arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_LOGO");

if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
	$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
	$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
else
	$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);

$resCarusel = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
while($rowCarusel = $resCarusel->Fetch()) {

	if($rowCarusel["PROPERTY_LOGO_VALUE"]):
		$srcLogo = CFile::GetPath($rowCarusel["PROPERTY_LOGO_VALUE"]);
	elseif($rowCarusel["PREVIEW_PICTURE"]):
		$srcLogo = CFile::GetPath($rowCarusel["PREVIEW_PICTURE"]);
	elseif($rowCarusel["DETAIL_PICTURE"]):
		$srcLogo = CFile::GetPath($rowCarusel["DETAIL_PICTURE"]);
	else:
		$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
	endif;

	$rowCarusel["TYPE"]  = $arrType[$rowCarusel["IBLOCK_ID"]];

	$url = '/uchebnye-zavedeniya/' . $rowCarusel["TYPE"] . '/' . $rowCarusel["CODE"] . '/?sect=opendoor';

	$idOd = 0;
	$resEvents = CIBlockElement::GetProperty($rowCarusel["IBLOCK_ID"], $rowCarusel["ID"], "sort", "asc", array("CODE" => "OPENDOOR"));
	while ($obEvents = $resEvents->GetNext()) {

		$tempEvents = explode('#', $obEvents['VALUE']);

		if(!$tempEvents[0])
			continue;

		$rowCarusel["DATA"] = $tempEvents;
		$rowCarusel["ID_OPENDOOR"]  = $tempEvents[12];
		$rowCarusel["NAME_OPENDOOR"]  = $tempEvents[0];
		$rowCarusel["IMG"]  = $srcLogo;
		$rowCarusel["URL"]  = $url;

		$fullTime = $tempEvents[1] . ' ' . $tempEvents[2];
		$strDate = get_str_time_post(strtotime($fullTime));
		$rowCarusel["FORMAT_DATE"] = $strDate;
		$curDate = explode(' ', $strDate);
		$rowCarusel["DAY"] = $curDate[0];
		$rowCarusel["MONTH"] = $curDate[1];

		$rowCarusel["sort"] = strtotime($fullTime);

		$arrNews[] = $rowCarusel;
		$idOd++;
	}
}

usort($arrNews, "cmp");

$val_item = array();
$cntArr = sizeof($arrNews);

if($cntArr > 20)
	$cntArr = 20;

if($cntArr) {
	for($n = 0; $n < $cntArr; $n++) {
		$val_item[] = $arrNews[$n];
	}
}

?>
<div class="st-content-right">
	<div class="breadcrumbs">
	<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/uchebnye-zavedeniya/">Учебные заведения</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Дни открытых дверей</span>
	</div>
	<div class="page-content" id="page">
		<div class="name-block text-center txt-up"><span>Дни открытых дверей</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="m-header">
					<a href="#" data-filter="all" class="filter color-silver js-vuz-list">все</a> &nbsp;
					<a href="#" data-filter="universities" class="filter js-vuz-list">вузы</a> &nbsp;
					<a href="#" data-filter="colleges" class="filter js-vuz-list">колледжи</a> &nbsp;
					<a href="#" data-filter="schools" class="filter js-vuz-list">школы</a> &nbsp;
					<a href="#" data-filter="language-class" class="filter js-vuz-list">языковые курсы</a> &nbsp;
				</div>
				<div class="line" id="all">
				<?php
				$go = 1;
				$cur_time = time();
				$tuday = 1;

				foreach($val_item as $ts => $item) {
					$arrItem = $item["DATA"];
				?>
				<?php
				if($cur_time > $item['sort'] && $tuday) {
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
				<div class="news-item open-days" data-id="<?php echo $item['ID']; ?>" data-ukey="<?php echo $item['ID_OPENDOOR']; ?>">
					<div class="right">
						<div class="date-ico" style="margin-bottom: 10px;"><span><?php echo $item["DAY"]; ?></span><?php echo $item["MONTH"]; ?></div>
						<? if($arrItem[4]) { ?>
						<div class="btns text-right">
							<a href="/map/?map=<?php echo $item["ID"]; ?>&opendoor=<?php echo $item["ID_OPENDOOR"]; ?>" class="button">
								<span style="font-family: Verdana;">на карте</span>
							</a>
						</div>
						<? } ?>
					</div>
					<div class="image left brd">
						<a href="<?php echo $item["URL"]; ?>">
							<img style="width: 111px;" src="<?php echo $item["IMG"]; ?>" alt="<?php echo $item["NAME"]; ?>" title="<?php echo $item["NAME"]; ?>">
						</a>
					</div>
					<div class="date" style="margin-bottom: 7px;"><?php echo $item["FORMAT_DATE"]; if($cur_time > $item['sort']) { echo ' (событие уже прошло)'; } ?></div>
					<div class="news-name">
						<? if($arrItem[6]) { ?>
						<a href="<?php echo $item["URL"]; ?>" target="blank"><span><?php echo $item["NAME_OPENDOOR"]; ?></span></a>
						<? } else { ?>
						<span><?php echo $item["NAME_OPENDOOR"]; ?></span>
						<? } ?>
					</div>
					<p>
					<?php
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
				<?php
				}
				?>
				</div>
				<div class="line" id="universities" style="display: none;"></div>
				<div class="line" id="colleges" style="display: none;"></div>
				<div class="line" id="schools" style="display: none;"></div>
				<div class="line" id="language-class" style="display: none;"></div>
			</div>
			 <!-- st-news -->
		</div>
		 <!-- st-content-bottom -->
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>