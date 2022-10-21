<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . 'lib/functions.php';
require_once PROJECT_DIR . 'lib/input_filter.php';
require_once PROJECT_DIR . 'lib/DataTable.php';

if (in_array(YT_QUERY, $sayfaIslemleriId)) {
    $aColumns = array("(case log_giris.tipi when 0 then 'Çıkış' 
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
                                            AS tipi"
        , 'yt_kullanici.kullanici_adi', 'yt_kullanici.adi', 'yt_kullanici.soyadi', 'yt_grup.adi AS grup_adi', 'log_giris.baslik', 'log_giris.aciklama', 'ip', "date_format(log_giris.zaman,'%d/%m/%Y %H:%i:%s') AS zaman", "(CASE log_giris.aktif_mi WHEN 1 THEN 'Akfif' ELSE 'Pasif' END) AS aktif_mi");

    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "log_giris.id";

    /* DB table to use */
    $sTable = "log_giris "
            . "LEFT JOIN yt_kullanici ON yt_kullanici.id = log_giris.yt_kullanici_id "
            . "LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id	 ";

    $where = "";
    $columnsName = array('tipi', 'yt_kullanici.kullanici_adi', 'yt_kullanici.adi', 'yt_kullanici.soyadi', 'yt_grup.adi', 'log_giris.baslik', 'log_giris.aciklama', 'ip', "log_giris.zaman", 'log_giris.aktif_mi');

    try {
        $link = $db->getConnection();

        foreach ($_GET as $key => $value) {
            $_GET[$key] = mysqli_real_escape_string($link, $value);
        }

        echo DataTable::process_table_content($aColumns, $sIndexColumn, $sTable, $where, $columnsName);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
}