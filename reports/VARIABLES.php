<?php

     // set default header data
      //   $pdf->SetHeaderData(, PDF_HEADER_LOGO_WIDTH);

        // set header and footer fonts
      //   $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(3, 2, 3);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 10);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

      //   $pdf->setPrintHeader(true);
      //   $pdf->setPrintFooter(true);

        $pdf->setCellHeightRatio(1);
