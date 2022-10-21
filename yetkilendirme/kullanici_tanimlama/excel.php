<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . 'lib/functions.php';
require_once PROJECT_DIR . 'lib/input_filter.php';
require_once PROJECT_DIR . 'lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Kullanıcı Tanımlama Ekranı");
    $excel_sender->toExcelFromArray(array('Kullanıcı Adı', 'Adı', 'Soyadı', 'Ünvanı', 'Grubu', 'Giriş Türü', 'Aktif Mi?'), "header", "Kullanıcı Tanımlama Ekranı");
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
                                        FROM yt_kullanici
                                            LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id
                                        WHERE NOT(yt_kullanici.yt_grup_id=1 OR yt_kullanici.yt_grup_id=2) AND (yt_kullanici.kullanici_adi = ? OR yt_kullanici.adi LIKE ? OR yt_kullanici.soyadi LIKE ? ) ";
    }
    $excel_sender->toExcelFromSqlZend($ItemsSQL, array($_POST['kullanici_adi'], "%" . $_POST['kisi_adi_soyadi'] . "%", "%" . $_POST['kisi_adi_soyadi'] . "%"));
    $excel_sender->toExcelFinish();
}
?>                                         
   