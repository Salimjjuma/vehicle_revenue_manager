<?php

require_once __DIR__ . "/../../fxn/config.php";


    function to_add_a_new_owner($first_name, $second_name, $last_name, $phone_number){
        global $dbh;

        $data = array();

    try {

        if (empty($first_name) || empty($second_name) || empty($last_name) || empty($phone_number))
            throw new Exception("Elements cannot be null.");

        $owner_id = bin2hex(random_bytes(30));

        $stmt = "INSERT INTO `owner` (`owner_id`, `first_name`, `second_name`, `last_name`, 
                        `phone_number`, `created_at`) 
                    VALUES (:owners_id, :first_name, 
                        :second_name, :last_name, :phone_number, NOW())";

        $query = $dbh->prepare($stmt);

        $query->bindParam(":owners_id", $owner_id, PDO::PARAM_STR);
        $query->bindParam(":first_name", $first_name, PDO::PARAM_STR);
        $query->bindParam(":second_name", $second_name, PDO::PARAM_STR);
        $query->bindParam(":last_name", $last_name, PDO::PARAM_STR);
        $query->bindParam(":phone_number", $phone_number, PDO::PARAM_STR);

        $query->execute();

        $data['success'] = true;
        $data['message'] = "Owner added succesfully";

    } catch (PDOException $e) {
        $data['success'] = false;
        $data['message'] = $e;
    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e;
    }

        return $data;
    }

    function to_get_all_the_owners(){
        global $dbh;

        $stmt = "SELECT `owner_id`, `first_name`, `second_name`, 
                `last_name`, `phone_number`, `created_at`, `isActive` 
                FROM `owner`";

        $query = $dbh->prepare($stmt);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;    
    }