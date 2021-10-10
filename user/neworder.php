<?php
$APPLICATION->SetTitle("Оформление заказа");
global $USER;

$user_id = 0;
$user_name = '';
$user_avatar = '';
$pageAdmin = 0;

if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}
?>
<style>

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
</style>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>

<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Оформление заказа</span>
	</div><br>
	<div class="page-content">
		<div class="st-content-bottom clear">
			<div class="name-block text-left"> &nbsp;&nbsp;<span>Оформление заказа</span></div>
			<div class="news-name">
				<a href="/user/<?php echo $url; ?>/topbanner/">
					<span>Верхний баннер</span>
				</a>
			</div>
			<div class="news-hr"></div>
			<div class="news-name">
				<a href="/user/<?php echo $url; ?>/sidebanner/">
					<span>Квадратный баннер</span>
				</a>
			</div>
			<div class="news-hr"></div>
		<!--<a href="/map" class="button">
		<i class="fa fa-caret-up">
		</i> <span>Карта</span></a> <br><br>-->
		</div><!-- st-content-bottom -->
	</div><!-- page-item -->
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>