<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../lib/config.php';
require_once '../lib/functions.php';
if ($girisYapanKullaniciGrupId == 1) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        switch ($_POST["post_type"]) {
            case 1://ERİŞİLEBİLEN SAYFALAR
                $ItemsSQL = "SELECT * FROM yt_erisilebilecek_sayfalar WHERE yt_erisilebilecek_sayfalar.yt_menu_id = ? ";
                try {
                    $_POST['secilen_menu_id'] = mdecrypt($_POST['secilen_menu_id'], $_SESSION['key']);
                    $erisilebilen_sayfalar = $GLOBALS['db']->fetchAll($ItemsSQL, $_POST["secilen_menu_id"]);
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                }
                echo json_encode($erisilebilen_sayfalar);
                break;
            case 2://EKLEME - ERİŞEBİLEN SAYFALAR
                $result = 1;
                if ($_POST['secilen_menu_id'] != "") {
                    $ItemsSQL = "INSERT INTO yt_erisilebilecek_sayfalar VALUES( ?, ? , ? , ? , ? ) ";
                    try {
                        $_POST['secilen_menu_id'] = mdecrypt($_POST['secilen_menu_id'], $_SESSION['key']);
                        $GLOBALS['db']->fetchAll($ItemsSQL, array(NULL, $_POST["sayfa_adi"], $_POST["secilen_menu_id"], $_POST["sayfa_url"], 1));
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        $result = -1;
                    }
                } else {
                    $result = -1;
                }
                echo $result;
                break;
            case 3://SİLME - ERİŞEBİLEN SAYFALAR
                $result = 1;
                $ItemsSQL = "DELETE FROM yt_erisilebilecek_sayfalar WHERE id = ? ";
                try {
                    $_POST['secilen_sayfa'] = mdecrypt($_POST['secilen_sayfa'], $_SESSION['key']);
                    $GLOBALS['db']->fetchAll($ItemsSQL, $_POST["secilen_sayfa"]);
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    $result = -1;
                }
                echo $result;
                break;

            default:
                break;
        }
    }
}
