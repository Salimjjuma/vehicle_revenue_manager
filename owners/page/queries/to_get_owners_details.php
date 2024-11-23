<?php

    include __DIR__."/index.php";

    $owners_id = htmlentities($_GET['owners_id']);

    echo json_encode(to_get_owners_details($owners_id));