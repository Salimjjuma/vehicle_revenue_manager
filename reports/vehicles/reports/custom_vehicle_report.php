<?php

    try{ 

        if(file_exists(__DIR__."/../queries/query_index.php")){
            require __DIR__."/../queries/query_index.php";
        }else{
            throw new Exception("Error Processing Request. File Template not Found", 1);
        }

        if(file_exists(__DIR__. "/../../scafolding.php")){
        
            require __DIR__.'/../../scafolding.php';
            
            // default page of portrait orientation, pageunit mm, PDF_PAGE_FORMAT A4. 
            $pdf = new CustomPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // url parameters for vehicle, startDate, endDate. 
            $v = htmlentities($_GET['v']);$e = htmlentities($_GET['e']);$s = htmlentities($_GET['s']);
        
            $startDateObject = DateTime::createFromFormat('Y-m-d', $s);$endDateObject = DateTime::createFromFormat('Y-m-d', $e);
            $startDateConverted = $startDateObject->format('d-F-Y');$endDateConverted = $endDateObject->format('d-F-Y');  

            $revenue_data = get_vehicle_custom_date_revenue_amounts($v, $s, $e);
            $expense_data =  get_vehicle_custom_date_expense_amounts($v, $s, $e);
            $vehicle_details = to_get_individual_vehicle_details($v);
            $cellWidth = 48;

            $total_revenue_ = to_get_total_revenue_and_expenses_for_individual_report_for_a_period_of_time($v, $s, $e);

            $pdf->SetTitle($s . " to " . $e);
            
            require "../../VARIABLES.php";
            
            $pdf->AddPage();     
            
            require "./_partials.php";


            $pdf->Output('custom report - ' .$s . ' - '. $e. " ". $vehicle_details['vehicle_name']. '.pdf'); 
    
        }else{
            throw new Exception("Error Processing Request. File Template not Found", 1);
        }

    }catch(Exception $e){
        echo 'Exception Caught ',  $e->getMessage() , "\n";
    }