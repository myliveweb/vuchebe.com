<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

global $USER;

CModule::IncludeModule('iblock');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$auth = 0;
if($_SESSION['USER_DATA'])
	$auth = 1;

if($input['type'] != 'group-chat') {

    if ($input['type'] == 'events') {
        if ($input['hash'] == 'like') {
            $table = 'a_like_events';
        } elseif ($input['hash'] == 'deslike') {
            $table = 'a_deslike_events';
        } elseif ($input['hash'] == 'go') {
            $table = 'a_events_go';
        }
        $users = $dbh->query('SELECT id_user from ' . $table . ' WHERE id_vuz = ' . $input['vuz'] . ' AND id_event = ' . $input['id'] . ' ORDER BY id DESC')->fetchAll();
    }

    if ($input['type'] == 'news') {
        if ($input['hash'] == 'like') {
            $table = 'a_like_news';
        } elseif ($input['hash'] == 'deslike') {
            $table = 'a_deslike_news';
        }
        $users = $dbh->query('SELECT id_user from ' . $table . ' WHERE id_vuz = ' . $input['vuz'] . ' AND id_news = ' . $input['id'] . ' ORDER BY id DESC')->fetchAll();
    }

    if ($input['type'] == 'vuz') {
        if ($input['hash'] == 'like') {
            $table = 'a_like';
        } elseif ($input['hash'] == 'deslike') {
            $table = 'a_deslike';
        }
        $users = $dbh->query('SELECT id_user from ' . $table . ' WHERE id_vuz = ' . $input['vuz'] . ' ORDER BY id DESC')->fetchAll();
    }

    if ($input['type'] == 'post') {
        if ($input['hash'] == 'like') {
            $table = 'a_like_user';
        } elseif ($input['hash'] == 'deslike') {
            $table = 'a_deslike_user';
        }
        $users = $dbh->query('SELECT id_user from ' . $table . ' WHERE id_vuz = ' . $input['vuz'] . ' AND id_post = ' . $input['id'] . ' ORDER BY id DESC')->fetchAll();
    }

} else {

    $users = array();

    $arrUserGroupId = array();
    $arrUser  = $dbh->query('SELECT user_id from a_group_user WHERE chat_id = ' . (int) $input['chat'])->fetchAll();
    foreach($arrUser as $item)
        $arrUserGroupId[] = $item['user_id'];

    $arrAdminGroupId = array();
    $arrAdmin = $dbh->query('SELECT user_id from a_group_admin WHERE chat_id = ' . (int) $input['chat'])->fetchAll();
    foreach($arrAdmin as $item)
        $arrAdminGroupId[] = $item['user_id'];

    if($input['typeuser'] == 'all') {
        $users = $arrUserGroupId;
    } elseif($input['typeuser'] == 'admin') {
        $users = $arrAdminGroupId;
    } else {
        $teacer = array();
        $user   = array();
        foreach ($arrUserGroupId as $item) {
            $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $item)->fetch();
            if ($arrTeacher['cnt'] > 0) {
                $teacer[] = $item;
            } else {
                $userId[] = $item;
            }
        }
        if($input['typeuser'] == 'user') {
            $users = $userId;
        } elseif($input['typeuser'] == 'teacher') {
            $users = $teacer;
        }
    }

}

if($users) {
	foreach($users as $user) {
		$tempArray = array();
		if($input['type'] != 'group-chat') {
            $rsUserData = CUser::GetByID($user['id_user']);
        } else {
            $rsUserData = CUser::GetByID($user);
        }
		$userData = $rsUserData->Fetch();
		if($userData) {
			$tempArray['id'] = $userData['ID'];

			if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
				$format_name = '<span>' . strtoupper(mb_substr(trim($userData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['NAME']), 1);
				if($userData['SECOND_NAME']) {
					$format_name .= ' ';
					$format_name .= '<span>' . strtoupper(mb_substr($userData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($userData['SECOND_NAME'], 1);
				}
				$format_name .= ' ';
				$format_name .= '<span>' . strtoupper(mb_substr(trim($userData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['LAST_NAME']), 1);
			} else {
				$format_name = '<span>' . strtoupper(mb_substr(trim($userData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($userData['LOGIN']), 1);
			}

			$tempArray['format_name'] = $format_name;

			if($userData['PERSONAL_PHOTO']) {
				$tempArray['avatar'] = CFile::GetPath($userData['PERSONAL_PHOTO']);
			} else {
				$tempArray['avatar'] = SITE_TEMPLATE_PATH . "/images/user-1.png";
			}

			if($userData['WORK_WWW']) {
				$tempArray['teacher'] = 'Y';
			} else {
				$tempArray['teacher'] = 'N';
			}

			$tempArray['online'] = $userData['IS_ONLINE'];

			$result[] = $tempArray;
		}
	}
}

$data = array("status" => "success", 'res' => $result, 'auth' => $auth);
die(json_encode($data));
?>