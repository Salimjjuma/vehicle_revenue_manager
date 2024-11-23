<?php 

    include __DIR__."/index.php";

    $owners_id_input = htmlentities($_POST["owners_id_input"]);
    $vehicle = htmlentities($_POST['vehicle']);

    echo json_encode(to_add_ownership_of_vehicle($owners_id_input, $vehicle));