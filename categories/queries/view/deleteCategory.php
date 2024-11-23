<?php

require_once __DIR__ . '/index.php';

$category_id = htmlentities($_POST['category_id_for_delete']);
$email = htmlentities($_POST['email']);
$password = htmlentities($_POST['password']);

echo json_encode(to_delete_a_category($category_id, $email, $password));