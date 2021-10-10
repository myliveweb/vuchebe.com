<?php
$sth = $dbh->prepare('SELECT * from a_admin WHERE id = ?');
$sth->execute(array($input['id_user']));
$hash = $sth->fetch();

$error = array();

if(mb_strlen($input['lname']) < 2){
    $error['lname'] = 'Введите вашу фамилию';
}

if(mb_strlen($input['sname']) < 2){
    $error['sname'] = 'Введите ваше отчество';
}

if(mb_strlen($input['phone']) < 2){
    $error['phone'] = 'Введите ваш телефон';
}

if($input['day'] == 0){
    $error['day'] = 'Выберите день';
}

if($input['month'] == 0){
    $error['month'] = 'Выберите месяц';
}

if($input['year'] == 0){
    $error['year'] = 'Выберите год';
}

if($input['country'] == 0){
    $error['country'] = 'Выберите страну';
}

if(!$input['region']){
    $error['region'] = 'Выберите регион';
}

if(!$input['city_id'] || !$input['city']){
    $error['city'] = 'Введите город';
    $input['city_id'] = 0;
    $input['city'] = '';
}

if($error) {
    require($_SERVER["DOCUMENT_ROOT"] . '/company/admin/confirm.php');
} else {

    global $USER;
    global $DB;

    $login = $hash['login'] . '@vuchebe.com';

    $bday = '';

    $day   = $input['day'];
    $month = $input['month'];
    $year  = $input['year'];

    if($day || $month || $year) {
        $bday = $day . '.' . $month . '.' . $year;
    }

    $avatarPath = SITE_TEMPLATE_PATH . '/img/adminava.png';
    $arFile = CFile::MakeFileArray($avatarPath);

    $arFields = array(
        "NAME"              => $hash['name'],
        "LAST_NAME"         => $input['lname'],
        "SECOND_NAME"       => $input['sname'],
        "EMAIL"             => $login,
        "LOGIN"             => $login,
        "LID"               => SITE_ID,
        "ACTIVE"            => "N",
        "GROUP_ID"          => array(2, 8),
        "PASSWORD"          => $hash['password'],
        "CONFIRM_PASSWORD"  => $hash['password'],
        "CHECKWORD" 		=> md5(CMain::GetServerUniqID().uniqid()),
        "~CHECKWORD_TIME"   => $DB->CurrentTimeFunction(),
        "CONFIRM_CODE" 	    => randString(8),
        "PERSONAL_CITY"     => $input['city'],
        "PERSONAL_PHONE"    => $input['phone'],
        "PERSONAL_ICQ"      => $input['icq'],
        "PERSONAL_BIRTHDAY" => $bday,
        "PERSONAL_MAILBOX"  => $hash['email'],
        "PERSONAL_NOTES"    => $input['notes'],
        "PERSONAL_PHOTO"    => $arFile,
        "WORK_CITY"         => $input['r_city'],
        "UF_VK"             => $input['vk'],
        "UF_FB"      		=> $input['fb'],
        "UF_OK"     		=> $input['ok'],
        "UF_TW"             => $input['tw'],
        "UF_INST"      	    => $input['inst'],
        "UF_YOU"     		=> $input['you'],
        "UF_LJ"     		=> '',
        "UF_ACTIVATE"		=> $hash['hash'],
        "UF_COUNTRY"      	=> 0,
        "UF_REGION"     	=> 0,
        "UF_CITY"     		=> 0
    );

    if($input['city_id']) {
        $arSelect = array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID", "PROPERTY_REGION");
        $arFilter = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $input['city_id']);
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        if($row = $res->GetNext()) {
            $arFields["UF_COUNTRY"] = $row["IBLOCK_SECTION_ID"];
            $arFields["UF_REGION"]  = $row["PROPERTY_REGION_VALUE"];
            $arFields["UF_CITY"] 	= $row["ID"];
        }
    }

    $CUser = new CUser;

    $USER_ID = $CUser->Add($arFields);

    if (intval($USER_ID) > 0){
        $stmt= $dbh->prepare("UPDATE a_admin SET status = 1 WHERE id = " . $hash['id']);
        $stmt->execute();

        CIBlockElement::SetPropertyValueCode($hash['bx_id'], "STATUS", "Y");
    }

    ?>
    <div>
        <div id="error-message-moderate" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
        <div class="row-line">
            <div class="col-12" style="text-align: center; color: green; margin: 46px auto 28px; font-size: 24px; line-height: 22px;">
                Вы успешно зарегистрированы
            </div>
        </div>
        <div class="row-line">
            <div class="col-5" style="text-align: right;">
                Логин:
            </div>
            <div class="col-7" style="text-align: left; padding: 0;">
                <?php echo $login; ?>
            </div>
        </div>
        <div class="row-line" style="margin-top: 7px;">
            <div class="col-5" style="text-align: right;">
                Пароль:
            </div>
            <div class="col-7" style="text-align: left; padding: 0;">
                <?php echo $hash['password']; ?>
            </div>
        </div>
        <div class="row-line" style="margin-top: 28px;">
            <div class="col-12" style="text-align: center;">
                <a href="https://vuchebe.com/bitrix/admin/">Войти в панель администраторов</a>
            </div>
        </div>
    </div>
    <?php
}
?>