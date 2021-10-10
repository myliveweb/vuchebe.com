<form id="form-moderate" method="post" action="/company/admin/">
    <input type="hidden" name="step" value="2">
    <input type="hidden" name="hash" value="<?php echo $hash['hash']; ?>">
    <input type="hidden" name="id_user" value="<?php echo $hash['id']; ?>">
    <div>
        <div id="error-message-moderate" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
        <div class="row-line">
            <div class="col-12">
                <div class="label">Имя<span class="error-text error-name"><?php if($error['name']) { echo $error['name']; } ?></span></div>
                <input name="name" class="name js-moderate" type="text" value="<?php echo $hash['name']; ?>" disabled="true" />
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">Фамилия<span class="error-text error-lname"><?php if($error['lname']) { echo $error['lname']; } ?></span></div>
                <input name="lname" class="lname js-moderate" type="text" value="<?php echo $input['lname']; ?>" />
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">Отчество<span class="error-text error-sname"><?php if($error['sname']) { echo $error['sname']; } ?></span></div>
                <input name="sname" class="sname js-moderate" type="text" value="<?php echo $input['sname']; ?>" />
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">Логин<span class="error-text error-login"><?php if($error['login']) { echo $error['login']; } ?></span></div>
                <input name="login" class="login js-moderate" type="text" value="<?php echo $hash['login']; ?>" disabled="true" />
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">Email<span class="error-text error-email"><?php if($error['email']) { echo $error['email']; } ?></span></div>
                <input name="email" class="email js-moderate" type="text" value="<?php echo $hash['email']; ?>" disabled="true" />
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">Пароль<span class="error-text error-password"><?php if($error['password']) { echo $error['password']; } ?></span></div>
                <input name="password" class="password js-moderate" type="text" value="<?php echo $hash['password']; ?>" disabled="true" />
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">Телефон<span class="error-text error-phone"><?php if($error['phone']) { echo $error['phone']; } ?></span></div>
                <input name="phone" class="phone js-moderate" type="text" value="<?php echo $input['phone']; ?>">
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-4">
                <div class="label">Число<span class="error-text error-day"><?php if($error['day']) { echo $error['day']; } ?></span></div>
                <select name="day" class="day js-moderate" style="color: black;">
                    <option value="0">Выберите</option>
                    <?php
                    for($n = 1; $n < 32; $n++) {
                        ?>
                        <option value="<?php echo $n; ?>"<?php if($input['day'] == $n) { echo ' selected'; } ?>><?php echo $n; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-4">
                <div class="label">Месяц<span class="error-text error-month"><?php if($error['month']) { echo $error['month']; } ?></span></div>
                <select name="month" class="month js-moderate" style="color: black;">
                    <option value="0">Выберите</option>
                    <option value="1"<?php if($input['month'] == 1) { echo ' selected'; } ?>>Январь</option>
                    <option value="2"<?php if($input['month'] == 2) { echo ' selected'; } ?>>Февраль</option>
                    <option value="3"<?php if($input['month'] == 3) { echo ' selected'; } ?>>Март</option>
                    <option value="4"<?php if($input['month'] == 4) { echo ' selected'; } ?>>Апрель</option>
                    <option value="5"<?php if($input['month'] == 5) { echo ' selected'; } ?>>Май</option>
                    <option value="6"<?php if($input['month'] == 6) { echo ' selected'; } ?>>Июнь</option>
                    <option value="7"<?php if($input['month'] == 7) { echo ' selected'; } ?>>Июль</option>
                    <option value="8"<?php if($input['month'] == 8) { echo ' selected'; } ?>>Август</option>
                    <option value="9"<?php if($input['month'] == 9) { echo ' selected'; } ?>>Сентябрь</option>
                    <option value="10"<?php if($input['month'] == 10) { echo ' selected'; } ?>>Октябрь</option>
                    <option value="11"<?php if($input['month'] == 11) { echo ' selected'; } ?>>Ноябрь</option>
                    <option value="12"<?php if($input['month'] == 12) { echo ' selected'; } ?>>Декабрь</option>
                </select>
            </div>
            <div class="col-4">
                <div class="label">Год<span class="error-text error-year"><?php if($error['year']) { echo $error['year']; } ?></span></div>
                <select name="year" class="year js-moderate" style="color: black;">
                    <option value="0">Выберите</option>
                    <?php
                    for($n = 2012; $n > 1939; $n--) {
                        ?>
                        <option value="<?php echo $n; ?>"<?php if($input['year'] == $n) { echo ' selected'; } ?>><?php echo $n; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">Статус</div>
                <input name="icq" class="icq js-moderate" type="text" value="<?php echo $input['icq']; ?>">
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">Страна<span class="error-text error-country"><?php if($error['country']) { echo $error['country']; } ?></span></div>
                <select name="country" class="country js-moderate" style="color: black;">
                    <option value="0">Выберите</option>
                    <?php
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
                <div class="label">Регион<span class="error-text error-region"><?php if($error['region']) { echo $error['region']; } ?></span></div>
                <select name="region" class="region js-moderate" style="color: black;">
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
                <div class="label">Город<span class="error-text error-city"><?php if($error['city']) { echo $error['city']; } ?></span></div>
                <input name="city_id" class="city-id js-moderate" type="hidden" value="<?php echo $input['city_id']; ?>" />
                <input name="city" style="color: black;" class="city js-moderate" type="text" value="<?php echo $input['city']; ?>" />
                <div class="auto-complit" style="overflow: auto;"></div>
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">Родной город</div>
                <input name="r_city" class="r-city js-moderate" type="text" value="<?php echo $input['r-city']; ?>">
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-4">
                <div class="label">Вконтакте</div>
                <input name="vk" class="vk js-moderate" type="text" value="<?php echo $input['vk']; ?>">
            </div>
            <div class="col-4">
                <div class="label">Facebook</div>
                <input name="fb" class="fb js-moderate" type="text" value="<?php echo $input['fb']; ?>">
            </div>
            <div class="col-4">
                <div class="label">Однокласники</div>
                <input name="ok" class="ok js-moderate" type="text" value="<?php echo $input['ok']; ?>">
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-4">
                <div class="label">Twitter</div>
                <input name="tw" class="tw js-moderate" type="text" value="<?php echo $input['tw']; ?>">
            </div>
            <div class="col-4">
                <div class="label">Instagram</div>
                <input name="inst" class="inst js-moderate" type="text" value="<?php echo $input['inst']; ?>">
            </div>
            <div class="col-4">
                <div class="label">Youtube</div>
                <input name="you" class="you js-moderate" type="text" value="<?php echo $input['you']; ?>">
            </div>
        </div>
        <div class="row-line mt-10">
            <div class="col-12">
                <div class="label">О себе</div>
                <textarea name="notes" class="notes js-moderate"><?php echo $input['notes']; ?></textarea>
            </div>
        </div>
        <div class="contact-form-footer">
            <div class="st-captcha left" style="margin-top: 23px;">
                <?php $CaptchaCode = htmlspecialcharsbx($APPLICATION->CaptchaGetCode()); ?>
                <span class="image brd capcha_img" style="display: inline-block;"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$CaptchaCode?>" alt="img"></span>
                <a href="#" id="cb" class="capcha_button"><img src="<?=SITE_TEMPLATE_PATH?>/images/reload.png"></a>
            </div>
            <div class="st-captcha-input left" style="margin-top: 14px;">
                <span class="label">Введите цифры с картинки</span>
                <input type="hidden" name="captcha_sid" class="captcha_sid" value="<?=$CaptchaCode?>">
                <input type="text" class="captcha_word" name="captcha_word" style="margin-top: 6px; width: 100%;">
            </div>
        </div>
        <div class="row-line mt-15 mb-10">
            <div class="col-12">
                <label class="radio" style="display: inline-block;">
                    <input class="js-law" type="checkbox" name="law" value="1">
                    <div class="radio__text">Я соглашаюсь с правилами бла-бла-бла...</div>
                </label>
                <a href="/company/law/" style="margin-left: 20px; cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: -2px; color: #9f9f9f; text-decoration: none;" title="Правила сервиса" target="blank">Правила сервиса</a>
                <div class="law-text" style="color: red;" >Необходимо ознакомиться и согласиться с правилами сервиса.</div>
            </div>
        </div>
        <div class="row-line mb-10" style="margin-top: 25px;">
            <div class="col-4">
                <button type="submit" class="js-submit-moderate-end"><span>Отправить</span></button>
            </div>
        </div>
    </div>
</form>