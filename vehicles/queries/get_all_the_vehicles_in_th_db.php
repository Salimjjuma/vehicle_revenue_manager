<?php 

    include __DIR__."/index.php";

    echo json_encode(get_all_the_vehicles_in_the_database());

?>