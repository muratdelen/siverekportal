<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . '/lib/functions.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
require_once PROJECT_DIR . '/lib/DataTable.php';
if (in_array(YT_QUERY, $sayfaIslemleriId)) {
    $aColumns = array('log_cikti.tipi', 'yt_kullanici.kullanici_adi', 'yt_kullanici.adi', 'yt_kullanici.soyadi', 'yt_grup.adi AS grup_adi', 'log_cikti.baslik', 'log_cikti.aciklama', 'log_cikti.sayfa_url', 'log_cikti.ip', "date_format(log_cikti.zaman,'%d/%m/%Y %H:%i:%s') AS zaman");

    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "log_cikti.id";

    /* DB table to use */
    $sTable = "log_cikti "
            . "LEFT JOIN yt_kullanici ON yt_kullanici.id = log_cikti.yt_kullanici_id "
            . "LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id	 ";
    $where = "";
    $columnsName = array('log_cikti.tipi', 'yt_kullanici.kullanici_adi', 'yt_kullanici.adi', 'yt_kullanici.soyadi', 'yt_grup.adi', 'log_cikti.baslik', 'log_cikti.aciklama', 'log_cikti.sayfa_url', 'log_cikti.ip', "log_cikti.zaman");

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