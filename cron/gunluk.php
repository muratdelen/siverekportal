<?php

require_once 'cron_config.php';
try {
    $GLOBALS['db']->fetchAll("SELECT kalan_izin_ayarla_tum()");
} catch (Zend_Db_Exception $ex) {
//        log::DB_hata_kaydi_ekle(__FILE__, $ex);
}

//try {
//    $personeller = $GLOBALS['db']->fetchAll("SELECT * FROM sks_sozlesmeli_personel WHERE aktif_mi");
//   $GLOBALS['db']->fetchAll("UPADATE sks_sozlesmeli_personel aktif_mi = '1' WHERE aktif_mi");
//} catch (Zend_Db_Exception $ex) {
////        log::DB_hata_kaydi_ekle(__FILE__, $ex);
//}
//foreach ($personeller as $key => $personel) {    
//   $GLOBALS['db']->fetchAll("UPADATE sks_sozlesmeli_personel aktif_mi = '1' WHERE yt_kullanici_id = ? ");
//}
echo 'CALISTI.';
