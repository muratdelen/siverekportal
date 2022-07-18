
<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.pdf.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_PDF, $sayfaIslemleriId)) {
    $rol_id = mdecrypt($_POST['secilen_rol'], $_SESSION['key']);
    $ItemsSQL = "SELECT
                                        yt_menu.adi AS menuler,
                                        yt_sayfa_islemleri.adi AS yetkiler,
                                        yt_sayfa_islemleri.aciklama
                                        FROM
                                        yt_rol_sayfa_yetkileri
                                        INNER JOIN yt_menu ON yt_menu.id = yt_rol_sayfa_yetkileri.yt_menu_id
                                        INNER JOIN yt_sayfa_islemleri ON yt_sayfa_islemleri.id = yt_rol_sayfa_yetkileri.yt_sayfa_islemleri_id
                                        WHERE yt_rol_sayfa_yetkileri.aktif_mi AND yt_sayfa_islemleri.aktif_mi 
                                        AND yt_rol_sayfa_yetkileri.yt_rol_id = ? 
                                        ORDER BY yt_rol_sayfa_yetkileri.yt_menu_id";
    try {
        $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $rol_id);
        htmlspecialchar_array($listItems);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $listItemsTitle = array(
        'menuler' => 'Menuler',
        'yetkiler' => 'Yetkiler',
        'aciklama' => 'Aktif Açıklama');
    $pdf_class = new myPDF("Rol_" . (date('d-m-Y H:i:s')));
//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
//    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
//    $pdf_class->headerVisible = FALSE;// BU YORUM KALKTIĞINDA HEADER YOK OLACAK
//    $pdf_class->footerVisible = FALSE;// BU YORUM KALKTIĞINDA FOOTER YOK OLACAK
    $pdf_class->headerTitle = "Rol Tanımlama Sorgulama Ekranı";
    $pdf_class->headerSubString = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = PDF_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);
}
?>
 
