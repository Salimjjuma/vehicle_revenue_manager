<?php   

    include __DIR__. "/../../fxn/config.php";

    $fillables = [
        "acc_id",
        "acc_name",
        "acc_description",
        "creation_date"
    ];

    function get_all_the_accounts_in_the_database(){

        global $dbh;

        $stmt = "SELECT acc_id, acc_name,
                acc_description, creation_date, status
                FROM accounts_type";
        
        $query = $dbh->prepare($stmt);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC); 

        return $results;
    }

    function to_add_an_account_to_the_db($acc_name, $acc_description){
        global $dbh;
        
        $data = array(); $errors = array();

        if(empty($acc_name) || empty($acc_description))
            $errors['errors'] = "some of the input fields are empty";

        if(!empty($errors)){
            $data['success'] = false;
            $data['message'] = $errors;
        }else{
            $acc_id = bin2hex(random_bytes(30));

            $stmt = "INSERT INTO accounts_type (acc_id, acc_name, acc_description, creation_date)
                    VALUES(:acc_id, :acc_name, :acc_description, NOW())";

            $query = $dbh->prepare($stmt);
            $query->bindParam(":acc_id", $acc_id, PDO::PARAM_STR);
            $query->bindParam(":acc_name", $acc_name, PDO::PARAM_STR);
            $query->bindParam(":acc_description", $acc_description,PDO::PARAM_STR);

            $query->execute(); $errorInfo = $query->errorInfo();

            if($query->rowCount() > 0){
                $data['success'] = true;
                $data['message'] = "Account added succesfully";
            }else{
                $data['success'] = false;
                $data['message'] = $errorInfo[2];
            }
        }
        return $data;       
    }

    