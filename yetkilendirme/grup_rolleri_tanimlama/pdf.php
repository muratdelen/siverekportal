
<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.pdf.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_PDF, $sayfaIslemleriId)) {
    $grup_id = mdecrypt($_POST['secilen_grup'], $_SESSION['key']);
    try {
        $listItems = $GLOBALS['db']->fetchAll("CALL grup_yetkilerini_goster ( ? )", $grup_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    try {
        $grup = $GLOBALS['db']->fetchRow("SELECT yt_grup.adi FROM yt_grup WHERE yt_grup.id = ? ", $grup_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);
    foreach ($listItems as $value) {
        $value->id = $grup->adi;
    }
    $listItemsTitle = array(
        'id' => 'Grup Adı',
        'menuler' => 'Menuler',
        'yetkiler' => 'Yetkiler',
        'aciklama' => 'Açıklama');

    $pdf_class = new myPDF("GrupRolleri_" . (date('d-m-Y H:i:s')));
//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
//    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
//    $pdf_class->headerVisible = FALSE;// BU YORUM KALKTIĞINDA HEADER YOK OLACAK
//    $pdf_class->footerVisible = FALSE;// BU YORUM KALKTIĞINDA FOOTER YOK OLACAK
    $pdf_class->headerTitle = "Grup Rol Tanımlama Sorgulama Ekranı";
    $pdf_class->headerSubString = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = PDF_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);
}
?>
 
