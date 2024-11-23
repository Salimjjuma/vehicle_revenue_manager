<?php

try{
    if(file_exists(__DIR__. "/../vendor/autoload.php")){
        
        require __DIR__.'/../vendor/autoload.php';

        // setting a global timezone to use across all pdfs.
        date_default_timezone_set('Africa/Nairobi');


        // Extend TCPDF class to create custom header and footer
        class CustomPDF extends TCPDF {
            public function Header() {
                // set document information
                $this->SetCreator(PDF_CREATOR);
                $this->SetAuthor('Vanga Transporters');

                // set some language dependent data:
                //  include  __DIR__ ."/LANGUAGE.php";

                $this->Image('../../retro_logo_2.png', 7,-5,45,40);


                $this->SetFont('times', 'B', 24);
                $this->SetTextColor(0,0,0);

                $this->Cell(0,10,'Vanga Transporters Limited.',$border=0,$ln=1,'C',$fill=false, $link='', $stretch=0, $ignore_min_height=true, $calign='T', $valign='B');
                    
                $this->SetFont('aefurat', "", 10);

                $this->Cell(0,5,'P.O.Box: 98616-80100 Mombasa-Kenya', 0, 1, 'C', false);
                $this->Cell(0,5,'Email: info@vanga.co.ke', 0, 1, 'C', false);

                $this->Cell(0,5,'Tel No: 0720 211 495/ 0733 806 604', 0, 1, 'C', false);
                $this->Cell(0,5,'Website : www.vanga.co.ke', 0, 1, 'C', false);
            }

            public function Footer() {
                $this->SetY(-15);
                // Arial italic 8
                $this->Cell(200, 5, "",$border=0,$ln=1,'C',$fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='C');
                $this->SetFont('aefurat','',10);

                $this->Cell(0, 5, 'Printed on: '.date("F j, Y, g:i a").' ~ By: '.' Abdulhakim Alamin Kassim' ,$border=0,$ln=0,'C',$fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='C');
            }
        }



    }else{
         throw new Exception("Error Processing Request. File Template not Found", 1);
    }
}catch(Exception $e){
        echo 'Exception Caught ',  $e->getMessage() , "\n";
}

