
<?php

require_once '../lib/config.php';
require_once '../lib/functions.php';
require_once '../lib/class.pdf.php';

//$_POST = unserialize($_POST["params"]);
//    var_dump($_POST) ;
if (isset($_POST['ekpertiz_raporu']) && in_array(YT_PDF, $sayfaIslemleriId)) {

//    var_dump($secilen_ruhsat, $secilen_ruhsat_bilgileri);die();
    $pdf_class = new myPDF(date('d-m-Y')."_".$_POST["dosya_adi"] ."_". date('H-i-s'));

//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
    $pdf_class->headerVisible = FALSE; // BU YORUM KALKTIĞINDA HEADER YOK OLACAK
    $pdf_class->footerVisible = FALSE; // BU YORUM KALKTIĞINDA FOOTER YOK OLACAK 
//        $pdf_class->fontName = "times"; //"dejavusans";
    $pdf_class->fontSize = 12;
    $pdf_class->headerTitle = "Ekspertiz Çıktısı";
    $pdf_class->headerSubString = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = PDF_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
//    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);

    $pdf_class->FromHtmlToPdf($_POST['ekpertiz_raporu']);
}
?>
 
