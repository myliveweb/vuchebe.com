<style>
.radio input {
    position: absolute;
    z-index: -1;
    opacity: 0;
    margin: 10px 0 0 7px;
}
.radio__text {
    position: relative;
    padding: 3px 0 0 35px;
    cursor: pointer;
}
.radio__text:before {
    content: '';
    position: absolute;
    top: -3px;
    left: 0;
    width: 22px;
    height: 22px;
    border: 1px solid #9f9f9f;
    border-radius: 50%;
    background: #FFF;
}
.radio input:checked + .radio__text:after {
    opacity: 1;
}
.radio__text:after {
    content: '';
    position: absolute;
    top: 1px;
    left: 4px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #ff4719;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.5);
    opacity: 0;
    transition: .2s;
}
.radio-text,
.offline-text,
.color-text {
	color: #9f9f9f;
	margin-top: 15px;
	display: none;
}
.del-text,
.block-user,
.change-pass {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
}
.warning-text {
    margin-left: 15px;
    color: #9f9f9f;
    margin-top: 15px;
    display: none;
}
.del-action {
    cursor: pointer;
    border-bottom: 1px dashed red;
    color: red;
}
</style>
<div class="hideForm-setting setting">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-setting" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Настройки</span></div>
				<div id="error-message-setting" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
                <div class="row-line">
                    <div class="col-12">
                        <div>
                            <span class="change-pass">Сменить пароль</span>
                        </div>
                    </div>
                </div>
                <div class="row-line mt-15" style="margin-top: 20px;">
                    <div class="col-12">
                        <label class="radio">
                            <input class="js-offline" type="checkbox" name="offline" value="1">
                            <div class="radio__text">Отключить «В сети»</div>
                        </label>
                        <div class="offline-text">Если Вы скроете свой статус «В сети», то Вы тоже не сможете видеть статус у других пользователей.</div>
                    </div>
                </div>
                <div class="row-line mt-15">
                    <div class="col-12">
                        <label class="radio">
                            <input class="js-color-off" type="checkbox" name="color_off" value="1">
                            <div class="radio__text">Отключить "Статус о прочтении сообщений"</div>
                        </label>
                        <div class="color-text">Если Вы отключите "Статус о прочтении", то и Вы не увидите прочитаны ли Ваши сообщения.</div>
                    </div>
                </div>
                <div class="row-line mt-15">
                    <div class="col-12">
                        <label class="radio">
                            <input class="js-chat-off" type="checkbox" name="chat_off" value="1">
                            <div class="radio__text">Отключить чат</div>
                        </label>
                    </div>
                </div>
                <div class="row-line mt-15">
                    <div class="col-6">
                        <div class="label">Введите свой URL<span class="color-orange" style="margin-left: 10px; display: none;"></span></div>
                        <input class="url js-url" name="url" type="text" placeholder="">
                    </div>
                </div>
				<div class="row-line mt-15">
					<div class="col-12">
						<label class="radio">
							<input class="js-teacher" type="checkbox" name="teacher" value="1">
							<div class="radio__text">Я преподаватель</div>
						</label>
						<div class="radio-text">Укажите учебные заведения, где преподавали и Ваш статус преподавателя будет подтвержден.</div>
					</div>
				</div>
                <div class="row-line mt-10">
                    <div class="col-12">
                        <div>
                            <span class="block-user">Заблокированные пользователи</span>
                        </div>
                    </div>
                </div>
                <div class="row-line mt-15">
                    <div class="col-12">
                        <div>
                            <span class="del-text">Удалить аккаунт</span>
                        </div>
                        <div class="warning-text">Внимание! аккаунт будет удалён без возможности востановления.<div><span class="del-action">Вы подтверждаете удаление?</span></div></div>
                    </div>
                </div>
				<div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">
					<div class="col-4">
						<button type="submit" class="js-submit-setting" data-form="setting"><span>Сохранить</span></button>
					</div>
                    <div class="col-4">
                        <button class="gray" type="button" style="background-color: #a7a7a7;" onclick="close_form();"><span>Отмена</span></button>
                    </div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit ring -->