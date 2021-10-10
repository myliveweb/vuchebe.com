<?php
$APPLICATION->SetTitle("Денежные средства");
global $USER;

$user_id = 0;
$user_name = '';
$user_avatar = '';
$pageAdmin = 0;

if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$politic = 'PROPERTY_PAY';
$lavStr = getLawPopUpStr($politic);
?>
<style>
/* Checkbox Style */
.radio input {
    position: absolute;
    z-index: -1;
    opacity: 0;
    margin: 10px 0 0 7px;
}
.radio__text:before {
    content: '';
    position: absolute;
    top: -3px;
    left: 0;
    width: 22px;
    height: 22px;
    border: 1px solid #9f9f9f;
    border-radius: 50%;
    background: #FFF;
}
.radio input:checked + .radio__text:after {
    opacity: 1;
}
.radio__text:after {
    content: '';
    position: absolute;
    top: 1px;
    left: 4px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #ff4719;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.5);
    opacity: 0;
    transition: .2s;
}
.law-text {
    color: #9f9f9f;
    margin-top: 15px;
    display: none;
}
/* End Checkbox Style */

.st-content-bottom .news-name {
    font-size: 20px;
    color: #000;
    margin-left: 20px;
}
.st-content-bottom .news-name a span {
	color: #000;
}
.st-content-bottom .news-hr {
    padding: 0 0 25px;
    border-bottom: 1px solid #ccc;
    margin-bottom: 20px;
}
.st-content-bottom .balance-num {
    display: inline-block;
    margin: 0 3px 0 5px;
    font-size: 1.5rem;
}
.st-content-bottom .balance-back,
.st-content-bottom .balance-tarif {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
    font-size: 17px;
}
.st-content-bottom .balance-back-del {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
    font-size: 15px;
    margin-left: 5px;
}
.st-content-bottom .balance-free,
.st-content-bottom .balance-hold {
    display: inline-block;
    margin: 0 3px;
}
.st-content-bottom .balance-small {
    font-size: 17px;
    margin-top: 6px;
}
.st-content-bottom .balance-label {
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
}
.st-content-bottom .js-balance {
    margin-top: 3px;
    text-align: right;
    padding-right: 10px;
    max-width: 236px;
    color: #000000;
    display: inline-block;
}
.st-content-bottom .js-balance-submit,
.st-content-bottom .js-check-submit {
    line-height: 31px;
    top: -2px;
}
.st-content-bottom a.lav-href {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    position: relative;
    color: #9f9f9f;
    text-decoration: none;
    line-height: 1.4;
}
.balance-tarif-table {
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
    font-size: 17px;
    margin-left: 15px;
}
</style>
<?php

function cmp($a, $b) {
    if ($a['create_at'] == $b['create_at']) {
        return 0;
    }
    return ($a['create_at'] > $b['create_at']) ? -1 : 1;
}

$num  = round($_SESSION['USER_DATA']["WORK_FAX"], 2);
$free = round(($_SESSION['USER_DATA']["WORK_FAX"] - $_SESSION['USER_DATA']["WORK_PAGER"]), 2);
$hold = round($_SESSION['USER_DATA']["WORK_PAGER"], 2);

$addBalance = $dbh->query('SELECT * from a_balance_history WHERE user_id = ' . $user_id . ' AND direction > 0 ORDER BY create_at DESC')->fetchAll();
$delBalance = $dbh->query('SELECT SUM(tax) AS tax, direction, disc, create_at, date_at from a_balance_history WHERE user_id = ' . $user_id . ' AND direction = 0 GROUP BY date_at ORDER BY create_at DESC')->fetchAll();
$mergeArr = array_merge($addBalance, $delBalance);

usort($mergeArr, "cmp");
?>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>

<div class="st-content-right" id="page">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Денежные средства</span>
	</div><br>
	<div class="page-content" id="balance" data-max="<?php echo $free; ?>">
		<div class="st-content-bottom clear">
			<div class="name-block text-left"> &nbsp;&nbsp;<span>Денежные средства</span></div>
			<div class="news-name">
                <div>Денежные средства на счёте:<div class="balance-num"><?php echo $num; ?></div>руб.</div>
                <div class="balance-small" style="margin-top: 10px;">Доступно:<div class="balance-free"><?php echo $free; ?></div>руб.</div>
                <div class="balance-small">
                    К возврату:<div class="balance-hold"><?php echo $hold; ?></div>руб.
                    <?php if($hold) { ?>
                        <span class="balance-back-del">Отменить возврат</span>
                    <?php } else { ?>
                        <span class="balance-back-del" style="display: none;">Отменить возврат</span>
                    <?php } ?>
                </div>
            </div>
            <div class="news-name" style="margin-top: 10px;">
                <span class="balance-back">Возврат денежных средств</span>
            </div>
            <div class="name-block text-left" style="margin-top: 35px;"> &nbsp;&nbsp;<span>Оплата услуг</span></div>
            <div class="row-line mt-10">
                <div class="col-12">
                    <div class="label"><span class="balance-label">Внести на счёт (<span class="js-error-sum">укажите сумму</span>)</span></div>
                    <input class="js-balance" type="text"><div style="margin: 0 7px; display: inline-block;">руб.</div>
                    <button type="submit" class="js-balance-submit"><span>Оплата картой</span></button>
                    <?php if($_SESSION['USER_DATA']['PRO_TYPE'] == 'U') { ?>
                    <button type="submit" class="js-check-submit"><span>Выставить счёт</span></button>
                    <?php } ?>
                </div>
            </div>
            <div class="row-line" style="margin-top: 25px;">
                <div class="col-12">
                    <label class="radio" style="display: inline-block;">
                        <input class="js-law" type="checkbox" name="law" value="1">
                        <div class="radio__text"><?php echo $lavStr; ?></div>
                    </label>
                    <div class="law-text" style="color: red;" >Необходимо ознакомиться и согласиться с правилами сервиса.</div>
                </div>
            </div>

            <div class="news-name" style="margin-top: 20px; margin-left: 0px;">
                <span class="balance-tarif js-tarif" style="border-bottom: 1px solid #9f9f9f;">Тарифы</span>
            </div>

            <div id="balance-list-name" class="name-block text-left" style="margin-top: 25px;<?php if(!$mergeArr) { echo ' display: none;'; } ?>"> &nbsp;&nbsp;<span>Детализация денежных средств</span></div>
            <div id="balance-list-table" class="row-line" style="margin-bottom: 15px; font-size: 15px;<?php if(!$mergeArr) { echo ' display: none;'; } ?>">
                <div class="col-3"><span class="balance-tarif-table">Дата</span></div>
                <div class="col-3"><span class="balance-tarif-table">Сумма</span></div>
                <div class="col-6"><span class="balance-tarif-table">Описание</span></div>
            </div>
            <div id="balance-list">
            <?php
            if ($mergeArr) {
            foreach($mergeArr as $itemBalance) {
                $arrDate = explode('-', $itemBalance['date_at']);
                $formatDate = $arrDate[2] . '.' . $arrDate[1] . '.' . $arrDate[0];
            ?>
                <div class="row-line" style="margin-bottom: 10px; font-size: 15px;">
                    <div class="col-3"><?php echo $formatDate; ?></div>
                    <?php if($itemBalance['direction'] == 0) { ?>
                        <div class="col-3" style="color: red;">-<?php echo $itemBalance['tax']; ?> руб.</div>
                    <?php } elseif($itemBalance['direction'] == 1 || $itemBalance['direction'] == 8) { ?>
                        <div class="col-3" style="color: green;">+<?php echo $itemBalance['tax']; ?> руб.</div>
                    <?php } elseif($itemBalance['direction'] == 2) { ?>
                        <div class="col-3" style="color: #9f9f9f;">-<?php echo $itemBalance['tax']; ?> руб.</div>
                    <?php } elseif($itemBalance['direction'] == 3) { ?>
                        <div class="col-3" style="color: #9f9f9f;">+<?php echo $itemBalance['tax']; ?> руб.</div>
                    <?php } elseif($itemBalance['direction'] == 4 || $itemBalance['direction'] == 5 || $itemBalance['direction'] == 6 || $itemBalance['direction'] == 7) { ?>
                        <div class="col-3" style="color: #9f9f9f;"><?php echo $itemBalance['tax']; ?> руб.</div>
                    <?php } ?>
                    <div class="col-6"><?php echo $itemBalance['disc']; ?></div>
                </div>
            <?php
                }
            }
            ?>
            </div>
		</div><!-- st-content-bottom -->
	</div><!-- page-item -->
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>