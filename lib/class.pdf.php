<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('tcpdf/tcpdf.php');

/**
 * Description of pdf
 *
 * @author Bilgi
 */
class myPDF extends TCPDF {

    public $FileName; // height of a single module in points
    public $pageSize;
    public $pageIsVertical;
    public $footerVisible;
    public $headerVisible;
    public $headerTitle;
    public $headerSubString;
    public $fontName; //"times";//"dejavusans";
    public $fontSize;
    public $barcodeVisible;
    public $barcodeType; //'QRCODE,L'  'QRCODE,M'  'QRCODE,H'
    public $barcodeUrl;
    public $barcodeStartX;
    public $barcodeEndX;
    public $barcodeStartY;
    public $barcodeEndY;
    public $barcodeBorder;
    public $barcodeVpadding;
    public $barcodeHpadding;
    public $barcodeFgcolor;
    public $barcodeBgcolor; //array(255,255,255)
    public $barcodeModuleWidth; // width of a single module in points
    public $barcodeModuleHeight; // height of a single module in points
    public $printTitle;
    public $printDescription;

    function __construct($file_name = "") {
        if ($file_name != "") {
            $this->FileName = $file_name;
        } else {
            $this->FileName = "PDF" . date('d-m-Y H:i:s'); // 
        }
        $this->pageSize = "A4"; //"A5"; "A6"; "A7";
        $this->pageIsVertical = FALSE;
        $this->footerVisible = TRUE;
        $this->headerVisible = TRUE;
        $this->headerTitle = "";
        $this->headerSubString = "";
        $this->fontName = "freeserif"; //  "freeserif";// "times";//"dejavusans";
        $this->fontSize = 8;
        $this->barcodeVisible = TRUE;
        $this->barcodeType = "DATAMATRIX"; //"QRCODE,Q"; 'QRCODE,L'  'QRCODE,M'  'QRCODE,H' 'RAW' 'RAW2' 'DATAMATRIX'
        $this->barcodeUrl = "";
        $this->barcodeStartX = 180;
        $this->barcodeEndX = 190;
        $this->barcodeStartY = 4;
        $this->barcodeEndY = 8;
//        $this->barcodeStartX = 175;
//        $this->barcodeEndX = 230;
//        $this->barcodeStartY = 3;
//        $this->barcodeEndY = 23;
        $this->barcodeBorder = 0;
        $this->barcodeVpadding = "auto";
        $this->barcodeHpadding = "auto";
        $this->barcodeFgcolor = array(0, 0, 0);
        $this->barcodeBgcolor = FALSE; //array(255,255,255)
        $this->barcodeModuleWidth = 1; // width of a single module in points
        $this->barcodeModuleHeight = 1; // height of a single module in points
        $this->printTitle = "";
        $this->printDescription = "";
    }

//
    function myUrlEncode($string) {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, urlencode($string));
    }

    public function mcrypt($data, $key) {
        return $data;
        $algorithm = MCRYPT_BLOWFISH;
        $mode = MCRYPT_MODE_ECB;
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, $mode), MCRYPT_DEV_URANDOM);
        $encrypted_data = mcrypt_encrypt($algorithm, $key, $data, $mode, $iv);
        return $this->myUrlEncode(base64_encode($encrypted_data));
    }

    public function cikti_log_kaydi_ekle() {
        global $kullaniciId, $pageUrl, $Ip, $girisYapanKullaniciGrupId;
        if ($this->printTitle == "") {
            $this->printTitle = $this->headerTitle;
        }
        if ($this->printDescription == "") {
            $this->printDescription = $this->headerSubString;
        }
        $sqlLog = "INSERT INTO log_cikti VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try {
            $GLOBALS['db']->fetchAll($sqlLog, array(NULL, 'pdf', $kullaniciId, $pageUrl, $girisYapanKullaniciGrupId, $this->printTitle, $this->printDescription, $Ip, date('Y-m-d H:i:s'), 1));
            $last_insert_id = $GLOBALS['db']->lastInsertId();
            if ($this->barcodeUrl == "") {
                $encrypt_string = $this->mcrypt($last_insert_id, "muratdelen");
                $this->barcodeUrl = "https://skspersonel.Siverek.edu.tr/loglist/print/index.php?print=" . $encrypt_string; //$this->myUrlEncode($encrypt_string);
            }
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }

    public function FromSqlToPdf($datas_pdf, $titles) {
        $this->cikti_log_kaydi_ekle();
        $html_pdf = '<style> table { 
            color: #333; 
            }
            td, th { border: 1px solid #CCC; }
            th {
            background: #F3F3F3; 
            font-weight: bold; 
            }
            td {
            background: #FAFAFA; /* Lighter grey background */
            text-align: center; /* Center our text */
            }
            </style> ';
        if (is_array($datas_pdf)) {
            $count = 0;
            foreach ($datas_pdf as $row) {
                $count++;
                $html_pdf .= '<table>';
                $html_pdf .= '<tr style="text-align:center;background-color:#E6E6FA;"><th colspan="2"><b>' . $count . '. KAYIT</b></th></tr>';
                foreach ($row as $key => $column) {
                    $html_pdf .= '<tr><th><b>' . $titles[$key] . '</b></th><td>' . $column . '</td></tr>';
                }
                $html_pdf .= '</table>';
                $html_pdf .= '<br/><br/>';
            }
        } else {
            $html_pdf .= '<table>';
            $html_pdf .= '<tr style="text-align:center;background-color:#E6E6FA;"><th colspan="2"><b>1. KAYIT</b></th></tr>';
            foreach ($datas_pdf as $key => $column) {
                $html_pdf .= '<tr><th><b>' . $titles[$key] . '</b></th><td>' . $column . '</td></tr>';
            }
            $html_pdf .= '</table>';
            $html_pdf .= '<br/><br/>';
        }


//        PDF AYARLAMARI
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->SetAuthor($writer_name);
//        $pdf->SetTitle($title);
//        $pdf->SetSubject($subject);
//        $pdf->SetKeywords($keyword);
// remove default header/footer

        $pdf->setPrintHeader($this->headerVisible);
        $pdf->setPrintFooter($this->footerVisible);
// set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $this->headerTitle, $this->headerSubString);

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->setHeaderFont(Array($this->fontName, '', 8, '', true));
        $pdf->setFooterFont(Array($this->fontName, '', $this->fontSize, '', true));
        $pdf->SetFont($this->fontName, '', $this->fontSize, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
        if ($this->pageIsVertical) {
            $pdf->AddPage('L', $this->pageSize);
            $this->barcodeStartX += 85;
//            $this->barcodeStartY -=1;
            $this->barcodeEndX += 85;
//            $this->barcodeEndY -=1;
        } else {
            $pdf->AddPage('P', $this->pageSize);
        }

//$pdf->Cell(0, 0, 'A4 LANDSCAPE', 1, 1, 'C');
//Print text using writeHTMLCell()

        if ($this->barcodeVisible) {
// set style for barcode
            $style = array(
                'border' => $this->barcodeBorder,
                'vpadding' => $this->barcodeVpadding,
                'hpadding' => $this->barcodeHpadding,
                'fgcolor' => $this->barcodeFgcolor,
                'bgcolor' => $this->barcodeBgcolor, //array(255,255,255)
                'module_width' => $this->barcodeModuleWidth, // width of a single module in points
                'module_height' => $this->barcodeModuleHeight // height of a single module in points
            );
//            $pdf->write2DBarcode($this->barcodeUrl, 'QRCODE,Q', 175, 3, 290, 12, $style, 'N');
            $pdf->write2DBarcode($this->barcodeUrl, $this->barcodeType, $this->barcodeStartX, $this->barcodeStartY, $this->barcodeEndX, $this->barcodeEndY, $style, 'N');
//            $pdf->Text(150, 5, 'Çıktı Alınan Sayfa Adresi:');//Çıktı Alınan Sayfa Adresi:
        }

        $pdf->writeHTML($html_pdf, true, false, false, false, '');
//$pdf->writeHTML($html2, true, false, true, false, '');
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
        ob_clean();
        $pdf->Output($this->FileName . '.pdf', 'D');
    }

    public function FromSqlToPdfVertical($datas_pdf, $titles) {
        $this->cikti_log_kaydi_ekle();
        $html_pdf = '<style> table { 
            color: #333; 
            }
            td, th { border: 1px solid #CCC; }
            th {
            background: #F3F3F3; 
            font-weight: bold; 
            }
            td {
            background: #FAFAFA; /* Lighter grey background */
            text-align: center; /* Center our text */
            }
            </style> ';
        if (is_array($datas_pdf)) {
            $count = 0;
            foreach ($datas_pdf as $row) {
                $count++;
                $html_titles = "";
                $html_values = "";
                $colspan = 0;
                foreach ($row as $key => $column) {
                    $html_titles .= '<th><b>' . $titles[$key] . '</b></th>';
                    $html_values .= '<td>' . $column . '</td>';
                    $colspan++;
                }
                $html_pdf .= '<table>';
                $html_pdf .= '<tr style="text-align:center;background-color:#E6E6FA;"><th colspan="' . $colspan . '"><b>' . $count . '. KAYIT</b></th></tr>';
                $html_pdf .= '<tr>' . $html_titles . '</tr>';
                $html_pdf .= '<tr>' . $html_values . '</tr>';
                $html_pdf .= '</table>';
                $html_pdf .= '<br/><br/>';
            }
        } else {
            $html_titles = "";
            $html_values = "";
            $colspan = 0;
            foreach ($datas_pdf as $key => $column) {
                $html_titles .= '<th><b>' . $titles[$key] . '</b></th>';
                $html_values .= '<td>' . $column . '</td>';
                $colspan++;
            }
            $html_pdf .= '<table>';
            $html_pdf .= '<tr style="text-align:center;background-color:#E6E6FA;"><th colspan="' . $colspan . '"><b>1. KAYIT</b></th></tr>';
            $html_pdf .= '<tr>' . $html_titles . '</tr>';
            $html_pdf .= '<tr>' . $html_values . '</tr>';
            $html_pdf .= '</table>';
            $html_pdf .= '<br/><br/>';
        }
//        var_dump($html_pdf);
//        die();
//        PDF AYARLAMARI
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->SetAuthor($writer_name);
//        $pdf->SetTitle($title);
//        $pdf->SetSubject($subject);
//        $pdf->SetKeywords($keyword);
// remove default header/footer

        $pdf->setPrintHeader($this->headerVisible);
        $pdf->setPrintFooter($this->footerVisible);
// set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $this->headerTitle, $this->headerSubString);

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->setHeaderFont(Array($this->fontName, '', $this->fontSize, '', true));
        $pdf->setFooterFont(Array($this->fontName, '', $this->fontSize, '', true));
        $pdf->SetFont($this->fontName, '', $this->fontSize, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
        if ($this->pageIsVertical) {
            $pdf->AddPage('L', $this->pageSize);
            $this->barcodeStartX += 85;
//            $this->barcodeStartY -=1;
            $this->barcodeEndX += 85;
//            $this->barcodeEndY -=1;
        } else {
            $pdf->AddPage('P', $this->pageSize);
        }

//$pdf->Cell(0, 0, 'A4 LANDSCAPE', 1, 1, 'C');
//Print text using writeHTMLCell()
        if ($this->barcodeVisible) {
// set style for barcode
            $style = array(
                'border' => $this->barcodeBorder,
                'vpadding' => $this->barcodeVpadding,
                'hpadding' => $this->barcodeHpadding,
                'fgcolor' => $this->barcodeFgcolor,
                'bgcolor' => $this->barcodeBgcolor, //array(255,255,255)
                'module_width' => $this->barcodeModuleWidth, // width of a single module in points
                'module_height' => $this->barcodeModuleHeight // height of a single module in points
            );
//            $pdf->write2DBarcode($this->barcodeUrl, 'QRCODE,Q', 175, 3, 290, 12, $style, 'N');
            $pdf->write2DBarcode($this->barcodeUrl, $this->barcodeType, $this->barcodeStartX, $this->barcodeStartY, $this->barcodeEndX, $this->barcodeEndY, $style, 'N');
//            $pdf->Text(150, 5, 'Çıktı Alınan Sayfa Adresi:');//Çıktı Alınan Sayfa Adresi:
        }

        $pdf->writeHTML($html_pdf, true, false, false, false, '');
//$pdf->writeHTML($html2, true, false, true, false, '');
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
        ob_clean();
        $pdf->Output($this->FileName . '.pdf', 'D');
    }

    public function FromHtmlToPdf($html_pdf) {
        $this->cikti_log_kaydi_ekle();
//        $html_pdf .= '<style> table { 
//            color: #333; 
//            }
//            td, th { border: 1px solid #CCC; }
//            th {
//            background: #F3F3F3; 
//            font-weight: bold; 
//            }
//            td {
//            background: #FAFAFA; /* Lighter grey background */
//            text-align: center; /* Center our text */
//            }
//            </style> ';



//        PDF AYARLAMARI
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->SetAuthor($writer_name);
//        $pdf->SetTitle($title);
//        $pdf->SetSubject($subject);
//        $pdf->SetKeywords($keyword);
// remove default header/footer

        $pdf->setPrintHeader($this->headerVisible);
        $pdf->setPrintFooter($this->footerVisible);
// set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $this->headerTitle, $this->headerSubString);

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->setHeaderFont(Array($this->fontName, '', 8, '', true));
        $pdf->setFooterFont(Array($this->fontName, '', $this->fontSize, '', true));
        $pdf->SetFont($this->fontName, '', $this->fontSize, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
        if ($this->pageIsVertical) {
            $pdf->AddPage('L', $this->pageSize);
            $this->barcodeStartX += 85;
//            $this->barcodeStartY -=1;
            $this->barcodeEndX += 85;
//            $this->barcodeEndY -=1;
        } else {
            $pdf->AddPage('P', $this->pageSize);
        }

//$pdf->Cell(0, 0, 'A4 LANDSCAPE', 1, 1, 'C');
//Print text using writeHTMLCell()

        if ($this->barcodeVisible) {
// set style for barcode
            $style = array(
                'border' => $this->barcodeBorder,
                'vpadding' => $this->barcodeVpadding,
                'hpadding' => $this->barcodeHpadding,
                'fgcolor' => $this->barcodeFgcolor,
                'bgcolor' => $this->barcodeBgcolor, //array(255,255,255)
                'module_width' => $this->barcodeModuleWidth, // width of a single module in points
                'module_height' => $this->barcodeModuleHeight // height of a single module in points
            );
//            $pdf->write2DBarcode($this->barcodeUrl, 'QRCODE,Q', 175, 3, 290, 12, $style, 'N');
            $pdf->write2DBarcode($this->barcodeUrl, $this->barcodeType, $this->barcodeStartX, $this->barcodeStartY, $this->barcodeEndX, $this->barcodeEndY, $style, 'N');
//            $pdf->Text(150, 5, 'Çıktı Alınan Sayfa Adresi:');//Çıktı Alınan Sayfa Adresi:
        }

        $pdf->writeHTML($html_pdf, true, false, false, false, '');
//$pdf->writeHTML($html2, true, false, true, false, '');
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
        ob_clean();
        $pdf->Output($this->FileName . '.pdf', 'D');
    }

    public function htmlVertical($is_count, $datas_pdf, $titles = "", $data_style = null, $title_style = null) {
        $html_pdf = "";
//        if (is_array($titles)) {
        if ($titles != "") {
            foreach ($titles as $row) {
                $html_pdf .='<tr style="text-align:center;background-color:#E6E6FA;">';
                foreach ($row as $key => $column) {
                    $html_pdf .= '<td ' . (isset($title_style[$key]) ? $title_style[$key] : "") . ' >' . $column . '</td>';
                }
                $html_pdf .= '</tr>';
            }
        }
//        } else {
//            $html_pdf .= '<tr style="text-align:center;background-color:#E6E6FA;">';
//            foreach ($titles as $key => $column) {
//                $html_pdf .= '<td ' . (isset($title_style[$key]) ? $title_style[$key] : "") . ' >' . $column . '</td>';
//            }
//            $html_pdf .= '</tr>';
//        }
        $count = 0;
        if (is_array($datas_pdf)) {
            foreach ($datas_pdf as $row) {
                $count++;
                $html_pdf .= '<tr>';
                if ($is_count) {
                    $html_pdf .= '<td style="text-align:center;" >' . $count . '</td>';
                }
                foreach ($row as $key => $column) {
                    $html_pdf .= '<td ' . (isset($data_style[$key]) ? $data_style[$key] : "") . ' >' . $column . '</td>';
                }
                $html_pdf .= '</tr>';
            }
        } else {
            $html_pdf .= '<tr>';
            if ($is_count) {
                $html_pdf .= '<td style="text-align:center;" >1</td>';
            }
            foreach ($datas_pdf as $key => $column) {
                $html_pdf .= '<td ' . (isset($data_style[$key]) ? $data_style[$key] : "") . ' >' . $column . '</td>';
            }
            $html_pdf .= '</tr>';
        }
        return $html_pdf;
    }

    public function htmlhorizontal($datas_pdf, $titles) {
        $html_pdf = "";
        if (is_array($datas_pdf)) {
            foreach ($datas_pdf as $row) {
                $count++;
                $html_pdf .= '<table>';
                foreach ($row as $key => $column) {
                    $html_pdf .= '<tr><th><b>' . $titles[$key] . '</b></th><td>' . $column . '</td></tr>';
                }
                $html_pdf .= '</table>';
                $html_pdf .= '<br/><br/>';
            }
        } else {
            $html_pdf .= '<table>';
            $html_pdf .= '<tr style="text-align:center;background-color:#E6E6FA;"><th colspan="2"><b>1. KAYIT</b></th></tr>';
            foreach ($datas_pdf as $key => $column) {
                $html_pdf .= '<tr><th><b>' . $titles[$key] . '</b></th><td>' . $column . '</td></tr>';
            }
            $html_pdf .= '</table>';
            $html_pdf .= '<br/><br/>';
        }
        return $html_pdf;
    }

}
