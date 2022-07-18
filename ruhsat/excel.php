<?php

require_once '../lib/config.php';
require_once PROJECT_DIR . '/lib/functions.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
require_once PROJECT_DIR . '/lib/class.excel.php';
$_POST = unserialize($_POST["params"]);
//var_dump($_POST);
//die();
if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Ruhsat Ekranı");
    $excel_sender->toExcelFromArray(array('RUHSAT NO', 'İskan', 'İskan Ruhsat Tarihi', 'Adı Soyadı', 'Cinsi', 'Ruhsat Veriliş Amacı', 'Fenni Mesul', 'Ruhsat Tarihi', 'Mahallesi', 'Bülten No', 'Ada/Parsel', 'YİBF No', 'Ölçüsü'));
    $ItemsSQL = "";
    $ruhsat_where_string = "";
    $ruhsat_where = array();
    if (trim($_POST['ruhsat_no']) == "") {
        if (trim($_POST['ruhsat_cinsi']) !== "") {
            $ruhsat_where_string .= " AND cinsi = ? ";
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
        $ItemsSQL = "SELECT
                                            s_ruhsat_bilgileri.ruhsat_no, 
                                            (CASE s_ruhsat_bilgileri.iskan_verildi_mi WHEN 1 THEN 'İskan Verildi' WHEN 0 THEN 'İskan Yok' END) AS iskan_verildi_mi, 
                                            DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,  
                                            s_ruhsat_bilgileri.adi_soyadi, 
                                            s_ruhsat_bilgileri.cinsi, 
                                            s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
                                            s_ruhsat_bilgileri.fenni_mesul, 
                                            DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
                                            s_ruhsat_bilgileri.mahallesi, 
                                            s_ruhsat_bilgileri.bulten_no, 
                                            s_ruhsat_bilgileri.ada_parsel, 
                                            s_ruhsat_bilgileri.yibf_no, 
                                            s_ruhsat_bilgileri.olcusu
                                    FROM
                                            s_ruhsat_bilgileri
                                            WHERE aktif_mi " . $ruhsat_where_string . " LIMIT 1000";
    } else {
        $ItemsSQL = "SELECT 
                                                s_ruhsat_bilgileri.ruhsat_no, 
                                                (CASE s_ruhsat_bilgileri.iskan_verildi_mi WHEN 1 THEN 'İskan Verildi' WHEN 0 THEN 'İskan Yok' END) AS iskan_verildi_mi, 
                                                DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,  
                                                s_ruhsat_bilgileri.adi_soyadi, 
                                                s_ruhsat_bilgileri.cinsi, 
                                                s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
                                                s_ruhsat_bilgileri.fenni_mesul, 
                                                DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
                                                s_ruhsat_bilgileri.mahallesi, 
                                                s_ruhsat_bilgileri.bulten_no, 
                                                s_ruhsat_bilgileri.ada_parsel, 
                                                s_ruhsat_bilgileri.yibf_no, 
                                                s_ruhsat_bilgileri.olcusu
                                        FROM
                                                s_ruhsat_bilgileri
                                                WHERE aktif_mi AND id = ? ";
        $ruhsat_id = mdecrypt($_POST['ruhsat_no'], $_SESSION['key']);
        array_push($ruhsat_where, $ruhsat_id);
    }
    $excel_sender->toExcelFromSqlZend($ItemsSQL, $ruhsat_where);
    $excel_sender->toExcelFinish();
}
?>                                         
