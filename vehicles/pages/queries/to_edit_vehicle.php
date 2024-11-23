<?php 

    require __DIR__."/index.php";

    $vehicle_id = htmlentities($_POST['vehicle_id']);
    $vehicle_name = htmlentities($_POST['vehicle_name']);
    $registration_number = htmlentities($_POST['registration_number']);
    $category = htmlentities($_POST['category']);
    $company_name = htmlentities($_POST['company_name']);


    echo json_encode(to_edit_vehicle($vehicle_id, $vehicle_name, $registration_number, $category, $company_name));

    exit();