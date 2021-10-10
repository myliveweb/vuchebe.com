<?php
global $dbh;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$arrPlan = array();
$arSelectPlan = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PLAN_ID");
$arFilterPlan = array("IBLOCK_ID" => array(38, 44), "ACTIVE" => "Y");
$resPlan = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilterPlan, false, false, $arSelectPlan);
while($rowPlan = $resPlan->GetNext()) {

    if($rowPlan['IBLOCK_ID'] == 38)
        $tarifPlan = 34;
    else
        $tarifPlan = 35;
    $arrPlan[$tarifPlan][$rowPlan['PROPERTY_PLAN_ID_VALUE']] = $rowPlan['NAME'];
}

$cnt = 10;

$outArray = array();

$arrFilter = array('all' => 0, 'start' => 0, 'stop' => 0, 'moderate' => 0, 'otklon' => 0, 'finich' => 0);

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE");

$arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "!PROPERTY_DELETE" => "Y", "PROPERTY_OWNER" => $user_id); //

$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
while($obRes = $res->GetNextElement()) {

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['PROPERTY_PLAN_VALUE'] = $props['PLAN']['VALUE'];
    $row['PROPERTY_PLAN_TAX_VALUE'] = $props['PLAN_TAX']['VALUE'];
    $row['PROPERTY_URL_VALUE'] = $props['URL']['VALUE'];
    $row['PROPERTY_COUNTER_VALUE'] = $props['COUNTER']['VALUE'];
    $row['PROPERTY_LIMIT_VALUE'] = $props['LIMIT']['VALUE'];
    $row['PROPERTY_CLICK_VALUE'] = $props['CLICK']['VALUE'];
    $row['PROPERTY_HIDE_VALUE'] = $props['HIDE']['VALUE'];
    $row['PROPERTY_REJECTED_VALUE'] = $props['REJECTED']['VALUE'];
    $row['PROPERTY_REASON_VALUE'] = $props['REASON']['VALUE'];
    $row['PROPERTY_LAUNCHED_VALUE'] = $props['LAUNCHED']['VALUE'];
    $row['PROPERTY_MODERATION_VALUE'] = $props['MODERATION']['VALUE'];

    $row['LIMIT_PROMO']  = $props['LIMIT_PROMO']['VALUE'];
    $row['LIMIT_CURENT'] = $props['LIMIT_CURENT']['VALUE'];

    $row['PROMOCODE'] = $props['PROMOCODE']['VALUE'];
    $row['DISCOUNT']  = $props['DISCOUNT']['VALUE'];

    if($row['PROMOCODE']) {
        $row['STRPROMOCODE'] = 'Промокод: ' . $row['PROMOCODE'] . ' (скидка ' . $row['DISCOUNT'] . '%';
        if($row['LIMIT_PROMO']) {
            $row['STRPROMOCODE'] .= ' на ' . $row['LIMIT_PROMO'] . ' показов, осталось ' . $row['LIMIT_CURENT'];
        }
        $row['STRPROMOCODE'] .= ')';
    }

    if(sizeof($outArray) < $cnt) {

        list($dateFormat) = explode(' ', $row["DATE_CREATE"]);
        $row["DATE_FORMAT"] = $dateFormat;

        if ($row["IBLOCK_ID"] == 34) {
            $row["REPEAT"] = '/user/' . $user_id . '/topbanner/' . $row["ID"] . '/';
        } elseif ($row["IBLOCK_ID"] == 35) {
            $row["REPEAT"] = '/user/' . $user_id . '/sidebanner/' . $row["ID"] . '/';
        }

        $row["PIC"] = CFile::GetPath($row["PREVIEW_PICTURE"]);

        if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] == 'Y') {
            $row['STATUS_NAME'] = 'Активен';
            $row['STATUS_STYLE'] = 'color: green;';
        }

        if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] != 'Y') {
            $row['STATUS_NAME'] = 'Остановлен';
            $row['STATUS_STYLE'] = 'color: #9f9f9f;';
        }

        if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y') {
            $row['STATUS_NAME'] = 'На модерации';
            $row['STATUS_STYLE'] = 'color: #9f9f9f;';
        }

        if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] == 'Y') {
            $row['STATUS_NAME'] = 'Отклонён';
            $row['STATUS_STYLE'] = 'color: red;';
        }

        if ($row['PROPERTY_COUNTER_VALUE'] >= $row['PROPERTY_LIMIT_VALUE']) {
            $row['STATUS_NAME'] = 'Завершён';
            $row['STATUS_STYLE'] = 'color: #000000;';
        }

        $outArray[] = $row;
    }

    $arrFilter['all']++;

    if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] == 'Y') {
        $arrFilter['start']++;
    }

    if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] != 'Y' && $row['PROPERTY_COUNTER_VALUE'] < $row['PROPERTY_LIMIT_VALUE']) {
        $arrFilter['stop']++;
    }

    if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y') {
        $arrFilter['moderate']++;
    }

    if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] == 'Y') {
        $arrFilter['otklon']++;
    }

    if ($row['PROPERTY_COUNTER_VALUE'] >= $row['PROPERTY_LIMIT_VALUE']) {
        $arrFilter['finich']++;
    }
}
?>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<style>
#page .js-order-start {
    padding: 0;
    width: 100%;
}
#page .js-order-start.active span::before,
#page .js-order-start:hover span::before {
    border-color: #ff471a;
}
#page .js-order-start:active {
    color: #ff471a;
    background: #fff;
    box-shadow: none;
    box-shadow: 0 0 13px #999 inset;
}
#page .js-order-start.active:hover {
    color: ffffff;
    box-shadow: none;
}
#page .js-order-start.active:hover span {
    text-decoration: none;
    color: #ffffff;
}
.display-name {
	color: #000 !important;
	cursor: pointer;
	text-decoration-color: #ff471a;
}
.display-name span {
	color: #ff471a;
}
.m-header .filter {
  color: #ff471a;
  line-height: 1.5;
}
.m-header .filter.color-silver {
	color: #9f9f9f;
	text-decoration: none;
	cursor: default;
}

#page .news-item .params-banner-top {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-top: 10px;
}
#page .news-item .params-banner {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-top: 5px;
}

#page .news-item .color-silver {
	color: #9f9f9f;
  cursor: pointer;
  border-bottom: 1px dashed #9f9f9f;
  margin-right: 15px;
}
#page .error-start-stop {
    color: red;
    margin-top: 10px;
    font-size: 13px;
    text-align: center;
    display: none;
}
</style>
<script>
var startFromListOrders = 1;
var cnt = <?php echo $cnt; ?>;
</script>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>

<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Мои заказы</span>
	</div><br>
	<div class="page-content" id="page">
		<div class="name-block text-center txt-up" style="margin: 0 0 15px;"><span>Мои заказы</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="m-header">
					<a href="#" data-filter="all" class="filter js-orders-list color-silver">все(<?php echo $arrFilter['all']; ?>)</a> &nbsp;
					<a href="#" data-filter="start" class="filter js-orders-list">активные(<span class="js-start"><?php echo $arrFilter['start']; ?></span>)</a> &nbsp;
					<a href="#" data-filter="stop" class="filter js-orders-list">остановленные(<span class="js-stop"><?php echo $arrFilter['stop']; ?></span>)</a> &nbsp;
					<a href="#" data-filter="moderate" class="filter js-orders-list">на модерации(<?php echo $arrFilter['moderate']; ?>)</a> &nbsp;
					<a href="#" data-filter="otklon" class="filter js-orders-list">отклонённые(<?php echo $arrFilter['otklon']; ?>)</a> &nbsp;
					<a href="#" data-filter="finich" class="filter js-orders-list">завершённые(<?php echo $arrFilter['finich']; ?>)</a> &nbsp;
				</div>
				<div class="line-orders all">
        <?php
        foreach($outArray as $arrData) {
        ?>
        <div class="news-item" data-id="<?php echo $arrData['ID']; ?>">
          <div class="col-3 width-sm content-left" style="padding: 0;">
            <div class="image left brd" style="width: 100%;">
              <img style="width: 100%;" src="<?=$arrData["PIC"]?>" alt="<?=$arrData["NAME"]?>" title="<?=$arrData["NAME"]?>" />
            </div>
            <div class="btns" style="margin-top: 10px;">
              <a href="#" class="button js-order-start<?php if($arrData['PROPERTY_MODERATION_VALUE'] !== 'Y') echo ' no-moderate'; ?><?php if($arrData['PROPERTY_LAUNCHED_VALUE'] === 'Y') echo ' active'; ?>" data-id="<?php echo $arrData['ID']; ?>" data-status="<?php if($arrData['PROPERTY_LAUNCHED_VALUE'] === 'Y') { echo '1'; } else { echo '0'; } ?>">
                <span style="font-size: 18px; text-decoration-color: #ff4719;">Пуск / Стоп</span>
              </a>
              <div class="error-start-stop">Недостаточно средств</div>
            </div>
          </div>
          <div class="col-9 width-sm content-right">
            <div class="news-name">
              Заказ №<?php echo $arrData["ID"]; ?> от <?php echo $arrData["DATE_FORMAT"]; ?>
            </div>
              <?php
              $planTax = 0;
              if($arrData["PROPERTY_PLAN_TAX_VALUE"] > 0) {
                  $planTax = round($arrData["PROPERTY_PLAN_TAX_VALUE"], 2);
              }
              ?>
            <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
              <?php if($arrData["PROPERTY_REJECTED_VALUE"] == 'Y') { ?>
              <div class="params-banner-top" style="white-space: normal; margin: 15px 0px 15px 0px;">Причина отказа:<br/><?php echo $arrData["PROPERTY_REASON_VALUE"]; ?></div>
              <?php } ?>
                <div class="params-banner-top">Статус заказа: <span style="<?php echo $arrData["STATUS_STYLE"]; ?>"><?php echo $arrData["STATUS_NAME"]; ?></span></div>
                <div class="params-banner">Название баннера: <?php echo $arrData["NAME"]; ?></div>
                <div class="params-banner">Ссылка: <a href="<?php echo $arrData["PROPERTY_URL_VALUE"]; ?>" target="blank"><?php echo $arrData["PROPERTY_URL_VALUE"]; ?></a></div>
                <div class="params-banner">Количество показов: <?php echo ($arrData["PROPERTY_COUNTER_VALUE"] ? $arrData["PROPERTY_COUNTER_VALUE"] : '0') ?> (из <?php echo ($arrData["PROPERTY_LIMIT_VALUE"] ? $arrData["PROPERTY_LIMIT_VALUE"] : '0') ?>)</div>
                <div class="params-banner">Количество переходов: <?php echo ($arrData["PROPERTY_CLICK_VALUE"] ? $arrData["PROPERTY_CLICK_VALUE"] : '0') ?></div>
                <div class="params-banner">Баннер скрыли: <?php echo ($arrData["PROPERTY_HIDE_VALUE"] ? $arrData["PROPERTY_HIDE_VALUE"] : '0') ?></div>
                <div class="params-banner">Тариф: <a href="#" data-type="<?php if($arrData["IBLOCK_ID"] == 34) { echo 'top'; } else { echo 'side'; } ?>" data-tarif="<?php echo $arrData["PROPERTY_PLAN_VALUE"]; ?>" class="color-silver js-tarif"><?php echo $arrPlan[$arrData["IBLOCK_ID"]][$arrData["PROPERTY_PLAN_VALUE"]]; ?></a></div>
                <div class="params-banner">Стоимость показа: <?php echo $planTax; ?> руб.</div>
                <?php if($arrData['PROMOCODE']) { ?>
                    <div class="params-banner"><?php echo $arrData['STRPROMOCODE']; ?></div>
                <?php } ?>
                <div class="params-banner-top">
                <a href="<?php echo $arrData["REPEAT"]; ?>" class="color-silver">Повторить заказ</a>
                <a class="color-silver js-info-order" data-id="<?php echo $arrData["ID"]; ?>">Детализация заказа</a>
                    <a class="color-silver js-delete-order" data-id="<?php echo $arrData["ID"]; ?>">Удалить заказ</a>
              </div>
            </div>
          </div>
        </div>
        <?php
        }
        ?>
        </div>
        <div class="line-orders start" style="display: none;"></div>
        <div class="line-orders stop" style="display: none;"></div>
        <div class="line-orders moderate" style="display: none;"></div>
        <div class="line-orders otklon" style="display: none;"></div>
        <div class="line-orders finich" style="display: none;"></div>
        </div>
			 <!-- st-news -->
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>