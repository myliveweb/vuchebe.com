<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

$user_id = 0;
$section = '';

$arrUri = explode('/', $_REQUEST['url']);

if($arrUri[1]) {
	if (is_numeric($arrUri[1])) {
		$user_id = (int) $arrUri[1];
	} else {
		$user_id = getUserIdByUrl($arrUri[1]);
	}
}

if($arrUri[2])
	$section = $arrUri[2];

if (!$USER->IsAuthorized() || !$user_id) {
    LocalRedirect('/');
}

$group = getGroup();

$resultChat = array();
if($group == 2) {
	$resultChat = $dbh->query('SELECT group_chat from a_chat_support WHERE group_owner = ' . $_SESSION['USER_DATA']['ID'] . ' AND del_to = 0 GROUP BY group_chat')->fetchAll();
}

$btnSupport = 0;
if($section === 'service' || $resultChat || isEdit()) {
	$btnSupport = 1;
}

if($_SESSION['USER_DATA']['PRO'] === 'Y') {
  require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_profile_pro.php');
} else {
  	require($_SERVER["DOCUMENT_ROOT"].'/include/left_menu_profile.php');
}

if($user_id == $_SESSION['USER_DATA']['ID']) {


	if($section == 'dialogs' && $_SESSION['USER_DATA']['PRO'] === 'N') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/dialogs.php');
	} elseif($section == 'educations' && $_SESSION['USER_DATA']['PRO'] === 'N') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/educations.php');
	} elseif($section == 'bookmarks' && $_SESSION['USER_DATA']['PRO'] === 'N') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/bookmarks.php');
	} elseif($section == 'events' && $_SESSION['USER_DATA']['PRO'] === 'N') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/events.php');
	} elseif($section == 'admin' && $_SESSION['USER_DATA']['PRO'] === 'N') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/admin.php');
	} elseif($section == 'neworder' && $_SESSION['USER_DATA']['PRO'] === 'Y') {
    require($_SERVER["DOCUMENT_ROOT"].'/user/neworder.php');
	} elseif($section == 'orders' && $_SESSION['USER_DATA']['PRO'] === 'Y') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/orders.php');
	} elseif($section == 'topbanner' && $_SESSION['USER_DATA']['PRO'] === 'Y') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/topbanner.php');
	} elseif($section == 'sidebanner' && $_SESSION['USER_DATA']['PRO'] === 'Y') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/sidebanner.php');
	} elseif($section == 'balance' && $_SESSION['USER_DATA']['PRO'] === 'Y') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/balance.php');
	} elseif($section == 'service') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/service.php');
	} elseif($section == 'refund' && isEdit()) {
		require($_SERVER["DOCUMENT_ROOT"].'/user/refund.php');
	} elseif($section == 'control' && isEdit()) {
		require($_SERVER["DOCUMENT_ROOT"].'/user/orders_admin.php');
	} elseif($section == 'avatar' && isEdit()) {
		require($_SERVER["DOCUMENT_ROOT"].'/user/avatar.php');
	} elseif($section == 'reviews' && isEdit()) {
		require($_SERVER["DOCUMENT_ROOT"].'/user/reviews.php');
	} elseif($section == 'check' && isEdit()) {
		require($_SERVER["DOCUMENT_ROOT"].'/user/check.php');
	} elseif($section == 'check' && $_SESSION['USER_DATA']['PRO'] === 'Y' && $_SESSION['USER_DATA']['PRO_TYPE'] === 'U') {
		require($_SERVER["DOCUMENT_ROOT"].'/user/check_pro.php');
	} elseif($section == 'spam' && isEdit()) {
		require($_SERVER["DOCUMENT_ROOT"].'/user/spam.php');
	} elseif($section == 'adduz' && isEdit()) {
		require($_SERVER["DOCUMENT_ROOT"].'/user/adduz.php');
	} else {
		if($_SESSION['USER_DATA']['PRO'] === 'Y') {
			require($_SERVER["DOCUMENT_ROOT"].'/user/user_main_pro.php');
		} else {
			require($_SERVER["DOCUMENT_ROOT"].'/user/user_main.php');
		}
	}
} else {
	$section = '';
	require($_SERVER["DOCUMENT_ROOT"].'/user/user.php');
}
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>