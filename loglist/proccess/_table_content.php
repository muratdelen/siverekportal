<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . '/lib/functions.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
require_once PROJECT_DIR . '/lib/DataTable.php';
if (in_array(YT_QUERY, $sayfaIslemleriId)) {
//    'tableHeaders' => array('Kullanıcı Adı', 'Adı', 'Soyadı', 'Grubu', 'İşlem Yetkisi', 'Başlık', 'Açıklama', 'Sayfa Url', 'Tablodaki İşlem', 'Table Adı', 'Table Id', 'Yeni Veriler', 'Eski Veriler', 'ip', 'Zaman', 'Aktif mi?'),

    $aColumns = array('yt_kullanici.kullanici_adi', 'yt_kullanici.adi', 'yt_kullanici.soyadi', 'yt_grup.adi AS grup_adi', 'yt_sayfa_islemleri.adi AS islem_adi',  'log_kullanici_sayfa_islemleri_bilgisi.baslik', 'log_kullanici_sayfa_islemleri_bilgisi.aciklama','log_kullanici_sayfa_islemleri_bilgisi.sayfa_url','(SELECT adi FROM yt_sayfa_islemleri WHERE id = log_kullanici_sayfa_islemleri.tabloda_yapilan_islem ) AS tabloda_yapilan_islem', 'log_kullanici_sayfa_islemleri.tablo_adi', 'log_kullanici_sayfa_islemleri.tablo_id', 'log_kullanici_sayfa_islemleri.yeni_veriler', 'log_kullanici_sayfa_islemleri.eski_veriler', 'log_kullanici_sayfa_islemleri_bilgisi.ip', "date_format(log_kullanici_sayfa_islemleri_bilgisi.zaman,'%d/%m/%Y %H:%i:%s') AS zaman", "(CASE log_kullanici_sayfa_islemleri.aktif_mi WHEN 1 THEN 'Akfif' ELSE 'Pasif' END) AS aktif_mi");
//$aColumns = array('kullanici_adi', 'adi', 'soyadi', 'grubu', 'islem_yetkisi', 'baslik', 'aciklama', 'sayfa_url', 'tabloda_yapilan_islem', 'tablo_adi', 'tablo_id', 'yeni_veriler', 'eski_veriler', 'ip', 'zaman', 'aktif_mi');

    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "log_kullanici_sayfa_islemleri.id";

    /* DB table to use */
    $sTable = "log_kullanici_sayfa_islemleri "
            . "LEFT JOIN log_kullanici_sayfa_islemleri_bilgisi ON log_kullanici_sayfa_islemleri_bilgisi.id = log_kullanici_sayfa_islemleri.log_kullanici_sayfa_islemleri_bilgisi_id "
            . "LEFT JOIN yt_kullanici ON yt_kullanici.id = log_kullanici_sayfa_islemleri_bilgisi.yt_kullanici_id "
            . "LEFT JOIN  yt_sayfa_islemleri ON yt_sayfa_islemleri.id = log_kullanici_sayfa_islemleri_bilgisi.yt_sayfa_islemleri "
            . "LEFT JOIN yt_grup ON yt_grup.id = log_kullanici_sayfa_islemleri_bilgisi.yt_grup_id	 ";
    $where = "";
    $columnsName = array('yt_kullanici.kullanici_adi', 'yt_kullanici.adi', 'yt_kullanici.soyadi', 'yt_grup.adi', 'yt_sayfa_islemleri.adi', 'log_kullanici_sayfa_islemleri_bilgisi.sayfa_url', 'log_kullanici_sayfa_islemleri.tablo_adi', 'log_kullanici_sayfa_islemleri.tablo_id', 'log_kullanici_sayfa_islemleri.yeni_veriler', 'log_kullanici_sayfa_islemleri.eski_veriler', 'log_kullanici_sayfa_islemleri_bilgisi.ip', "log_kullanici_sayfa_islemleri_bilgisi.zaman", 'log_kullanici_sayfa_islemleri.aktif_mi');

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