<?php   
    
    include __DIR__."/index.php";

    $vehicle_id = htmlentities($_GET['vehicle_id']);
    
    echo json_encode(to_get_details_of_vehicle($vehicle_id));

