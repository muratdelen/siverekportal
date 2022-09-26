<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . '/lib/functions.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
require_once PROJECT_DIR . '/lib/class.excel.php';
//$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Çıktı Kayıtları");
    $excel_sender->toExcelFromArray(array('Dosya Tipi', 'Kullanıcı Adı', 'Adı', 'Soyadı', 'Grubu', 'Başlık', 'Açıklama', 'sayfa_url', 'ip', 'Zaman', 'Aktif mi?'), "header", "Çıktı Kayıtları");
    $ItemsSQL = "SELECT log_cikti.tipi, yt_kullanici.kullanici_adi, yt_kullanici.adi, yt_kullanici.soyadi, yt_grup.adi AS grup_adi, log_cikti.baslik, log_cikti.aciklama, log_cikti.sayfa_url, log_cikti.ip,date_format(log_cikti.zaman,'%d/%m/%Y %H:%i:%s') AS zaman, (CASE log_cikti.aktif_mi WHEN 1 THEN 'Akfif' ELSE 'Pasif' END) AS aktif_mi FROM log_cikti "
            . "LEFT JOIN yt_kullanici ON yt_kullanici.id = log_cikti.yt_kullanici_id "
            . "LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id	 ";
    $excel_sender->toExcelFromSqlZend($ItemsSQL);
    $excel_sender->toExcelFinish();
}
?>                                       