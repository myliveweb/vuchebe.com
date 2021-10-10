<div class="hideForm-admins admins">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-admins" method="post" action="">
	  		<input class="vuz-id" name="vuz_id" type="hidden" value="" />
	  		<input class="old-id" name="old_id" type="hidden" value="" />
			<div>
				<div class="name_form text-center"><span>Редактирование администратора</span></div>
				<div id="error-message-admins" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
				<div class="row-line mb-10">
					<div class="col-12">
						<div class="label">ID администратора</div>
						<input class="admin-id" name="admin_id" type="text" value="" />
					</div>
				</div>
				<div class="row-line mb-10 mt-15 css-btn">
					<div class="col-4">
						<button type="submit" class="js-submit-admins" data-form="admins"><span>Сохранить</span></button>
					</div>
					<div class="col-4">
						<button type="button" style="background-color: #a7a7a7; display: none;" class="js-abort gray" onclick="close_form();"><span>Отменить</span></button>
					</div>
					<div class="col-4">
						<button type="button" style="background-color: #a7a7a7; display: none;" class="js-del-admins gray" data-form="admins"><span>Удалить</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit fakultets -->