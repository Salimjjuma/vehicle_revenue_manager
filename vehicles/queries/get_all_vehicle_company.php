<?php 

    include __DIR__."/index.php";

    $searchTerms = htmlentities($_POST['searchTerm']);

    echo json_encode(get_vehicle_company($searchTerms));

?>