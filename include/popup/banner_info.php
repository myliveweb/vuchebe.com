<style>
#banner-info .params-banner-top {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 10px;
}
#banner-info .params-banner {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 5px;
}

#banner-info .params-banner a {
    color: #ff471a;
}

#banner-info .params-banner a.color-silver {
    color: #9f9f9f;
}

#banner-info .color-silver {
    color: #9f9f9f;
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    margin-right: 15px;
}
#form-banner-info .color-silver-pdf {
    color: #9f9f9f;
    cursor: pointer;
    font-size: 16px;
}
#form-banner-info .head-line {
    margin-top: 15px;
    margin-bottom: 20px;
}
#form-banner-info .head-line div span {
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
    font-size: 17px;
}
#banner-info-list {
    height: 200px;
    overflow: auto;
}
#banner-info-list .one-line {
    margin-bottom: 10px;
    font-size: 15px;
}
#banner-info-list .one-line .red-line {
    color: red;
    text-align: right;
}
#banner-info-list .one-line .silver-line {
    color: #9f9f9f;
    text-align: right;
}
</style>
<div class="hideForm-banner-info banner-info" style="display: none;">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-banner-info" method="post" action="" style="width: 600px;">
			<div style="padding-bottom: 20px; max-height: 600px; overflow: auto;" id="box-line" class="line">
				<div class="name_form text-center"><span></span></div>
                <div class="row-line mb-10 mt-15" style="margin-top: 10px;">
                    <div class="col-12" id="banner-info"></div>
                </div>
                <div class="row-line head-line">
                    <div class="col-4"><span style="margin-left: 15px;">Дата (Время)</span></div>
                    <div class="col-3"><span style="margin-left: 30px;">Сумма</span></div>
                    <div class="col-5"><span>Описание</span></div>
                </div>
                <div class="row-line mb-10 mt-15 list-line" style="margin-top: 10px; margin-bottom: 10px;">
                    <div class="col-12" id="banner-info-list"></div>
                </div>
				<div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">
					<div class="col-4" style="text-align: center;">
						<button type="button" onclick="close_form();"><span>Закрыть</span></button>
					</div>
                    <div class="col-8" style="text-align: center; padding-top: 10px;">
                        <a href="javascript:void(0);" class="color-silver-pdf">Скачать детализацию заказа (PDF)</a>
                    </div>
				</div>
			</div>
			<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
	  	</form>
  	</div>
</div><!-- hideForm tarif -->