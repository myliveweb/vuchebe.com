<style>
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
	cursor: pointer;
	display: inline-block;
	font-family: 'Poppins', sans-serif;
	font-weight: 600;
	outline: none;
	position: relative;
	transition: all 0.3s;
	width: 100%;
    max-width: 738px;
}

[type="file"]:focus + label,
[type="file"] + label:hover {
    outline: none;
}
.label-img {
	color: #9f9f9f;
	font-size: 15px;
	font-weight: 500;
}
</style>
<div class="hideForm-news-edit news">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-news-news" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Редактирование новости</span></div>
				<div id="error-message-news-edit" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
				<div class="row-line">
					<div class="col-12 links" style="position: relative;">
						<div class="label">Заголовок</div>
						<input class="name js-news-edit-form error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12 links" style="position: relative;">
						<div class="label">Содержание</div>
						<textarea class="message js-news-edit-form error-reset" style="height: 250px;"></textarea>
					</div>
				</div>
				<div class="row-line mt-10 box-img" style="position: relative;">
					<div class="label add-news-img" style="color: #9f9f9f; position: absolute; right: 15px; top: -7px; border-bottom: 1px dashed #9f9f9f; cursor: pointer;">
						<input type="file" id="news_img" accept="image/*">
						<label class="label-img" for="news_img" style="	color: #9f9f9f; font-size: 15px; font-weight: 500;">добавить фото</label>
					</div>
				</div>
				<div class="row-line mb-10 mt-15 css-btn">
					<div class="col-4">
						<button type="submit" class="js-submit-news-edit" data-form="news"><span>Сохранить</span></button>
					</div>
					<div class="col-4">
						<button type="button" style="background-color: #a7a7a7;" class="js-del-news gray" data-form="news"><span>Удалить</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit news -->