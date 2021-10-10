<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация администратора");

global $USER;

$user_id = 0;
$user_name = '';
$pageAdmin = 0;

if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_company.php');
?>
<style>
#form-moderate .mt-10 {
    margin-top: 10px;
}
#form-moderate .mt-15 {
    margin-top: 15px;
}
#form-moderate .mb-10 {
    margin-bottom: 10px;
}
#form-moderate .row-line input {
    color: #000000;
}
#form-moderate .row-line span.error-text {
    color: red;
    margin-left: 10px;
}
/* Checkbox Style*/
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
.law-text {
    color: #9f9f9f;
    margin-top: 15px;
    display: none;
}
#form-moderate .row-line input[type="text"]:disabled {
    background: #fafafa;
    color: #888888;
}
#form-moderate a.lav-href {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    position: relative;
    color: #9f9f9f;
    text-decoration: none;
    line-height: 1.4;
}
</style>
<div class="st-content-right">
    <div class="breadcrumbs">
        <a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/company/">Компания</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Регистрация администратора</span>
    </div>
    <div class="page-content">
        <div class="st-content-bottom clear">
            <div class="name-block text-left"> &nbsp;&nbsp;<span>Регистрация администратора</span></div>
            <?php
                $input = filter_input_array(INPUT_POST);

                if($input['step'] == 1) {
                    require($_SERVER["DOCUMENT_ROOT"] . '/company/admin/step2.php');
                } else {
                    require($_SERVER["DOCUMENT_ROOT"] . '/company/admin/step1.php');
                }
            ?>
        </div><!-- st-content-bottom -->
    </div><!-- page-item -->
</div><!-- st-content-right -->
<div class="clear"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>