<?php

require_once '../lib/config.php';
require_once '../lib/functions.php';
require_once '../lib/input_filter.php';

$validator = new InputFilterClass();
$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

if (isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
    $rules = array(
        'ruhsat' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $ruhsat_id = mdecrypt($_POST['ruhsat'], $_SESSION['key']);
        $data = array(
            "s_ruhsat_bilgileri_id" => $ruhsat_id,
            "adi_soyadi" => tr_uppercase($_POST['adi_soyadi']),
            "ada_parsel" => tr_uppercase($_POST['ada_parsel']),
            "blok_no" => tr_uppercase($_POST['blok_no'])
        );
        try {
            log::islem_aciklamasi_kaydi("Asansör Bilgileri", "Asansör Bilgileri Ekleme", YT_INSERT);
            $GLOBALS['db']->insert('s_asansor_uygulama_projeleri', $data, null);
            adminLTE_redirect(false, __("Ekleme Sonucu"), __("Asansör Bilgileri Eklendi"), "success", 1000000, BASE_URL . "ruhsat_asansor/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, __("Ekleme Sonucu"), __("Asansör Bilgileri Eklenemedi!"), "danger", 1000000, BASE_URL . "ruhsat_asansor/index.php");
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "ruhsat_asansor/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $rules = array(
        'ruhsat' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $guncellenecek_id = mdecrypt($_POST['update'], $_SESSION['key']);
        $ruhsat_id = mdecrypt($_POST['ruhsat'], $_SESSION['key']);

        $data = array(
            "s_ruhsat_bilgileri_id" => $ruhsat_id,
            "adi_soyadi" => tr_uppercase($_POST['adi_soyadi']),
            "ada_parsel" => tr_uppercase($_POST['ada_parsel']),
            "blok_no" => tr_uppercase($_POST['blok_no'])
        );
//        echo '<pre>';
//        var_dump($_POST,$data);
//        die();
        $where["id = ?"] = $guncellenecek_id;
        try {
            log::islem_aciklamasi_kaydi("Asansör Başvurusu", "Yeni Asansör Güncelleme", YT_INSERT);
            $GLOBALS['db']->update('s_asansor_uygulama_projeleri', $data, $where);
            adminLTE_redirect(true, "Asansör Başvurusu", "Asansör Güncelleme İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "ruhsat_asansor/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Asansör Başvurusu", "Asansör güncellerken bir hata oluştu.", "danger", 1000000, BASE_URL . "ruhsat_asansor/index.php");
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "ruhsat_asansor/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (in_array(YT_DELETE, $sayfaIslemleriId) && isset($_POST['remove'])) {//güncelleme işlemi
    $silinecek_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
        try {
            log::islem_aciklamasi_kaydi("Asansör Bilgileri", "Asansör Bilgisi Silme", YT_DELETE);
            $data = array('aktif_mi' => 0);
            $where["id = ?"] = $silinecek_id;
            try {
                $GLOBALS['db']->update('s_asansor_uygulama_projeleri', array('aktif_mi' => 0), $where);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
//            $where["yt_kullanici_id = ?"] = $kullaniciId;
//            $GLOBALS['db']->update('sks_sozlesmeli_personel', array('kalan_izin_gun_sayisi' => $toplam_izin_sayisi), $where);
            adminLTE_redirect(true, "Asansör Başvurusu", "Asansör Silme İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "ruhsat_asansor/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Asansör Başvurusu", "Asansör silerken bir hata oluştu.", "danger", 1000000, BASE_URL . "ruhsat_asansor/index.php");
        }
    } else {
        adminLTE_redirect(true, "Silme Yapamazsınız.", "Silmek İçin Yetkiniz Yoktur.", "danger", 1000000, BASE_URL . "ruhsat_asansor/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız.", "danger", 1000000, BASE_URL . "ruhsat_asansor/index.php");
}
?>

