<?php

    include __DIR__."/index.php";

    $company_name = htmlentities($_POST['vehicle_brand_name']);

    echo json_encode(to_add_new_vehicle_brand($company_name));