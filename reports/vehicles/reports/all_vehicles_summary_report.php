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
        
        $pdf->SetFont('aefurat', '', 15);
        
        $pdf->SetFillColor(0,0,0);
        
        $pdf->Cell(0, 10, "SUMMARY REPORT", 1, 1, 'C', 'B');

        $pdf->SetFont('aefurat', '', 12);

        $pdf->Cell(0, 10, "SUMMARY REPORT - ALL THE VEHICLES AND THE OWNERS ASSOCIATED WITH THEM", 0, 1, 'C', 'B');
        
        $pdf->SetFont('aefurat', 'I', 12);
        $pdf->SetTextColor(0, 0, 0);
        
        $pdf->Cell(0, 10, "Report Date: " . date('d-M-Y'), 0, 1, 'R', false);
        
        $pdf->SetTextColor(0,0,0);
        
        $pdf->Ln(3);

        $vehicles = to_get_all_the_vehicles();

        // Set cell widths
        $cellWidth = 40;
        
        // Table header
        $pdf->SetTextColor(255,255,255);
        // $pdf->Cell(10, 10, '', 0,0);
        $pdf->MultiCell(25, 10, 'Registration Date.', 1, 'C', true, 0, '', '', true);
        $pdf->Cell($cellWidth, 10, 'Vehicle Name', 1, 0, "C", true);
        $pdf->MultiCell(25, 10, 'Registration Number', 1, 'C', true, 0, '', '', true);
        $pdf->Cell(25, 10, 'Category.', 1, 0, "C", true);
        $pdf->MultiCell(25, 10, 'Vehicle Brand.', 1, 'C', true, 0, '', '', true);
        $pdf->MultiCell(46, 10, 'Owner / Custodian / Driver', 1, 'C', true, 0, '', '', true);
        $pdf->Cell(18, 10, 'Status.', 1, 0, "C", true);
        $pdf->Ln();
        
        // Table vehicles rows
        $pdf->SetFont('aefurat', "b", 10);
        $pdf->SetTextColor(0,0,0);
        
        
        foreach ($vehicles as $row) {
            // $pdf->Cell(10, 10, '', 0,0);
            $pdf->Cell(25, 10, $row['created_at'], 1, 0, "C", false);
            $pdf->Cell($cellWidth, 10, $row["vehicle_name"], 1, 0, "C");
            $pdf->Cell(25, 10, $row["registration_number"], 1, 0, "C", false);
            $pdf->Cell(25, 10, $row['category_name'], 1, 0, "C", false);
            $pdf->Cell(25, 10, $row['company_name'], 1, 0, "C", false);
            $pdf->Cell(46, 10, $row['first_name'] . " ".  $row['second_name'] . " " . $row['last_name'], 1, 0, "C", false);

            if($row['isActive'] == 1){
                $pdf->Cell(18, 10, "Active", 1, 0, "C", false);
            }else{
                $pdf->Cell(18, 10, "InActive", 1, 0, "C", false);
            }

            $pdf->Ln();
        }
        
        $pdf->Output('hello_world.pdf'); 
    }else{
        throw new Exception("Error Processing Request. File Template not Found", 1);
    }
}catch(Exception $e){
    echo 'Exception Caught ',  $e->getMessage() , "\n";
}

