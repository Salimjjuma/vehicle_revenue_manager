<?php


try{


    if(file_exists(__DIR__."/../queries/query_index.php")){
        require __DIR__."/../queries/query_index.php";

    }else{
        throw new Exception("Error Processing Request. File Template not Found", 1);

    }
    if(file_exists(__DIR__. "/../../scafolding.php")){
        
        require __DIR__.'/../../scafolding.php';
        
        $pdf = new CustomPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetTitle('Vehicle Management');
        
        include "../../VARIABLES.php";
        
        
        $pdf->AddPage();     
        
        // ----------------------------- Start of Page ---------------------------------------------------  
        
        $pdf->Ln(35);
        
        // Rectangle Around the Page.
        $pdf->Rect(3, 48, 204, 245); 
        
        $pdf->SetTextColor(255,255,255);
        $pdf->setFillColor(1,50,32);
        
        $pdf->SetFont('aefurat', '', 15);
        
        $pdf->Cell(0, 10, "DAILY REVENUE AND EXPENSE VEHICLE REPORT", 1, 1, 'C', true);
        
        $pdf->SetFont('aefurat', 'I', 12);
        $pdf->SetTextColor(0, 0, 0);
        
        $pdf->Cell(0, 10, "Reporting Date: " . date('d-M-Y'), 0, 1, 'R', false);
        
        $pdf->SetTextColor(0,0,0);

        $vehicle_id = $_GET['vid'];
        $date = $_GET['date'];

        $car_details = to_get_individual_vehicle_details($vehicle_id);
        
        // object containing the vehicle details. 
        $vehicles = to_get_individual_vehicle_revenue_and_expenses($vehicle_id, $date);


        $vehicles_totals = to_get_total_revenue_and_expenses_for_individual_report($vehicle_id, $date);

        // var_dump($vehicles_totals);


        // Set cell widths
        $cellWidth = 48;

          // // Table header
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(5, 10, '', 0,0);
        $pdf->Cell(90, 10, 'Date of Transcation (DOT) / Date of Entry (DOE)', 1, 0, "L", true);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('aefurat', 'I', 16);
        $pdf->Cell(103, 10, $date, 1, 1, "C", false);
        
        $pdf->Cell(0,1,"",0,1);

        $pdf->SetFont('aefurat', '', 12);


        // // Table header
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(5, 10, '', 0,0);
        
        $pdf->Cell(50, 10, 'Vehicle Name', 1, 0, "L", true);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('aefurat', '', 14);

        $pdf->Cell(143, 10, $car_details['vehicle_name'], 1, 1, "C", false);
        $pdf->SetFont('aefurat', '', 12);

        // // Table header
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(5, 10, '', 0,0);
        $pdf->Cell(50, 10, 'Registration Number:', 1, 0, "L", true);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(50, 10, $car_details['registration_number'], 1, 0, "C", false);
        
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(50, 10, 'Date of Registration:', 1, 0, "C", true);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(43, 10, $car_details['created_at'], 1, 1, "R", false);


        // // Table header
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(5, 10, '', 0,0);
        $pdf->Cell(50, 10, 'Owner / Custodian / Driver: ', 1, 0, "L", true);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(50, 10, $car_details['owner_name'], 1, 0, "C", false);
        
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(50, 10, 'Vehicle Brand:', 1, 0, "C", true);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(43, 10, $car_details['company_name'], 1, 1, "R", false);

                // // Table header
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(5, 10, '', 0,0);
        $pdf->Cell(50, 10, 'V. Category: ', 1, 0, "L", true);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(50, 10, $car_details['category_name'], 1, 0, "C", false);
        
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(50, 10, 'Vehicle Status:', 1, 0, "C", true);
        $pdf->SetTextColor(0,0,0);

        if($car_details['isActive'] == 1){
            $pdf->Cell(43, 10, 'Active', 1, 1, "R", false);
        }else{
            $pdf->Cell(43, 10, 'InActive', 1, 1, "R", false);
        }

        $pdf->SetTextColor(255,255,255);
        // Draw a horizontal line
        $pdf->Line(5, 116, 205, 116);  // (x1, y1, x2, y2)

        $pdf->Ln(3);

         // Table header
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(5, 5, '', 0,0);
        $pdf->SetFont('aefurat', '', 10);

        $pdf->Cell(0, 10, 'Summary Total Revenue and Total Expenses.', 0,1, "", false);
        $pdf->SetFont('aefurat', '', 12);

        // // Table header
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(5, 10, '', 0,0);
        $pdf->Cell(50, 10, 'Total Revenues: ', 1, 0, "C", false);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(50, 10, $vehicles_totals['total_revenue'], 1, 0, "C", false);
        
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(50, 10, 'Total Expenses:', 1, 0, "C", false);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(43, 10, $vehicles_totals['total_expense'], 1, 1, "C", false);

         // Table header
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(5, 5, '', 0,0);
        $pdf->SetFont('aefurat', '', 10);

        $pdf->Cell(0, 10, 'Table showing the accounts affected by the transaction.', 0,1, "", false);
        $pdf->Cell(5, 5, '', 0,0);

        $pdf->SetTextColor(255,255,255);

        $pdf->SetFont('aefurat', '', 12);

        $pdf->Cell($cellWidth, 10, 'Account(s)', 1, 0, "C", true);
        $pdf->Cell($cellWidth, 10, 'Type of Account', 1, 0, "C", true);
        $pdf->Cell($cellWidth, 10, 'Amount', 1, 0, "C", true);
        $pdf->Cell($cellWidth, 10, 'Transcation Date', 1, 1, "C", true);

        // Table vehicles rows
        $pdf->SetFont('aefurat', "b", 10);
        $pdf->SetTextColor(0,0,0);
        
        // // Table vehicles rows
        $pdf->SetFont('aefurat', "b", 10);
        $pdf->SetTextColor(0,0,0);
        
        
        for ($i = 0; $i < count($vehicles); $i++) {
            $pdf->Cell(5, 10, '', 0,0);
            $pdf->Cell($cellWidth, 10, $vehicles[$i]['chart_name'], 1, 0, "C", false);
            $pdf->Cell($cellWidth, 10, $vehicles[$i]['type'], 1, 0, "C");
            $pdf->Cell($cellWidth, 10, $vehicles[$i]['amount'], 1, 0, "C", false);
            $pdf->Cell($cellWidth, 10, $vehicles[$i]['entry_date'], 1, 1, "C", false);
        }


        $pdf->Ln(2);

        $lastCellY = ($pdf->GetY() + 20);

        $pdf->SetXY(0, $lastCellY);
        $pdf->SetFont('aefurat', "B", 16);


        $pdf->Cell(0, 10, "Net Income", 0,1, "C", false);

        $pdf->SetFont('aefurat', "U", 20);

        $pdf->Cell(0, 10, $vehicles_totals['difference'], 0,1, "C", false);

        // Set border properties
        $borderWidth = 2;  // Border width in user units
        $borderColor = array(200, 200, 200);  // RGB color for the border (black in this case)

        // Set fill color for the rectangle
        $fillColor = array(255, 255, 255);  // RGB color for the fill (gray in this case)


        $pdf->Rect(75, $lastCellY, 60, 30,'', array('all' => $borderWidth, 'color' => $borderColor), $fillColor);

        $pdf->Ln(3);


        $pdf->Output($car_details['vehicle_name'] .'('.$car_details['registration_number'].') -Daily Revenue & Expense report dated -'.$date.'.pdf'); 
    
    }else{
        throw new Exception("Error Processing Request. File Template not Found", 1);
    }

}catch(Exception $e){
    echo 'Exception Caught ',  $e->getMessage() , "\n";
}

