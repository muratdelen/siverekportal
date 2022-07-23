<?php

require_once '../lib/config.php';
require_once '../lib/functions.php';
require_once '../lib/input_filter.php';

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
            "iskan_verildi_mi" => trim($_POST['iskan_verildi_mi'])
        );
        try {
            log::islem_aciklamasi_kaydi("Ruhsat Bilgileri", "Ruhsat Bilgileri Ekleme", YT_INSERT);
            $GLOBALS['db']->insert('s_ruhsat_bilgileri', $data, null);
            adminLTE_redirect(false, __("Ekleme Sonucu"), __("Ruhsat Bilgileri Eklendi"), "success", 1000000, BASE_URL . "ruhsat/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, __("Ekleme Sonucu"), __("Ruhsat Bilgileri Eklenemedi!"), "danger", 1000000, BASE_URL . "ruhsat/index.php");
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "ruhsat/index.php"); //BURADA STANDART HATALAR VARDIR.
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
            "iskan_ruhsat_tarihi" => (trim($_POST['iskan_ruhsat_tarihi']) == "" ? null : convertDateFormatBasicDefault($_POST['iskan_ruhsat_tarihi'])),
            "iskan_verildi_mi" => trim($_POST['iskan_verildi_mi'])
        );
//        echo '<pre>';
//        var_dump($_POST,$data);
//        die();
        $where["id = ?"] = $guncellenecek_id;
        try {
            log::islem_aciklamasi_kaydi("Ruhsat Başvurusu", "Yeni Ruhsat Güncelleme", YT_INSERT);
            $GLOBALS['db']->update('s_ruhsat_bilgileri', $data, $where);
            adminLTE_redirect(true, "Ruhsat Başvurusu", "Ruhsat Güncelleme İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "ruhsat/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Ruhsat Başvurusu", "Ruhsat güncellerken bir hata oluştu.", "danger", 1000000, BASE_URL . "ruhsat/index.php");
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "ruhsat/index.php"); //BURADA STANDART HATALAR VARDIR.
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
//            $where["yt_kullanici_id = ?"] = $kullaniciId;
//            $GLOBALS['db']->update('sks_sozlesmeli_personel', array('kalan_izin_gun_sayisi' => $toplam_izin_sayisi), $where);
            adminLTE_redirect(true, "Ruhsat Başvurusu", "Ruhsat Silme İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "ruhsat/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Ruhsat Başvurusu", "Ruhsat silerken bir hata oluştu.", "danger", 1000000, BASE_URL . "ruhsat/index.php");
        }
    } else {
        adminLTE_redirect(true, "Silme Yapamazsınız.", "Silmek İçin Yetkiniz Yoktur.", "danger", 1000000, BASE_URL . "ruhsat/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız.", "danger", 1000000, BASE_URL . "ruhsat/index.php");
}
?>

