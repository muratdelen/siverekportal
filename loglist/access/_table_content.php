<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . 'lib/functions.php';
require_once PROJECT_DIR . 'lib/input_filter.php';
require_once PROJECT_DIR . 'lib/DataTable.php';
if (in_array(YT_QUERY, $sayfaIslemleriId)) {
    $aColumns = array('yt_kullanici.kullanici_adi', 'yt_kullanici.adi', 'yt_kullanici.soyadi', 'yt_grup.adi AS grup_adi', 'yt_sayfa_islemleri.adi AS islem_adi', 'log_kullanici_sayfa_erisimleri.sayfa_url', 'log_kullanici_sayfa_erisimleri.ip', "date_format(log_kullanici_sayfa_erisimleri.zaman,'%d/%m/%Y %H:%i:%s') AS zaman", "(CASE log_kullanici_sayfa_erisimleri.aktif_mi WHEN 1 THEN 'Akfif' ELSE 'Pasif' END) AS aktif_mi");

    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "log_kullanici_sayfa_erisimleri.id";

    /* DB table to use */
    $sTable = "log_kullanici_sayfa_erisimleri "
            . "LEFT JOIN yt_kullanici ON yt_kullanici.id = log_kullanici_sayfa_erisimleri.yt_kullanici_id "
            . "LEFT JOIN  yt_sayfa_islemleri ON yt_sayfa_islemleri.id = log_kullanici_sayfa_erisimleri.yt_sayfa_islemleri "
            . "LEFT JOIN yt_grup ON yt_grup.id = log_kullanici_sayfa_erisimleri.yt_grup_id	 ";
    $where = "";
    $columnsName = array('yt_kullanici.kullanici_adi', 'yt_kullanici.adi', 'yt_kullanici.soyadi', 'yt_grup.adi', 'yt_sayfa_islemleri.adi', 'log_kullanici_sayfa_erisimleri.sayfa_url', 'log_kullanici_sayfa_erisimleri.ip', "log_kullanici_sayfa_erisimleri.zaman", 'log_kullanici_sayfa_erisimleri.aktif_mi');

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
