<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . 'lib/functions.php';
require_once PROJECT_DIR . 'lib/input_filter.php';
require_once PROJECT_DIR . 'lib/class.excel.php';
//$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Hata Kayıtları");
    $excel_sender->toExcelFromArray(array('Kullanıcı Adı', 'ip', 'Zaman', 'Başlık', 'Açıklama', 'Sayfa Satırı', 'Sayfa Url', 'Hata Tipi', 'Aktif mi?'), "header", "Hata Kayıtları");
    $ItemsSQL = "SELECT yt_kullanici.kullanici_adi, log_hatalar.ip, date_format(log_hatalar.zaman,'%d/%m/%Y %H:%i:%s') AS zaman, log_hatalar.baslik, log_hatalar.aciklama, log_hatalar.satir_sirasi, log_hatalar.sayfa_url, log_hatalar.tipi, (CASE log_hatalar.aktif_mi WHEN 1 THEN 'Akfif' ELSE 'Pasif' END) AS aktif_mi FROM log_hatalar LEFT JOIN yt_kullanici ON yt_kullanici.id = log_hatalar.yt_kullanici_id ";
    $excel_sender->toExcelFromSqlZend($ItemsSQL);
    $excel_sender->toExcelFinish();
}
?>                                       