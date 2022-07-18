<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';

$validator = new InputFilterClass();

$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

if (isset($_POST['detailId']) && in_array(YT_QUERY, $sayfaIslemleriId)) {
    $kullanici_id = mdecrypt($_POST['detailId'], $_SESSION['key']);
    $ItemsSQL = "SELECT
                    '' AS resim,
                    yt_kullanici.kullanici_adi,
                    yt_kullanici.adi,
                    yt_kullanici.soyadi,
                    yt_kullanici.aciklamasi,
                    yt_grup.adi AS grup,
                    (CASE yt_kullanici.giris_tipi WHEN 1 THEN 'Siverek E-posta' WHEN 2 THEN 'Sistem' END) AS giris_tipi ,
                    (CASE yt_kullanici.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                FROM yt_kullanici
                    INNER JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id
                WHERE yt_kullanici.id = ? ";
    try {
        $listItems = $GLOBALS['db']->fetchRow($ItemsSQL, $kullanici_id);
        htmlspecialchar_array($listItems);
        $listItemsTitle = array(
            'kullanici_adi' => 'Kullanıcı Adı',
            'adi' => 'Adı',
            'soyadi' => 'Soyadı',
            'aciklamasi' => 'Ünvanı',
            'giris_tipi' => 'Giriş Türü',
            'grup' => 'Grubu',
            'aktif_mi' => 'Aktif Mi?');
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    echo '<div class="box box-primary"> <table  class="table table-striped table-bordered table-hover table-condensed"><tbody>';
    foreach ($listItems as $key => $value) {
        if ($key == "resim") {
            echo '<tr><td colspan="2" style="text-align:center"><img src="' . BASE_URL . 'upload/user_images/' . $listItems->kullanici_adi . '.jpg"  onerror="this.src=' . "'" . BASE_URL . 'upload/user_images/public.png' . "'" . '" alt="Kullanıcı Resmi" style="width:250px;height:250px;"</td></tr>';
        } else {
            echo '<tr><th>' . $listItemsTitle[$key] . '</th><td>' . $value . '</td></tr>';
        }
    }
    echo '</tbody></table></div>';
} else if (isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
    $rules = array(
        'kullanici_adi' => 'required',
        'adi' => 'required',
        'soyadi' => 'required',
        'sifre' => 'required|min_len,3',
        'grup_id' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $grup_id = mdecrypt($_POST['grup_id'], $_SESSION['key']);

        $data = array(
            'kullanici_adi' => $_POST['kullanici_adi'],
            'adi' => $_POST['adi'],
            'soyadi' => $_POST['soyadi'],
            'aciklamasi' => $_POST['aciklamasi'],
            'sifre' => md5(crypt($_POST['sifre'], $_POST['sifre'])),
            'giris_tipi' => $_POST['giris_tipi'],
            'yt_grup_id' => $grup_id,
            'aktif_mi' => 1
        );
        try {
            log::islem_aciklamasi_kaydi("Kullanıcı Tanımlama", "Yeni Kullanıcı Ekleme", YT_INSERT);
            $GLOBALS['db']->insert('yt_kullanici', $data, null);
            adminLTE_redirect(false, "Kullanıcı Ekledi", $_POST['adi'] . " " . $_POST['soyadi'] . " kullanıcısı Eklendi.", "success", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
        } catch (Zend_Db_Exception $ex) {
//            if("Mysqli statement execute error : Duplicate entry '".$_POST['kullanici_adi']."' for key 'kullanici_adi'" == $ex->getMessage())
            if ($ex->getCode() == 1062) {
                adminLTE_redirect(false, "Kullanıcı Eklenemedi.", $_POST['adi'] . " " . $_POST['soyadi'] . " kullanıcısı Eklenemedi.<br> Aynı kullanıcı adından kişi bulunabilir.", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php?kullanici_adi=" . $_POST['kullanici_adi'] . "&kisi_adi_soyadi=" . $_POST['adi'] . "+" . $_POST['soyadi']);
            } else {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                adminLTE_redirect(false, "Kullanıcı Eklenemedi.", $_POST['adi'] . " " . $_POST['soyadi'] . " kullanıcısı Eklenemedi.<br> " . $_POST['kullanici_adi'] . "  kullanıcı adından kişi vardır lütfen güncelleyiniz.", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
            }
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $rules = array(
        'kullanici_adi' => 'required',
        'adi' => 'required',
        'soyadi' => 'required',
//        'sifre' => 'min_len,3',
        'grup_id' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $guncellenecek_id = mdecrypt($_POST['update'], $_SESSION['key']);
        $grup_id = mdecrypt($_POST['grup_id'], $_SESSION['key']);
        if ($_POST['sifre'] == "") {
            $data = array(
                'kullanici_adi' => $_POST['kullanici_adi'],
                'adi' => $_POST['adi'],
                'soyadi' => $_POST['soyadi'],
                'aciklamasi' => $_POST['aciklamasi'],
//            'sifre' => md5(crypt($_POST['sifre'], $_POST['sifre'])),
                'giris_tipi' => $_POST['giris_tipi'],
                'yt_grup_id' => $grup_id,
                'aktif_mi' => $_POST['kullanici_aktif_mi']);
        } else {
            $data = array(
                'kullanici_adi' => $_POST['kullanici_adi'],
                'adi' => $_POST['adi'],
                'soyadi' => $_POST['soyadi'],
                'aciklamasi' => $_POST['aciklamasi'],
                'sifre' => md5(crypt($_POST['sifre'], $_POST['sifre'])),
                'giris_tipi' => $_POST['giris_tipi'],
                'yt_grup_id' => $grup_id,
                'aktif_mi' => $_POST['kullanici_aktif_mi']);
        }
        $where["id = ?"] = $guncellenecek_id;
        try {
            log::islem_aciklamasi_kaydi("Kullanıcı Tanımlama", "Yeni Kullanıcı Güncelleme", YT_UPDATE);
            $GLOBALS['db']->update('yt_kullanici', $data, $where);
            adminLTE_redirect(false, "Kullanıcı Güncellendi", $_POST['adi'] . " " . $_POST['soyadi'] . " kullanıcısı Güncellendi.", "success", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Kullanıcı Güncellemedi.", $_POST['adi'] . " " . $_POST['soyadi'] . " kullanıcısı Güncellemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (in_array(YT_PAGEADMIN, $sayfaIslemleriId) && isset($_POST['remove'])) {//güncelleme işlemi
    $kullanici_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    try {
        log::islem_aciklamasi_kaydi("Kullanıcı Tanımlama", "Kullanıcı Silme", YT_PAGEADMIN);
        $where["id = ?"] = $kullanici_id;
        $GLOBALS['db']->delete('yt_kullanici', $where);
        adminLTE_redirect(false, "Silme İşlemi", "Kişi Silindi.", "success", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_redirect(false, "Silme İşlemi", "Kullanıcı Silinmedi.", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız...", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_tanimlama/index.php");
}
?>

