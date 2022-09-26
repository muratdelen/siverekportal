
<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.pdf.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_PDF, $sayfaIslemleriId)) {
    if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
        $ItemsSQL = "SELECT
                            yt_kullanici.kullanici_adi,
                            yt_kullanici.adi,
                            yt_kullanici.soyadi,
                            yt_kullanici.aciklamasi,
                            yt_grup.adi AS grup,
                            (CASE yt_kullanici.giris_tipi WHEN 1 THEN 'Siverek E-posta' WHEN 2 THEN 'Sistem' END) AS giris_tipi ,
                            (CASE yt_kullanici.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                        FROM yt_kullanici
                            LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id
                        WHERE yt_kullanici.kullanici_adi = ? OR yt_kullanici.adi LIKE ? OR yt_kullanici.soyadi LIKE ?  ";
    } else {
        $ItemsSQL = "SELECT
                            yt_kullanici.kullanici_adi,
                            yt_kullanici.adi,
                            yt_kullanici.soyadi,
                            yt_kullanici.aciklamasi,
                            yt_grup.adi AS grup,
                            (CASE yt_kullanici.giris_tipi WHEN 1 THEN 'Siverek E-posta' WHEN 2 THEN 'Sistem' END) AS giris_tipi ,
                            (CASE yt_kullanici.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                        FROM yt_kullanici
                            LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id
                        WHERE NOT(yt_kullanici.yt_grup_id=1 OR yt_kullanici.yt_grup_id=2) AND (yt_kullanici.kullanici_adi = ? OR yt_kullanici.adi LIKE ? OR yt_kullanici.soyadi LIKE ? ) ";
    }
    try {
        $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, array($_POST['kullanici_adi'], "%" . $_POST['kisi_adi_soyadi'] . "%", "%" . $_POST['kisi_adi_soyadi'] . "%"));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);

    $listItemsTitle = array(
        'kullanici_adi' => 'Kullanıcı Adı',
        'adi' => 'Adı',
        'soyadi' => 'Soyadı',
        'aciklamasi' => 'Ünvanı',
        'grup' => 'Grubu',
        'giris_tipi' => 'Giriş Türü',
        'aktif_mi' => 'Aktif Mi?');
    $pdf_class = new myPDF("Kullanıcılar" . (date('d-m-Y H:i:s')));
//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
//    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
//    $pdf_class->headerVisible = FALSE;// BU YORUM KALKTIĞINDA HEADER YOK OLACAK
//    $pdf_class->footerVisible = FALSE;// BU YORUM KALKTIĞINDA FOOTER YOK OLACAK
    $pdf_class->headerTitle = "Kullanıcı Tanımlama Sorgulama Ekranı";
    $pdf_class->headerSubString = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = PDF_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);
}
?>
 
