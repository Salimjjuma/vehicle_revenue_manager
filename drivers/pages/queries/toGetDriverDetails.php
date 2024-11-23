<?php

require __DIR__ . '/index.php';

$driverId = htmlentities($_GET['driverId']);

echo json_encode(toGetDriverDetails($driverId));