<div class="foneBg-2" onClick="close_form();"></div>
<div class="hideForm">
	<div class="foneBg" onClick="close_form();"></div>
  <div class="form-open-block">

  	<form id="form1" method="post" action="/login/?login=yes">
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" />
		<?php $back = $_SERVER["REQUEST_URI"]; ?>
		<input type="hidden" name="backurl" value="<?=$back?>" />
			<div>
				<div class="name_form text-center"><span>Авторизация</span></div>
				<div class="row-line">
					<div class="col-6">
					<div class="label">Логин</div>
					<input name="USER_LOGIN" class="name js-auth" type="text">
					</div>
					<div class="col-6">
						<div class="label">Пароль</div>
					<input name="USER_PASSWORD" class="pass js-auth" type="password">
					</div>
				</div>
				<br>
				<div class="row-line">
					<div class="col-4">
						<button type="submit" class="js-submit"><span>Войти</span></button>
					</div>
					<div class="col-8">
						<a href="/login/?forgot_password=yes" style="line-height: 35px;">Забыли пароль?</a>
					</div>
				</div>

				<div class="bottom-form">
					Нет аккаунта? <a href="/reg/">Регистрация</a>
				</div>

				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
  	</form>

  </div>
</div><!-- hideForm -->