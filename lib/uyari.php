<?php

/*
 * burada kişi veya gruplara uyarı gönderilmektedir
 */


/*
 * bu fonksiyon kullanılarak kullanıcılara uyarı verilebilir.
 * 1 DAKİKA İÇERİSİNDE İLGİLİ KULLANICIYA GÖSTERİLİR.
 */

//
//function adminLTE_redirect($uyari_mesaji, $uyari_tipi, $yonlendirilecek_url = "") {
//    $_SESSION['adminLTE_alert'] = array($uyari_mesaji, $uyari_tipi);
//    if ($yonlendirilecek_url == "") {
//        header("location:" . $_SERVER['PHP_SELF']);
//    } else {
//        header("location:" . $yonlendirilecek_url);
//    }
//}

function kisiye_uyari_gonder($uyari_gonderilecek_kullanici_adi, $uyari_basligi, $uyari_aciklamasi, $uyari_tiklandiginda_url = "", $kullaniciya_sms_gonderilsin_mi = FALSE) {
    $createLogSQL = "INSERT INTO st_uyari  VALUES ( ? , ? , ? , ?, ? , ? , ?, ? )";
    $logInfo = array(NULL, $uyari_gonderilecek_kullanici_adi, $uyari_basligi, $uyari_aciklamasi, $uyari_tiklandiginda_url, date('Y-m-d H:i:s'), 1, 1);
    try {
        $GLOBALS['db']->fetchAll($createLogSQL, $logInfo);
        if ($kullaniciya_sms_gonderilsin_mi) {
            require_once 'class.mail.php';
            $mail = new MAIL();
            $mesaj = "";
            $mesaj .= '<br>' . $uyari_basligi . '<br>';
            $mesaj .= '<br>' . $uyari_aciklamasi;
            if ($uyari_tiklandiginda_url != "") {
                $mesaj .= '<br><a href="' . $uyari_tiklandiginda_url . '">uygulamaya erişim linki</a>';
            }
            $mesaj .= '<br><br>';
            $mesaj .= '<br><br>';
            $mesaj .= '<br><a href="' . WEBSITE_URL . '">Bu mesaj sistem tarafından gönderilmiştir.</a><br>';
            $mail->sendMail($uyari_gonderilecek_kullanici_adi . "@Siverek.edu.tr", WEBSITE_URL . ' sitesinden bildiriminiz var', $mesaj);
        }
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
}

function gruba_uyari_gonder($uyari_gonderilecek_grup_adi, $uyari_basligi, $uyari_aciklamasi, $uyari_tiklandiginda_url = "", $kullaniciya_sms_gonderilsin_mi = FALSE) {

    $createSQL = "SELECT DISTINCT users.username FROM groups
                       INNER JOIN users ON users.groups_id = groups.id
                        LEFT JOIN authority_alert ON users.username = authority_alert.username
                       WHERE users.is_active AND authority_alert.is_active AND authority_alert.is_active_alert AND groups.title =  ? ";
    try {
        $select_users_for_alert = $GLOBALS['db']->fetchAll($createSQL, $uyari_gonderilecek_grup_adi);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    foreach ($select_users_for_alert as $uyari_gonderilecek_kullanici_adi) {
        $createLogSQL = "INSERT INTO alert  VALUES ( ? , ? , ? , ?, ? , ? , ?, ? )";
        $logInfo = array(NULL, $uyari_gonderilecek_kullanici_adi->username, $uyari_basligi, $uyari_aciklamasi, $uyari_tiklandiginda_url, date('Y-m-d H:i:s'), 1, 1);
        try {
            $GLOBALS['db']->fetchAll($createLogSQL, $logInfo);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }
    if ($kullaniciya_sms_gonderilsin_mi) {
        $createSQL = "SELECT DISTINCT users.username FROM groups
                       INNER JOIN users ON users.groups_id = groups.id
                        LEFT JOIN authority_alert ON users.username = authority_alert.username
                       WHERE users.is_active AND authority_alert.is_active AND authority_alert.is_active_email AND groups.title =  ? ";
        try {
            $select_users_for_email = $GLOBALS['db']->fetchAll($createSQL, $uyari_gonderilecek_grup_adi);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        foreach ($select_users_for_email as $uyari_gonderilecek_kullanici_adi) {
            if ($kullaniciya_sms_gonderilsin_mi) {
                require_once 'class.mail.php';
                $mail = new MAIL();
                $mesaj = "";
                $mesaj .= '<br>' . $uyari_basligi . '<br>';
                $mesaj .= '<br>' . $uyari_aciklamasi;
                if ($uyari_tiklandiginda_url != "") {
                    $mesaj .= '<br><a href="' . $uyari_tiklandiginda_url . '">uygulamaya erişim linki</a>';
                }
                $mesaj .= '<br><br>';
                $mesaj .= '<br><br>';
                $mesaj .= '<br><a href="' . WEBSITE_URL . '">Bu mesaj Gelir Takip Sistemi Uygulaması tarafından gönderilmiştir.</a><br>';
                $mail->sendMail($uyari_gonderilecek_kullanici_adi->username . "@Siverek.edu.tr", WEBSITE_URL . ' sitesinden bildiriminiz var', $mesaj);
            }
        }
    }

    function role_uyari_gonder($uyari_gonderilecek_rol, $uyari_basligi, $uyari_aciklamasi, $uyari_tiklandiginda_url = "", $kullaniciya_sms_gonderilsin_mi = FALSE) {

        $createSQL = "SELECT DISTINCT users.username FROM groups
                       INNER JOIN users ON users.groups_id = groups.id
                        LEFT JOIN authority_alert ON users.username = authority_alert.username
                       WHERE users.is_active AND authority_alert.is_active AND authority_alert.is_active_alert AND groups.title =  ? ";
        try {
            $select_users_for_alert = $GLOBALS['db']->fetchAll($createSQL, $uyari_gonderilecek_rol);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        foreach ($select_users_for_alert as $uyari_gonderilecek_kullanici_adi) {
            $createLogSQL = "INSERT INTO alert  VALUES ( ? , ? , ? , ?, ? , ? , ?, ? )";
            $logInfo = array(NULL, $uyari_gonderilecek_kullanici_adi->username, $uyari_basligi, $uyari_aciklamasi, $uyari_tiklandiginda_url, date('Y-m-d H:i:s'), 1, 1);
            try {
                $GLOBALS['db']->fetchAll($createLogSQL, $logInfo);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
        }
        if ($kullaniciya_sms_gonderilsin_mi) {
            $createSQL = "SELECT DISTINCT users.username FROM groups
                       INNER JOIN users ON users.groups_id = groups.id
                        LEFT JOIN authority_alert ON users.username = authority_alert.username
                       WHERE users.is_active AND authority_alert.is_active AND authority_alert.is_active_email AND groups.title =  ? ";
            try {
                $select_users_for_email = $GLOBALS['db']->fetchAll($createSQL, $uyari_gonderilecek_rol);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            foreach ($select_users_for_email as $uyari_gonderilecek_kullanici_adi) {
                if ($kullaniciya_sms_gonderilsin_mi) {
                    require_once 'class.mail.php';
                    $mail = new MAIL();
                    $mesaj = "";
                    $mesaj .= '<br>' . $uyari_basligi . '<br>';
                    $mesaj .= '<br>' . $uyari_aciklamasi;
                    if ($uyari_tiklandiginda_url != "") {
                        $mesaj .= '<br><a href="' . $uyari_tiklandiginda_url . '">uygulamaya erişim linki</a>';
                    }
                    $mesaj .= '<br><br>';
                    $mesaj .= '<br><br>';
                    $mesaj .= '<br><a href="' . WEBSITE_URL . '">Bu mesaj Gelir Takip Sistemi Uygulaması tarafından gönderilmiştir.</a><br>';
                    $mail->sendMail($uyari_gonderilecek_kullanici_adi->username . "@Siverek.edu.tr", WEBSITE_URL . ' sitesinden bildiriminiz var', $mesaj);
                }
            }
        }
    }

}
