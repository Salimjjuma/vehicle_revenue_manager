<?php 

    require_once __DIR__."/../../fxn/config.php";

    define("STATUS", 1);



// function to view all the vehicles in the database. 
// GET 
// FETCH_ALL
function get_all_the_vehicles_in_the_database(){
     global $dbh;

        $stmt = "SELECT vehicle_name, registration_number, category_name, company_name, v.isActive, v.vehicle_id,
                 DATE_FORMAT(v.created_at, '%W, %M %e, %Y') AS created_at
                 FROM vehicles v 
                 LEFT JOIN vehicle_company vc 
                 ON vc.vehicle_company_id = v.vehicle_company
                 LEFT JOIN category c
                 ON v.category = c.category_id";
                
        $query = $dbh->prepare($stmt);
        $query->execute();

        $vehicle = $query->fetchAll(PDO::FETCH_OBJ);

        return $vehicle;     
}

// function to create a new vehicle.
// POST. 
function add_new_vehicle_in_the_database($vehicle_name, $reg_number, $category, $vehicle_company){
        
        global $dbh;

        $error = array();
        $data = array();

        if(empty($vehicle_name) || empty($reg_number) || empty($category) || empty($vehicle_company))
            $error['errors'] = "There are some missing fields in the array.";
        
        if(!empty($error)){
            $data['success'] = false;
            $data['message'] = $error;
        }else{

            $vehicle_id = bin2hex(random_bytes(30));

            $stmt = "INSERT INTO vehicles(vehicle_id, vehicle_name, registration_number, category,
                    vehicle_company, created_at)
                    VALUES(:vehicle_id, :vehicle_name, :reg_number, :category, :vehicle_company,
                    NOW())";
            
            $query = $dbh->prepare($stmt);
            
            $query->bindParam(":vehicle_id", $vehicle_id, PDO::PARAM_STR);
            $query->bindParam(":vehicle_name", $vehicle_name, PDO::PARAM_STR);
            $query->bindParam(":reg_number", $reg_number, PDO::PARAM_STR);
            $query->bindParam(":category", $category, PDO::PARAM_STR);
            $query->bindParam(":vehicle_company", $vehicle_company, PDO::PARAM_STR);

            $query->execute();

            $errorInfo = $query->errorInfo();

                if($query->rowCount() > 0){
                    $data['success'] = true;
                    $data['message'] = "Vehicle succesfully added.";
                }else{
                    $data['success'] = false;
                    $data['message'] = $errorInfo[2];
                }
            
            }
         return $data;
}


// function to edit a vehicle.


// function to delete a vehicle. 

// function to fetch categories
function get_vehicle_categories($searchTerms){

    global $dbh; 


        if(!isset($searchTerms)){
            $sql = "SELECT category_id, category_name 
                    FROM category
                    WHERE isActive =".STATUS;
            $query = $dbh->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);            
        }else{

             $query = "SELECT category_id, category_name 
                        FROM category
                        WHERE category_name 
                        LIKE :category_name AND
                        isActive =".STATUS;
            $sql = $dbh->prepare($query);
            $sql->bindValue(":category_name", '%'.$searchTerms.'%', PDO::PARAM_STR);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        }

         $category = array();

        foreach ($result as $r) {
            $category [] = array (
                "id" => $r['category_id'],
                "text" => $r['category_name']
            );
        };

    
        return $category;


}

function get_vehicle_company($searchTerms){
     global $dbh; 

        if(!isset($searchTerms)){

            $sql = "SELECT vehicle_company_id, company_name 
                    FROM vehicle_company
                    WHERE isActive =".STATUS;

            $query = $dbh->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);            
        }else{

             $query = "SELECT vehicle_company_id, company_name 
                        FROM vehicle_company
                        WHERE company_name 
                        LIKE :company_name AND
                        isActive =".STATUS;
            $sql = $dbh->prepare($query);
            $sql->bindValue(":company_name", '%'.$searchTerms.'%', PDO::PARAM_STR);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        }

         $company = array();

        foreach ($result as $r) {
            $company [] = array (
                "id" => $r['vehicle_company_id'],
                "text" => $r['company_name']
            );
        };
        
        return $company;
}