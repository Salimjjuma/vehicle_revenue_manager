<?php


$pdf->Ln(35);

$pdf->SetTextColor(255, 255, 255);
$pdf->setFillColor(1, 50, 32);
$pdf->SetFont('aefurat', '', 15);
$pdf->Cell(0, 10, "Periodic Revenue & Expense Report (P&L)", 1, 1, 'C', true);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('aefurat', 'I', 12);
$pdf->Cell(0, 10, "Reporting Date: " . date('d-M-Y'), 0, 1, 'R', false);

$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(5, 10, '', 0, 0);
$pdf->Cell(90, 10, 'Period of Transcation (POT)', 1, 0, "L", true);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('aefurat', 'I', 16);
$pdf->Cell(103, 10, $startDateConverted . " to " . $endDateConverted, 1, 1, "C", false);

$pdf->Cell(0, 1, "", 0, 1);

$pdf->SetFont('aefurat', '', 12);


$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(5, 10, '', 0, 0);

$pdf->Cell(50, 10, 'Vehicle Name', 1, 0, "L", true);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('aefurat', '', 16);
$pdf->Cell(143, 10, $vehicle_details['vehicle_name'], 1, 1, "C", false);
$pdf->SetFont('aefurat', '', 12);


$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(5, 10, '', 0, 0);
$pdf->Cell(50, 10, 'Registration Number:', 1, 0, "L", true);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(50, 10, $vehicle_details['registration_number'], 1, 0, "C", false);

$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(50, 10, 'Date of V. Registration:', 1, 0, "C", true);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(43, 10, $vehicle_details['created_at'], 1, 1, "R", false);


//     // // Table header
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(5, 10, '', 0, 0);
$pdf->Cell(50, 10, 'Owner / Custodian: ', 1, 0, "L", true);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(50, 10, $vehicle_details['owner_name'], 1, 0, "C", false);

$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(50, 10, 'V. Brand:', 1, 0, "C", true);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(43, 10, $vehicle_details['company_name'], 1, 1, "R", false);

//             // // Table header
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(5, 10, '', 0, 0);
$pdf->Cell(50, 10, 'V. Category: ', 1, 0, "L", true);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(50, 10, $vehicle_details['category_name'], 1, 0, "C", false);

$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(50, 10, 'Vehicle Status:', 1, 0, "C", true);
$pdf->SetTextColor(0, 0, 0);

if ($vehicle_details['isActive'] == 1) {
    $pdf->Cell(43, 10, 'Active', 1, 1, "R", false);
} else {
    $pdf->Cell(43, 10, 'InActive', 1, 1, "R", false);
}

$pdf->SetTextColor(255, 255, 255);
// Draw a horizontal line
$pdf->Line(5, 116, 205, 116);  // (x1, y1, x2, y2)

$pdf->Ln(3);

// Table header
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(5, 5, '', 0, 0);
$pdf->SetFont('aefurat', '', 10);

$pdf->Cell(0, 10, 'Summary Total Revenue and Total Expenses for the date ~ ' . $startDateConverted . " to " . $endDateConverted, 0, 1, "", false);
$pdf->SetFont('times', 'B', 12);

// // Table header
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(5, 10, '', 0, 0);
$pdf->Cell(50, 10, 'Total Revenues: ', 1, 0, "C", false);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(50, 10, $total_revenue_['total_revenue'], 1, 0, "C", false);

$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(50, 10, 'Total Expenses:', 1, 0, "C", false);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(43, 10, $total_revenue_['total_expense'], 1, 1, "C", false);

// Revenue Table 
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('times', 'B', 12);

$pdf->Cell(0, 5, '', 0, 1);
$pdf->Cell(5, 5, '', 0, 0);
$pdf->Cell(192, 10, 'Revenue Transcations', 1, 1, "L", true);
$pdf->SetTextColor(0, 0, 0);

$pdf->Cell(5, 5, '', 0, 0);
$pdf->Cell($cellWidth, 10, 'Transcation Date', 1, 0, "C", false);
$pdf->Cell($cellWidth, 10, 'Account(s)', 1, 0, "C", false);
$pdf->Cell($cellWidth, 10, 'Type of Account', 1, 0, "C", false);
$pdf->Cell($cellWidth, 10, 'Amount', 1, 1, "C", false);

// // Table vehicles rows
$pdf->SetFont('aefurat', "b", 10);
$pdf->SetTextColor(0, 0, 0);

$contentHeightThreshold = 280;

for ($i = 0; $i < count($revenue_data); $i++) {
    $pdf->Cell(5, 10, '', 0, 0);
    $pdf->Cell($cellWidth, 10, $revenue_data[$i]['entry_date'], 1, 0, "C", false);
    $pdf->Cell($cellWidth, 10, $revenue_data[$i]['chart_name'], 1, 0, "C", false);
    $pdf->Cell($cellWidth, 10, $revenue_data[$i]['type'], 1, 0, "C");
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell($cellWidth, 10, $revenue_data[$i]['amount'], 1, 1, "C", false);
    $pdf->SetFont('aefurat', "b", 10);

    $currentContentHeight = $pdf->GetY();
    if ($currentContentHeight > $contentHeightThreshold) {
        $pdf->AddPage();
        $pdf->Ln(40);

        $pdf->Line(5, 36, 205, 36);  // (x1, y1, x2, y2)
    }
}
// // Table header
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(5, 10, '', 0, 0);
$pdf->SetFont('times', "B", 11);
$pdf->Cell(144, 10, 'Total Revenues: ', 1, 0, "C", true);
$pdf->Cell(48, 10, $total_revenue_['total_revenue'], 1, 1, "C", true);

// Expense Table
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('times', 'BU', 12);

$pdf->Cell(0, 5, '', 0, 1);
$pdf->Cell(5, 5, '', 0, 0);
$pdf->Cell(192, 10, 'Expense Transcations', 1, 1, "L", true);
$pdf->SetTextColor(0, 0, 0);

$pdf->Cell(5, 5, '', 0, 0);
$pdf->Cell($cellWidth, 10, 'Transcation Date', 1, 0, "C", false);
$pdf->Cell($cellWidth, 10, 'Account(s)', 1, 0, "C", false);
$pdf->Cell($cellWidth, 10, 'Type of Account', 1, 0, "C", false);
$pdf->Cell($cellWidth, 10, 'Amount', 1, 1, "C", false);

// // Table vehicles rows
$pdf->SetFont('aefurat', "b", 10);
$pdf->SetTextColor(0, 0, 0);

$contentHeightThreshold = 280;

for ($i = 0; $i < count($expense_data); $i++) {
    $pdf->Cell(5, 10, '', 0, 0);
    $pdf->Cell($cellWidth, 10, $expense_data[$i]['entry_date'], 1, 0, "C", false);
    $pdf->Cell($cellWidth, 10, $expense_data[$i]['chart_name'], 1, 0, "C", false);
    $pdf->Cell($cellWidth, 10, $expense_data[$i]['type'], 1, 0, "C");

    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell($cellWidth, 10, $expense_data[$i]['amount'], 1, 1, "C", false);
    $pdf->SetFont('aefurat', "", 10);

    $currentContentHeight = $pdf->GetY();
    if ($currentContentHeight > $contentHeightThreshold) {
        $pdf->AddPage();
        $pdf->Ln(37);

        $pdf->Line(5, 36, 205, 36);  // (x1, y1, x2, y2)
    }
}
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(5, 10, '', 0, 0);
$pdf->SetFont('times', "B", 11);
$pdf->Cell(144, 10, 'Total Expenses:', 1, 0, "C", true);
$pdf->Cell(48, 10, $total_revenue_['total_expense'], 1, 1, "C", true);

$pdf->Ln(2);
$lastCellY = ($pdf->GetY() + 20);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(0, $lastCellY);

$pdf->SetFont('aefurat', "U", 11);
$pdf->Cell(0, 10, "Net Income", 0, 1, "C", false);
$pdf->SetFont('aefurat', "B", 20);

$pdf->Cell(0, 10, $total_revenue_['difference'], 0, 1, "C", false);

// Set border properties
$borderWidth = 2;  // Border width in user units
$borderColor = array(200, 200, 200);  // RGB color for the border (black in this case)

// Set fill color for the rectangle
$fillColor = array(255, 255, 255);  // RGB color for the fill (gray in this case)


$pdf->Rect(75, $lastCellY, 60, 20, '', array('all' => $borderWidth, 'color' => $borderColor), $fillColor);

$pdf->Ln(3);