
<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.pdf.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_PDF, $sayfaIslemleriId)) {
    $kullanici_id = mdecrypt($_POST['secilen_kullanici'], $_SESSION['key']);
    try {
        $listItems = $GLOBALS['db']->fetchAll("CALL kullanici_yetkilerini_goster ( ? )", $kullanici_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    try {
        $kullanici_adi = $GLOBALS['db']->fetchRow("SELECT yt_kullanici.kullanici_adi FROM yt_kullanici WHERE yt_kullanici.id = ? ", $kullanici_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);
    foreach ($listItems as $value) {
        $value->id = $kullanici_adi->kullanici_adi;
    }
    $listItemsTitle = array(
        'id' => 'Kullanıcı Adı',
        'menuler' => 'Menuler',
        'yetkiler' => 'Yetkiler',
        'aciklama' => 'Açıklama');

    $pdf_class = new myPDF("KullanıcıRolleri_" . (date('d-m-Y H:i:s')));
//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
//    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
//    $pdf_class->headerVisible = FALSE;// BU YORUM KALKTIĞINDA HEADER YOK OLACAK
//    $pdf_class->footerVisible = FALSE;// BU YORUM KALKTIĞINDA FOOTER YOK OLACAK
    $pdf_class->headerTitle = "Kullanıcı Rol Tanımlama Sorgulama Ekranı";
    $pdf_class->headerSubString = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = PDF_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);
}
?>
 
