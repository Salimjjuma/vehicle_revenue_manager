<?php 

    include __DIR__. "/index.php";

    $owners_id_revoke = htmlentities($_POST["owners_id_revoke"]);
    $revoke_vehicle = htmlentities($_POST['revoke_vehicle']);

    echo json_encode(to_revoke_ownership($owners_id_revoke, $revoke_vehicle));