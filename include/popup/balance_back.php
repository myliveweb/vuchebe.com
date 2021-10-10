<?php
$politic = 'PROPERTY_HOLD';
$lavStr = getLawPopUpStr($politic);
?>
<style>
#form-setting .mt-10 {
    margin-top: 10px;
}
#form-setting .mt-15 {
    margin-top: 15px;
}
#form-setting .mb-10 {
    margin-bottom: 10px;
}
#form-setting .row-line input {
    color: #000000;
}
#form-setting .row-line span.error-text {
    color: red;
    margin-left: 10px;
}
/* Checkbox Style*/
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
#form-setting a.lav-href {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    position: relative;
    color: #9f9f9f;
    text-decoration: none;
    line-height: 1.4;
}
#form-setting .row-line input[type="text"]:disabled {
    background: #fafafa;
    color: #888888;
}
#form-setting .balance-label {
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
}
#form-setting .js-balance {
    margin-top: 3px;
    text-align: right;
    padding-right: 10px;
    max-width: 181px;
    color: #000000;
    display: inline-block;
}
</style>
<div class="hideForm-back back" style="display: none;">
	<div class="foneBg" onClick="close_form();" style="position: fixed;"></div>
  	<div class="form-open-block">
	  	<form id="form-setting" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Возврат денежных средств</span></div>
				<div id="error-message-setting" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
                <div class="js-start">
                    <div class="row-line">
                        <div class="col-12">
                            <div style="margin: 15px auto 10px auto; font-size: 16px;">
                                Если Вы хотите осуществить возврат денежных средств, то все заказы будут остановлены, а денежные средства будут заблокированы.
                            </div>
                            <div class="js-max-sum-div" style="margin-bottom: 10px; font-size: 16px;">Максимальная сумма для возврата<span class="js-max-sum" style="margin: 0 3px 0 6px;">800</span>руб.</div>
                        </div>
                    </div>
                    <div class="row-line mt-10">
                        <div class="col-12">
                            <div class="label"><span class="balance-label">К возврату (<span class="js-error-sum">укажите сумму</span>)</span></div>
                            <input class="js-balance" type="text"><div style="margin: 0 7px; display: inline-block;">руб.</div>
                        </div>
                    </div>
                    <div class="row-line mt-15 mb-10" style="margin-top: 25px; margin-bottom: 50px;">
                        <div class="col-12">
                            <label class="radio" style="display: inline-block;">
                                <input class="js-law" type="checkbox" name="law" value="1">
                                <div class="radio__text"><?php echo $lavStr; ?></div>
                            </label>
                            <div class="law-text" style="color: red;" >Необходимо ознакомиться и согласиться с правилами сервиса.</div>
                        </div>
                    </div>
                    <div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">
                        <div class="col-12" style="text-align: center;">
                            <button type="submit" class="js-balance-submit"><span>Возврат</span></button>
                            <button type="button" style="background-color: #a7a7a7; margin-left: 30px;" class="js-abort gray" onclick="close_form();"><span>Отмена</span></button>
                        </div>
                    </div>
                </div>
                <div class="js-end" style="display: none;">
                    <div class="row-line">
                        <div class="col-12">
                            <div style="margin: 30px auto; font-size: 18px; text-align: center;">
                                Денежные средства успешно возвращены.
                            </div>
                        </div>
                    </div>
                    <div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">
                        <div class="col-12" style="text-align: center;">
                            <button type="button" onclick="close_form(); return false;"><span>Ок</span></button>
                        </div>
                    </div>
                </div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit ring -->