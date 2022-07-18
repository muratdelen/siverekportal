<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Kullanıcı Diğer Tanımlama Ekranı");
    $excel_sender->toExcelFromArray(array('Kullanıcı Adı', 'Adı', 'Soyadı', 'Ünvanı', 'Grubu', 'Aktif Mi?'), "header", "Kullanıcı Diğer Grup Ekranı");
    $ItemsSQL = "SELECT
                        yt_kullanici.kullanici_adi,
                        yt_kullanici.adi,
                        yt_kullanici.soyadi,
                        yt_kullanici.aciklamasi,
                        yt_grup.adi AS grup,
                        (CASE yt_kullanici_diger_gruplari.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                    FROM yt_kullanici
                        INNER JOIN yt_kullanici_diger_gruplari ON yt_kullanici_diger_gruplari.yt_kullanici_id = yt_kullanici.id
                        INNER JOIN yt_grup ON yt_grup.id = yt_kullanici_diger_gruplari.yt_grup_id
                    WHERE NOT(yt_kullanici.yt_grup_id=1 OR yt_kullanici.yt_grup_id=2) AND (yt_kullanici.kullanici_adi = ? OR yt_kullanici.adi LIKE ? OR yt_kullanici.soyadi LIKE ? ) ";

  $excel_sender->toExcelFromSqlZend($ItemsSQL, array($_POST['kullanici_adi'], "%" . $_POST['kisi_adi_soyadi'] . "%", "%" . $_POST['kisi_adi_soyadi'] . "%"));
    $excel_sender->toExcelFinish();
}
?>