<?php

    include __DIR__."/../../fxn/config.php";

    $fillable = [
        "vehicle_company_id",
        "company_name",
        "created_at"    
    ];

    // function to get all the vehicle brands.
    function to_get_all_the_vehicle_brands(){
        global $dbh;

        $stmt = "SELECT vehicle_company_id, company_name, isActive,
                DATE_FORMAT(created_at, '%W, %M %e, %Y' ) AS created_at
                FROM vehicle_company";
        
        $query = $dbh->prepare($stmt);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


    // function to add new vehicle brand. 
    function to_add_new_vehicle_brand($company_name){
        global $dbh;

        $data = array();
        $error = array();

        if(empty($company_name)){
            $error['company_name'] = "company name cannot be set to null";
        }

        if(!empty($error)){
            $data['success'] = false;
            $data['message'] = $error;
        }else{

            $vehicle_id = bin2hex(random_bytes(30));

            $stmt = "INSERT INTO `vehicle_company`
                    (`vehicle_company_id`, `company_name`, `created_at`) 
                    VALUES (:vehicle_id, :company_name, NOW())";

            $query = $dbh->prepare($stmt);
            $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
            $query->bindParam(":company_name", $company_name, PDO::PARAM_STR);

            $query->execute();
            $errorInfo = $query->errorInfo();

            if($query->rowCount() > 0){
                $data['success'] = true;
                $data['message'] = "Vehicle brand added succesfully";
            }else{
                $data['success'] = false;
                $data['message'] = $errorInfo[2];
            }
        }
        return $data;
    }

