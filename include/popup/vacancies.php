<div class="hideForm-vacancies vacancies">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-vacancies" method="post" action="">
  			<input class="vuz-id" name="vuz_id" type="hidden" value="" />
  			<input class="vac-id" name="vac_id" type="hidden" value="" />
			<div>
				<div class="name_form text-center"><span>Редактирование вакансии</span></div>
				<div id="error-message-vacancies" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;">ыварваораотапоап</div>
				<div class="row-line">
					<div class="col-12">
						<div class="label">Название</div>
						<input class="name" name="name" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-6">
						<div class="label">Телефон</div>
						<input class="phone-vacancies" name="phone" type="text">
					</div>
					<div class="col-6">
						<div class="label">E-mail</div>
						<input class="e-mail" name="email" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Кнтактное лицо</div>
						<input class="contacts" name="contacts" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Факультет</div>
						<input class="spec" name="spec" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12 links" style="position: relative;">
						<div class="label">Описание</div>
						<textarea class="message" name="message" style="height: 100px;"></textarea>
					</div>
				</div>
				<div class="row-line mb-10 mt-15 css-btn">
					<div class="col-4">
						<button type="submit" class="js-submit-vacancies" data-form="vacancies"><span>Сохранить</span></button>
					</div>
					<div class="col-4">
						<button type="button" style="background-color: #a7a7a7; display: none;" class="js-abort gray" onclick="close_form();"><span>Отменить</span></button>
					</div>
					<div class="col-4">
						<button type="button" style="background-color: #a7a7a7; display: none;" class="js-del-vacancies gray" data-form="vacancies"><span>Удалить</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit fakultets -->