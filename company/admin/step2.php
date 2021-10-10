<?php
$error = array();

if(mb_strlen($input['name']) < 2){
    $error['name'] = 'Введите ваше имя';
}

if(mb_strlen($input['lname']) < 2){
    $error['lname'] = 'Введите вашу фамилию';
}

if(mb_strlen($input['sname']) < 2){
    $error['sname'] = 'Введите ваше отчество';
}

if(!preg_match("/^(.*)?@vuchebe\.com$/", $input['email'])) {
    $error['email'] = 'Адрес электронной почты должен заканчиваться на @vuchebe.com';
}

if(!$error['email'] && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)){
    $error['email'] = 'Адрес электронной почты введён некорректно';
}

$filter = array("ACTIVE" => "Y", "EMAIL" => $input['email']);
$rsUsers = CUser::GetList($userBy, $userOrder, $filter);
if($res = $rsUsers->Fetch()) {
    $error['email'] = 'Такой Email уже занят';
}

if(mb_strlen($input['password']) < 6 || mb_strlen($input['password']) > 10){
    $error['password'] = 'Пароль должен иметь длину от 6 до 10 символов';
}

if(!$error['password'] && !preg_match("/^[_a-zA-Z\d]+$/", $input['password'])) {
    $error['password'] = 'Допустимые символы для пароля _ a-z A-Z 0-9';
}

if(!$error['password'] && $input['password'] != $input['confirm_password']) {
    $error['confirm_password'] = 'Пароль и повтор пароля не совпадают';
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
    require($_SERVER["DOCUMENT_ROOT"] . '/company/admin/step1.php');
} else {

    global $USER;
    global $DB;

    $activateStr = randString(32);

    $bday = '';

    $day   = $input['day'];
    $month = $input['month'];
    $year  = $input['year'];

    if($day || $month || $year) {
        $bday = $day . '.' . $month . '.' . $year;
    }

    $arFields = array(
        "NAME"              => $input['name'],
        "LAST_NAME"         => $input['lname'],
        "SECOND_NAME"       => $input['sname'],
        "EMAIL"             => $input['email'],
        "LOGIN"             => $input['email'],
        "LID"               => SITE_ID,
        "ACTIVE"            => "N",
        "GROUP_ID"          => array(2, 8),
        "PASSWORD"          => $input['password'],
        "CONFIRM_PASSWORD"  => $input['password'],
        "CHECKWORD" 		=> md5(CMain::GetServerUniqID().uniqid()),
        "~CHECKWORD_TIME"   => $DB->CurrentTimeFunction(),
        "CONFIRM_CODE" 	    => randString(8),
        "PERSONAL_CITY"     => $input['city'],
        "PERSONAL_PHONE"    => $input['phone'],
        "PERSONAL_ICQ"      => $input['icq'],
        "PERSONAL_BIRTHDAY" => $bday,
        "PERSONAL_MAILBOX"  => $input['email'],
        "PERSONAL_NOTES"    => $input['notes'],
        "WORK_CITY"         => $input['r_city'],
        "UF_VK"             => $input['vk'],
        "UF_FB"      		=> $input['fb'],
        "UF_OK"     		=> $input['ok'],
        "UF_TW"             => $input['tw'],
        "UF_INST"      	    => $input['inst'],
        "UF_YOU"     		=> $input['you'],
        "UF_LJ"     		=> '',
        "UF_ACTIVATE"		=> $activateStr,
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
?>
<div>
    <div id="error-message-moderate" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
    <div class="row-line">
        <div class="col-12" style="text-align: center; margin: 46px auto; font-size: 16px; line-height: 22px;">
            Для продолжения регистрации вам неоходимо перейти по ссылке,<br>
            которая была выслана на Email <?php echo $input['email']; ?><br>

        </div>
    </div>
</div>
<?php
}
?>
