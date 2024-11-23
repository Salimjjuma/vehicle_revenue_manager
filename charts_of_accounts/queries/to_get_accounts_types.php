<?php

    include __DIR__. "/index.php";

    $searchTerms = htmlentities($_POST['searchTerms']);

    echo json_encode(to_get_accounts_types($searchTerms));