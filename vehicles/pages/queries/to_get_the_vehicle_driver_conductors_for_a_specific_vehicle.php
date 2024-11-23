<?php

include __DIR__ . "/index.php";

$vehicle_id = htmlentities($_GET['vehicle_id']);

echo json_encode(to_get_the_vehicle_driver_conductors_for_a_specific_vehicle($vehicle_id));