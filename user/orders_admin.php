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

$input = filter_input_array(INPUT_POST);
$search = $input['s'];

$cnt = 10;

$outArray = array();

$arrFilter = array('all' => 0, 'start' => 0, 'stop' => 0, 'new' => 0, 'otklon' => 0, 'finich' => 0);

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE");

$arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y");

if($search) {
    $arFilter['ID'] = $search;
}

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
while($obRes = $res->GetNextElement()) {

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['PROPERTY_OWNER_VALUE']        = $props['OWNER']['VALUE'];
    $row['PROPERTY_PLAN_VALUE']         = $props['PLAN']['VALUE'];
    $row['PROPERTY_PLAN_TAX_VALUE']     = $props['PLAN_TAX']['VALUE'];
    $row['PROPERTY_URL_VALUE']          = $props['URL']['VALUE'];
    $row['PROPERTY_COUNTER_VALUE']      = $props['COUNTER']['VALUE'];
    $row['PROPERTY_LIMIT_VALUE']        = $props['LIMIT']['VALUE'];
    $row['PROPERTY_CLICK_VALUE']        = $props['CLICK']['VALUE'];
    $row['PROPERTY_HIDE_VALUE']         = $props['HIDE']['VALUE'];
    $row['PROPERTY_REJECTED_VALUE']     = $props['REJECTED']['VALUE'];
    $row['PROPERTY_REASON_VALUE']       = $props['REASON']['VALUE'];
    $row['PROPERTY_LAUNCHED_VALUE']     = $props['LAUNCHED']['VALUE'];
    $row['PROPERTY_MODERATION_VALUE']   = $props['MODERATION']['VALUE'];
    $row['PROPERTY_DELETE_VALUE']       = $props['DELETE']['VALUE'];

    $row['LIMIT_PROMO']  = $props['LIMIT_PROMO']['VALUE'];
    $row['LIMIT_CURENT'] = $props['LIMIT_CURENT']['VALUE'];

    $row['PROMOCODE'] = $props['PROMOCODE']['VALUE'];
    $row['DISCOUNT']  = $props['DISCOUNT']['VALUE'];

    if($row['PROMOCODE']) {
        $row['STRPROMOCODE'] = '????????????????: ' . $row['PROMOCODE'] . ' (???????????? ' . $row['DISCOUNT'] . '%';
        if($row['LIMIT_PROMO']) {
            $row['STRPROMOCODE'] .= ' ???? ' . $row['LIMIT_PROMO'] . ' ??????????????, ???????????????? ' . $row['LIMIT_CURENT'];
        }
        $row['STRPROMOCODE'] .= ')';
    }

    $row['TICKET'] = $props['TICKET']['VALUE'];

    if($row['TICKET']) {
        $arrChat = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $row['TICKET'] . ' ORDER BY id DESC')->fetch();
        if($arrChat['del_owner']) {
            $row['TICKET_COLOR'] = 'red';
        } else {
            $row['TICKET_COLOR'] = 'green';
        }
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

        if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_DELETE_VALUE'] != 'Y') {
            $row['STATUS_NAME'] = '???? ??????????????????';
            $row['STATUS_STYLE'] = 'color: #9f9f9f;';
        }

        if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] == 'Y' && $row['PROPERTY_DELETE_VALUE'] != 'Y' && !$row['STATUS_NAME']) {
            $row['STATUS_NAME'] = '??????????????';
            $row['STATUS_STYLE'] = 'color: green;';
        }

        if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] != 'Y' && $row['PROPERTY_DELETE_VALUE'] != 'Y' && !$row['STATUS_NAME']) {
            $row['STATUS_NAME'] = '????????????????????';
            $row['STATUS_STYLE'] = 'color: #9f9f9f;';
        }

        if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] == 'Y' && $row['PROPERTY_DELETE_VALUE'] != 'Y' && !$row['STATUS_NAME']) {
            $row['STATUS_NAME'] = '????????????????';
            $row['STATUS_STYLE'] = 'color: red;';
        }

        if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_COUNTER_VALUE'] >= $row['PROPERTY_LIMIT_VALUE'] && $row['PROPERTY_DELETE_VALUE'] != 'Y') {
            $row['STATUS_NAME'] = '????????????????';
            $row['STATUS_STYLE'] = 'color: #000000;';
        }

        if ($row['PROPERTY_DELETE_VALUE'] == 'Y') {
            $row['STATUS_NAME'] = '????????????';
            $row['STATUS_STYLE'] = 'color: red;';
        }

        if($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_DELETE_VALUE'] != 'Y') {
            $outArray[] = $row;
        } elseif($search) {
            $outArray[] = $row;
        }
    }

    $arrFilter['all']++;

    if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] == 'Y' && $row['PROPERTY_DELETE_VALUE'] != 'Y') {
        $arrFilter['start']++;
    }

    if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_LAUNCHED_VALUE'] != 'Y' && $row['PROPERTY_COUNTER_VALUE'] < $row['PROPERTY_LIMIT_VALUE'] && $row['PROPERTY_DELETE_VALUE'] != 'Y') {
        $arrFilter['stop']++;
    }

    if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_DELETE_VALUE'] != 'Y') {
        $arrFilter['new']++;
    }

    if ($row['PROPERTY_MODERATION_VALUE'] != 'Y' && $row['PROPERTY_REJECTED_VALUE'] == 'Y' && $row['PROPERTY_DELETE_VALUE'] != 'Y') {
        $arrFilter['otklon']++;
    }

    if ($row['PROPERTY_MODERATION_VALUE'] == 'Y' && $row['PROPERTY_REJECTED_VALUE'] != 'Y' && $row['PROPERTY_COUNTER_VALUE'] >= $row['PROPERTY_LIMIT_VALUE'] && $row['PROPERTY_DELETE_VALUE'] != 'Y') {
        $arrFilter['finich']++;
    }
}

$arrRejected = array();
$arSelectRejected = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_TEXT");
$arFilterRejected = array("IBLOCK_ID" => 46, "ACTIVE" => "Y");
$resRejected = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilterRejected, false, false, $arSelectRejected);
while($rowRejected = $resRejected->GetNext()) {
    $arrRejected[] = $rowRejected;
}
?>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/orders_admin.css">
<script>
var startFromListOrdersAdmin = 1;
var cnt = <?php echo $cnt; ?>;
const rejected = [];
<?php
foreach($arrRejected as $itemRejected) {
    $title = str_replace("<br />", "", $itemRejected['PREVIEW_TEXT']);
    echo "rejected[" . $itemRejected['ID'] . "] = {id: `" . $itemRejected['ID'] ."`, name: `" . $itemRejected['NAME'] ."`, title: `" . $title ." `};\n";
}
?>
</script>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>
<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">??????????????</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">??????????????</a> <i class="fa fa-angle-double-right color-orange"></i> <span>????????????</span>
	</div><br>
    <div class="page-content st-content-users" id="page">
        <!-- ?????????? -->
        <div class="structure-cat bg-silver text-center" style="margin: 15px 0 20px;">
            <div class="row-line">
                <form id="form-search" method="post" accept-charset="utf-8">
                    <div class="col-10 search-filed" style="padding: 0 0 0 15px;">
                        <input type="text" name="s" id="search-order" value="<?php echo $search; ?>" placeholder="?????????????? ??? ????????????" />
                        <input type="hidden" name="p" value="1" />
                        <input type="hidden" name="filter" id="filterinput" value="<?php echo $filter; ?>" />
                    </div>
                    <div class="col-2 button-filed">
                        <button type="submit" style="line-height: 30px; width: 100%;">
                            <span class="short"><i class="fa fa-search"></i></span>
                            <span class="full">??????????</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End ?????????? -->
		<div class="name-block text-center txt-up" style="margin: 0 0 15px;"><span>????????????</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
                <?php if(!$search) { ?>
				<div class="m-header">
                    <a href="#" data-filter="new" class="filter js-orders-list-admin color-silver">??????????(<span class="js-new"><?php echo $arrFilter['new']; ?></span>)</a>
					<a href="#" data-filter="start" class="filter js-orders-list-admin">????????????????(<span class="js-start"><?php echo $arrFilter['start']; ?></span>)</a>
					<a href="#" data-filter="stop" class="filter js-orders-list-admin">??????????????????????????(<span class="js-stop"><?php echo $arrFilter['stop']; ?></span>)</a>
					<a href="#" data-filter="otklon" class="filter js-orders-list-admin">??????????????????????(<span class="js-rej"><?php echo $arrFilter['otklon']; ?></span>)</a>
					<a href="#" data-filter="finich" class="filter js-orders-list-admin">??????????????????????(<?php echo $arrFilter['finich']; ?>)</a>
                    <a href="#" data-filter="all" class="filter js-orders-list-admin">??????(<?php echo $arrFilter['all']; ?>)</a>
				</div>
                <?php } else { ?>
                    <div class="search-result">
                        <div class="result-left">??????????????: <?php echo sizeof($outArray); ?></div>
                        <div class="result-right"><a href="/user/<?php echo $url; ?>/control/">?????????? ?? ???????????? ??????????????</a></div>
                    </div>
                <?php } ?>
            <div class="line-orders new">
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
                <span style="font-size: 18px; text-decoration-color: #ff4719;">???????? / ????????</span>
              </a>
              <div class="error-start-stop">???????????????????????? ??????????????</div>
            </div>
          </div>
          <div class="col-9 width-sm content-right">
            <div class="news-name">
              ?????????? ???<?php echo $arrData["ID"]; ?> ???? <?php echo $arrData["DATE_FORMAT"]; ?>
            </div>
              <?php
              $planTax = 0;
              if($arrData["PROPERTY_PLAN_TAX_VALUE"] > 0) {
                  $planTax = round($arrData["PROPERTY_PLAN_TAX_VALUE"], 2);
              }
              ?>
            <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
              <?php if($arrData["PROPERTY_REJECTED_VALUE"] == 'Y') { ?>
              <div class="params-banner-top" style="white-space: normal; margin: 15px 0px 15px 0px;">?????????????? ????????????:<div style="margin-top: 10px;"><?php echo $arrData["PROPERTY_REASON_VALUE"]; ?></div></div>
              <?php } ?>
                <div class="params-banner-top">???????????? ????????????: <span style="<?php echo $arrData["STATUS_STYLE"]; ?>"><?php echo $arrData["STATUS_NAME"]; ?></span></div>
                <div class="params-banner">???????????????? ??????????????: <?php echo $arrData["NAME"]; ?></div>
                <div class="params-banner">????????????: <a href="<?php echo $arrData["PROPERTY_URL_VALUE"]; ?>" target="blank"><?php echo $arrData["PROPERTY_URL_VALUE"]; ?></a></div>
                <div class="params-banner">???????????????????? ??????????????: <?php echo ($arrData["PROPERTY_COUNTER_VALUE"] ? $arrData["PROPERTY_COUNTER_VALUE"] : '0') ?> (???? <?php echo ($arrData["PROPERTY_LIMIT_VALUE"] ? $arrData["PROPERTY_LIMIT_VALUE"] : '0') ?>)</div>
                <div class="params-banner">???????????????????? ??????????????????: <?php echo ($arrData["PROPERTY_CLICK_VALUE"] ? $arrData["PROPERTY_CLICK_VALUE"] : '0') ?></div>
                <div class="params-banner">???????????? ????????????: <?php echo ($arrData["PROPERTY_HIDE_VALUE"] ? $arrData["PROPERTY_HIDE_VALUE"] : '0') ?></div>
                <div class="params-banner">??????????: <a href="#" data-type="<?php if($arrData["IBLOCK_ID"] == 34) { echo 'top'; } else { echo 'side'; } ?>" data-tarif="<?php echo $arrData["PROPERTY_PLAN_VALUE"]; ?>" class="color-silver js-tarif"><?php echo $arrPlan[$arrData["IBLOCK_ID"]][$arrData["PROPERTY_PLAN_VALUE"]]; ?></a></div>
                <div class="params-banner">?????????????????? ????????????: <?php echo $planTax; ?> ??????.</div>
                <?php if($arrData['PROMOCODE']) { ?>
                    <div class="params-banner"><?php echo $arrData['STRPROMOCODE']; ?></div>
                <?php } ?>
                <div class="more-info" style="display: none;"></div>
                <div class="params-banner col-12" style="margin-top: 5px; text-align: right;">
                    <a class="color-silver js-more-info" data-id="<?php echo $arrData["ID"]; ?>">??????????????????</a>
                </div>
            </div>
          </div>
        <div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">
            <a class="color-silver js-push-order" data-id="<?php echo $arrData["ID"]; ?>">?????????????????????? ??????????</a>
            <a class="color-silver js-reject-order" data-id="<?php echo $arrData["ID"]; ?>">?????????????????? ??????????</a>
            <a class="color-silver js-info-order" data-id="<?php echo $arrData["ID"]; ?>">?????????????????????? ????????????</a>
            <?php if($arrData['TICKET']) { ?>
            <a href="/user/support/<?php echo $arrData['TICKET']; ?>/" class="color-silver" target="_blank" style="color: <?php echo $arrData['TICKET_COLOR']; ?>;">?????????? ???<?php echo $arrData['TICKET']; ?></a>
            <?php } else { ?>
            <a class="color-silver js-new-chat" data-id="<?php echo $arrData["ID"]; ?>">?????????? ????????????</a>
            <?php } ?>
        </div>
        <div class="params-banner-top col-12 textarea" style="margin-top: 15px; padding: 0; display: none;">
            <div>?????????????? ????????????:</div>
            <textarea style="width: 100%; height: 100px; margin-top: 7px;"></textarea>
            <div class="col-12" style="text-align: left; padding: 0; margin-top: 10px;">
                <?php
                foreach($arrRejected as $itemRejected) {
                ?>
                    <a class="fast-touch" data-id="<?php echo $itemRejected['ID']; ?>"><?php echo $itemRejected['NAME']; ?></a>
                <?php
                }
                ?>
            </div>
            <div class="col-12" style="text-align: right; padding: 0; margin-top: 10px;">
                <button type="submit" class="add-block js-moderate-bad" data-id="<?php echo $arrData["ID"]; ?>"><span>??????????????????</span></button>
                <a style="margin-left: 15px; text-decoration: none;" class="color-silver cancel-block">????????????</a>
            </div>

        </div>
        </div>
        <?php
        }
        ?>
        </div>
        <div class="line-orders start" style="display: none;"></div>
        <div class="line-orders stop" style="display: none;"></div>
        <div class="line-orders otklon" style="display: none;"></div>
        <div class="line-orders finich" style="display: none;"></div>
        <div class="line-orders all" style="display: none;"></div>
        </div>
			 <!-- st-news -->
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>