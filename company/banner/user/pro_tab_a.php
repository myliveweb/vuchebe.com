<style>
#pro-auth .left {
    margin-top: 5px;
}
</style>
<form id="pro-auth" method="post" action="/login/?login=yes">
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />
	<?php $back = $_SERVER["REQUEST_URI"]; ?>
	<input type="hidden" name="backurl" value="<?=$back?>" />
	<div>
		<div class="row-line">
			<div class="col-6 mob-line">
				<div class="label">Логин</div>
				<input name="USER_LOGIN" class="name js-auth left" type="text">
			</div>
			<div class="col-6 mob-line">
				<div class="label">Пароль</div>
				<input name="USER_PASSWORD" class="pass js-auth left" type="password">
			</div>
		</div>
		<div class="row-line" style="margin: 20px -15px;">
			<div class="col-4 mob-line">
				<button type="submit" class="js-submit"><span>Войти</span></button>
			</div>
			<div class="col-8 mob-line">
				<a href="/login/?forgot_password=yes" style="line-height: 35px;">Забыли пароль?</a>
			</div>
		</div>
		<div class="bottom-form" style="padding: 15px 0 0;">
			Нет бизнес-аккаунта? <a href="#" class="pro-switch" data-tab="pro-r" style="margin-left: 10px;">Регистрация</a>
		</div>
	</div>
</form>