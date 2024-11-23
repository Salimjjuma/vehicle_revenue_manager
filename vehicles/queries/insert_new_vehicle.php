<?php 

    include __DIR__."/index.php";

$vehicle_name = htmlentities($_POST['vehicle_name']);
$vehicle_company = htmlentities($_POST['vehicle_company']);
$category = htmlentities($_POST['category']);
$reg_number = htmlentities($_POST['reg_no']);


echo json_encode(add_new_vehicle_in_the_database($vehicle_name, $reg_number, $category, $vehicle_company));