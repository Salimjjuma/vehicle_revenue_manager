<?php
require_once __DIR__ . "/index.php";

$category_name = htmlentities($_POST['edit_category_name']);
$category_id = htmlentities($_POST['edit_category_id']);

echo json_encode(update_category($category_name, $category_id));