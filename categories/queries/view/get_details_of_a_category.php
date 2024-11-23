<?php

require_once __DIR__ . "/index.php";

$category_id = htmlentities($_GET['cid']);

echo json_encode(get_details_of_a_category($category_id));