<?php
$result = array();

$input = json_decode(file_get_contents('php://input'), true);

$result['text'] = 'Server Ok!';
$result['data'] = $input;

$data = array("status" => "success", 'res' => $result);
echo json_encode($data);
?>