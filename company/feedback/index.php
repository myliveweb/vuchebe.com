<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обратная связь");

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

$politic = 'PROPERTY_FEED';
$lavStr = getLawPopUpStr($politic);

require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_company.php');
?>
<style>
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

.law-text {
    color: #9f9f9f;
    margin-top: 15px;
    display: none;
}
#form-feedback a.lav-href {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    position: relative;
    color: #9f9f9f;
    text-decoration: none;
    line-height: 1.4;
}
</style>
<div class="st-content-right">
<div class="breadcrumbs">
<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/about/">Компания</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Обратная связь</span>
</div>
<div class="page-content">
					<div class="name-block text-left txt-up"> &nbsp;&nbsp;<span>Обратная связь</span></div>

					<p>
                        - Нашли ошибку?<br>
                        - Хотите сообщить о новом учебном заведении?<br>
                        - Если Вы администрация учебного заведения, напишите в Службу поддержки.<br>
                        <?php
                        if($_SESSION['USER_DATA']['ID']) {
                            $url = getUserUrl($_SESSION['USER_DATA']);
                            ?>
                        - Если Вы зарегестрированный пользователь, то можете обратиться в <a href="/user/<?php echo $url; ?>/service/">Службу поддержки</a>.
                        <?php } else { ?>
                        - Если Вы зарегестрированный пользователь, то можете обратиться в <a href="" class="js-noauth">Службу поддержки</a>.
                        <?php } ?>
                    </p>
							<div class="st-content-bottom clear">
								<div id="error-message" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
								<div class="contact-form bg-silver">
									<form id="form-feedback" method="post">
									<div class="row-line">
										<div class="col-6">
											<span class="label">Ваше имя</span>
											<input class="w-80 left firstname error-reset" type="text" name="firstname" value="<?=$_POST['firstname']?>">
										</div>
										<div class="col-6">
											<span class="label">Электронная почта</span>
											<input class="w-80 email error-reset" style="margin-top: 14px;" type="email" name="email" value="<?=$_POST['email']?>">
										</div>
									</div>
									<div class="row-line">
										<div class="col-12">
											<span class="label">Текст сообщения</span>
											<textarea class="message_fb error-reset" name="message_fb"><?=$_POST['message_fb']?></textarea>
										</div>
									</div>
									<div class="contact-form-footer">
										<div class="st-captcha left" style="margin-top: 15px;">
											<?php $CaptchaCode = htmlspecialcharsbx($APPLICATION->CaptchaGetCode()); ?>
											<span class="image brd capcha_img" style="display: inline-block;"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$CaptchaCode?>" alt="img"></span>
											<a href="#" id="cb" class="capcha_button"><img src="<?=SITE_TEMPLATE_PATH?>/images/reload.png"></a>
										</div>
										<div class="st-captcha-input left">
											<span class="label">Введите цифры с картинки</span>
				                            <input type="hidden" name="captcha_sid" class="captcha_sid" value="<?=$CaptchaCode?>">
				                            <input type="text" class="captcha_word" name="captcha_word">
										</div>
									</div>
                                    <div class="row-line" style="margin-top: 10px;">
                                        <div class="col-12">
                                            <label class="radio" style="display: inline-block;">
                                                <input class="js-law" type="checkbox" name="law" value="1">
                                                <div class="radio__text"><?php echo $lavStr; ?></div>
                                            </label>
                                            <div class="law-text" style="color: red;" >Необходимо ознакомиться и согласиться с правилами сервиса.</div>
                                        </div>
                                    </div>
                                    <div class="row-line" style="margin-top: 30px;">
                                        <div class="col-12">
                                            <button class="button right txt-up" type="submit">
                                                <i class="fa fa-caret-up"></i> <span>отправить</span>
                                            </button>
                                        </div>
                                    </div>
									</form>
								</div><!-- contact-form -->

							</div><!-- st-content-bottom -->
</div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>