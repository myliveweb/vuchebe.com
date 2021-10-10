<style>
.hide-day-text,
.hide-status-text,
.hide-r-city-text,
.hide-phone-text,
.hide-email-text,
.hide-soc-text,
.hide-note-text,
.hide-pol-text {
	position: absolute;
	top: -2px;
	right: 0px;
	cursor: pointer;
	border-bottom: 1px dashed #9f9f9f;
}
.del-text,
.block-user {
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

/* Checkbox Style */
.radio input {
    position: absolute;
    z-index: -1;
    opacity: 0;
    margin: 10px 0 0 7px;
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
</style>
<div class="hideForm2 hideForm-profile-edit">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-profile" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Редактирование профиля</span></div>
				<div id="error-message-profile" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
				<div class="row-line">
					<div class="col-12">
						<div class="label">Имя</div>
						<input name="PROFILE_FNAME" class="fname js-profile error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Фамилия</div>
						<input name="PROFILE_LNAME" class="lname js-profile error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Отчество</div>
						<input name="PROFILE_SNAME" class="sname js-profile error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-4">
						<div class="label">Число</div>
						<select name="PROFILE_DAY" class="day js-profile error-reset" style="color: black;">
							<option value="0">Выберите</option>
							<?php
							for($n = 1; $n < 32; $n++) {
							?>
								<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
							<?php
							}
							?>
						</select>
					</div>
					<div class="col-4">
						<div class="label">Месяц</div>
						<select name="PROFILE_MONTH" class="month js-profile error-reset" style="color: black;">
							<option value="0">Выберите</option>
							<option value="1">Январь</option>
							<option value="2">Февраль</option>
							<option value="3">Март</option>
							<option value="4">Апрель</option>
							<option value="5">Май</option>
							<option value="6">Июнь</option>
							<option value="7">Июль</option>
							<option value="8">Август</option>
							<option value="9">Сентябрь</option>
							<option value="10">Октябрь</option>
							<option value="11">Ноябрь</option>
							<option value="12">Декабрь</option>
						</select>
					</div>
					<div class="col-4">
						<div class="label">Год
							<div data-show="скрыть ДР" class="color-silver hide-day-text">скрыть ДР</div>
							<input name="HIDE_DAY" class="hide-day" type="hidden">
						</div>
						<select name="PROFILE_YEAR" class="year js-profile error-reset" style="color: black;">
							<option value="0">Выберите</option>
							<?php
							for($n = 2012; $n > 1939; $n--) {
							?>
								<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
                <div class="row-line mt-10">
                    <div class="col-4">
                        <div class="label">Пол</div>
                        <label class="radio" style="display: inline-block; margin-top: 5px;">
                            <input class="js-law" type="radio" name="PROFILE_POL" value="M">
                            <div class="radio__text">Мужской</div>
                        </label>
                    </div>
                    <div class="col-4">
                        <div class="label">&nbsp;</div>
                        <label class="radio" style="display: inline-block; margin-top: 5px;">
                            <input class="js-law" type="radio" name="PROFILE_POL" value="F">
                            <div class="radio__text">Женский</div>
                        </label>
                    </div>
                    <div class="col-4">
                        <div class="label">
                            <div data-show="скрыть" class="color-silver hide-pol-text">скрыть</div>
                            <input name="HIDE_POL" class="hide-pol" type="hidden">
                        </div>
                    </div>
                </div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Статус
							<div data-show="скрыть" class="color-silver hide-status-text">скрыть</div>
							<input name="HIDE_STATUS" class="hide-status" type="hidden">
						</div>
						<input name="PROFILE_ICQ" class="icq js-profile" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Страна</div>
						<select name="PROFILE_COUNTRY" class="country js-profile js-profile-country" style="color: black;">
							<option value="0">Выберите</option>
							<?php
								//$_SESSION['USER_DATA']['UF_COUNTRY'] = 0;
								$arrCountry = array();
							    $arSelectCountry = array("ID", "NAME", "IBLOCK_ID");
							    $arFilterCountry = array("IBLOCK_ID" => 32, "ACTIVE" => "Y");
							    $resCountry = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilterCountry, false, $arSelectCountry);
							    while($rowCountry = $resCountry->GetNext()) {
							    ?>
								<option value="<?php echo $rowCountry['ID']; ?>"<?php if($rowCountry['ID'] === $_SESSION['USER_DATA']['UF_COUNTRY']) { echo ' selected'; } ?>><?php echo $rowCountry['NAME']; ?></option>
							    <?php
								}
							?>
						</select>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Регион</div>
						<select name="PROFILE_REGION" class="region js-profile js-profile-region" style="color: black;"<?php if(!$_SESSION['USER_DATA']['UF_COUNTRY']) { echo ' disabled'; } ?>>
							<option value="0">Выберите</option>
							<?php
							if($_SESSION['USER_DATA']['UF_COUNTRY']) {

								$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION");
								$arFilter = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "SECTION_ID" => $_SESSION['USER_DATA']['UF_COUNTRY'], "!PROPERTY_REGION" => false);
								$res = CIBlockElement::GetList(array("PROPERTY_REGION" => "ASC"), $arFilter, array("PROPERTY_REGION"));
								while($row = $res->GetNext()) {
							    ?>
								<option value="<?php echo $row["PROPERTY_REGION_VALUE"]; ?>"<?php if($row["PROPERTY_REGION_VALUE"] === $_SESSION['USER_DATA']['UF_REGION']) { echo ' selected'; } ?>><?php echo $row["PROPERTY_REGION_VALUE"]; ?></option>
							    <?php
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Город</div>
						<input name="PROFILE_CITY" id="city" style="color: black;" class="city js-profile js-profile-city" type="text">
						<div class="auto-complit" style="overflow: auto;"></div>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Родной город
							<div data-show="скрыть" class="color-silver hide-r-city-text">скрыть</div>
							<input name="HIDE_R_CITY" class="hide-r-city" type="hidden">
						</div>
						<input name="PROFILE_R_CITY" class="r-city js-profile" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Телефон
							<div data-show="скрыть" class="color-silver hide-phone-text">скрыть</div>
							<input name="HIDE_PHONE" class="hide-phone" type="hidden">
						</div>
						<input name="PROFILE_PHONE" class="phone js-profile" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Email
							<div data-show="скрыть" class="color-silver hide-email-text">скрыть</div>
							<input name="HIDE_EMAIL" class="hide-email" type="hidden">
						</div>
						<input name="PROFILE_EMAIL" class="email js-profile error-reset" type="text">
					</div>
				</div>
				<div class="row-line mt-10" style="height: 11px; margin-right: 0px;">
					<div data-show="скрыть соц.сети" class="color-silver hide-soc-text">скрыть соц.сети</div>
					<input name="HIDE_SOC" class="hide-soc" type="hidden">
				</div>
				<div class="row-line mt-10">
					<div class="col-4">
						<div class="label">Вконтакте</div>
						<input name="PROFILE_VK" class="vk js-profile" type="text">
					</div>
					<div class="col-4">
						<div class="label">Facebook</div>
						<input name="PROFILE_FB" class="fb js-profile" type="text">
					</div>
					<div class="col-4">
						<div class="label">Однокласники</div>
						<input name="PROFILE_OK" class="ok js-profile" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-4">
						<div class="label">Twitter</div>
						<input name="PROFILE_TW" class="tw js-profile" type="text">
					</div>
					<div class="col-4">
						<div class="label">Instagram</div>
						<input name="PROFILE_INST" class="inst js-profile" type="text">
					</div>
					<div class="col-4">
						<div class="label">Youtube</div>
						<input name="PROFILE_YOU" class="you js-profile" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">LiveJornal</div>
						<input name="PROFILE_LJ" class="lj js-profile" type="text">
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">О себе
							<div data-show="скрыть" class="color-silver hide-note-text">скрыть</div>
							<input name="HIDE_NOTE" class="hide-note" type="hidden">
						</div>
						<textarea name="PROFILE_NOTES" class="notes js-profile"></textarea>
					</div>
				</div>
                <div class="row-line mt-15">
                    <div class="col-12">
                        <div>
                            <span class="del-text">Удалить аватар</span>
                        </div>
                        <div class="warning-text" style="display: inline-block;">Внимание! Аватар будет удалён.</div><div class="warning-text" style="display: inline-block; margin-left: 5px;"><span class="del-action">Вы подтверждаете удаление?</span></div>
                    </div>
                </div>
				<div class="row-line mb-10 mt-15 css-btn">
					<div class="col-4">
						<button type="submit" class="js-submit-profile"><span>Сохранить</span></button>
					</div>
                    <div class="col-4">
                        <button class="gray" type="button" style="background-color: #a7a7a7;" onclick="close_form();"><span>Отмена</span></button>
                    </div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm2 -->