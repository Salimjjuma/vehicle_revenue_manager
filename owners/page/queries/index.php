<?php

include __DIR__ . "/../../../fxn/config.php";

//  `vehicle_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
//     `owner_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,

//     `date_of_ownership` date NOT NULL,  
//     `updated_at` timestamp on update CURRENT_TIMESTAMP NULL,

//     `isActive` int DEFAULT 1 NOT NULL,

//     PRIMARY KEY (`vehicle_id`, `owner_id`),

// FILLABLE FIELD IN THE DATABASE TABLE. 
$fillable = [
    'vehicle_id',
    'owner_id',
    'date_of_ownership',
];

// GET REQUEST TO FIND ALL THE VEHICLES ASSIGNED TO OWNERS. 
function to_get_all_vehicles_assign_to_owners($owners_id)
{

    global $dbh;

    $stmt = "SELECT vo.vehicle_id, date_of_ownership, vo.isActive, 
                 vehicle_name, registration_number, category_name, company_name, end_date_of_ownership
                 FROM `vehicle_owners` vo
                 LEFT JOIN vehicles v ON v.vehicle_id = vo.vehicle_id
                 LEFT JOIN category c ON c.category_id = v.category
                 LEFT JOIN vehicle_company vc ON vc.vehicle_company_id = v.vehicle_company
                 WHERE owner_id =:owners_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":owners_id", $owners_id, PDO::PARAM_STR);

    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

// TO GET ALL THE VEHICLES BEFORE ASSIGN TO AN OWNER. 
function to_get_all_the_vehicles_in_the_db($searchTerms)
{

    global $dbh;


    if (!isset($searchTerms)) {
        $sql = "SELECT vehicle_id, vehicle_name, registration_number 
                    FROM vehicles";
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {

        $query = "SELECT vehicle_id, vehicle_name, registration_number 
                        FROM vehicles
                        WHERE vehicle_name 
                        LIKE :vehicle_name";
        $sql = $dbh->prepare($query);
        $sql->bindValue(":vehicle_name", '%' . $searchTerms . '%', PDO::PARAM_STR);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    $accounts_type = array();

    foreach ($result as $r) {
        $accounts_type[] = array(
            "id" => $r['vehicle_id'],
            "text" => $r['vehicle_name'] . " - (" . $r['registration_number'] . ")"
        );
    }
    ;
    return $accounts_type;
}

// POST REQUEST. 
function to_add_ownership_of_vehicle($owners_id, $vehicle_id)
{
    global $dbh;

    $data = array();
    $errors = array();

    if (empty($owners_id) || empty($vehicle_id))
        $errors['errors'] = "Missing input fields in your submit request";

    if (!empty($errors)) {
        $data['success'] = false;
        $data['message'] = $errors;
    } else {

        try {
            // Set PDO error mode to exception
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = "INSERT INTO `vehicle_owners` (`vehicle_id`, `owner_id`, `date_of_ownership`) 
                        VALUES (:vehicle_id, :owners_id, NOW())";

            $query = $dbh->prepare($stmt);

            $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
            $query->bindParam(":owners_id", $owners_id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                $data['success'] = true;
                $data['message'] = "Ownership updated succesfully";
            }

        } catch (PDOException $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
        }
    }
    return $data;
}

// GET REQUEST FOR OWNERS DETAILS.
function to_get_owners_details($owners_id)
{

    global $dbh;

    $stmt = "SELECT `first_name`, `second_name`, 
                `last_name`, `phone_number`, `created_at`, `updated_at`, `isActive` 
                 FROM `owner` 
                 WHERE owner_id=:owners_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":owners_id", $owners_id, PDO::PARAM_STR);

    $query->execute();

    $results = $query->fetch(PDO::FETCH_OBJ);

    return $results;
}

function to_revoke_ownership($owners_id, $vehicle_id)
{

    global $dbh;

    $data = array();
    $errors = array();

    if (empty($owners_id))
        $errors['owners'] = "Owners Id Missing Inputs";

    if (empty($vehicle_id))
        $errors['vehicle'] = "Vehicle Id Missing Inputs";

    if (!empty($errors)) {
        $data['success'] = false;
        $data['message'] = $errors;
    } else {

        //  Update the end_date of the current ownership record
        $stmt = "UPDATE vehicle_owners 
                 SET end_data_of_ownership = NOW() 
                 WHERE vehicle_id = :vehicle_id 
                 AND owner_id =:owner_id
                 AND end_data_of_ownership IS NULL";

        $query = $dbh->prepare($stmt);

        $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
        $query->bindParam(":owner_id", $owners_id, PDO::PARAM_STR);
        $query->execute();

        $errorInfo = $query->errorInfo();

        if ($query->rowCount() > 0) {
            $data['success'] = true;
            $data['message'] = "Updated Successfully";
        } else {
            $data['success'] = false;
            $data['message'] = $errorInfo[2];
        }
    }
    return $data;

}