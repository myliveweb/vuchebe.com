<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/popup.css">

<?php
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/head_city.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/auth.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/setting.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/new_otziv.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/new_comment.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/avatar.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/baloon.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/uz.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/uz_add.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/block_user.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/change_pass.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/popup/law.php');

if(CSite::InDir('/uchebnye-zavedeniya/')) {
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/first.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/soc.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/license.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/service.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/history.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/news.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/events.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/opendoor.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/programs.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/corpus.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/fillials.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/units.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/obchegitie.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/ring.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/sections.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/fakultets.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/admins.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/vacancies.php');
}

if(CSite::InDir('/user/')) {
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/edit_user_profile.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/no_chat.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/group_chat.php');
    require($_SERVER["DOCUMENT_ROOT"].'/include/popup/support_chat.php');
	require($_SERVER["DOCUMENT_ROOT"].'/include/popup/balance_success.php');
    require($_SERVER["DOCUMENT_ROOT"].'/include/popup/balance_back.php');
    require($_SERVER["DOCUMENT_ROOT"].'/include/popup/tarif.php');
    require($_SERVER["DOCUMENT_ROOT"].'/include/popup/banner_info.php');

    /*  Кропперы  */
    if(!$section) {
        require($_SERVER["DOCUMENT_ROOT"] . '/include/popup/create_avatar.php'); // PopUp аватара
    }
    if($section == 'topbanner') {
        require($_SERVER["DOCUMENT_ROOT"] . '/include/popup/cropp_top_banner.php'); // PopUp верхнего  баннерa
    }
    if($section == 'sidebanner') {
        require($_SERVER["DOCUMENT_ROOT"] . '/include/popup/cropp_side_banner.php'); // PopUp нижнего баннера
    }

    if($section == 'avatar' || $section == 'spam') {
        require($_SERVER["DOCUMENT_ROOT"] . '/include/popup/avatar_admin.php'); // PopUp для работы с Аватарами модераторам
    }

    if($section == 'spam') {
        require($_SERVER["DOCUMENT_ROOT"] . '/include/popup/spam_admin.php'); // PopUp для работы с Аватарами модераторам
    }

    if($section == 'reviews') {
        require($_SERVER["DOCUMENT_ROOT"] . '/include/popup/reviews_admin.php'); // PopUp для работы с Отзывами модераторам
    }

}

if(CSite::InDir('/ugolok-znaniy/')) {
    require($_SERVER["DOCUMENT_ROOT"].'/include/popup/ugolok.php');
    require($_SERVER["DOCUMENT_ROOT"].'/include/popup/create_avatar_ugolok.php');
}
?>