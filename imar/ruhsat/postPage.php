<?php

require_once '../../lib/config.php'; 
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';

$validator = new InputFilterClass();
$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

if (isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
    $rules = array(
        'ruhsat_no' => 'required',
        'adi_soyadi' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $data = array(
            "ruhsat_no" => tr_uppercase($_POST['ruhsat_no']),
            "adi_soyadi" => tr_uppercase($_POST['adi_soyadi']),
            "ruhsat_cinsi" => tr_uppercase($_POST['ruhsat_cinsi']),
            "ruhsat_verilis_amaci" => tr_uppercase($_POST['ruhsat_verilis_amaci']),
            "fenni_mesul" => tr_uppercase($_POST['fenni_mesul']),
            "ruhsat_tarihi" => (trim($_POST['ruhsat_tarihi']) == "" ? null : convertDateFormatBasicDefault($_POST['ruhsat_tarihi'])),
            "mahallesi" => tr_uppercase($_POST['mahallesi']),
            "bulten_no" => tr_uppercase($_POST['bulten_no']),
            "ada_parsel" => tr_uppercase($_POST['ada_parsel']),
            "yibf_no" => tr_uppercase($_POST['yibf_no']),
            "yapi_alani" => trim($_POST['yapi_alani']),
            "iskan_ruhsat_tarihi" => (trim($_POST['iskan_ruhsat_tarihi']) == "" ? null : convertDateFormatBasicDefault($_POST['iskan_ruhsat_tarihi'])),
            "iskan_verildi_mi" => trim($_POST['iskan_verildi_mi']),
            "iskan_no" => trim($_POST['iskan_no']),
            "iskan_bulten_no" => trim($_POST['iskan_bulten_no']),
            "aciklama" => trim($_POST['aciklama']),
            "aktif_mi" => trim($_POST['ruhsat_aktif_mi'])
        );
        $son_ruhsat_no = $_POST['son_ruhsat_no'] + 1;
        $son_iskan_no = $_POST['son_iskan_no'] + 1;

        try {
            log::islem_aciklamasi_kaydi("Ruhsat Bilgileri", "Ruhsat Bilgileri Ekleme", YT_INSERT);
            $eklen_id = $GLOBALS['db']->insert('s_ruhsat_bilgileri', $data, null);
            if (trim($_POST['iskan_verildi_mi']) == -1) {// Yeni Ruhsat Ekleniyor
                $GLOBALS['db']->fetchAll("UPDATE s_degiskenler SET deger = ? WHERE aktif_mi AND degisken = 'son_ruhsat_no' ", $son_ruhsat_no);
            } else {// Yeni İskan Ekleniyor.
                $GLOBALS['db']->fetchAll("UPDATE s_degiskenler SET deger = ? WHERE aktif_mi AND degisken = 'son_iskan_no' ", $son_iskan_no);
            }
            adminLTE_redirect(false, __("Ekleme Sonucu"), __("Ruhsat Bilgileri Eklendi"), "success", 1000000, BASE_URL . "imar/ruhsat/index.php?sorgula&ruhsat=" . mcrypt($eklen_id, $_SESSION['key']));
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, __("Ekleme Sonucu"), __("Ruhsat Bilgileri Eklenemedi!"), "danger", 1000000, BASE_URL . "imar/ruhsat/index.php");
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "imar/ruhsat/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $rules = array(
        'ruhsat_no' => 'required',
        'adi_soyadi' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $guncellenecek_id = mdecrypt($_POST['update'], $_SESSION['key']);

        $data = array(
            "ruhsat_no" => tr_uppercase($_POST['ruhsat_no']),
            "adi_soyadi" => tr_uppercase($_POST['adi_soyadi']),
            "ruhsat_cinsi" => tr_uppercase($_POST['ruhsat_cinsi']),
            "ruhsat_verilis_amaci" => tr_uppercase($_POST['ruhsat_verilis_amaci']),
            "fenni_mesul" => tr_uppercase($_POST['fenni_mesul']),
            "ruhsat_tarihi" => (trim($_POST['ruhsat_tarihi']) == "" ? null : convertDateFormatBasicDefault($_POST['ruhsat_tarihi'])),
            "mahallesi" => tr_uppercase($_POST['mahallesi']),
            "bulten_no" => tr_uppercase($_POST['bulten_no']),
            "ada_parsel" => tr_uppercase($_POST['ada_parsel']),
            "yibf_no" => tr_uppercase($_POST['yibf_no']),
            "yapi_alani" => trim($_POST['yapi_alani']),
            "kacak_islem_yapildi_mi" => trim($_POST['kacak_islem_yapildi_mi']),
            "kacak_islem_bilgisi" => trim($_POST['kacak_islem_bilgisi']),
            "iskan_ruhsat_tarihi" => (trim($_POST['iskan_ruhsat_tarihi']) == "" ? null : convertDateFormatBasicDefault($_POST['iskan_ruhsat_tarihi'])),
            "iskan_verildi_mi" => trim($_POST['iskan_verildi_mi']),
            "iskan_no" => trim($_POST['iskan_no']),
            "iskan_bulten_no" => trim($_POST['iskan_bulten_no']),
            "aciklama" => trim($_POST['aciklama']),
            "aktif_mi" => trim($_POST['ruhsat_aktif_mi'])
        );
        $where["id = ?"] = $guncellenecek_id;
        try {
            log::islem_aciklamasi_kaydi("Ruhsat Başvurusu", "Yeni Ruhsat Güncelleme", YT_INSERT);
            $GLOBALS['db']->update('s_ruhsat_bilgileri', $data, $where);
//            //İskan verilen ada parselin tüm insaat ruhsatlarının iskan bilgileri güncellenecek
//            if (trim($_POST['iskan_verildi_mi']) == 1) {
//                $data2 = array(
//                    "iskan_ruhsat_tarihi" => (trim($_POST['iskan_ruhsat_tarihi']) == "" ? null : convertDateFormatBasicDefault($_POST['iskan_ruhsat_tarihi'])),
//                    "iskan_verildi_mi" => trim($_POST['iskan_verildi_mi']),
//                    "iskan_no" => trim($_POST['iskan_no']),
//                    "iskan_bulten_no" => trim($_POST['iskan_bulten_no'])
//                );
//                $where2["ada_parsel = ?"] = tr_uppercase($_POST['ada_parsel']);
//                $GLOBALS['db']->update('s_ruhsat_bilgileri', $data2, $where2);
//            }
            adminLTE_redirect(true, "Ruhsat Başvurusu", "Ruhsat Güncelleme İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "imar/ruhsat/index.php?sorgula&ruhsat_id=" . urlencode($_POST['update']));
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Ruhsat Başvurusu", "Ruhsat güncellerken bir hata oluştu.", "danger", 1000000, BASE_URL . "imar/ruhsat/index.php?sorgula&ruhsat_id=" . urlencode($_POST['update']));
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "imar/ruhsat/index.php?sorgula&ruhsat=" . urlencode($_POST['update'])); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (in_array(YT_DELETE, $sayfaIslemleriId) && isset($_POST['remove'])) {//güncelleme işlemi
    $silinecek_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
        try {
            log::islem_aciklamasi_kaydi("Ruhsat Bilgileri", "Ruhsat Bilgisi Silme", YT_DELETE);
            $data = array('aktif_mi' => 0);
            $where["id = ?"] = $silinecek_id;
            try {
                $GLOBALS['db']->update('s_ruhsat_bilgileri', array('aktif_mi' => 0), $where);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            adminLTE_redirect(true, "Ruhsat Başvurusu", "Ruhsat Silme İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "imar/ruhsat/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Ruhsat Başvurusu", "Ruhsat silerken bir hata oluştu.", "danger", 1000000, BASE_URL . "imar/ruhsat/index.php");
        }
    } else {
        adminLTE_redirect(true, "Silme Yapamazsınız.", "Silmek İçin Yetkiniz Yoktur.", "danger", 1000000, BASE_URL . "imar/ruhsat/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız.", "danger", 1000000, BASE_URL . "imar/ruhsat/index.php");
}
?>

