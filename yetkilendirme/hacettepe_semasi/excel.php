
<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {

    if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
        $ItemsSQL = "SELECT
                            yt_kullanici.kullanici_adi,
                            yt_kullanici.adi,
                            yt_kullanici.soyadi,
                            yt_kullanici.aciklamasi,
                            yt_grup.adi AS grup,
                            (CASE yt_kullanici.giris_tipi WHEN 1 THEN 'Siverek E-posta' WHEN 2 THEN 'Sistem' END) AS giris_tipi ,
                            (CASE yt_kullanici.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                        FROM yt_kullanici
                            LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id
                        WHERE yt_kullanici.kullanici_adi = ? OR yt_kullanici.adi LIKE ? OR yt_kullanici.soyadi LIKE ?  ";
    } else {
        $ItemsSQL = "SELECT
                            yt_kullanici.kullanici_adi,
                            yt_kullanici.adi,
                            yt_kullanici.soyadi,
                            yt_kullanici.aciklamasi,
                            yt_grup.adi AS grup,
                            (CASE yt_kullanici.giris_tipi WHEN 1 THEN 'Siverek E-posta' WHEN 2 THEN 'Sistem' END) AS giris_tipi ,
                            (CASE yt_kullanici.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                        FROM yt_kullanici
                            LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id
                        WHERE NOT(yt_kullanici.yt_grup_id=1 OR yt_kullanici.yt_grup_id=2) AND (yt_kullanici.kullanici_adi = ? OR yt_kullanici.adi LIKE ? OR yt_kullanici.soyadi LIKE ? ) ";
    }
    $kullanici_id = mdecrypt($_POST['kullanici_adi'], $_SESSION['key']);
    try {
        $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, array($kullanici_id, "%" . $_POST['kisi_adi_soyadi'] . "%", "%" . $_POST['kisi_adi_soyadi'] . "%"));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);

    $listItemsTitle = array(
        'kullanici_adi' => 'Kullanıcı Adı',
        'adi' => 'Adı',
        'soyadi' => 'Soyadı',
        'aciklamasi' => 'Ünvanı',
        'grup' => 'Grubu',
        'giris_tipi' => 'Giriş Türü',
        'aktif_mi' => 'Aktif Mi?');

    $sender = new myexcel("Kullanıcılar_" . (date('d-m-Y H:i:s')));
    $sender->start();
    $sender->printTitle = "Kullanıcı Tanımlama Sorgulama Ekranı";
    $sender->printDescription = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
    $sender->FromArrayToExcel($listItems, $listItemsTitle);
}
?>
 
