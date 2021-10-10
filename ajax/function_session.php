<?php
ini_set('display_errors', 1);

session_start();
//$_SESSION = array();
require_once($_SERVER["DOCUMENT_ROOT"].'/ajax/lp.php');
$initArr = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+03:00'");
try {
    $dbh = new PDO('mysql:host=localhost;dbname=admin_vuchebe', $user, $pass, $initArr);
    $dbh->exec("set names utf8mb4");
    $_SESSION['DBH'] = $dbh;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>