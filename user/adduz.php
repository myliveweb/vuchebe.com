<?php
global $dbh;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$input = filter_input_array(INPUT_POST);
$search = $input['s'];

$cnt = 10;

$outArray = array();

$arrFilter = array('new' => 0, 'pending' => 0, 'add' => 0, 'del' => 0, 'all' => 0);

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE");

if($search) {
    $arFilter = array(
        "IBLOCK_ID" => 50,
        array(
            "LOGIC" => "OR",
            array("NAME" => $search . '%'),
            array("PROPERTY_SITE" => $search . '%'),
            array("PROPERTY_PHONE" => $search . '%')
        ));
} else {
    $arFilter = array("IBLOCK_ID" => 50);
}

$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
while($obRes = $res->GetNextElement()) {

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['COUNTRY'] = $props['COUNTRY']['VALUE'];
    $row['REGION']  = $props['REGION']['VALUE'];
    $row['CITY']    = $props['CITY']['VALUE'];

    $row['COUNTRY_ID'] = $props['COUNTRY_ID']['VALUE'];
    $row['REGION_ID']  = $props['REGION_ID']['VALUE'];
    $row['CITY_ID']    = $props['CITY_ID']['VALUE'];

    $row['ADRESS'] = $props['ADRESS']['VALUE'];
    $row['PHONE']  = $props['PHONE']['VALUE'];
    $row['EMAIL']  = $props['EMAIL']['VALUE'];
    $row['SITE']   = $props['SITE']['VALUE'];

    $row['PENDING'] = $props['PENDING']['VALUE'];
    $row['ADD']     = $props['ADD']['VALUE'];
    $row['DEL']     = $props['DEL']['VALUE'];

    $row['TYPE']   = $props['TYPE']['VALUE'];
    $row['TICKET'] = $props['TICKET']['VALUE'];
    $row['AUTHOR'] = $props['AUTHOR']['VALUE'];

    $row['UZ_ID'] = $props['UZ_ID']['VALUE'];

    if($row['TICKET']) {
        $arrChat = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $row['TICKET'] . ' ORDER BY id DESC')->fetch();
        if($arrChat['del_owner']) {
            $row['TICKET_COLOR'] = 'red';
        } else {
            $row['TICKET_COLOR'] = 'green';
        }
    }

    if((sizeof($outArray) < $cnt && $row['PENDING'] != 'Y' && $row['ADD'] != 'Y' && $row['DEL'] != 'Y') || $search) {

        list($dateFormat, $timeFormat) = explode(' ', $row["DATE_CREATE"]);
        list($hoursFormat, $minFormat) = explode(':', $timeFormat);

        $row["DATE_FORMAT"] = $dateFormat . ' (' . $hoursFormat . '.' . $minFormat .  ')';

        $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $row['AUTHOR'])->fetch();
        if($arrTeacher['cnt'] > 0) {
            $row['TEACHER'] = 1;
        } else {
            $row['TEACHER'] = 0;
        }

        if($row['AUTHOR']) {
            $rsAuthorData = CUser::GetByID($row['AUTHOR']);
            $authorData = $rsAuthorData->Fetch();

            $row["URL"] = getUserUrl($authorData);

            if (strlen(trim($authorData['NAME']))) {
                $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['NAME']), 1);
                if ($authorData['SECOND_NAME']) {
                    $format_name .= ' ';
                    $format_name .= '<span>' . strtoupper(mb_substr($authorData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($authorData['SECOND_NAME'], 1);
                }
                if ($authorData['LAST_NAME']) {
                    $format_name .= ' ';
                    $format_name .= '<span>' . strtoupper(mb_substr(trim($authorData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LAST_NAME']), 1);
                }
            } else {
                $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LOGIN']), 1);
            }

            $row['FORMAT_NAME'] = $format_name;

            $row["PHOTO"] = $authorData['PERSONAL_PHOTO'];

            if ($authorData['PERSONAL_PHOTO']) {
                $row["PIC"] = CFile::GetPath($authorData['PERSONAL_PHOTO']);
            } else {
                $row["PIC"] = SITE_TEMPLATE_PATH . "/images/user-1.png";
            }
        }

        $row["TICKET_CNT"] = 0;
        $arFilterTicket = Array("IBLOCK_ID"=>50, "!PROPERTY_TICKET" => false);
        $cntTicket = CIBlockElement::GetList(false, $arFilterTicket, array('IBLOCK_ID'))->Fetch()['CNT'];
        if($cntTicket) {
            $row["TICKET_CNT"] = $cntTicket;
        }

        $outArray[] = $row;
    }

    $arrFilter['all']++;

    if ($row['PENDING'] != 'Y' && $row['ADD'] != 'Y' && $row['DEL'] != 'Y') {
        $arrFilter['new']++;
    }

    if ($row['PENDING'] == 'Y') {
        $arrFilter['pending']++;
    }

    if ($row['ADD'] == 'Y') {
        $arrFilter['add']++;
    }

    if ($row['DEL'] == 'Y') {
        $arrFilter['del']++;
    }
}

?>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/spam.css">
<script>
var startFromListAdmin = 1;
var cnt = <?php echo $cnt; ?>;
</script>
<style>
#form-check .auto-complit .item div.check {
    position: relative;
    top: -4px;
    float: right;
    color: #9f9f9f;
}
</style>
<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>
<div class="st-content-right">
<div class="breadcrumbs">
<a href="/">??????????????</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">??????????????</a> <i class="fa fa-angle-double-right color-orange"></i> <span>?????????? ?????????????? ??????????????????</span>
</div><br>
<div class="page-content st-content-users" id="page">
<!-- ?????????? -->
<div class="structure-cat bg-silver text-center" style="margin: 15px 0 20px;">
<div class="row-line">
    <form id="form-check" method="post" accept-charset="utf-8">
        <div class="col-10 search-filed" style="padding: 0 0 0 15px;">
            <input class="js-add-check" style="color: black;" type="text" name="s" id="search-avatar" value="<?php echo $search; ?>" placeholder="?????????????? ???????????????? ?????? ?????????? ?????????? ?????? ?????????? ????????????????" />
            <div class="auto-complit" style="overflow: auto; width: calc(83.3% - 15px); top: 28px; text-align: left;"></div>
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
<div class="name-block text-center txt-up" style="margin: 0 0 15px;"><span>?????????? ?????????????? ??????????????????</span></div>
<div class="st-content-bottom clear">
<div class="module st-news">
    <?php if(!$search) { ?>
    <div class="m-header">
        <a href="#" data-filter="new" class="filter js-adduz-list color-silver">??????????(<span class="js-new"><?php if($arrFilter['new']) { echo $arrFilter['new']; } else { echo '0'; } ?></span>)</a>
        <a href="#" data-filter="add" class="filter js-adduz-list">??????????????????????(<span class="js-add"><?php if($arrFilter['add']) { echo $arrFilter['add']; } else { echo '0'; } ?></span>)</a>
        <a href="#" data-filter="pending" class="filter js-adduz-list">????????????????(<span class="js-pending"><?php if($arrFilter['pending']) { echo $arrFilter['pending']; } else { echo '0'; } ?></span>)</a>
        <a href="#" data-filter="del" class="filter js-adduz-list">??????????????????(<span class="js-del-info"><?php if($arrFilter['del']) { echo $arrFilter['del']; } else { echo '0'; } ?></span>)</a>
        <a href="#" data-filter="all" class="filter js-adduz-list">??????(<span class="js-all"><?php if($arrFilter['all']) { echo $arrFilter['all']; } else { echo '0'; } ?></span>)</a>
    </div>
    <?php } else { ?>
        <div class="search-result">
            <div class="result-left">??????????????: <?php echo sizeof($outArray); ?></div>
            <div class="result-right"><a href="/user/<?php echo $url; ?>/adduz/">?????????? ?? ???????????? ????????????</a></div>
        </div>
    <?php } ?>
<div class="line-adduz new">
<?php
foreach($outArray as $arrData) {
?>
<div class="news-item" data-id="<?php echo $arrData['ID']; ?>">
<div class="col-12 width-sm content-right">
<div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
    <div class="params-banner">??????????: <?php echo $arrData["DATE_FORMAT"]; ?></div>
    <?php if($arrData["COUNTRY_ID"]) { ?>
    <div class="params-banner">????????????: <?php echo $arrData["COUNTRY"]; ?></div>
    <input type="hidden" value="<?php echo $arrData["COUNTRY_ID"]; ?>" class="js-country-id" />
    <?php } else { ?>
    <div class="params-banner input-block">????????????:
        <input type="text" value="<?php echo $arrData["COUNTRY"]; ?>" class="input-add js-country" placeholder="????????????">
        <input type="text" value="" class="input-add js-capital" placeholder="??????????????">
        <input type="text" value="" class="input-add js-region-country" placeholder="????????????">
        <input type="text" value="" class="input-add js-utc-country" style="width: 110px;" placeholder="UTC+0:00">
        <a class="color-silver js-uz-add-button" data-id="<?php echo $arrData["ID"]; ?>" data-type="country" data-user="<?=$arrData['AUTHOR']?>" style="text-decoration: none;">????????????????</a>
    </div>
    <?php } ?>
    <?php if($arrData["REGION_ID"]) { ?>
    <div class="params-banner">????????????: <?php echo $arrData["REGION"]; ?></div>
    <input type="hidden" value="<?php echo $arrData["REGION_ID"]; ?>" class="js-region-id" />
    <?php } else { ?>
    <div class="params-banner">????????????:
        <input type="text" value="<?php echo $arrData["REGION"]; ?>" class="input-add js-region" style="width: 342px;" placeholder="????????????">
    </div>
    <?php } ?>
    <?php if($arrData["CITY_ID"]) { ?>
    <div class="params-banner">??????????: <?php echo $arrData["CITY"]; ?></div>
    <input type="hidden" value="<?php echo $arrData["CITY_ID"]; ?>" class="js-city-id" />
    <?php } else { ?>
    <div class="params-banner">??????????:
        <input type="text" value="<?php echo $arrData["CITY"]; ?>" class="input-add js-city" style="width: 342px; margin-left: 7px;" placeholder="??????????">
        <input type="text" value="" class="input-add js-utc-city" style="width: 110px;" placeholder="UTC+0:00">
        <a class="color-silver js-uz-add-button" data-id="<?php echo $arrData["ID"]; ?>" data-type="city" data-user="<?=$arrData['AUTHOR']?>" style="text-decoration: none;<?php if(!$arrData["COUNTRY_ID"]) { ?> display: none;<?php } ?>">????????????????</a>
    </div>
    <?php } ?>
    <div class="params-banner">?????? ???????????????? ??????????????????: <?php echo $arrData["TYPE"]; ?></div>
    <div class="params-banner">????????????????: <?php echo $arrData["NAME"]; ?></div>
    <div class="params-banner">??????????: <?php echo $arrData["ADRESS"]; ?></div>
    <div class="params-banner">??????????????: <?php echo $arrData["PHONE"]; ?></div>
    <div class="params-banner">????????: <a href="<?php echo $arrData["SITE"]; ?>" target="_blank"><?php echo $arrData["SITE"]; ?></a></div>
    <div class="params-banner">E-mail: <a href="mailto:<?php echo $arrData["EMAIL"]; ?>" target="_blank"><?php echo $arrData["EMAIL"]; ?></a></div>
</div>
</div>
<div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">
<?php if($arrData["ADD"] != 'Y' && $arrData["COUNTRY_ID"] && $arrData["REGION_ID"] && $arrData["CITY_ID"] && $arrData["DEL"] != 'Y') { ?>
<a class="color-silver js-edit-button add" data-type="add" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>">???????????????? ??????????????????</a>
<?php } ?>
<?php if($arrData["PENDING"] != 'Y' && $arrData["DEL"] != 'Y') { ?>
<a class="color-silver js-edit-button pending" data-type="pending" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>" data-uz-id="<?=$arrData['UZ_ID']?>">????????????????</a>
<?php } ?>
<?php if($arrData["DEL"] != 'Y') { ?>
<a class="color-silver js-edit-button del" data-type="del" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>" data-uz-id="<?=$arrData['UZ_ID']?>">?????????????? ????????????</a>
<?php } ?>
</div>
</div>
<?php
}
?>
</div>
<div class="line-adduz add" style="display: none;"></div>
<div class="line-adduz pending" style="display: none;"></div>
<div class="line-adduz del" style="display: none;"></div>
<div class="line-adduz all" style="display: none;"></div>
</div>
 <!-- st-news -->
</div>
</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>