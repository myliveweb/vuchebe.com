<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Бизнес-аккаунт");

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
<style>
#form-jobs input,
#form-jobs textarea {
    color: #000;
}

/* Checkbox Style */
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
</style>
<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/company/">Компания</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/company/banner/">Реклама</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Бизнес-аккаунт</span>
	</div>
	<div id="pro" class="page-content">
		<div class="st-content-bottom clear">
			<div class="name-block text-left"> &nbsp;&nbsp;<span>Бизнес-аккаунт</span></div>
			<div class="pro-tab pro-a">
			<?php
			require($_SERVER["DOCUMENT_ROOT"].'/company/banner/user/pro_tab_a.php');
			?>
			</div>
			<div class="pro-tab pro-r" style="display: none;">
			<?php
			require($_SERVER["DOCUMENT_ROOT"].'/company/banner/user/pro_tab_r.php');
			?>
        	</div>
            <div class="pro-tab pro-f" style="display: none;">
                <div class="row-line">
                    <div class="col-12 mob-line">
                        <div style="color: orange; font-size: 17px; font-weight: 600; line-height: 22px; margin-top: 15px; text-align: center;">
                            Вы успешно зарегистрировались,<br>Вам отправлено пиьсмо для подтверждения. <br> Письмо могло уйти в СПАМ.
                        </div>
                    </div>
                </div>
            </div>
		</div><!-- st-content-bottom -->
	</div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>