<?php
$politic = 'PROPERTY_R_PRO';
$lavStr = getLawPopUpStr($politic);
?>
<style>
#pro-reg .left {
    margin-top: 10px;
}
#pro-reg .left-5 {
    margin-top: 5px;
}
#pro-reg .row-line {
    margin: 15px -15px;
}
#pro-reg .reg-tab {
	margin: 20px 0 0;
}
#pro-reg select {
	background-color: #fff;
    border: 1px solid #ff471a;
    border-radius: 4px;
    height: 30px;
    padding: 0 15px;
    box-sizing: border-box;
    color: black;
    width: 100%;
}

.law-text {
    color: #9f9f9f;
    margin-top: 15px;
    display: none;
}

#pro .error-message {
	color: red;
	font-weight: 500;
	font-size: 18px;
	height: 24px;
	margin-bottom: 25px;
	display: none;
}
.page-content a.lav-href {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    position: relative;
    color: #9f9f9f;
    text-decoration: none;
    line-height: 1.4;
}
#form-reg .duble-email-hide,
#form-reg .success-duble-email-hide {
    display: none;
}
#form-reg .duble-email {
    margin-top: 10px;
}
#form-reg .duble-email a {
    text-decoration: none;
}
#form-reg .duble-email a:hover {
    text-decoration: underline;
}
#form-reg .duble-email a span,
#form-reg .duble-email span {
    color: black;
}
#form-reg .success-duble-email-hide .duble-email {
    color: green;
}
</style>
<div class="error-message"></div>
<form id="pro-reg" method="post" action="#">
	<div>
		<div class="row-line" style="margin-bottom: 25px;">
		    <div class="col-6 mob-line">
		        <label class="radio" style="display: inline-block;">
		            <input class="js-pro" data-tab="6" type="radio" name="pro" value="6" checked>
		            <div class="radio__text">Юридическое лицо</div>
		        </label>
		    </div>
		    <div class="col-6 mob-line">
		        <label class="radio" style="display: inline-block;">
		            <input class="js-pro" data-tab="7" type="radio" name="pro" value="7">
		            <div class="radio__text">Физическое лицо</div>
		        </label>
		    </div>
		</div>
		<div class="row-line reg-tab reg-6">
		<?php
		require($_SERVER["DOCUMENT_ROOT"].'/company/banner/user/pro_tab_r_u.php');
		?>
		</div>
		<div class="row-line reg-tab reg-7" style="display: none;">
		<?php
		require($_SERVER["DOCUMENT_ROOT"].'/company/banner/user/pro_tab_r_f.php');
		?>
		</div>
        <div class="row-line" id="form-reg">
            <div class="col-12">
                <div class="duble-email-hide">
                    <div class="duble-email"><a href="#" data-email="">Выслать повторно на E-mail <span></span> код подтверждения</a></div>
                    <div style="margin: 10px 0; font-size: 13px;"><span style="font-weight: bold; margin-right: 5px;">Внимание!</span>Обязательно проверьте папку СПАМ в вашем почтовом ящике. Некоторые письма могут попасть туда.</div>
                </div>
                <div class="success-duble-email-hide">
                    <div class="duble-email">На E-mail <span></span> был выслан код подтверждения</div>
                    <div style="margin: 10px 0; font-size: 13px;">Зайдите в ваш почтовый ящик и перейдите по ссылке указанной в письме. Обязательно проверьте папку СПАМ. Некоторые письма могут попасть туда.</div>
                </div>
            </div>
        </div>
		<div class="row-line">
			<div class="col-6 mob-line">
				<span class="label">Email<span class="color-orange" style="margin-left: 10px; display: none;">Такой E-mail уже используется</span></span>
				<input class="w-80 left email" type="text" name="email" value="<?=$_POST['email']?>">
			</div>
			<div class="col-6 mob-line">
				<span class="label">Телефон</span>
				<input class="w-80 left phone" type="text" name="phone" value="<?=$_POST['phone']?>">
			</div>
		</div>
		<div class="row-line">
			<div class="col-6 mob-line">
				<span class="label">Пароль</span>
				<input class="w-80 left password pass" type="password" name="password" value="<?=$_POST['password']?>">
			</div>
			<div class="col-6 mob-line">
				<span class="label">Повторите пароль</span>
				<input class="w-80 left password_confirm pass" type="password" name="password_confirm" value="<?=$_POST['password_confirm']?>">
			</div>
		</div>
		<div class="contact-form-footer">
			<div class="st-captcha left" style="margin-top: 23px;">
				<?php $CaptchaCode = htmlspecialcharsbx($APPLICATION->CaptchaGetCode()); ?>
				<span class="image brd capcha_img" style="display: inline-block;"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$CaptchaCode?>" alt="img"></span>
				<a href="#" id="cb" class="capcha_button"><img src="<?=SITE_TEMPLATE_PATH?>/images/reload.png"></a>
			</div>
			<div class="st-captcha-input left">
				<span class="label">Введите цифры с картинки</span>
	            <input type="hidden" name="captcha_sid" class="captcha_sid" value="<?=$CaptchaCode?>">
	            <input type="text" class="captcha_word" name="captcha_word" style="margin-top: 10px; width: 100%;">
			</div>
		</div>
    <div class="row-line">
        <div class="col-12">
            <label class="radio" style="display: inline-block;">
                <input class="js-law" type="checkbox" name="law" value="1">
                <div class="radio__text"><?php echo $lavStr; ?></div>
            </label>
            <div class="law-text" style="color: red;" >Необходимо ознакомиться и согласиться с правилами сервиса.</div>
        </div>
    </div>
		<div class="row-line" style="margin: 25px -15px; text-align: right;">
			<div class="col-12">
				<button type="submit"><span>зарегистрировать</span></button>
			</div>
		</div>
		<div class="bottom-form" style="padding: 15px 0 0;">
			Есть бизнес-аккаунта? <a href="#" class="pro-switch" data-tab="pro-a" style="margin-left: 10px;">войти</a>
		</div>
	</div>
</form>