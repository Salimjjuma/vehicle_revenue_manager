<?php

require_once __DIR__ . "/../../../fxn/config.php";
require_once __DIR__ . "/../../../fxn/authenticate.php";

function get_details_of_a_category($category_id)
{

    global $dbh;

    $stmt = "SELECT c.category_name, 
                DATE_FORMAT(created_at, '%d-%m-%Y') AS created_at, isActive
             FROM category c
             WHERE category_id =:category_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":category_id", $category_id, PDO::PARAM_STR);
    $query->execute();

    $category_details = $query->fetch(PDO::FETCH_ASSOC);

    return $category_details;

}

function get_all_the_vehicles_in_a_datatables($category_id)
{
    global $dbh;

    $stmt = "SELECT vehicle_name, registration_number, vc.company_name, v.created_at, v.isActive
                FROM category c
             JOIN vehicles v
                ON c.category_id = v.category 
             JOIN vehicle_company vc 
                ON v.vehicle_company = vc.vehicle_company_id 
             WHERE v.category =:category_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":category_id", $category_id, PDO::PARAM_STR);
    $query->execute();

    $category_vehicles = $query->fetchAll(PDO::FETCH_ASSOC);

    return $category_vehicles;
}

function to_delete_a_category($category_id, $email, $password)
{

    global $dbh;
    $data = array();

    $obje = checkAuthentication($email, $password);

    try {

        if (empty($category_id)) {
            throw new Exception("Vehicle id cannot be empty");

        } elseif ($obje['success'] == true) {

            $stmt = "DELETE FROM `category` 
                    WHERE category_id =:category_id";

            $query = $dbh->prepare($stmt);
            $query->bindParam(":category_id", $category_id, PDO::PARAM_STR);
            $query->execute();

            $data['success'] = true;
            $data['message'] = "Vehicle Deleted Successfully.";
        } else {
            $data['success'] = $obje['success'];
            $data['message'] = $obje['message'];
        }

    } catch (PDOException $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = $e->getMessage();
    }

    return $data;
}

function update_category($category_name, $category_id)
{
    global $dbh;

    $data = array();

    try {

        if (empty($category_name) || empty($category_id))
            throw new Exception("Input field cannot be null");

        $stmt = "UPDATE `category` 
                    SET `category_name`=:category_name 
                    WHERE category_id=:category_id";

        $query = $dbh->prepare($stmt);
        $query->bindParam(":category_name", $category_name, PDO::PARAM_STR);
        $query->bindParam(":category_id", $category_id, PDO::PARAM_STR);

        $query->execute();

        $data['success'] = true;
        $data['message'] = "Changes have been saved successfully";


    } catch (Exception $e) {
        $data['message'] = $e->getMessage();
        $data['success'] = false;
    } catch (PDOException $e) {
        $data['message'] = $e->getMessage();
        $data['success'] = false;
    }
    return $data;
}