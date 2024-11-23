<?php   

try{
    
    if(file_exists(__DIR__."/./query_index.php")){
        require(__DIR__."/./query_index.php");
    }else{
        throw new Exception("Error Processing Request. File Template not Found", 1);
    }
}catch(Exception $e){
    echo 'Exception Caught ',  $e->getMessage() , "\n";
}  

echo json_encode(to_get_all_the_vehicles());