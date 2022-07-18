<?php

/* 
 * yetkilerin erişim kontrolü yapar
 */


//ERİŞİM KONTROLÜ YAPAR.
//EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


//    if (isset($_POST["page_proccess_id"])) {
//        $sayfadaYapilanIslemId = $_POST["page_proccess_id"];
//    }
//    if (isset($_POST["page_post_title"])) {
//        $islemBasligi = $_POST["page_post_title"];
//    }
//    if (isset($_POST["page_post_description"])) {
//        $islemAciklamasi = $_POST["page_post_description"];
//    }
//    if (isset($_POST["page_post_histroy"])) {
//        $islemGecmisi = $_POST["page_post_histroy"];
//    }

    if (in_array($pageUrl, $nologinPostPage)) {//Giriş yapılmadan tün herkesin erişebileceği post sayfalar 
//        if (isset($_SESSION["post_log_active"])) {//post sayfası ise ne post edildiği bilgisi kaydediliyor.
//            $createLogSQL = "SELECT insert_post_page_nologin_log( ? , ? , ? , ? , ? , ?, ? ,? )";
//            $logInfo = array($username, $pageUrl, $islemBasligi, $islemAciklamasi, $islemGecmisi, $sayfadaYapilanIslemId, $Ip, $girisYapanKullaniciGrupId);
//            try {
//                $GLOBALS['db']->fetchAll($createLogSQL, $logInfo);
//            } catch (Zend_Db_Exception $ex) {
//                hata_kaydi_ekle("veritabanı hatası", $ex->getCode(), $ex->getMessage(), $ex->getTraceAsString(), __LINE__);
//            }
//        }
    } else if (isset($_SESSION["username"])) {//giriş yapmış kullanıcının erişebileceği sayfalar
        $is_post_access = FALSE;
        if (in_array($pageUrl, $loginPostPage)) {
            $is_post_access = TRUE;
        } else {
            try {
                $ItemsSQL = 'CALL select_proccess_id_in_post_page_by_url(? , ? ) ';
                $SQLPageProccess = $GLOBALS['db']->fetchAll($ItemsSQL, array($_SESSION["username"], $pageUrl));
                if (isset($SQLPageProccess)) {
                    foreach ($SQLPageProccess as $value) {
                        array_push($sayfaIslemleriId, $value->page_proccess_id);
                    }
                }
                if ($sayfadaYapilanIslemId != -2) {//eğer procces id gönderilmiş ise bu işlemi yapmaya yetkili olup olmadığı kontrol edilmektedir.
                    if (in_array($sayfadaYapilanIslemId, $sayfaIslemleriId)) {
                        $is_post_access = TRUE;
                    }
                } else {//gönderilmemiş ise sadece bu sayfaya post yetkisi olup olmadığı kontrol edilmektedir.
                    if (count($sayfaIslemleriId) != 0) {
                        $is_post_access = TRUE;
                    }
                }
            }  catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
        }
        if (!$is_post_access) {//Engellendi //kişinin erişim yoksa
            $_SESSION["post_log_active"] = TRUE;
            header("location:" . BASE_URL . "accessErrors/forbid.php");
            exit();
        }
    } else {// sayfada giriş yapmamış ve giriş yapılması gerek bir post sayfası
        $_SESSION["post_log_active"] = TRUE;
        header("location:" . BASE_URL . "accessErrors/forbid.php");
        exit();
    }
} else {//SAYFA ERİŞİM
    if (in_array($pageUrl, $girissizErisilecekSayfalar)) {//Giriş yapılmadan tün herkesin erişebileceği sayfalar 
//            return TRUE;
    } else if (isset($_SESSION["username"])) {//giriş yapmış kullanıcının erişebileceği sayfalar
        if (in_array($pageUrl, $ErisilecekSayfalar)) {
            
        } else {
            try {
                $ItemsSQL = 'CALL select_proccess_id_in_page_by_url(? , ? ) ';
                $SQLPageProccess = $GLOBALS['db']->fetchAll($ItemsSQL, array($_SESSION["username"], $pageUrl));

                if (isset($SQLPageProccess)) {
                    foreach ($SQLPageProccess as $value) {
                        array_push($sayfaIslemleriId, $value->page_proccess_id);
                    }
                }
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            if (count($sayfaIslemleriId) == 0) {//Engellendi
                $_SESSION["post_log_active"] = TRUE;
                header("location:" . BASE_URL . "accessErrors/forbid.php");
                exit();
            }
        }
    } else {
        $_SESSION["post_log_active"] = TRUE;
        header("location:" . BASE_URL . "accessErrors/forbid.php");
        exit();
    }
}