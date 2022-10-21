<?php
require_once '../../lib/config.php';
require_once PROJECT_DIR . 'lib/functions.php';
require_once PROJECT_DIR . 'lib/input_filter.php';
require_once PROJECT_DIR . 'lib/class.pdf.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_PDF, $sayfaIslemleriId)) {
    if (isset($_POST['ruhsat'])) {
                        if ($_POST['ruhsat'] == "ruhsatyok") {
                           $ItemsSQL = "SELECT
                                        s_ruhsat_bilgileri.id, 
                                        s_ruhsat_bilgileri.ruhsat_no, 
                                        DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,	
                                       (CASE s_ruhsat_bilgileri.kacak_islem_yapildi_mi
                                        WHEN 1 THEN '<h6 style=\"back-color:red\">Kaçak İşlem Yapıldı.</h6>' 
                                        WHEN 0 THEN '' END) AS kacak_islem_yapildi_mi, 
                                        s_ruhsat_bilgileri.bulten_no, 
                                        s_ruhsat_bilgileri.ada_parsel, 
                                        s_ruhsat_bilgileri.yibf_no,
                                        s_ruhsat_bilgileri.adi_soyadi, 
                                        s_ruhsat_bilgileri.ruhsat_cinsi, 
                                        s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
                                        s_ruhsat_bilgileri.fenni_mesul, 
                                        s_ruhsat_bilgileri.mahallesi,  
                                        s_ruhsat_bilgileri.yapi_alani, 
                                         (CASE s_ruhsat_bilgileri.iskan_verildi_mi 
                                        WHEN 1 THEN '<h6 style=\"color:green\">İskan Verildi</h6>' 
                                        WHEN 0 THEN '<h6 style=\"color:red\">İskan Yok</h6>' END) AS iskan_verildi_mi, 
                                        DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
                                        s_ruhsat_bilgileri.iskan_no, 
                                        s_ruhsat_bilgileri.iskan_bulten_no, 
                                        s_ruhsat_bilgileri.kacak_islem_bilgisi
                                FROM s_ruhsat_bilgileri WHERE s_ruhsat_bilgileri.aktif_mi AND ISNULL(ruhsat_no) LIMIT 1000";
                            try {
                                $listItems = $GLOBALS['db']->fetchAll($ItemsSQL);
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            } 
                        } else {
                            $ItemsSQL = "SELECT
                                        s_ruhsat_bilgileri.id, 
                                        s_ruhsat_bilgileri.ruhsat_no, 
                                        DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,	
                                       (CASE s_ruhsat_bilgileri.kacak_islem_yapildi_mi
                                        WHEN 1 THEN '<h6 style=\"back-color:red\">Kaçak İşlem Yapıldı.</h6>' 
                                        WHEN 0 THEN '' END) AS kacak_islem_yapildi_mi, 
                                        s_ruhsat_bilgileri.bulten_no, 
                                        s_ruhsat_bilgileri.ada_parsel, 
                                        s_ruhsat_bilgileri.yibf_no,
                                        s_ruhsat_bilgileri.adi_soyadi, 
                                        s_ruhsat_bilgileri.ruhsat_cinsi, 
                                        s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
                                        s_ruhsat_bilgileri.fenni_mesul, 
                                        s_ruhsat_bilgileri.mahallesi,  
                                        s_ruhsat_bilgileri.yapi_alani, 
                                         (CASE s_ruhsat_bilgileri.iskan_verildi_mi 
                                        WHEN 1 THEN '<h6 style=\"color:green\">İskan Verildi</h6>' 
                                        WHEN 0 THEN '<h6 style=\"color:red\">İskan Yok</h6>' END) AS iskan_verildi_mi, 
                                        DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
                                        s_ruhsat_bilgileri.iskan_no, 
                                        s_ruhsat_bilgileri.iskan_bulten_no, 
                                        s_ruhsat_bilgileri.kacak_islem_bilgisi
                                FROM s_ruhsat_bilgileri WHERE s_ruhsat_bilgileri.aktif_mi AND id = ? ";
                            $ruhsat_id = mdecrypt($_POST['ruhsat'], $_SESSION['key']);
                            try {
                                $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $ruhsat_id);
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            }
                        }
                    } else {
                        $ruhsat_where_string = "";
                        $ruhsat_where = array();
                        if (trim($_POST['ruhsat_cinsi']) !== "") {
                            $ruhsat_where_string .= " AND ruhsat_cinsi = ? ";
                            array_push($ruhsat_where, trim($_POST['ruhsat_cinsi']));
                        }
                        if (trim($_POST['iskan_verildi_mi']) !== "") {
                            $ruhsat_where_string .= " AND iskan_verildi_mi = ? ";
                            array_push($ruhsat_where, trim($_POST['iskan_verildi_mi']));
                        }
                        if (trim($_POST['adi_soyadi']) !== "") {
                            $ruhsat_where_string .= " AND adi_soyadi LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_POST['adi_soyadi']) . "%");
                        }
                        if (trim($_POST['ruhsat_tarihi']) !== "") {
                            $ruhsat_where_string .= " AND ruhsat_tarihi = ? ";
                            array_push($ruhsat_where, convertDateFormatBasicDefault($_POST['ruhsat_tarihi']));
                        }
                        if (trim($_POST['mahallesi']) !== "") {
                            $ruhsat_where_string .= " AND mahallesi LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_POST['mahallesi']) . "%");
                        }
                        if (trim($_POST['bulten_no']) !== "") {
                            $ruhsat_where_string .= " AND bulten_no LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_POST['bulten_no']) . "%");
                        }
                        if (trim($_POST['ada_parsel']) !== "") {
                            $ruhsat_where_string .= " AND ada_parsel LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_POST['ada_parsel']) . "%");
                        }
                        if (trim($_POST['yibf_no']) !== "") {
                            $ruhsat_where_string .= " AND yibf_no LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_POST['yibf_no']) . "%");
                        }
                        if (trim($_POST['kacak_islem_yapildi_mi']) !== "") {
                            $ruhsat_where_string .= " AND kacak_islem_yapildi_mi = ? ";
                            array_push($ruhsat_where, trim($_POST['kacak_islem_yapildi_mi']));
                        }
                        $ItemsSQL = "SELECT
                                        s_ruhsat_bilgileri.id, 
                                        s_ruhsat_bilgileri.ruhsat_no, 
                                        DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,	
                                       (CASE s_ruhsat_bilgileri.kacak_islem_yapildi_mi
                                        WHEN 1 THEN '<h6 style=\"back-color:red\">Kaçak İşlem Yapıldı.</h6>' 
                                        WHEN 0 THEN '' END) AS kacak_islem_yapildi_mi, 
                                        s_ruhsat_bilgileri.bulten_no, 
                                        s_ruhsat_bilgileri.ada_parsel, 
                                        s_ruhsat_bilgileri.yibf_no,
                                        s_ruhsat_bilgileri.adi_soyadi, 
                                        s_ruhsat_bilgileri.ruhsat_cinsi, 
                                        s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
                                        s_ruhsat_bilgileri.fenni_mesul, 
                                        s_ruhsat_bilgileri.mahallesi,  
                                        s_ruhsat_bilgileri.yapi_alani, 
                                         (CASE s_ruhsat_bilgileri.iskan_verildi_mi 
                                        WHEN 1 THEN '<h6 style=\"color:green\">İskan Verildi</h6>' 
                                        WHEN 0 THEN '<h6 style=\"color:red\">İskan Yok</h6>' END) AS iskan_verildi_mi, 
                                        DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
                                        s_ruhsat_bilgileri.iskan_no, 
                                        s_ruhsat_bilgileri.iskan_bulten_no, 
                                        s_ruhsat_bilgileri.kacak_islem_bilgisi
                                FROM s_ruhsat_bilgileri WHERE s_ruhsat_bilgileri.aktif_mi " . $ruhsat_where_string . " LIMIT 1000";
                        try {
                            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $ruhsat_where);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
//                        var_dump($listItems, $ItemsSQL);
                    }
    
    htmlspecialchar_array($listItems);
   $listItemsTitle =  array(
       "ruhsat_no" => 'RUHSAT NO', 
       "ruhsat_tarihi" => 'Ruhsat Tarihi', 
       "kacak_islem_yapildi_mi" => 'Kaçak', 
       "bulten_no" => 'Bülten No', 
       "ada_parsel" => 'Ada/Parsel', 
       "yibf_no" => 'YİBF No', 
       "adi_soyadi" => 'Adı Soyadı', 
       "ruhsat_cinsi" => 'Ruhsat Cinsi', 
       "ruhsat_verilis_amaci" => 'Ruhsat Veriliş Amacı', 
       "fenni_mesul" => 'Fenni Mesul/YDK', 
       "mahallesi" => 'Mahallesi', 
       "yapi_alani" => 'Yapı Alanı', 
       "iskan_verildi_mi" => 'İskan', 
       "iskan_ruhsat_tarihi" => 'İskan Tarihi', 
       "iskan_no" => 'İskan No', 
       "iskan_bulten_no" => 'İskan Bülten No', 
       "kacak_islem_bilgisi" => 'Kaçak Bilgisi');
       
    $pdf_class = new myPDF("Ruhsatlar" . (date('d-m-Y H:i:s')));
//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
//    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
//    $pdf_class->headerVisible = FALSE;// BU YORUM KALKTIĞINDA HEADER YOK OLACAK
//    $pdf_class->footerVisible = FALSE;// BU YORUM KALKTIĞINDA FOOTER YOK OLACAK   
    $pdf_class->fontSize = 12;
    $pdf_class->headerTitle = "Ruhsat Ekranı";
    $pdf_class->headerSubString = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = PDF_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);
}
?>
 