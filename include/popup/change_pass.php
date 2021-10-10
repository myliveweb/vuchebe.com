<style>
.js-hidden {
	position: absolute;
	top: -2px;
	right: 0px;
	cursor: pointer;
	border-bottom: 1px dashed #9f9f9f;
}
#form-change-pass .name_form {
	color: #000000;
}
#form-change-pass .send-old-pass {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
}
#form-change-pass .js-info {
	display: none;
}
#form-change-pass .js-info div {
	text-align: center;
	color: red;
}
</style>
<div class="hideForm-news-edit change-pass">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-change-pass" method="post" action="">
			<div style="padding-bottom: 20px; max-height: 600px; overflow: auto;" id="box-line" class="line">
				<div class="name_form text-center"><span>Смена пароля</span></div>
				<div class="row-line mb-10 js-info">
					<div class="col-12"></div>
				</div>
				<div class="row-line">
					<div class="col-12">
						<div class="label">Старый пароль</div>
						<input name="old_pass" class="old-pass js-old-pass error-reset" type="password" placeholder="Введите старый пароль">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Новый пароль</div>
						<input name="new_pass" class="new-pass js-new-pass error-reset" type="password" placeholder="Не менее 6 знаков. Допустимые символы: a-z A-Z 0-9 _">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Повторите новый пароль</div>
						<input name="confirm_pass" class="confirm-pass js-confirm-pass error-reset" type="password" placeholder="Повторите новый пароль">
					</div>
				</div>
				<div class="row-line mt-15" style="margin-top: 20px;">
                    <div class="col-12">
                        <div>
                        	<span class="send-info" style="display: none; color: green;">Старый пароль выслан на ваш E-Mail</span>
                            <span class="send-old-pass">Выслать старый пароль на E-Mail</span>
                        </div>
                    </div>
                </div>
				<div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">
					<div class="col-4">
						<button type="submit" class="js-submit-change-pass"><span>Сохранить</span></button>
					</div>
					<div class="col-4">
						<button class="gray" type="button" style="background-color: #a7a7a7;" onclick="close_form();"><span>Отмена</span></button>
					</div>
				</div>
			</div>
			<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
	  	</form>
  	</div>
</div><!-- hideForm baloon -->