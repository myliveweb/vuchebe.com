<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$politic = 'PROPERTY_VAK';
$lavStr = getLawPopUpStr($politic);
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

.law-text {
    color: #9f9f9f;
    margin-top: 15px;
    display: none;
}

#form-jobs .label {
    margin-bottom: 0px;
}

#form-jobs .links {
	margin-top: 14px;
}

#form-jobs .links.add {
	margin-top: 0px;
}

#form-jobs .add-link {
	padding: 0 10px;
	line-height: 30px;
	bottom: 15px;
	position: absolute;
	right: 15px;
}

#form-jobs .add-link span {
	text-decoration: none;
	font-weight: 900;
}
#form-jobs a.lav-href {
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
			<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/company/">Компания</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/company/job/">Вакансии</a> <i class="fa fa-angle-double-right color-orange"></i> <?=$arResult["NAME"]?>
	</div>
	<div class="page-content">
		<div class="st-content-bottom clear">
			<div class="name-block text-left"> &nbsp;&nbsp;<span><?=$arResult["NAME"]?></span></div>

			<div class="news-detail" style="margin-bottom: 30px;">
				<?echo $arResult["DETAIL_TEXT"];?>
				<div style="clear:both"></div>
			</div>

			<div id="error-message" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
			<div class="contact-form bg-silver">
				<form id="form-jobs" method="post">
					<input type="hidden" class="js-name" name="name" value="<?=$arResult["NAME"]?>" />
					<div class="row-line">
						<div class="col-4 mob-line">
							<span class="label">Фамилия</span>
							<input class="w-80 left last_name" type="text" name="last_name" value="<?=$_POST['last_name']?>">
						</div>
						<div class="col-4 mob-line">
							<span class="label">Имя</span>
							<input class="w-80 left first_name" type="text" name="first_name" value="<?=$_POST['first_name']?>">
						</div>
						<div class="col-4 mob-line">
							<span class="label">Отчество</span>
							<input class="w-80 left second_name" type="text" name="second_name" value="<?=$_POST['second_name']?>">
						</div>
					</div>
					<div class="row-line">
						<div class="col-6 mob-line">
							<span class="label">Электронная почта</span>
							<input class="w-80 left email" type="text" name="email" value="<?=$_POST['email']?>">
						</div>
						<div class="col-6 mob-line">
							<span class="label">Телефон</span>
							<input class="w-80 left phone" type="text" name="phone" value="<?=$_POST['phone']?>">
						</div>
					</div>
					<div class="row-line">
						<div class="col-11 js-box-links">
							<span class="label">Ссылки на ваше резюме</span>
							<input class="w-80 left links" type="text" name="links[]" value="">
						</div>
						<div class="col-1">
							<button class="button right txt-up add-link" type="submit">
								<span>+</span>
							</button>
						</div>
					</div>
					<div class="row-line">
						<div class="col-12">
							<span class="label">Ваш ответ</span>
							<textarea class="message" name="message"><?=$_POST['message']?></textarea>
						</div>
					</div>

					<div class="contact-form-footer">
						<div class="st-captcha left" style="margin-top: 10px;">
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

		            <div class="row-line">
		                <div class="col-12">
		                    <label class="radio" style="display: inline-block;">
		                        <input class="js-law" type="checkbox" name="law" value="1">
		                        <div class="radio__text"><?php echo $lavStr; ?></div>
		                    </label>
		                    <div class="law-text" style="color: red;" >Необходимо ознакомиться и согласиться с правилами сервиса.</div>
		                </div>
		            </div>
		            <div class="row-line">
		                <div class="col-12" style="text-align: right; margin-top: 20px;">
		                    <div class="btns">
								<button class="button right txt-up" type="submit">
									<span>отправить</span>
								</button>
		                    </div>
		                </div>
		            </div>
				</form>
			</div><!-- contact-form -->
		</div><!-- st-content-bottom -->
	</div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>