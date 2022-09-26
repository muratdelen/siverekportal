<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . '/lib/functions.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
require_once PROJECT_DIR . '/lib/class.excel.php';
//$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Giriş Kayıtları");
    $excel_sender->toExcelFromArray(array('Giriş Tipi', 'Kullanıcı Adı', 'Adı', 'Soyadı', 'Grubu', 'Başlık', 'Açıklama', 'ip', 'Zaman', 'aktif_mi'), "header", "Giriş Kayıtları");
    $ItemsSQL = "SELECT (case log_giris.tipi when 0 then 'Çıkış' 
                                            when 1 then'Giriş[Veritabanı]' 
                                            when 2 then 'Hata[E-posta]' 
                                            when 3 then 'Giriş[Pasif Kullanıcı]' 
                                            when 4 then 'Hata Pasif Kullanıcı[E-posta]' 
                                            when 5 then 'Giriş[E-posta]' 
                                            when 6 then 'Ldap Hatası[Admin]' 
                                            when 7 then'Veritabanı Giriş[Admin]' 
                                            when 8 then 'Veritabanı Hatası[Admin]' 
                                            when 9 then 'Giriş[Veritabanında Yok]' 
                                            else 'Diğer Hatalar' end) 
                                            AS tipi
        , yt_kullanici.kullanici_adi, yt_kullanici.adi, yt_kullanici.soyadi, yt_grup.adi AS grup_adi, log_giris.baslik, log_giris.aciklama, ip, date_format(log_giris.zaman,'%d/%m/%Y %H:%i:%s') AS zaman, (CASE log_giris.aktif_mi WHEN 1 THEN 'Akfif' ELSE 'Pasif' END) AS aktif_mi FROM log_giris "
            . "LEFT JOIN yt_kullanici ON yt_kullanici.id = log_giris.yt_kullanici_id "
            . "LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id";
    $excel_sender->toExcelFromSqlZend($ItemsSQL);
    $excel_sender->toExcelFinish();
}
?>                                       