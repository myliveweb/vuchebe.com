<div class="hideForm-vuz-edit license">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-vuz-license" method="post" action="">
			<div>
				<div class="more-title">Лицензии</div>
				<div class="name_form text-center"><span>Редактирование ВУЗа</span></div>
				<div id="error-message-vuz-edit" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;">ыварваораотапоап</div>
				<div class="row-line">
					<div class="col-12">
						<div class="label">Тип учебного заведения</div>
						<select class="gov js-vuz-edit-form">
							<option value="">Неустановлено</option>
							<option value="2">Негосударственный вуз</option>
							<option value="3">Государственный вуз</option>
						</select>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="label" style="padding: 0 15px;">Государственная аккредитация</div>
					<div class="col-4">
						<input class="ga-num js-vuz-edit-form" type="text" placeholder="№">
					</div>
					<div class="col-4">
						<input class="ga-start js-vuz-edit-form" type="text" placeholder="От">
					</div>
					<div class="col-4">
						<input class="ga-end js-vuz-edit-form" type="text" placeholder="До">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Свидетельство о государственной аккредитации</div>
						<input class="ga-svid js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="label" style="padding: 0 15px;">Лицензия</div>
					<div class="col-4">
						<input class="licese-num js-vuz-edit-form" type="text" placeholder="№">
					</div>
					<div class="col-4">
						<input class="licese-start js-vuz-edit-form" type="text" placeholder="От">
					</div>
					<div class="col-4">
						<input class="licese-end js-vuz-edit-form" type="text" placeholder="До">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Ссылка на Лицензию</div>
						<input class="licese-link js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="label" style="padding: 0 15px;">Аккредитация</div>
					<div class="col-4">
						<input class="akk-num js-vuz-edit-form" type="text" placeholder="№">
					</div>
					<div class="col-4">
						<input class="akk-start js-vuz-edit-form" type="text" placeholder="От">
					</div>
					<div class="col-4">
						<input class="akk-end js-vuz-edit-form" type="text" placeholder="До">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Ссылка на Аккредитацию</div>
						<input class="ga-link js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Учредитель</div>
						<input class="uchreditel js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Должность руководителя</div>
						<select class="rukovodstvo js-vuz-edit-form">
						</select>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Ф.И.О. руководителя</div>
						<input class="fio-rukovodstvo js-vuz-edit-form" type="text">
					</div>
				</div>
				<div class="row-line mb-10 mt-15 css-btn">
					<div class="col-4">
						<button type="submit" class="js-submit-vuz-edit" data-form="license"><span>Сохранить</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-vuz-edit license -->