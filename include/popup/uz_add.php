<style>
.auto-complit-country,
.auto-complit-region,
.auto-complit-city {
	position: absolute;
	top: 47px;
	left: 15px;
	max-height: 200px;
	width: 466px;
	background-color: #fff;
	border-right: 1px solid #ff471a;
	border-bottom: 1px solid #ff471a;
	border-left: 1px solid #ff471a;
	border-radius: 0px 0px 4px 4px;
	z-index: 100;
	padding: 4px 0px;
	display: none;
}
.auto-complit-country .item,
.auto-complit-region .item,
.auto-complit-city .item {
	width: 100%;
	color: #000;
	padding: 8px 15px;
	height: 30px;
	cursor: pointer;
	white-space: nowrap;
}
.auto-complit-country .item div,
.auto-complit-region .item div,
.auto-complit-city .item div {
	overflow: hidden;
}
.auto-complit-country .item:hover,
.auto-complit-region .item:hover,
.auto-complit-city .item:hover {
	background-color: #e0e0e0;
}
.js-block {
	display: none;
}
</style>
<div class="hideForm-news-edit uz-add">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-news-uz-add" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Добавление учебного заведения</span></div>
				<div id="error-message-vuz-add" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
				<div class="row-line">
					<div class="col-12">
						<div class="label">Образование</div>
						<select class="obr js-news-edit-form" name="obr" style="color: black;">
							<option value="0">Неустановлено</option>
							<option value="2">Высшее</option>
							<option value="3">Среднее</option>
							<option value="4">Начальное</option>
							<option value="6">Языковые курсы</option>
						</select>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Страна</div>
                        <input class="country-id" name="country_id`" type="hidden" value="0">
						<input class="country js-news-edit-form" name="country`" type="text" style="color: black;">
                        <div class="auto-complit-country" style="overflow: auto;"></div>
					</div>
				</div>
                <div class="row-line mt-10">
                    <div class="col-12">
                        <div class="label">Регион</div>
                        <input class="region-id" name="region_id`" type="hidden" value="0">
                        <input class="region js-news-edit-form" name="region" type="text" style="color: black;">
                        <div class="auto-complit-region" style="overflow: auto;"></div>
                    </div>
                </div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Город</div>
                        <input class="city-id" name="city_id`" type="hidden" value="0">
						<input class="city js-news-edit-form" name="city" type="text" style="color: black;">
                        <div class="auto-complit-city" style="overflow: auto;"></div>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12" style="position: relative;">
						<div class="label" id="naz">Название учебного заведения</div>
						<input class="name js-news-edit-form" name="name" type="text" style="color: black;">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Адрес учебного заведения</div>
						<input class="adress js-news-edit-form" name="adress" type="text" style="color: black;">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Телефон учебного заведения</div>
						<input class="tel js-news-edit-form" name="tel" type="text" style="color: black;">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Сайт учебного заведения</div>
						<input class="site js-news-edit-form" name="site" type="text" style="color: black;">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Ваш Email (для обратной связи)</div>
						<input class="email js-news-edit-form" name="email" type="text" style="color: black;">
					</div>
				</div>
				<div class="row-line mt-10">
	                <div class="col-12">
	                    <div class="contact-form-footer" style="display: inline-block;">
	                        <div class="st-captcha left" style="padding-top: 14px; margin: 0 20px 0 0; width: 250px;">
	                            <?php $CaptchaCode = htmlspecialcharsbx($APPLICATION->CaptchaGetCode()); ?>
	                            <span class="image brd capcha_img" style="display: inline-block;"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$CaptchaCode?>" alt="img"></span>
	                            <a href="#" id="cb" class="capcha_button" style="margin-left: 10px; bottom: 5px; position: relative;"><img src="<?=SITE_TEMPLATE_PATH?>/images/reload.png"></a>
	                        </div>
	                        <div class="st-captcha-input left" style="display: inline-block;">
	                            <span class="label">Введите цифры с картинки</span>
	                            <input type="hidden" name="captcha_sid" class="captcha_sid" value="<?=$CaptchaCode?>">
	                            <input style="width: 180px; top: 7px; position: relative;" type="text" class="captcha_word" name="captcha_word">
	                        </div>
	                    </div>
	                </div>
                </div>
				<div class="row-line mb-10 mt-15 css-btn">
					<div class="col-4">
						<button type="submit" class="js-submit-uz-add-edit" data-form="uz-add"><span>Сохранить</span></button>
					</div>
					<div class="col-4">
						<button type="button" style="background-color: #a7a7a7;" class="js-del-uz-add gray" onclick="close_form();"><span>Отменить</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit uz -->