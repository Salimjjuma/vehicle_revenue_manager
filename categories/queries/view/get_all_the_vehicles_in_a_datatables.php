<?php

require_once __DIR__ . "/index.php";

$category_id = htmlentities($_GET['cid']);

echo json_encode(get_all_the_vehicles_in_a_datatables($category_id));