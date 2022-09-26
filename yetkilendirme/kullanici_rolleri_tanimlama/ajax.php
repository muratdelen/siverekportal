<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';
date_default_timezone_set('Europe/Istanbul');

//var_dump($_POST);
//die();
$validator = new InputFilterClass();

$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

switch ($_POST["post_type"]) {
    case 0://MENU listeleme
        if (in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
            $secilen_kullanici_id = mdecrypt($_POST['secilen_kullanici'], $_SESSION['key']);
            $SelectedRoles = array();
            $ItemsSQL = "SELECT yt_kullanici_rolleri.yt_rol_id FROM yt_kullanici_rolleri WHERE yt_kullanici_rolleri.aktif_mi AND yt_kullanici_rolleri.yt_kullanici_id = ? ";
            try {
                $roles = $GLOBALS['db']->fetchAll($ItemsSQL, $secilen_kullanici_id);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            foreach ($roles as $value) {
                array_push($SelectedRoles, $value->yt_rol_id);
            }
            $ItemsSQL = "SELECT yt_rol.id, yt_rol.adi, yt_rol.aciklama FROM yt_rol WHERE aktif_mi";
            try {
                $RolItems = $GLOBALS['db']->fetchAll($ItemsSQL);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            $rol_html = "";
            foreach ($RolItems as $RolItem) {
                if (in_array($RolItem->id, $SelectedRoles)) {
                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->aciklama . '" selected >' . $RolItem->adi . '</option>';
                } else {
                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->aciklama . '">' . $RolItem->adi . '</option>';
                }
            }
            echo json_encode($rol_html);
        }
        break;
    case 1://EKLENECEKLER
        if (in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
            $secilen_kullanici = mdecrypt($_POST['secilen_kullanici'], $_SESSION['key']);
            $secilen_menu = mdecrypt($_POST['secilen_menu'], $_SESSION['key']);
            $secilenIslemler = array();
            $ItemsSQL = "SELECT yt_kullanici_sayfa_yetkileri.yt_sayfa_islemleri_id FROM yt_kullanici_sayfa_yetkileri
                        WHERE aktif_mi AND yt_kullanici_sayfa_yetkileri.eklensin_mi AND yt_kullanici_sayfa_yetkileri.yt_kullanici_id = ? AND yt_kullanici_sayfa_yetkileri.yt_menu_id = ?";
            try {
                $Islemler = $GLOBALS['db']->fetchAll($ItemsSQL, array($secilen_kullanici, $secilen_menu));
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            foreach ($Islemler as $value) {
                array_push($secilenIslemler, $value->yt_sayfa_islemleri_id);
            }
            $ItemsSQL = "SELECT yt_sayfa_islemleri.id, yt_sayfa_islemleri.adi, yt_sayfa_islemleri.aciklama FROM yt_sayfa_islemleri WHERE yt_sayfa_islemleri.aktif_mi AND (ISNULL(yt_sayfa_islemleri.yt_menu_id) OR  yt_sayfa_islemleri.yt_menu_id = ?)";
            try {
                $IslemItems = $GLOBALS['db']->fetchAll($ItemsSQL,$secilen_menu);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            $rol_html = "";
            foreach ($IslemItems as $RolItem) {
                if (in_array($RolItem->id, $secilenIslemler)) {
                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->aciklama . '" selected >' . $RolItem->adi . '</option>';
                } else {
                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->aciklama . '">' . $RolItem->adi . '</option>';
                }
            }
            echo json_encode($rol_html);
        }
        break;
    case 2://ÇIKARTILACAKLAR
         if (in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
            $secilen_kullanici = mdecrypt($_POST['secilen_kullanici'], $_SESSION['key']);
            $secilen_menu = mdecrypt($_POST['secilen_menu'], $_SESSION['key']);
            $secilenIslemler = array();
            $ItemsSQL = "SELECT yt_kullanici_sayfa_yetkileri.yt_sayfa_islemleri_id FROM yt_kullanici_sayfa_yetkileri
                        WHERE aktif_mi AND NOT yt_kullanici_sayfa_yetkileri.eklensin_mi AND yt_kullanici_sayfa_yetkileri.yt_kullanici_id = ? AND yt_kullanici_sayfa_yetkileri.yt_menu_id = ?";
            try {
                $Islemler = $GLOBALS['db']->fetchAll($ItemsSQL, array($secilen_kullanici, $secilen_menu));
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            foreach ($Islemler as $value) {
                array_push($secilenIslemler, $value->yt_sayfa_islemleri_id);
            }
            $ItemsSQL = "SELECT yt_sayfa_islemleri.id, yt_sayfa_islemleri.adi, yt_sayfa_islemleri.aciklama FROM yt_sayfa_islemleri WHERE yt_sayfa_islemleri.aktif_mi AND (ISNULL(yt_sayfa_islemleri.yt_menu_id) OR  yt_sayfa_islemleri.yt_menu_id = ?)";
            try {
                $IslemItems = $GLOBALS['db']->fetchAll($ItemsSQL,$secilen_menu);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            $rol_html = "";
            foreach ($IslemItems as $RolItem) {
                if (in_array($RolItem->id, $secilenIslemler)) {
                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->aciklama . '" selected >' . $RolItem->adi . '</option>';
                } else {
                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->aciklama . '">' . $RolItem->adi . '</option>';
                }
            }
            echo json_encode($rol_html);
        }
        break;
}
?>

