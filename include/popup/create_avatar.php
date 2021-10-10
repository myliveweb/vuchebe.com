<div class="hideForm-avatar avatar" style="display: none; z-index: 920;">
	<div class="foneBg" onClick="close_form_ug();" style="position: fixed;"></div>
  	<div class="form-open-block">
	  	<form id="form-setting" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Изменение аватара</span></div>
				<div id="error-message-setting" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
                <div class="row-line">
                    <div class="col-12" style="text-align: center;">
                        <div style="text-align: center; margin: 15px auto; font-size: 18px; position: relative;">
                            <div id="upload-input" data-avatar="" style=" margin: 0 auto;"></div>
                            <div class="section__item">
                                <div class="loader01"></div>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">
					<div class="col-12" style="text-align: center;">
                        <button type="button" class="js-avatar-submit"><span>Применить</span></button>
                        <button type="button" style="background-color: #a7a7a7; margin-left: 30px;" class="js-abort gray" onclick="close_form_ug();"><span>Отмена</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form_ug();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit ring -->