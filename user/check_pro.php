<?php
global $dbh;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$input = filter_input_array(INPUT_POST);
$search = $input['s'];
$sid = $input['sid'];

if(!$sid)
    $sid = 'IDSTR';

$cnt = 10;

$outArray = array();

$arrFilter = array('new' => 0, 'pending' => 0, 'pay' => 0,  'del' => 0, 'data' => 0, 'all' => 0);

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE");

$arFilter = array("IBLOCK_ID" => 47, "PROPERTY_USER" => $user_id);

if($search) {

    $property = 'PROPERTY_' . $sid;
    $arFilter[$property] = $search . "%";
}

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
while($obRes = $res->GetNextElement()) {

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['SUM']     = $props['SUM']['VALUE'];
    $row['STATE']   = $props['STATE']['VALUE'];
    $row['ORG']     = $props['ORG']['VALUE'];

    $row['TICKET']  = $props['TICKET']['VALUE'];

    $row['OGRN']    = $props['OGRN']['VALUE'];
    $row['INN']     = $props['INN']['VALUE'];
    $row['KPP']     = $props['KPP']['VALUE'];

    $row['ADRESS']  = $props['ADRESS']['VALUE'];
    $row['PHONE']   = $props['PHONE']['VALUE'];
    $row['EMAIL']   = $props['EMAIL']['VALUE'];

    $row['USER']    = $props['USER']['VALUE'];

    $row['PENDING'] = $props['PENDING']['VALUE'];
    $row['PAID']    = $props['PAID']['VALUE'];
    $row['CANCEL']  = $props['CANCEL']['VALUE'];

    if($row['TICKET']) {
        $arrChat = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $row['TICKET'] . ' ORDER BY id DESC')->fetch();
        if($arrChat['del_owner']) {
            $row['TICKET_COLOR'] = 'red';
        } else {
            $row['TICKET_COLOR'] = 'green';
        }
    }

    if((sizeof($outArray) < $cnt && $row['PENDING'] != 'Y' && $row['PAID'] != 'Y' && $row['CANCEL'] != 'Y') || $search) {

        list($dateFormat, $timeFormat) = explode(' ', $row["DATE_CREATE"]);

        $row["DATE_FORMAT"] = $dateFormat;

        $rsAuthorData = CUser::GetByID($row['USER']);
        $authorData = $rsAuthorData->Fetch();

        $row["URL"] = getUserUrl($authorData);

        $arGroups = CUser::GetUserGroup($authorData['ID']);
        if(in_array(6, $arGroups)) {
            $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['WORK_COMPANY']), 0, 1)) . '</span>' . mb_substr(trim($authorData['WORK_COMPANY']), 1);
        } elseif(in_array(7, $arGroups)) {
            if (strlen(trim($authorData['NAME']))) {
                $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['NAME']), 1);
                if($authorData['SECOND_NAME']) {
                    $format_name .= ' ';
                    $format_name .= '<span>' . strtoupper(mb_substr($authorData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($authorData['SECOND_NAME'], 1);
                }
                if($authorData['LAST_NAME']) {
                    $format_name .= ' ';
                    $format_name .= '<span>' . strtoupper(mb_substr(trim($authorData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LAST_NAME']), 1);
                }
            } else {
                $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LOGIN']), 1);
            }
        }

        $row['FORMAT_NAME'] = $format_name;

        if($row['STATE'] == 'Новый' || $row['STATE'] == 'Ожидает оплаты') {
            $row['STATE_COLOR'] = '#9f9f9f';
        } elseif($row['STATE'] == 'Оплачен') {
            $row['STATE_COLOR'] = 'green';
        } elseif($row['STATE'] == 'Отменён') {
            $row['STATE_COLOR'] = 'red';
        }

        $outArray[] = $row;
    }

    $arrFilter['all']++;

    if ($row['PENDING'] != 'Y' && $row['PAID'] != 'Y' && $row['CANCEL'] != 'Y') {
        $arrFilter['new']++;
    }

    if ($row['PENDING'] == 'Y') {
        $arrFilter['pending']++;
    }

    if ($row['PAID'] == 'Y') {
        $arrFilter['pay']++;
    }

    if ($row['CANCEL'] == 'Y') {
        $arrFilter['del']++;
    }
}
?>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/avatar.css">
<script>
var startFromListAdmin = 1;
var cnt = <?php echo $cnt; ?>;
</script>
<style>
.cur {
    margin: 10px 0px 5px 0px;
    font-style: italic; /* Курсивное начертание текста */
    color: navy; /* Синий цвет текста */
}
.cur::before {
    color: navy; /* Цвет маркера */
    content: "«"; /* Сам маркер */
    padding-right: 3px; /* Расстояние от маркера до текста */
    font-size: 15px;
}
.cur::after {
    color: navy; /* Цвет маркера */
    content: "»"; /* Сам маркер */
    padding-left: 3px; /* Расстояние от маркера до текста */
    font-size: 15px;
}
.auto-complit .item div.check {
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
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Выставление счёта</span>
	</div><br>
    <div class="page-content st-content-users" id="page" data-type="<?php echo $user_id; ?>">
        <!-- Поиск -->
        <div class="structure-cat bg-silver text-center" style="margin: 15px 0 20px;">
            <div class="row-line">
                <form id="form-check" method="post" accept-charset="utf-8">
                    <div class="col-10 search-filed" style="padding: 0 0 0 15px;">
                        <input class="js-add-check" type="text" name="s" id="search-avatar" value="<?php echo $search; ?>" placeholder="Введите № счёта или ОГРН или ИНН или КПП" />
                        <input type="hidden" name="sid" id="searchId" value="<?php echo $sid; ?>" />
                        <div class="auto-complit" style="overflow: auto; width: calc(83.3% - 15px); top: 28px; text-align: left;"></div>
                    </div>
                    <div class="col-2 button-filed">
                        <button type="submit" style="line-height: 30px; width: 100%;">
                            <span class="short"><i class="fa fa-search"></i></span>
                            <span class="full">найти</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Поиск -->
		<div class="name-block text-center txt-up" style="margin: 0 0 15px;"><span>Выставленные счета</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
                <?php if(!$search) { ?>
				<div class="m-header">
                    <a href="#" data-filter="new" class="filter js-check-list color-silver">новые(<span class="js-new"><?php if($arrFilter['new']) { echo $arrFilter['new']; } else { echo '0'; } ?></span>)</a>
					<a href="#" data-filter="pending" class="filter js-check-list">ожидание(<span class="js-pending"><?php if($arrFilter['pending']) { echo $arrFilter['pending']; } else { echo '0'; } ?></span>)</a>
					<a href="#" data-filter="pay" class="filter js-check-list">оплачены(<span class="js-pay"><?php if($arrFilter['pay']) { echo $arrFilter['pay']; } else { echo '0'; } ?></span>)</a>
					<a href="#" data-filter="del" class="filter js-check-list">отменены(<span class="js-del"><?php if($arrFilter['del']) { echo $arrFilter['del']; } else { echo '0'; } ?></span>)</a>
                    <a href="#" data-filter="sort" data-sort="DESC" class="filter js-check-sort">по дате</a>
                    <a href="#" data-filter="all" class="filter js-check-list">все(<span class="js-all"><?php if($arrFilter['all']) { echo $arrFilter['all']; } else { echo '0'; } ?></span>)</a>
				</div>
                <?php } else { ?>
                    <div class="search-result">
                        <div class="result-left">Найдено: <?php echo sizeof($outArray); ?></div>
                        <div class="result-right"><a href="/user/<?php echo $url; ?>/check/">назад к списку счетов</a></div>
                    </div>
                <?php } ?>
            <div class="line-orders new">
        <?php
        foreach($outArray as $arrData) {
        ?>
        <div class="news-item" data-id="<?php echo $arrData['ID']; ?>">
          <div class="col-12 width-sm content-right">
            <div class="news-name">
                Счёт №<?php echo $arrData['ID']; ?> от <?php echo $arrData['DATE_FORMAT']; ?>
            </div>
            <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; margin-top: 10px;">
                <div class="params-banner">Сумма: <?php echo number_format((float) $arrData["SUM"], 2, '.', ''); ?> руб.</div>
                <div class="params-banner">Статус: <span style="color: <?php echo $arrData["STATE_COLOR"]; ?>"><?php echo $arrData["STATE"]; ?></span></div>
                <div class="params-banner">Наименование организации: <a class="name-text" style="margin-left: 7px;" href="/user/<?php echo $arrData["URL"]; ?>/" target="_blank"><?=$arrData["FORMAT_NAME"]?></a></div>
                <div class="params-banner">ОГРН: <?php echo $arrData["OGRN"]; ?></div>
                <div class="params-banner">ИНН: <?php echo $arrData["INN"]; ?></div>
                <div class="params-banner">КПП: <?php echo $arrData["KPP"]; ?></div>
                <div class="params-banner">Юр. Адрес: <?php echo $arrData["ADRESS"]; ?></div>
                <div class="params-banner">Телефон: <?php echo $arrData["PHONE"]; ?></div>
                <div class="params-banner">E-mail: <?php echo $arrData["EMAIL"]; ?></div>
            </div>
          </div>
        <div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">
            <a href="/tcpdf/work/check.php?id=<?php echo $arrData['ID']; ?>" download="true" class="color-silver" data-type="pdf" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['USER']?>" data-sum="<?php echo $arrData["SUM"]; ?>">Скачать PDF</a>
            <a class="color-silver js-check-button del" data-type="del" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['USER']?>" data-sum="<?php echo $arrData["SUM"]; ?>">Отменить счёт</a>
            <?php if($arrData['TICKET']) { ?>
            <a href="/user/support/<?php echo $arrData['TICKET']; ?>/" class="color-silver" target="_blank" style="color: <?php echo $arrData['TICKET_COLOR']; ?>;">Тикет №<?php echo $arrData['TICKET']; ?></a>
            <?php } else { ?>
            <a class="color-silver js-new-chat" data-type="ticket" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['USER']?>">Новая заявка</a>
            <?php } ?>
        </div>
        </div>
        <?php
        }
        ?>
        </div>
        <div class="line-orders pending" style="display: none;"></div>
        <div class="line-orders pay" style="display: none;"></div>
        <div class="line-orders del" style="display: none;"></div>
        <div class="line-orders data" style="display: none;"></div>
        <div class="line-orders all" style="display: none;"></div>
        </div>
			 <!-- st-news -->
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>