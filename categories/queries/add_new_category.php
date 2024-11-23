<?php 

    include __DIR__. "/index.php";

    $category_name = htmlentities($_POST['category_name']);

    echo json_encode(to_add_new_category_to_the_database($category_name));