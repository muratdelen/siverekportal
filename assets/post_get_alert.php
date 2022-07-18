<?php

/*
 * BURADA KULLANICIYA BİLDİRİM GÖSTERMEK İÇİN KULLANILIR.
 */

require_once '../lib/config.php';
require_once '../lib/functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    switch ($_POST["post_type"]) {
        case 0://yen bildirim sayısı hesapla
            echo '1';
            break;
        case 1://yen bildirim sayısı hesapla
            if ($girisYapanKullaniciGrupId == 1) {
                try {
                    $ItemsSQL = "SELECT  count(*) AS hata_sayisi FROM log_hatalar WHERE aktif_mi ORDER BY log_hatalar.id DESC LIMIT 100 ";
                    $st_uyari = $GLOBALS['db']->fetchAll($ItemsSQL);
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                }
                echo json_encode($st_uyari);
            }
            break;
        case 2://yeni bildirimleri al
            if ($girisYapanKullaniciGrupId == 1) {
                try {
                    $ItemsSQL = "SELECT id, kullanici_adi, baslik, aciklama, satir_sirasi, sayfa_url, ip, zaman, tipi
                    FROM log_hatalar WHERE aktif_mi ORDER BY log_hatalar.id DESC LIMIT 100 ";
                    $st_uyari = $GLOBALS['db']->fetchAll($ItemsSQL);
//                    foreach ($st_uyari as $value) {
//                        $value->aciklama = mdecrypt($value->aciklama, $kullaniciAdi);
//                    }
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                }
                echo json_encode($st_uyari);
            }
            break;
        case 3://yen bildirim sayısı hesapla
            try {
                $ItemsSQL = "SELECT count(*) AS uyari_sayisi FROM st_uyari WHERE aktif_mi AND yeni_mi AND kullanici_adi = ? ORDER BY st_uyari.id DESC LIMIT 1000;";
                $st_uyari = $GLOBALS['db']->fetchAll($ItemsSQL, $kullaniciAdi);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            echo json_encode($st_uyari);
            break;
        case 4://yeni bildirimleri al
            try {
//                $ItemsSQL = "SELECT st_uyari.id,st_uyari.yt_kullanici_id,st_uyari.baslik,st_uyari.aciklama,st_uyari.link,st_uyari.tipi,DATE_FORMAT(st_uyari.zaman,'%d/%m/%Y %H:%i:%s') AS zaman,st_uyari.yeni_mi,st_uyari.aktif_mi FROM st_uyari WHERE aktif_mi AND yeni_mi AND kullanici_adi = ? ORDER BY UNIX_TIMESTAMP(zaman) DESC LIMIT 100;";
                $ItemsSQL = "SELECT st_uyari.id,st_uyari.yt_kullanici_id,st_uyari.baslik,st_uyari.aciklama,st_uyari.link,st_uyari.tipi,DATE_FORMAT(st_uyari.zaman,'%d/%m/%Y %H:%i:%s') AS zaman,st_uyari.yeni_mi,st_uyari.aktif_mi FROM st_uyari WHERE aktif_mi AND yeni_mi AND kullanici_adi = ? ORDER BY st_uyari.id DESC LIMIT 100;";
                $st_uyari = $GLOBALS['db']->fetchAll($ItemsSQL, $kullaniciAdi);

                foreach ($st_uyari as $value) {
                    $value->aciklama = mdecrypt($value->aciklama, $kullaniciAdi);
                }
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            echo json_encode($st_uyari);
            break;
        case 5://Tüm bildirimleri sil
            $ItemsSQL = "UPDATE st_uyari SET yeni_mi = FALSE WHERE yeni_mi AND kullanici_adi = ? ";
            try {
                $st_uyari = $GLOBALS['db']->fetchAll($ItemsSQL, $kullaniciAdi);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            break;
        default:
            break;
    }
}
