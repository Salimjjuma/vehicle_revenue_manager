<?php

require_once __DIR__ . '/./index.php';

$vehicle_id = htmlentities($_POST['vehicle_id_for_delete']);
$email = htmlentities($_POST['email']);
$password = htmlentities($_POST['password']);

echo json_encode(toDeleteAVehicle($vehicle_id, $email, $password));
