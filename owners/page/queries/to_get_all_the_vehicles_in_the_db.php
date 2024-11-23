<?php

    include __DIR__."/index.php";

    $searchTerms = htmlentities($_GET['searchTerms']);

    echo json_encode(to_get_all_the_vehicles_in_the_db($searchTerms));