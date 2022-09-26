
<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.pdf.php';
$_POST = unserialize($_POST["params"]);

if (in_array(7, $sayfaIslemleriId)) {
    $_POST['grup'] = mdecrypt($_POST['grup'], $_SESSION['key']);
    $ItemsSQL = "CALL alt_grup_getir( ?, ? )";
    try {
        $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, array($_POST['grup'],1));
        htmlspecialchar_array($listItems);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $listItemsTitle = array(
        'ana_grup' => 'Ana Grup Adı',
        'adi' => 'Grup Adı',
        'aciklama' => 'Açıklaması');

    $pdf_class = new myPDF("GrupŞeması_" . (date('d-m-Y H:i:s')));
//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
//    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
//    $pdf_class->headerVisible = FALSE;// BU YORUM KALKTIĞINDA HEADER YOK OLACAK
//    $pdf_class->footerVisible = FALSE;// BU YORUM KALKTIĞINDA FOOTER YOK OLACAK
    $pdf_class->headerTitle = "Grup Şeması Sorgulama Ekranı";
    $pdf_class->headerSubString = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = PDF_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);
}
?>
 
