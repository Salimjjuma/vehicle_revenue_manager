<?php

    include __DIR__. "/../../fxn/config.php";


    //  `charts_of_accounts_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    // `revenue_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    // `revenue_description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, 
    // `creation_date` date NOT NULL,

    // -- fpk for account table 
    //  `acc_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,

    // PRIMARY KEY (`charts_of_accounts_id`),
    // UNIQUE KEY `unq_revenue_key` (`revenue_name`, `acc_type`),

    // KEY `account_id_fpk` (`acc_type`),
    // CONSTRAINT `fpk_account_type` FOREIGN KEY (`acc_type`) 
    // REFERENCES `accounts_type` (`acc_id`) 
    // ON DELETE CASCADE ON UPDATE CASCADE


    $fillable = [
        "vehicle_company_id",
        "company_name",
        "created_at"    
    ];

    function to_get_all_the_charts_in_the_db(){
        global $dbh;

        $stmt = "SELECT `charts_of_accounts_id`, `revenue_name`, 
                `revenue_description`, ca.creation_date, `acc_name`, `isActive` 
                FROM `charts_of_accounts` as ca
                LEFT JOIN `accounts_type` as at
                ON ca.acc_type = at.acc_id";

        $query = $dbh->prepare($stmt);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    
    }

    function to_add_a_new_chart_of_account($revenue_name, $revenue_desc, $acc_type){
        global $dbh;

        $errors = array(); $data = array();

        if(empty($revenue_name) || empty($revenue_desc) || empty($acc_type))
            $errors['errors'] = "There are some missing input fields";

        if(!empty($errors)){
            $data['success'] = false;
            $data['message'] = $errors; 
        }else{
            $charts_of_acc = bin2hex(random_bytes(30));

            $stmt = "INSERT INTO `charts_of_accounts`
                    (`charts_of_accounts_id`, `revenue_name`, `revenue_description`, `creation_date`, `acc_type`) 
                    VALUES (:charts_of_acc_id,:revenue_name,:revenue_desc,NOW(),:acc_type)";
            
            $query = $dbh->prepare($stmt);

            $query->bindParam(":charts_of_acc_id", $charts_of_acc, PDO::PARAM_STR);
            $query->bindParam(":revenue_name", $revenue_name, PDO::PARAM_STR);
            $query->bindParam(":revenue_desc", $revenue_desc, PDO::PARAM_STR);
            $query->bindParam(":acc_type", $acc_type, PDO::PARAM_STR);
            
            $query->execute();
            $errorInfo = $query->errorInfo();

            if($query->rowCount() > 0) {
                $data['success'] = true;
                $data['message'] = "Account has been added succesfully";            
            }else{
                $data['success'] = false;
                $data['message'] = $errorInfo[2];
            }
        }
        
        return $data;
    }

    function to_get_accounts_types($searchTerms){

        global $dbh; 


        if(!isset($searchTerms)){
            $sql = "SELECT acc_id, acc_name 
                    FROM accounts_type";
            $query = $dbh->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);            
        }else{

             $query = "SELECT acc_id, acc_name 
                        FROM accounts_type
                        WHERE acc_name 
                        LIKE :acc_name";
            $sql = $dbh->prepare($query);
            $sql->bindValue(":acc_name", '%'.$searchTerms.'%', PDO::PARAM_STR);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        }

         $accounts_type = array();

        foreach ($result as $r) {
            $accounts_type [] = array (
                "id" => $r['acc_id'],
                "text" => $r['acc_name']
            );
        };
        return $accounts_type;
    }