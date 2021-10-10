<?php
$APPLICATION->SetTitle("Оформление заказа - Квадратный баннер");
global $USER;

$from = 0;
$arrUri = explode('/', $_REQUEST['url']);
$bannerId = (int) $arrUri[3];

$user_id = 0;
$user_name = '';
$pageAdmin = 0;
$crop = 5;

if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$banner_url = SITE_TEMPLATE_PATH . "/images/empty_banner2.png";

$politic = 'PROPERTY_ORDERS';
$lavStr = getLawPopUpStr($politic);
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
.page-content .news-hr {
    border-bottom: 1px solid #ccc;
    clear: both;
}
.page-content .page-item {
	margin-bottom: 15px;
}
.page-content .js-banner-title {
	margin-top: 15px;
	font-size: 12px;
}
.page-content .row-line {
    margin: 10px -15px;
}
.page-content select.country,
.page-content select.region {
	background-color: #fff;
    border: 1px solid #ff471a;
    border-radius: 4px;
    height: 30px;
    padding: 0 15px;
    box-sizing: border-box;
    color: #ffffff;
    width: 100%;
}
.page-content .row-line input,
.page-content .row-line select {
	width: 100%;
	max-width: 428px;
  color: #000000;
}
[type="file"] {
	border: 0;
	clip: rect(0, 0, 0, 0);
	height: 1px;
	overflow: hidden;
	padding: 0;
	position: absolute !important;
	white-space: nowrap;
	width: 1px;
}

[type="file"] + label {
	border: none;
	color: #fff;
	cursor: pointer;
	display: inline-block;
	font-family: 'Poppins', sans-serif;
	font-size: 1.2rem;
	font-weight: 600;
	outline: none;
	position: relative;
	transition: all 0.3s;
	vertical-align: middle;
	overflow: hidden;
	width: 100%;
    max-width: 738px;
}

[type="file"]:focus + label,
[type="file"] + label:hover {
    outline: none;
}

.form-banner .left {
    margin-top: 10px;
}
.form-banner .left-5 {
    margin-top: 5px;
}

.law-text {
    color: #9f9f9f;
    margin-top: 15px;
    display: none;
}

.form-banner .error-message {
	color: red;
	font-weight: 500;
	font-size: 18px;
	height: 24px;
	margin-bottom: 25px;
	display: none;
}
.form-banner .js-error-block {
  margin-left: 10px;
  font-size: 13px;
  display: none;
}
.form-banner .js-promocode-info {
    margin-left: 10px;
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

.page-content span.tarif-name {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    position: relative;
    color: #9f9f9f;
    text-decoration: none;
    line-height: 1.4;
}
</style>
<script>
  var bannerPrice = {
  <?php

  $startTax = 0;
  $startDescription = '';

  $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PLAN_ID", "PROPERTY_PRICE", "PROPERTY_MAIN_CITY", "PROPERTY_CAPITAL", "PROPERTY_DESCRIPTION");
  $arFilter = array("IBLOCK_ID" => 44, "ACTIVE" => "Y");
  $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
  while($rowPrice = $res->GetNext()) {

    if(!$rowPrice['PROPERTY_MAIN_CITY_VALUE'])
      $rowPrice['PROPERTY_MAIN_CITY_VALUE'] = 0;

    if(!$rowPrice['PROPERTY_CAPITAL_VALUE'])
      $rowPrice['PROPERTY_CAPITAL_VALUE'] = 0;

    echo "\t" . $rowPrice['PROPERTY_PLAN_ID_VALUE'] . ": { name: '" . $rowPrice['NAME'] . "', price: " . $rowPrice['PROPERTY_PRICE_VALUE'] . ", priceMainCity: " . $rowPrice['PROPERTY_MAIN_CITY_VALUE'] . ", priceCapital: " . $rowPrice['PROPERTY_CAPITAL_VALUE'] . ", description: '" . $rowPrice['PROPERTY_DESCRIPTION_VALUE'] . "'},\n";

    if($rowPrice['PROPERTY_PLAN_ID_VALUE'] === 'BASE') {

      $startTax = $rowPrice['PROPERTY_PRICE_VALUE'];
      $startDescription = $rowPrice['PROPERTY_DESCRIPTION_VALUE'];
    }
  }
  ?>
  }
</script>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>


<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/neworder/">Оформление заказа</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Квадратный баннер</span>
	</div>
  <div class="page-content form-banner success-banner" style="margin-top: 15px; display: none;">
		<div class="name-block text-left" style="margin-bottom: 0px;"> &nbsp;&nbsp;<span>Верхний баннер</span></div>
    <div class="row-line" style="margin: 80px -15px 10px -15px;">
			<div class="col-12">
				<div style="font-size: 24px; line-height: 1.3; color: green; text-align: center;">
					Ваш заказ успешно оформлен.<br/>
				</div>
			</div>
    </div>
  </div>
  <div class="page-content form-banner error-banner" style="margin-top: 15px; display: none;">
		<div class="name-block text-left" style="margin-bottom: 0px;"> &nbsp;&nbsp;<span>Верхний баннер</span></div>
    <div class="row-line" style="margin: 80px -15px 10px -15px;">
			<div class="col-12">
				<div style="font-size: 24px; line-height: 1.3; color: red; text-align: center;">
					Сайт временно недоступен для приёма заказов.<br/>
          Пожалуйста поторите через несколько минут.
				</div>
			</div>
    </div>
  </div>
	<div id="page" class="page-content form-banner start-banner" style="margin-top: 15px;">
		<div class="name-block text-left" style="margin-bottom: 0px;"> &nbsp;&nbsp;<span>Квадратный баннер</span></div>
		<div class="row-line" style="margin: 5px -15px 10px -15px;">
      <div style="height: 22px;"><span class="color-orange js-error-block js-error-img" style="margin-left: 15px;"></span></div>
			<div class="col-6">
				<div class="image brd">
					<input type="file" id="side-banner" data-type="banner" accept="image/*">
					<label for="side-banner" style="border-radius: 0;">
						<img data-type="side" class="side-banner profile-banner" style="cursor: pointer; width: 100%; max-width: 226px; max-height: 226ph; margin: 0;" src="<?php echo $banner_url; ?>" alt="">
					</label>
				</div>
				<div class="js-banner-title" style="height: 13px;">
					<?php echo $startDescription; ?>
				</div>
			</div>
			<div class="col-6">
				<div style="font-size: 12px;">
					Баннер будет отправлен на модерацию после оформления заказа.
				</div>
			</div>
		</div>
		<div class="row-line" style="margin-top: 35px;">
			<div class="col-9">
				<div class="label">Название баннера<span class="color-orange js-error-block js-error-name"></span></div>
				<input class="name js-banner-name" type="text">
			</div>
			<div class="col-3"></div>
		</div>
		<div class="row-line">
			<div class="col-9">
				<div class="label">Вставьте ссылку<span class="color-orange js-error-block js-error-link"></span></div>
				<input class="link js-banner-link" type="text">
			</div>
			<div class="col-3" style="font-size: 12px; padding-top: 18px;">
				Ссылка будет отправлена на модерацию.
			</div>
		</div>
		<div class="row-line">
			<div class="col-9">
				<div class="label">Страна<span class="color-orange js-error-block js-error-country"></span></div>
				<select name="PROFILE_COUNTRY" class="country js-banner js-banner-country" style="color: black;">
					<option value="0">Выберите</option>
					<?php
						$arrCountry = array();
					    $arSelectCountry = array("ID", "NAME", "IBLOCK_ID");
					    $arFilterCountry = array("IBLOCK_ID" => 32, "ACTIVE" => "Y");
					    $resCountry = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilterCountry, false, $arSelectCountry);
					    while($rowCountry = $resCountry->GetNext()) {
					    ?>
						<option value="<?php echo $rowCountry['ID']; ?>"><?php echo $rowCountry['NAME']; ?></option>
					    <?php
						}
					?>
				</select>
			</div>
		</div>
		<div class="row-line">
			<div class="col-9">
				<div class="label">Регион<span class="color-orange js-error-block js-error-region"></span></div>
				<select name="PROFILE_REGION" class="region js-banner js-banner-region" style="color: black;">
					<option value="0">Выберите</option>
				</select>
			</div>
		</div>
		<div class="row-line" style="margin-bottom: 20px;">
			<div class="col-9">
        <div class="label">Город<span class="color-orange js-error-block js-error-city"></span></div>
        <input type="hidden" class="js-banner-city-current" value="0" />
        <input type="hidden" class="js-banner-city-main-city" value="N" />
        <input type="hidden" class="js-banner-city-capital" value="N" />
				<input name="PROFILE_CITY" style="color: black;" class="city js-banner js-banner-city" type="text">
				<div class="auto-complit" style="overflow: auto; max-width: 428px;"></div>
			</div>
		</div>
		<div class="news-hr"></div>
		<div class="row-line">
			<div class="col-6">
				<div class="label">Колличество</div>
				<input class="price js-banner-price" type="number" min="1" max="1000000000" value="1" style="color: #000000;">
			</div>
			<div class="col-6" style="font-size: 20px; padding-top: 26px;">
				<span style="font-weight: 700;">Цена: </span><span class="js-price"><?php echo str_replace('.', ',', $startTax); ?> руб.</span>
			</div>
		</div>
        <div class="row-line" style="margin-top: 15px;">
            <div class="col-12">
                <div class="label">Выбранный тариф: <span class="tarif-name js-tarif" data-tarif="BASE" data-type="side"></span> (<span class="js-cost"></span>)</div>
            </div>
        </div>
        <div class="row-line">
            <div class="label col-12" style="margin-bottom: 7px;">Промокод<span class="js-promocode-info"></span></div>
            <div class="col-6">
                <input class="promocode js-banner-promocode" type="text" value="" placeholder="Введите и нажмите применить" style="color: #000000;">
            </div>
            <div class="col-6">
                <button style="line-height: 30px;" type="button" class="js-add-promocode"><span>Применить</span></button>
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
            <div class="law-text" style="color: red;" >Необходимо ознакомиться и согласиться с правилами рекламы.</div>
        </div>
    </div>
    <div class="row-line" style="margin: 25px -15px; text-align: right;">
			<div class="col-12">
				<button type="submit" class="js-add-banner"><span>Оформить заказ</span></button>
			</div>
		</div>
	</div><!-- page-item -->
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>