<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
require_once PROJECT_DIR . '/lib/class.excel.php';

$_POST = unserialize($_POST["params"]);
if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Kullanıcı Sayfa Erişim Kayıtları");
    $excel_sender->toExcelFromArray(array('Kullanıcı Adı', 'Adı', 'Soyadı', 'Grubu', 'Yapılan İşlem', 'Erişilen Sayfa', 'ip', 'Zaman', 'Aktif mi?'), "header", "Erişim Kayıtları");

    $ItemsSQL = "SELECT yt_kullanici.kullanici_adi, yt_kullanici.adi, yt_kullanici.soyadi, yt_grup.adi AS grup_adi, yt_sayfa_islemleri.adi AS islem_adi, log_kullanici_sayfa_erisimleri.sayfa_url, log_kullanici_sayfa_erisimleri.ip, date_format(log_kullanici_sayfa_erisimleri.zaman,'%d/%m/%Y %H:%i:%s') AS zaman, (CASE log_kullanici_sayfa_erisimleri.aktif_mi WHEN 1 THEN 'Akfif' ELSE 'Pasif' END) AS aktif_mi FROM log_kullanici_sayfa_erisimleri "
            . "LEFT JOIN yt_kullanici ON yt_kullanici.id = log_kullanici_sayfa_erisimleri.yt_kullanici_id "
            . "LEFT JOIN  yt_sayfa_islemleri ON yt_sayfa_islemleri.id = log_kullanici_sayfa_erisimleri.yt_sayfa_islemleri "
            . "LEFT JOIN yt_grup ON yt_grup.id = log_kullanici_sayfa_erisimleri.yt_grup_id	 ";
   
    $excel_sender->toExcelFromSqlZend($ItemsSQL);
    $excel_sender->toExcelFinish();
}
?>