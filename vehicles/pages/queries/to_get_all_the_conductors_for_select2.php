<?php

include __DIR__ . "/index.php";

$searchTerms = htmlentities($_POST['searchTerm']);

echo json_encode(to_get_all_the_conductors_for_select2($searchTerms));
