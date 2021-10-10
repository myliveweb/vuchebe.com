<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle('Регистрация | В учёбе');
if ($USER->IsAuthorized()){
    LocalRedirect('/');
}

$politic = 'PROPERTY_R_USER';
$lavStr = getLawPopUpStr($politic);
?>
<style>
.year .jq-selectbox__dropdown {
    height: 300px !important;
    overflow: scroll;
}
.auto-complit-reg {
    position: relative;
    top: -30px;
    left: 0px;
    max-height: 300px;
    width: 100%;
    background-color: #fff;
    border-right: 1px solid #ff471a;
    border-bottom: 1px solid #ff471a;
    border-left: 1px solid #ff471a;
    border-radius: 0px 0px 4px 4px;
    z-index: 100;
    padding: 4px 0px;
    display: none;
    overflow-y: scroll;
}
.auto-complit-reg .item {
    width: 100%;
    color: #000;
    padding: 8px 15px;
    height: 30px;
    cursor: pointer;
    white-space: nowrap;
}
.auto-complit-reg .item div {
    overflow: hidden;
}
.auto-complit-reg .item:hover {
    background-color: #e0e0e0;
}
.js-block {
    display: none;
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

.law-text {
    color: #9f9f9f;
    margin-top: 15px;
    display: none;
}
.page-content #form-reg a.lav-href {
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
    margin-top: 28px;
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
<div class="page-content registration-page">
    <div class="name-block text-center"><span>Регистрация</span></div>
    <div id="error-message" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
    <div class="breadcrumbs step">
        <a href="#" class="br-step-1">Шаг 1</a> <i class="fa fa-angle-double-right color-orange"></i>
        <a href="#" class="br-step-2" style="color: #9f9f9f; cursor: default;">Шаг 2</a> <i class="fa fa-angle-double-right"></i>
        <a href="#" class="br-step-3" style="color: #9f9f9f; cursor: default;">Шаг 3</a>
    </div>

    <div class="contact-form bg-silver">
        <form id="form-reg" method="post">
            <input type="hidden" name="action" value="1">
            <div class="row-line js-step js-step-1">
                <div class="col-5">
                    <div class="title"><b>Заполните свои контактные данные</b></div>
                    <p style="margin-bottom: 15px;">
                        <span class="label">E-mail<span class="color-orange" style="margin-left: 10px; display: none;">Такой E-mail уже используется</span></span>
                        <input class="w-80 email error-reset" style="margin-bottom: 5px;" type="email" name="email" value="<?=$_POST['email']?>" autocomplete="off">
                        <span style="font-size: 11px;">На этот E-mail будет выслан код подтверждения</span>
                    </p>
                    <p>
                        <span class="label">Пароль</span>
                        <input class="w-80 left password error-reset" type="password" name="password" value="">
                        <span class="after-text color-green"></span>
                    </p>
                    <p>
                        <span class="label">Пароль повторно</span>
                        <input class="w-80 left password_confirm error-reset" type="password" name="password_confirm" value="">
                        <span class="after-text color-green"></span>
                    </p>

                    <p>
                        <span class="label">Ваше имя</span>
                        <input class="w-80 left firstname error-reset" type="text" name="firstname" value="<?=$_POST['firstname']?>">
                    </p>
                    <p>
                        <span class="label">Ваша фамилия</span>
                        <input class="w-80 left lastname error-reset" type="text" name="lastname" value="<?=$_POST['lastname']?>">
                    </p>




                </div>
                <div class="col-7">
                    <div class="duble-email-hide">
                        <div class="duble-email"><a href="#" data-email="">Выслать повторно на E-mail <span></span> код подтверждения</a></div>
                        <div style="margin-top: 10px; font-size: 13px;"><span style="font-weight: bold; margin-right: 5px;">Внимание!</span>Обязательно проверьте папку СПАМ в вашем почтовом ящике. Некоторые письма могут попасть туда.</div>
                    </div>
                    <div class="success-duble-email-hide">
                        <div class="duble-email">На E-mail <span></span> был выслан код подтверждения</div>
                        <div style="margin-top: 10px; font-size: 13px;">Зайдите в ваш почтовый ящик и перейдите по ссылке указанной в письме. Обязательно проверьте папку СПАМ. Некоторые письма могут попасть туда.</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="contact-form-footer">
                        <div class="st-captcha left" style="padding-top: 14px;">
                            <?php $CaptchaCode = htmlspecialcharsbx($APPLICATION->CaptchaGetCode()); ?>
                            <span class="image brd capcha_img" style="display: inline-block;"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$CaptchaCode?>" alt="img"></span>
                            <a href="#" id="cb" class="capcha_button" style="margin-left: 10px; bottom: 5px; position: relative;"><img src="<?=SITE_TEMPLATE_PATH?>/images/reload.png"></a>
                        </div>
                        <div class="st-captcha-input left">
                            <span class="label">Введите цифры с картинки</span>
                            <input type="hidden" name="captcha_sid" class="captcha_sid" value="<?=$CaptchaCode?>">
                            <input style="width: 200px;" type="text" class="captcha_word" name="captcha_word">
                        </div>
                    </div>

                    <div class="btns">
                        <button class="button js-btn-ok-1"><span>далее</span></button>
                    </div>

                </div>
            </div>

            <div class="row-line js-step js-step-2" style="display: none;">
                <div class="col-6" style="position: relative;">
                    <div class="title"><b>Заполните свои контактные данные</b></div>
                    <p>
                        <span class="label">Город</span>
                        <input name="city_id" class="city_id js-reg-city_id" type="hidden" value="0">
                        <input name="city" class="w-80 city js-reg-city" style="color: black;" type="text" value="">
                        <div class="auto-complit" style="overflow: auto; top: 82px; width: 347px;"></div>
                    </p>
                    <p>
                        <span class="label">Номер телефона</span>
                        <input class="w-80 left phone" type="tel" name="phone" value="+7">
                        <span class="input-ico"><span>?</span></span>
                    </p>
                    <p>
                        <span class="label">Напишите немного о себе</span>
                        <textarea class="w-80" name="about" style="height: 312px;"></textarea>
                    </p>
                    <div class="btns">
                        <button class="button js-btn-ok-2"><span>далее</span></button>
                        <button class="button reverse js-btn-mv-2"><span>пропустить</span></button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="title"><b>Социальные сети</b></div>
                    <p class="links" style="position: relative;">
                        <span class="label">Вконтакте</span>
                        <i class="ico vk" style="position: absolute; top: 31px; left: 6px;"></i>
                        <input class="w-80 vk" style="padding: 0 15px 0 27px;" type="text" name="VK">
                    </p>
                    <p class="links" style="position: relative;">
                        <span class="label">Facebook</span>
                        <i class="ico fc" style="position: absolute; top: 31px; left: 6px;"></i>
                        <input class="w-80 fb" style="padding: 0 15px 0 27px;" type="text" name="FB">
                    </p>
                    <p class="links" style="position: relative;">
                        <span class="label">Однокласники</span>
                        <i class="ico ok" style="position: absolute; top: 31px; left: 6px;"></i>
                        <input class="w-80 ok" style="padding: 0 15px 0 27px;" type="text" name="OK">
                    </p>
                    <p class="links" style="position: relative;">
                        <span class="label">Twitter</span>
                        <i class="ico tw" style="position: absolute; top: 31px; left: 6px;"></i>
                        <input class="w-80 tw" style="padding: 0 15px 0 27px;" type="text" name="TW">
                    </p>
                    <p class="links" style="position: relative;">
                        <span class="label">Instagram</span>
                        <i class="ico inst" style="position: absolute; top: 31px; left: 6px;"></i>
                        <input class="w-80 inst" style="padding: 0 15px 0 27px;" type="text" name="INST">
                    </p>
                    <p class="links" style="position: relative;">
                        <span class="label">Youtube</span>
                        <i class="ico you" style="position: absolute; top: 31px; left: 6px;"></i>
                        <input class="w-80 you" style="padding: 0 15px 0 27px;" type="text" name="YOU">
                    </p>
                    <p class="links" style="position: relative;">
                        <span class="label">LiveJournal</span>
                        <i class="ico live" style="position: absolute; top: 31px; left: 6px;"></i>
                        <input class="w-80 lj" style="padding: 0 15px 0 27px;" type="text" name="LJ">
                    </p>
                </div>
            </div>

        <div class="js-step js-step-3" style="display: none;">
            <input type="hidden" class="js-id-uz" name="id_uz" value="">
            <div class="title"><b>Расскажите о своем образовании</b></div>
            <div id="step-3">
            <div class="row-line">
                <div class="col-4">
                    <div class="style-select">
                        <select name="select_uz" class="js-select-uz">
                            <option value="0">Выберите образование</option>
                            <option value="1">Высшее</option>
                            <option value="2">Среднее</option>
                            <option value="3">Начальное</option>
                            <option value="4">Языковые курсы</option>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <input type="text" class="js-name-uz" name="name_uz" disabled="true" value="" style="position: relative; top: -10px;">
                    <div class="auto-complit-reg"></div>
                </div>
                <div class="col-2">
                    <div class="style-select year">
                        <select name="select_start">
                            <option value="0">год начала</option>
                            <?php
                            for($n = 2027; $n > 1939; $n--) {
                            ?>
                                <option value="<?php echo $n; ?>"><?php echo $n; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="style-select year">
                        <select name="select_end">
                            <option value="0">окончание</option>
                            <?php
                            for($n = 2027; $n > 1939; $n--) {
                            ?>
                                <option value="<?php echo $n; ?>"><?php echo $n; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            </div>
            <div class="row-line">
                <div class="col-10">
                    <label class="radio" style="display: inline-block;">
                        <input class="js-law" type="checkbox" name="law" value="1">
                        <div class="radio__text"><?php echo $lavStr; ?></div>
                    </label>
                    <div class="law-text" style="color: red;" >Необходимо ознакомиться и согласиться с правилами сервиса.</div>
                </div>
                <div class="col-2" style="text-align: right;">
                    <div class="btns">
                        <button type="submit" class="button js-btn-ok-3 js-end" style="margin: 0px 15px 15px 0;"><span>готово</span></button>
                    </div>
                </div>
            </div>
        </div>

        </form>
    </div><!-- contact-form -->

</div><!-- registration-page -->

<div class="clear"></div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>