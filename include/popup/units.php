<div class="hideForm-news-edit units">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-news-units" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Редактирование подразделений</span></div>
				<div id="error-message-news-edit" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;">ыварваораотапоап</div>
				<div class="row-line">
					<div class="col-12">
						<div class="label">Заголовок</div>
						<input class="name js-news-edit-form error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-4">
						<div class="label">ID ВУЗа</div>
						<input class="id-v js-news-edit-form error-reset" type="text">
					</div>
					<div class="col-4">
						<div class="label">ID колледжа</div>
						<input class="id-k js-news-edit-form error-reset" type="text">
					</div>
					<div class="col-4">
						<div class="label">ID школы</div>
						<input class="id-s js-news-edit-form error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-4">
						<div class="label">Телефон</div>
						<input class="phone-units js-news-edit-form error-reset" type="text">
					</div>
                    <div class="col-4">
                        <div class="label">Метро</div>
                        <input class="metro js-news-edit-form error-reset" type="text">
                    </div>
                    <div class="col-4">
                        <div class="label">Email</div>
                        <input class="e-mail js-news-edit-form error-reset" type="text">
                    </div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Ссылка</div>
						<input class="link js-news-edit-form error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Адрес</div>
						<input class="adress js-news-edit-form error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Текст</div>
						<input class="text js-news-edit-form error-reset" type="text">
					</div>
				</div>
				<div class="row-line mb-10 mt-15 css-btn">
					<div class="col-4">
						<button type="submit" class="js-submit-news-edit" data-form="units"><span>Сохранить</span></button>
					</div>
					<div class="col-4">
						<button type="button" style="background-color: #a7a7a7;" class="js-del-news gray" data-form="units"><span>Удалить</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit units -->