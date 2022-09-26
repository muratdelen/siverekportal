
<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.pdf.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_PDF, $sayfaIslemleriId)) {
    if ($_POST['grup'] == "") {
        $ItemsSQL = "SELECT yt_grup.adi, yt_grup.aciklama, (CASE yt_grup.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                                          FROM yt_grup WHERE NOT(id=1 OR id=2 OR id=3)";
        try {
            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    } else {
        $grup_id = mdecrypt($_POST['grup'], $_SESSION['key']);
        $ItemsSQL = "SELECT yt_grup.adi, yt_grup.aciklama, (CASE yt_grup.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                                         FROM yt_grup WHERE NOT(id=1 OR id=2 OR id=3) AND id = ? ";
        try {
            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $grup_id);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }
    htmlspecialchar_array($listItems);

    $listItemsTitle = array(
        'adi' => 'Grup Adı',
        'aciklama' => 'Açıklaması',
        'aktif_mi' => 'Aktif Mi?');
    $pdf_class = new myPDF("Gruplar_" . (date('d-m-Y H:i:s')));
//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
//    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
//    $pdf_class->headerVisible = FALSE;// BU YORUM KALKTIĞINDA HEADER YOK OLACAK
//    $pdf_class->footerVisible = FALSE;// BU YORUM KALKTIĞINDA FOOTER YOK OLACAK
    $pdf_class->headerTitle = "Grup Tanımlama Sorgulama Ekranı";
    $pdf_class->headerSubString = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = PDF_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);
}
?>
 
