<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . '/lib/functions.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
require_once PROJECT_DIR . '/loglist/alert/DataTable.php';

/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */

if ($girisYapanKullaniciGrupId === 1 || $girisYapanKullaniciGrupId === 2 || $girisYapanKullaniciGrupId === 3 || $girisYapanKullaniciGrupId === 4) {
    $aColumns = array('st_uyari.baslik', 'st_uyari.kullanici_adi', "date_format(st_uyari.zaman,'%d/%m/%Y %H:%i:%s') AS zaman", 'st_uyari.yeni_mi', 'st_uyari.aciklama');
    $columnsName = array('st_uyari.baslik', 'st_uyari.kullanici_adi', "st_uyari.zaman", 'st_uyari.yeni_mi', 'st_uyari.aciklama');
    $where = "WHERE st_uyari.aktif_mi";
} else {
    $aColumns = array('st_uyari.baslik', "date_format(st_uyari.zaman,'%d/%m/%Y %H:%i:%s') AS zaman", 'st_uyari.yeni_mi', 'st_uyari.aciklama');
    $columnsName = array('st_uyari.baslik', "st_uyari.zaman", 'st_uyari.yeni_mi', 'st_uyari.aciklama');
    $where = "WHERE st_uyari.aktif_mi AND st_uyari.kullanici_adi = '" . $kullaniciAdi . "'";
}
/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "st_uyari.id";
$sLinkColumn = "link";

/* DB table to use */
$sTable = "st_uyari "
        . "LEFT JOIN yt_kullanici ON yt_kullanici.id = st_uyari.yt_kullanici_id "
        . "LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id	 ";

//$where = "WHERE aktif_mi AND  kullanici_adi = '".$kullaniciAdi."'";


try {
    $link = $db->getConnection();

    foreach ($_GET as $key => $value) {
        $_GET[$key] = mysqli_real_escape_string($link, $value);
    }

    echo DataTable::process_table_content($aColumns, $sIndexColumn, $sTable, $where, $sLinkColumn, $columnsName);
} catch (Zend_Db_Exception $ex) {
    log::DB_hata_kaydi_ekle(__FILE__, $ex);
}

