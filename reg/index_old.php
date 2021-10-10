<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle('В учёбе');
if ($USER->IsAuthorized()){
    //LocalRedirect('/');
}
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
                    <p>
                        <span class="label">E-mail<span class="color-orange"></span></span>
                        <input class="w-80 email error-reset" type="email" name="email" value="<?=$_POST['email']?>">
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
                </div>
                <div class="col-12">
                    <div class="contact-form-footer">
                        <div class="st-captcha left">
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

                    <div class="btns">
                        <button class="button js-btn-ok-1"><span>далее</span></button>
                    </div>

                </div>
            </div>

            <div class="row-line js-step js-step-2" style="display: none;">
                <div class="col-5">
                    <div class="title"><b>Заполните свои контактные данные</b></div>
                    <p>
                        <span class="label">Родной город</span>
                        <input class="w-80 city" type="text" name="city">
                    </p>
                    <p>
                        <span class="label">Номер телефона</span>
                        <input class="w-80 left phone" type="tel" name="phone" value="+7">
                        <span class="input-ico"><span>?</span></span>
                    </p>
                    <p>
                        <span class="label">Ссылки на профили в соц. сетях</span>
                        <input class="w-80 left soc" type="text" name="soc" value="http://">
                        <span class="input-ico plus"><span>+</span></span>
                    </p>
                    <div class="btns">
                        <button type="submit" class="button js-btn-ok-2"><span>далее</span></button>
                        <button type="submit" class="button reverse js-btn-mv-2"><span>пропустить</span></button>
                    </div>
                </div>
                <div class="col-7">
                    <div class="title"><b>Напишите немного о себе</b></div>
                    <textarea name="about"></textarea>
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
                <!--<div class="col-1">
                    <span class="input-ico plus js-plus" style="margin: 0px 0 15px 10px;"><span>+</span></span>
                </div>-->
            </div>
            </div>
            <div class="row-line">
                <div class="col-12">
                    <div class="btns">
                        <button class="button js-btn-ok-3"><span>готово</span></button>
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