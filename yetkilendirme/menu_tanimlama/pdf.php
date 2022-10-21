
<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . 'lib/functions.php';
require_once PROJECT_DIR . 'lib/input_filter.php';
require_once PROJECT_DIR . 'lib/class.pdf.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_PDF, $sayfaIslemleriId)) {
    if ($_POST['menu'] == "") {
        $ItemsSQL = "SELECT
                        (SELECT yt_menu.adi FROM yt_menu WHERE yt_menu.id = selected_menu.ana_menu_id) AS main_menu,
                         selected_menu.adi, selected_menu.link, (CASE selected_menu.disariya_acik_mi WHEN 1 THEN '" . __("Dışarıya Açık") . "' WHEN 0 THEN '" . __("Sadece Girişli") . "' END ) AS disariya_acik_mi, selected_menu.`language`, (CASE selected_menu.aktif_mi WHEN 1 THEN '" . __("Aktif") . "' WHEN 0 THEN '" . __("Pasif") . "' END) AS aktif_mi
                   FROM yt_menu selected_menu";
        try {
            $listItems = $GLOBALS["db"]->fetchAll($ItemsSQL);
        } catch (Zend_Db_Exception $ex) {
            log::DB_save_error_information(__FILE__, $ex);
        }
    } else {
        $menu_id = mdecrypt($_POST['menu'], $_SESSION['key']);
        $ItemsSQL = "SELECT
                        (SELECT yt_menu.adi FROM yt_menu WHERE yt_menu.id = selected_menu.ana_menu_id) AS main_menu,
                        selected_menu.adi, selected_menu.link, (CASE selected_menu.disariya_acik_mi WHEN 1 THEN '" . __("Dışarıya Açık") . "' WHEN 0 THEN '" . __("Sadece Girişli") . "' END ) AS disariya_acik_mi, selected_menu.`language`, (CASE selected_menu.aktif_mi WHEN 1 THEN '" . __("Aktif") . "' WHEN 0 THEN '" . __("Pasif") . "' END) AS aktif_mi
                    FROM yt_menu selected_menu WHERE id = ? ";
        try {
            $listItems = $GLOBALS["db"]->fetchAll($ItemsSQL, $menu_id);
        } catch (Zend_Db_Exception $ex) {
            log::DB_save_error_information(__FILE__, $ex);
        }
    }
    htmlspecialchar_array($listItems);

    $listItemsTitle = array(
        'main_menu' => 'Ana Menu Adı',
        'adi' => 'Menu Adı',
        'link' => 'Sayfa Url',
        'disariya_acik_mi' => 'Dışarıya Açık mı?',
        'language' => 'Dili',
        'aktif_mi' => 'Aktif mi?');
    $pdf_class = new myPDF("Menu_" . (date('d-m-Y H:i:s')));
//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
//    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
//    $pdf_class->headerVisible = FALSE;// BU YORUM KALKTIĞINDA HEADER YOK OLACAK
//    $pdf_class->footerVisible = FALSE;// BU YORUM KALKTIĞINDA FOOTER YOK OLACAK
    $pdf_class->headerTitle = "Menu Tanımlama Sorgulama Ekranı";
    $pdf_class->headerSubString = "Bu çıktı  " . $LoggedUser . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = THEME_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);
}
?>