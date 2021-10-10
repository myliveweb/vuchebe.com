<div class="hideForm4">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-comment-post" method="post" action="">
	  		<input type="hidden" name="user_post" class="user_post" value="<?=$_SESSION['USER_DATA']['ID']?>">
	  		<input type="hidden" name="name_post" class="name_post" value="">
	  		<input type="hidden" name="id_vuz_post" class="id_vuz_post" value="">
	  		<input type="hidden" name="id_post" class="id_post" value="">
			<div>
				<div class="name_form text-center"><span>Новый комментарий</span></div>
				<div id="error-message-comment-post" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
				<div class="row-line mt-10">
					<div class="col-12">
						<img src="<?=$_SESSION['USER_DATA']['AVATAR']?>" alt="img" style="width: 44px; border-radius: 50%; <?php if($_SESSION['USER_DATA']['TEACHER']) { echo 'border: 2px solid #ff5b32;'; } else { echo 'border: 1px solid #ff5b32;'; } ?>">
						<div class="news-name" style="display: inline-block; position: relative; top: -7px;">
							<span><?=$_SESSION['USER_DATA']['FULL_NAME']?></span>
						</div>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<textarea name="message_post" class="message_post" class="notes js-comment-post"></textarea>
					</div>
				</div>
				<div class="row-line mb-10 mt-15">
					<div class="col-4">
						<button type="submit" class="js-submit-comment-post"><span>Отправить</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm2 -->