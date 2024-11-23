<?php 

include __DIR__. "/../../fxn/config.php";

// FILLABLE FIELDS.
 $fillables_fields = [
    "category_id",
    "category_name",
    "created_at",
    "updated_at",
    "isActive" 
 ];

// function to fetch all the categories in the database. 
// GET REQUEST. 
function to_get_all_the_category_in_the_database(){

    global $dbh;

    $stmt = "SELECT category_id, category_name, 
            DATE_FORMAT(created_at, '%W, %M %e, %Y') AS created_at, 
            isActive FROM category";

    $query = $dbh->prepare($stmt);
    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    return $results;

}

// POST REQUEST
// ADD NEW CATEGORY 
function to_add_new_category_to_the_database($category_name){

    global $dbh;

    $error = array();
    $data = array();

    if(empty($category_name)){
        $error['category_name'] = "category cannot be null";
    }
    
    if(!empty($error)){
        $data['success'] = false;
        $data['message'] = $error;
    }else{

        $category_id = bin2hex(random_bytes(30));

        $stmt = "INSERT INTO category(`category_id`, `category_name`, `created_at`)
                VALUES(:category_id, :category_name, NOW())";

        $query = $dbh->prepare($stmt);
        $query->bindParam(":category_id", $category_id, PDO::PARAM_STR);
        $query->bindParam(":category_name", $category_name, PDO::PARAM_STR);
        $query->execute();

        $mysql_error = $query->errorInfo();

        if($query->rowCount() > 0){
            $data['success'] = true;
            $data['message'] = "Category successfully added.";
        }else{
            $data['success'] = false;
            $data['message'] = $mysql_error[2];
        }

    }

    return $data;    
}

// PUT REQUEST. 
// UPDATE A VEHICLE CATEGORY. 
function to_update_a_category($category_id, $category_name){

    global $dbh;

    $data = array();
    $error = array();

    if(empty($category_id) || empty($category_name)){
        $error['message'] = "There are missing fields";
    }

    if(!empty($error)){
        $data['success'] = false;
        $data['message'] = $error;
    }else{
        $stmt = "UPDATE category 
                SET `category_name`=:category_name
                WHERE `category_id=:category_id";

        $query = $dbh->prepare($stmt);
        $query->bindParam(":category_name", $category_name, PDO::PARAM_STR);
        $query->bindParam(":category_id", $category_id, PDO::PARAM_STR);

        $query->execute();

        $errorInfo = $query->errorInfo();

        if($query->rowCount() > 0){
            $data['success'] = true;
            $data['message'] = "Category updated succesfully";
        }else{
            $data['success'] = true;
            $data['message'] = $errorInfo[2];
        }
    }

    return $data;
}