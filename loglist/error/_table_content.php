<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . 'lib/functions.php';
require_once PROJECT_DIR . 'lib/input_filter.php';
require_once PROJECT_DIR . 'lib/DataTable.php';
if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {

    $aColumns = array('yt_kullanici.kullanici_adi', 'log_hatalar.ip', "date_format(log_hatalar.zaman,'%d/%m/%Y %H:%i:%s') AS zaman", 'log_hatalar.baslik', 'log_hatalar.aciklama', 'log_hatalar.satir_sirasi', 'log_hatalar.sayfa_url', 'log_hatalar.tipi', "(CASE log_hatalar.aktif_mi WHEN 1 THEN 'Akfif' ELSE 'Pasif' END) AS aktif_mi");

    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "log_hatalar.id";

    /* DB table to use */
    $sTable = "log_hatalar "
            . "LEFT JOIN yt_kullanici ON yt_kullanici.id = log_hatalar.yt_kullanici_id ";

    $where = "";
    $columnsName = array('yt_kullanici.kullanici_adi', 'log_hatalar.ip', "log_hatalar.zaman", 'log_hatalar.baslik', 'log_hatalar.aciklama', 'log_hatalar.satir_sirasi', 'log_hatalar.sayfa_url', 'log_hatalar.tipi', 'log_hatalar.aktif_mi');

    $link = $db->getConnection();

    foreach ($_GET as $key => $value) {
        $_GET[$key] = mysqli_real_escape_string($link, $value);
    }

    echo DataTable::process_table_content($aColumns, $sIndexColumn, $sTable, $where, $columnsName);
}
