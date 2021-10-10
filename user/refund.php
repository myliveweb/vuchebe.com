<?php

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

?>
<style>
#box-line .js-refund {
	padding: 0;
	width: 100%;
}
.balance-tarif-table {
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
    font-size: 17px;
    margin-left: 15px;
}
.js-refund.active span::before,
.js-refund:hover span::before {
    border-color: #ff471a;
}
.js-refund:active {
    color: #ff471a;
    background: #fff;
    box-shadow: none;
    box-shadow: 0 0 13px #999 inset;
}
.js-refund.active:hover {
    color: ffffff;
    box-shadow: none;
}
.js-refund.active:hover span {
    text-decoration: none;
    color: #ffffff;
}
</style>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);

function cmp($a, $b) {
    if ($a['DATE_SORT'] == $b['DATE_SORT']) {
        return 0;
    }
    return ($a['DATE_SORT'] > $b['DATE_SORT']) ? -1 : 1;
}

$arrUser = array();

$userBy = "ID";
$userOrder = "ASC";

$filter = array("ACTIVE" => "Y", "WORK_PAGER" => "_%");
$rsUsers = CUser::GetList($userBy, $userOrder, $filter);
while($dataUsers = $rsUsers->Fetch()) {
    if($dataUsers['WORK_PAGER'] > 0) {

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PROPERTY_USER");
        $arFilter = array("IBLOCK_ID" => 42, "ACTIVE" => "Y", "PROPERTY_USER" => $dataUsers['ID']);
        $res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
        if($row = $res->GetNext()) {
            $dataUsers['DATE_SORT'] = $row['DATE_CREATE'];
            $arrDate = explode(' ', $row['DATE_CREATE']);
            $dataUsers['DATE_SHOW'] = $arrDate[0];
        }

        $fullName = $dataUsers['NAME'];
        if($dataUsers['SECOND_NAME'] && 1) {
            $fullName .= ' ' . $dataUsers['SECOND_NAME'];
        }
        if($dataUsers['LAST_NAME']) {
            $fullName .= ' ' . $dataUsers['LAST_NAME'];
        }
        $dataUsers['FULL_NAME'] = $fullName;

        $arrUser[] = $dataUsers;
    }
}
usort($arrUser, "cmp");
?>

<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Возврат средств</span>
	</div>
	<div class="page-content">
		<div class="name-block text-center txt-up"><span>Возврат средств</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="line" id="box-line">
                    <div class="row-line" style="margin-bottom: 30px;">
                        <div class="col-2"><span class="balance-tarif-table">Дата</span></div>
                        <div class="col-5"><span class="balance-tarif-table">Пользователь</span></div>
                        <div class="col-2"><span class="balance-tarif-table">Сумма</span></div>
                        <div class="col-3"></div>
                    </div>
                    <?php foreach($arrUser as $itemUser) { ?>
                    <div class="row-line line-refund" style="margin-bottom: 7px; font-size: 15px;">
                        <div class="col-2"><?php echo $itemUser['DATE_SHOW']; ?></div>
                        <div class="col-5"><?php echo $itemUser['FULL_NAME']; ?></div>
                        <div class="col-2" style="text-align: right;" title="Баланс <?php echo $itemUser['WORK_FAX']; ?> руб."><?php echo $itemUser['WORK_PAGER']; ?> руб.</div>
                        <div class="col-3">
                            <div class="btns">
                                <a href="#" class="button js-refund" style="line-height: 25px; top: -7px;" data-id="<?php echo $itemUser['ID']; ?>">
                                    <span style="font-size: 18px; text-decoration: none;">вернуть</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
				</div>
			</div><!-- st-news -->
		</div><!-- st-content-bottom -->
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>