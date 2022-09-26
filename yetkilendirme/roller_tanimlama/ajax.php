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
    case 0://ÖNCEDEN EKLENEN YETKİLER SEÇİLMEKTEDİR.
        if (in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
            $rol_html = "";

            $secilen_menu = mdecrypt($_POST['secilen_menu'], $_SESSION['key']);
            if ($_POST['secilen_rol'] == "" && $_POST['secilen_menu'] == "") {
                $ItemsSQL = "SELECT yt_sayfa_islemleri.id, yt_sayfa_islemleri.adi, yt_sayfa_islemleri.aciklama FROM yt_sayfa_islemleri WHERE yt_sayfa_islemleri.aktif_mi AND (ISNULL(yt_sayfa_islemleri.yt_menu_id) OR  yt_sayfa_islemleri.yt_menu_id = ?)";
            try {
                    $RolItems = $GLOBALS['db']->fetchAll($ItemsSQL,$secilen_menu);
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                }
                foreach ($RolItems as $RolItem) {
                    $id_sifreli = mcrypt($RolItem->id, $_SESSION['key']);
                    $rol_html .= '<option value="' . $id_sifreli . '" title="' . $RolItem->aciklama . '">' . $RolItem->adi . '</option>';
                }
            } else {
                $secilen_rol = mdecrypt($_POST['secilen_rol'], $_SESSION['key']);
                $secilen_menu = mdecrypt($_POST['secilen_menu'], $_SESSION['key']);
                $SelectedRoles = array();
                $ItemsSQL = "SELECT yt_rol_sayfa_yetkileri.yt_sayfa_islemleri_id FROM yt_rol_sayfa_yetkileri 
                        WHERE aktif_mi AND yt_rol_sayfa_yetkileri.yt_rol_id = ? AND yt_rol_sayfa_yetkileri.yt_menu_id = ?";
                try {
                    $roles = $GLOBALS['db']->fetchAll($ItemsSQL, array($secilen_rol, $secilen_menu));
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                }
                foreach ($roles as $value) {
                    array_push($SelectedRoles, $value->yt_sayfa_islemleri_id);
                }
               $ItemsSQL = "SELECT yt_sayfa_islemleri.id, yt_sayfa_islemleri.adi, yt_sayfa_islemleri.aciklama FROM yt_sayfa_islemleri WHERE yt_sayfa_islemleri.aktif_mi AND (ISNULL(yt_sayfa_islemleri.yt_menu_id) OR  yt_sayfa_islemleri.yt_menu_id = ?)";
            try {
                    $RolItems = $GLOBALS['db']->fetchAll($ItemsSQL,$secilen_menu);
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex); 
                }
                foreach ($RolItems as $RolItem) {
                    $id_sifreli = mcrypt($RolItem->id, $_SESSION['key']);
                    if (in_array($RolItem->id, $SelectedRoles)) {
                        $rol_html .= '<option value="' . $id_sifreli . '" title="' . $RolItem->aciklama . '" selected >' . $RolItem->adi . '</option>';
                    } else {
                        $rol_html .= '<option value="' . $id_sifreli . '" title="' . $RolItem->aciklama . '">' . $RolItem->adi . '</option>';
                    }
                }
            }
            echo json_encode($rol_html);
        }
        break;
    case 1://ÖNCEDEN EKLENEN ROLLER SEÇİLMEKTEDİR.
        if (in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
            $secilen_rol = mdecrypt($_POST['secilen_rol'], $_SESSION['key']);
            $secilen_menu = mdecrypt($_POST['secilen_menu'], $_SESSION['key']);
            $SelectedRoles = array();
            $ItemsSQL = "SELECT yt_rol_sayfa_yetkileri.yt_sayfa_islemleri_id FROM yt_rol_sayfa_yetkileri 
                        WHERE aktif_mi AND yt_rol_sayfa_yetkileri.yt_rol_id = ? AND yt_rol_sayfa_yetkileri.yt_menu_id = ?";
            try {
                $roles = $GLOBALS['db']->fetchAll($ItemsSQL, array($secilen_rol, $secilen_menu));
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            foreach ($roles as $value) {
                array_push($SelectedRoles, $value->yt_sayfa_islemleri_id);
            }
            $ItemsSQL = "SELECT yt_sayfa_islemleri.id, yt_sayfa_islemleri.adi, yt_sayfa_islemleri.aciklama FROM yt_sayfa_islemleri WHERE yt_sayfa_islemleri.aktif_mi AND (ISNULL(yt_sayfa_islemleri.yt_menu_id) OR  yt_sayfa_islemleri.yt_menu_id = ?)";
            try {
                $RolItems = $GLOBALS['db']->fetchAll($ItemsSQL,$secilen_menu);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            $rol_html = "";
            foreach ($RolItems as $RolItem) {
                $id_sifreli = mcrypt($RolItem->id, $_SESSION['key']);
                if (in_array($RolItem->id, $SelectedRoles)) {
                    $rol_html .= '<option value="' . $id_sifreli . '" title="' . $RolItem->aciklama . '" selected >' . $RolItem->adi . '</option>';
                } else {
                    $rol_html .= '<option value="' . $id_sifreli . '" title="' . $RolItem->aciklama . '">' . $RolItem->adi . '</option>';
                }
            }
            echo json_encode($rol_html);
        }
        break;
//    case 2://ÇIKARTILACAKLAR
//        if (in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
//            $SelectedRoles = array();
//            $ItemsSQL = "SELECT authority_user_roles.page_proccess_id FROM authority_user_roles WHERE is_active AND authority_user_roles.username = ? AND authority_user_roles.roles_id = ? AND is_add = 0";
//            try {
//                $roles = $GLOBALS['db']->fetchAll($ItemsSQL, array($_POST["username"], $_POST["user_role"]));
//            } catch (Zend_Db_Exception $ex) {
//                log::DB_hata_kaydi_ekle(__FILE__, $ex);
//            }
//            foreach ($roles as $value) {
//                array_push($SelectedRoles, $value->page_proccess_id);
//            }
//            $ItemsSQL = "SELECT page_proccess.id, page_proccess.title, page_proccess.description, page_proccess.html FROM page_proccess WHERE is_active ";
//            try {
//                $RolItems = $GLOBALS['db']->fetchAll($ItemsSQL);
//            } catch (Zend_Db_Exception $ex) {
//                log::DB_hata_kaydi_ekle(__FILE__, $ex);
//            }
//            $rol_html = "";
//            foreach ($RolItems as $RolItem) {
//                if (in_array($RolItem->id, $SelectedRoles)) {
//                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->description . '" selected >' . $RolItem->title . '</option>';
//                } else {
//                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->description . '">' . $RolItem->title . '</option>';
//                }
//            }
//            echo json_encode($rol_html);
//        }
//        break;
//    case 3://MENU listeleme
//        if (in_array(2, $sayfaIslemleriId)) {//kaydetme işlemi
//            $ItemsSQL = "SELECT user_roles.description FROM user_roles WHERE is_active AND user_roles.username = ?  LIMIT 1";
//            try {
//                $roles_description = $GLOBALS['db']->fetchRow($ItemsSQL, $_POST["username"]);
//                if (isset($roles_description->description)) {
//                    echo $roles_description->description;
//                }
//            } catch (Zend_Db_Exception $ex) {
//             loglog::DB_hata_kaydi_ekle(__FILE__, $ex);e("Veritabanı Hatası", $hata_Zend_Db_Exception->getCode(), $hata_Zend_Db_Exception->getMessage(), $sayfa_url, $satir_numarası); }
//        }
//        break;
    case 5:
        $secilen_rol = mdecrypt($_POST['secilen_rol'], $_SESSION['key']);
        $ItemsSQL = "SELECT yt_rol.adi, yt_rol.aciklama, yt_rol.aktif_mi FROM yt_rol WHERE id = ?";
        try {
            $roles = $GLOBALS['db']->fetchAll($ItemsSQL, $secilen_rol);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        echo json_encode($roles);
        break;
}
?>

