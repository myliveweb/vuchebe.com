<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Баннер");

global $USER;

$user_id = 0;
$user_name = '';
$user_avatar = '';
$pageAdmin = 0;

if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
	$user_avatar = $_SESSION['USER_DATA']['AVATAR'];
}

require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_company.php');
?>
<div class="st-content-right">
<div class="breadcrumbs">
<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/company/">Компания</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Реклама</span>
</div>
<div class="page-content">
<div class="st-content-bottom clear">
<div class="name-block text-left"> &nbsp;&nbsp;<span>Реклама</span></div>
<p>Внимание!</p>
<p>Сайт работает в тестовом режиме!!!</p><br>

<style>
.st-content-bottom .news-name {
    font-size: 20px;
    color: #000;
    margin-left: 20px;
}
.st-content-bottom .news-name a span {
	color: #000;
}
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
</style>

<?php
$price = array();

$price['base']    = 10;
$price['country'] = 15;
$price['region']  = 20;
$price['city']    = 30;

?>

<div class="news-name" style="margin-top: 20px; margin-left: 0px;">
     <span class="balance-tarif js-tarif">Тарифы</span>
</div>

</div><!-- st-content-bottom -->
</div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>