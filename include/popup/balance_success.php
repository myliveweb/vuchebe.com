<div class="hideForm-balance balance" style="display: none;">
	<div class="foneBg" onClick="close_form();" style="position: fixed;"></div>
  	<div class="form-open-block">
	  	<form id="form-setting" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Оплата услуг</span></div>
				<div id="error-message-setting" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
                <div class="row-line">
                    <div class="col-12">
                        <div id="textBlock" style="text-align: center; margin: 15px auto; font-size: 18px;">
                            Оплата услуг прошла успешно!
                        </div>
                    </div>
                </div>
				<div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">
					<div class="col-12" style="text-align: center;">
						<button onclick="close_form(); return false;"><span>Ок</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit ring -->