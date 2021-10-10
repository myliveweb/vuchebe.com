<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

if($input['type'] == 'login' || $input['type'] == 'all') {

    $sth = $dbh->prepare('SELECT id from a_admin WHERE login = ?');
    $sth->execute(array($input['login']));
    $login = $sth->fetch();

    if($login) {
        $error['login'] = 'Такой логин уже занят';
    }
}

if($input['type'] == 'email' || $input['type'] == 'all') {

    $sth = $dbh->prepare('SELECT id from a_admin WHERE email = ?');
    $sth->execute(array($input['email']));
    $email = $sth->fetch();

    if($email) {
        $error['email'] = 'Такой Email уже занят';
    }
}

if($error) {
    $data = array("status" => "error", 'error' => $error);
} else {
    $data = array("status" => "success");
}

die(json_encode($data));
?>