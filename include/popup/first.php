<style>
.red-control {
	color: red;
}
</style>
<div class="hideForm-vuz-edit first">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-vuz-first" method="post" action="">
			<div>
				<div class="more-title">Основное</div>
				<div class="name_form text-center"><span>Редактирование ВУЗа</span></div>
				<div id="error-message-vuz-edit" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;">ыварваораотапоап</div>
				<div class="row-line">
					<div class="col-12">
						<div class="label">Название</div>
						<input class="name js-vuz-edit-form error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Полное название</div>
						<input class="name-full js-vuz-edit-form error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-6">
						<div class="label">Аббревиатура</div>
						<input class="abbr js-vuz-edit-form" type="text">
					</div>
					<div class="col-6">
						<div class="label">Год основания</div>
						<input class="year js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-6">
						<div class="label">Страна</div>
						<select class="country js-vuz-edit-form">
						</select>
					</div>
					<div class="col-6">
						<div class="label">Город</div>
						<select class="city js-vuz-edit-form">
						</select>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-6">
						<div class="label">Телефон</div>
						<input class="phone-first js-vuz-edit-form" type="text">
					</div>
					<div class="col-6">
						<div class="label">Телефон приемной комиссии</div>
						<input class="phone-pk js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-6">
						<div class="label">Email</div>
						<input class="email js-vuz-edit-form" type="text">
					</div>
					<div class="col-6">
						<div class="label">Email приемной комиссии</div>
						<input class="email-pk js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-6">
						<div class="label">Сайт</div>
						<input class="site js-vuz-edit-form" type="text">
					</div>
					<div class="col-6">
						<div class="label">Электронная приемная</div>
						<input class="e-pk js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10 hide-input" style="display: none;">
					<div class="col-6">
						<div class="label">Стоимость час</div>
						<input class="hours js-vuz-edit-form" type="text">
					</div>
					<div class="col-6">
						<div class="label">Стоимость месяц</div>
						<input class="month js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10 hide-input" style="display: none;">
					<div class="col-12">
						<div class="label">Есть лицензия</div>
						<input class="license js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10 hide-input" style="display: none;">
					<div class="col-6">
						<div class="label">Бесплатный пробный урок</div>
						<input class="free js-vuz-edit-form" type="text">
					</div>
					<div class="col-6">
						<div class="label">9 человек в группе</div>
						<input class="group js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10 hide-input" style="display: none;">
					<div class="col-6">
						<div class="label">Для детей</div>
						<input class="kind js-vuz-edit-form" type="text">
					</div>
					<div class="col-6">
						<div class="label">Помесячная оплата</div>
						<input class="pay js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10 adress-first">
					<div class="col-12">
						<div class="label">Адрес</div>
						<input class="addres js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10 hide-input add-button" style="display: none;">
					<div class="col-12" style="text-align: right;">
						<span class="color-silver add-adres" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">добавить</span>
					</div>
				</div>
				<div class="row-line mb-10 mt-15 css-btn">
					<div class="col-4">
						<button type="submit" class="js-submit-vuz-edit" data-form="first"><span>Сохранить</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-vuz-edit first -->