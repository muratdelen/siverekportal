<?php

require_once '../lib/config.php';
require_once '../lib/functions.php';
require_once '../lib/input_filter.php';

$validator = new InputFilterClass();

$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.
if (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $rules = array(
        'adi' => 'required',
        'soyadi' => 'required' 
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        if ($_POST['sifre'] == "") {
            $data = array(
                'adi' => $_POST['adi'],
                'soyadi' => $_POST['soyadi'],
                'aciklamasi' => $_POST['aciklamasi'],
                'giris_tipi' => $_POST['giris_tipi']);
        } else {
            $data = array(
                'adi' => $_POST['adi'],
                'soyadi' => $_POST['soyadi'],
                'aciklamasi' => $_POST['aciklamasi'],
                'sifre' => md5(crypt($_POST['sifre'], $_POST['sifre'])),
                'giris_tipi' => $_POST['giris_tipi']);
        }
        $where["id = ?"] = $kullaniciId;
        try {
            log::islem_aciklamasi_kaydi("Personel Kişisel Bilgisi Tanımlama", "Yeni Personel Kişisel Bilgisi Güncelleme", YT_UPDATE);
            $GLOBALS['db']->update('yt_kullanici', $data, $where);
            adminLTE_redirect(false, "Personel Kişisel Bilgisi Güncellendi", $_POST['adi'] . " " . $_POST['soyadi'] . " kullanıcısı Güncellendi.", "success", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Personel Kişisel Bilgisi Güncellemedi.", $_POST['adi'] . " " . $_POST['soyadi'] . " kullanıcısı Güncellemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız...", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
}
?>

