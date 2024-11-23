<?php

require_once __DIR__ . '/index.php';

$first_name = htmlentities($_POST['first_name']);
$second_name = htmlentities($_POST['second_name']);
$last_name = htmlentities($_POST['last_name']);
$phone_number = htmlentities($_POST['phone_number']);
$drivers_license_no = htmlentities($_POST['drivers_license_no']);

echo json_encode(toInsertDriver($first_name, $second_name, $last_name, $phone_number, $drivers_license_no));