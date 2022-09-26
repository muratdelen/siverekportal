<?php

/*
 * hata kaydını bu fonksiyonu kullanarak yapılabilir.
 * bazı hatalar otomatik yapılırken
 * veritabanı hatası kontrolü aşağıdaki gibi yapılabilir.
 * Standart Error"
 */

function on_error($hata_tipi, $hata_aciklamasi, $hata_yapilan_sayfa_url, $hata_yapilan_sayfa_satiri) {
    global $kullaniciId, $kullaniciAdi, $Ip, $kullaniciAdi;
    $createLogSQL = "INSERT INTO log_hatalar  VALUES ( ?, ? , ? , ? , ? , ? , ? , ? , ?, ? ,?)";
    $logInfo = array(NULL, $hata_tipi, $kullaniciId,$kullaniciAdi, "Standart Error", $hata_aciklamasi, $hata_yapilan_sayfa_satiri, $hata_yapilan_sayfa_url, $Ip, date('Y-m-d H:i:s'), 1);
          try {
        $GLOBALS['db']->query($createLogSQL, $logInfo);
    } catch (Zend_Db_Exception $ex) {
//        echo '<pre>';
//        var_dump($ex); 
//         echo '</pre>';die();
        return array('error' => $ex->getCode());
    }
}

set_error_handler("on_error");



/*
 * bu sınıf kullanılarak loglama yapılmaktadır.
 * bu sınıfın fonksiyonlarını kullanarak sayfa erişimlerini ve sayfa içindeki işlemlerle ilgili kayıt yapılmaktadır.
 * 
 */

/**
 * Description of log
 *
 * @author Bilgi
 */
class log {
    /*
     * bu fonksiyon kullanarak hata kayıtları eklenir.
     */

    static function hata_kaydi_ekle($hata_basligi, $hata_tipi, $hata_aciklamasi, $sayfa_url, $hata_yapilan_sayfa_satiri) {
        global $kullaniciId, $Ip;
        $createLogSQL = "INSERT INTO log_hatalar  VALUES ( ? , ? , ? , ? , ? , ? , ?, ? ,?, ?)";
        $logInfo = array(NULL, $hata_tipi, $kullaniciId, $hata_basligi, $hata_aciklamasi, $hata_yapilan_sayfa_satiri, $sayfa_url, $Ip, date('Y-m-d H:i:s'), 1);
        try {
            $GLOBALS['db']->query($createLogSQL, $logInfo);
        } catch (Zend_Db_Exception $ex) {
//            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            echo $ex->message();
        }
    }

    /*
     * catch yapıldığında veritabanı hatasını kaydeder.
     * 
     *  catch (Zend_Db_Exception $ex) {
      log::DB_hata_kaydi_ekle(__FILE__, $ex);
      }
     */

    static function DB_hata_kaydi_ekle($sayfa_url, $hata_Zend_Db_Exception) {
        foreach ($hata_Zend_Db_Exception->getTrace() as $value) {
            if (in_array($sayfa_url, $value)) {
                $satir_numarası = $value['line'];
            }
        }
        log::hata_kaydi_ekle("Veritabanı Hatası", $hata_Zend_Db_Exception->getCode(), $hata_Zend_Db_Exception->getMessage(), $sayfa_url, $satir_numarası);
    }

    /*
     * bu fonksiyon kullanarak Post loglama istenilen zamanda kaydedilir.
     * $islemBasligi = "";
     * $islemAciklamasi = "";
     * $sayfadaYapilanIslemId = "";
     */

    /**
     * 
     * @global type $kullaniciId
     * @global type $pageUrl
     * @global type $islemLogId
     * @global type $Ip
     * @global type $girisYapanKullaniciGrupId
     * @param type $islem_basligi
     * @param type $islem_aciklamasi
     * @param type $sayfada_yapilan_islem_id
     */
    static function islem_aciklamasi_kaydi($islem_basligi, $islem_aciklamasi, $sayfada_yapilan_islem_id) {
        global $kullaniciId, $pageUrl, $islemLogId, $Ip, $girisYapanKullaniciGrupId;
        $createLogSQL = "INSERT INTO log_kullanici_sayfa_islemleri_bilgisi  VALUES ( ? , ? , ? , ? , ? , ?, ? ,?, ?, ? )";
        $logInfo = array(NULL, $kullaniciId, $girisYapanKullaniciGrupId, $sayfada_yapilan_islem_id, $islem_basligi, $islem_aciklamasi, $pageUrl, $Ip, date('Y-m-d H:i:s'), 1);
        try {
            $GLOBALS['db']->query($createLogSQL, $logInfo);
            $islemLogId = $GLOBALS['db']->lastInsertId();
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }

    /*
     * bu fonksiyon kullanarak Post loglama istenilen zamanda kaydedilir.
     * $islemBasligi = "";
     * $islemAciklamasi = "";
     * $sayfadaYapilanIslemId = "";
     */

    static function islem_kaydi($islemler) {
        global $islemLogId;
        $createLogSQL = "INSERT INTO log_kullanici_sayfa_islemleri  VALUES (  ? , ? , ? , ? , ? , ? , ?, ? )";
        $logInfo = array(NULL, $islemLogId, $islemler["yapilan_islem"], $islemler["tablo_adi"], $islemler["tablo_id"], $islemler["yeni_veriler"], $islemler["eski_veriler"], 1);
        try {
            $GLOBALS['db']->query($createLogSQL, $logInfo);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }

    /*
     * bu fonksiyon kullanarak Erişim loglama istenilen zamanda kaydedilir.
     */

    static function erisim_kaydi($erisilen_sayfa_url = "", $sayfada_yapilan_id = 1) {
        global $kullaniciId, $pageUrl, $Ip, $girisYapanKullaniciGrupId;
        if($erisilen_sayfa_url == ""){
            $erisilen_sayfa_url = $pageUrl;
        }
        $createLogSQL = "INSERT INTO log_kullanici_sayfa_erisimleri VALUES ( ? , ? , ? , ?, ? , ? , ?, ?)";
        $logInfo = array(NULL, $kullaniciId, $sayfada_yapilan_id, $erisilen_sayfa_url , $girisYapanKullaniciGrupId, $Ip, date('Y-m-d H:i:s'), 1);
        try {
            $GLOBALS['db']->query($createLogSQL, $logInfo);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }

    static function post_yapildiginda_erisim_kaydi($erisilen_sayfa_url = "", $sayfada_yapilan_id = 1) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            log::erisim_kaydi($erisilen_sayfa_url, $sayfada_yapilan_id);
        }
    }

    static function get_yapildiginda_erisim_kaydi($erisilen_sayfa_url = "", $sayfada_yapilan_id = 1) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {// SAYFALAR ERİŞİM
            log::erisim_kaydi($erisilen_sayfa_url, $sayfada_yapilan_id);
        }
    }

    static function post_yapildiginda_islem_kaydi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            islem_kaydi();
        }
    }

    static function get_yapildiginda_islem_kaydi() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {// SAYFALAR ERİŞİM
            log::islem_kaydi();
        }
    }

    /*
     * post logların kaydedilmesini kontrol eder
     */

    static function activate_post_log() {
        $_SESSION["post_log_active"] = TRUE;
    }

    /*
     * post loglarının kaydedilmesini engeller
     */

    static function deactivate_post_log() {
        unset($_SESSION["post_log_active"]);
    }

    //BURADA 
}
