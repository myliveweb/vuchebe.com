<?php
require_once('function.php');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

if($user_id) {
	if($input['state']) {
		$result['res'] = $dbh->exec('DELETE FROM a_bookmark WHERE user_id = ' . $user_id . ' AND uz_id = ' . $input['id']);
	} else {
		$date_add = (int) time();

		$stmt = $dbh->prepare("INSERT INTO a_bookmark (user_id, type, uz_id, date_create) VALUES (:user_id, :type, :uz_id, :date_create)");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':type', $input['type']);
		$stmt->bindParam(':uz_id', $input['id']);
		$stmt->bindParam(':date_create', $date_add);
		$stmt->execute();
		$result['id'] = $dbh->lastInsertId();
	}
}
$data = $result ? array("status" => "success", 'result' => $result ) : array("status" => "error", 'message' => 'Ошибка.');
die(json_encode($data));
?>