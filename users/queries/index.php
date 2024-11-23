<?php

    include __DIR__."/../../fxn/config.php";


    $fillable = [
        "username",
        "first_name",
        "second_name",
        "last_name",
        "email_address",
        "password"
    ];

    function get_all_user_accounts(){

        global $dbh;

        $stmt = "SELECT id, username, first_name, second_name, last_name, 
                        email_address, created_at, isActive
                FROM users"; 

        $query = $dbh->prepare($stmt);
        $query->execute();

        $users = $query->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }

    function add_user_to_database($username, $first_name, $second_name, $last_name, $email_address, $password){

        global $dbh; 

        $data = array(); $error = array();

        if(empty($username) || empty($first_name) || empty($second_name) || empty($last_name) || empty($email_address) || empty($password))
            $error['message'] = "There are missing argurements in the request.";
        
        if(!empty($error)){
            $data['message'] = $error;
            $data['success'] = false;
        }else{

            try {

                // Set PDO error mode to exception
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $id = bin2hex(random_bytes(30));
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
                $sql = "INSERT INTO users (`id`, `username`, `first_name`, `second_name`, `last_name`, 
                        `email_address`, `password`, created_at)
                        VALUES (:id, :username, :first_name, :second_name, :last_name,
                        :email_address, :password, NOW())";
                
                // Prepare the SQL statement
                $query = $dbh->prepare($sql);

                // Bind parameters and execute the query
                $query->bindParam(":id", $id, PDO::PARAM_STR);
                $query->bindParam(":username", $username, PDO::PARAM_STR);
                $query->bindParam(":first_name", $first_name, PDO::PARAM_STR);
                $query->bindParam(":second_name", $second_name, PDO::PARAM_STR);
                $query->bindParam(":last_name", $last_name, PDO::PARAM_STR);
                $query->bindParam(":email_address", $email_address, PDO::PARAM_STR);
                $query->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            
                // Execute the query
                $query->execute();

                if($query->rowCount() > 0){
                    $data['message'] = "Added Successfully";
                    $data['success'] = true;
                }

            } catch (PDOException $e) {
                $data['message'] = $e->getMessage();
                $data['success'] = false;
            }
        }

        return $data;

    }