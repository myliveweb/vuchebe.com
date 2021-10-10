<style>
.js-hidden {
	position: absolute;
	top: -2px;
	right: 0px;
	cursor: pointer;
	border-bottom: 1px dashed #9f9f9f;
}
#form-block-user .name_form {
	color: #000000;
}
</style>
<div class="hideForm-news-edit block-user">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-block-user" method="post" action="">
			<div style="padding-bottom: 20px; max-height: 600px; overflow: auto;" id="box-line" class="line">
				<div class="name_form text-center"><span>Заблокированные пользователи</span></div>
			</div>
			<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
	  	</form>
  	</div>
</div><!-- hideForm baloon -->