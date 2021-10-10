<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Компания");

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
<a href="/">Главная</a><i class="fa fa-angle-double-right color-orange"></i>
<span>Компания</span>
</div>
<div class="page-content">
<div class="st-content-bottom clear">
<div class="name-block text-left"> &nbsp;&nbsp;<span>Компания</span></div>
<p>Внимание!</p>
<p>Сайт работает в тестовом режиме!!!</p><br>
<div class="name-block text-center">
<span>Компания</span></div>
<p>Внимание!</p>
<p>Сайт работает в тестовом режиме!!!</p><br>
<div class="name-block text-right"><span>Компания</span> &nbsp;&nbsp;</div>
<p>Внимание!</p>
<p>Сайт работает в тестовом режиме!!!</p><br>
<a href="/map" class="button">
<i class="fa fa-caret-up">
</i> <span>Карта</span></a> <br><br>
</div><!-- st-content-bottom -->
</div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>