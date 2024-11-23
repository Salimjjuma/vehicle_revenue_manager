<?php

    include __DIR__."/index.php";

    $owner_id = htmlentities($_GET['owners_id']);

    echo json_encode(to_get_all_vehicles_assign_to_owners($owner_id));