<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';

$validator = new InputFilterClass();

$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

if (isset($_POST['detailId']) && in_array(YT_QUERY, $sayfaIslemleriId)) {
    $diger_grup_id = mdecrypt($_POST['detailId'], $_SESSION['key']);
    $ItemsSQL = "SELECT
                        yt_kullanici.kullanici_adi,
                        yt_kullanici.adi,
                        yt_kullanici.soyadi,
                        yt_kullanici.aciklamasi,
                        yt_grup.adi AS grup,
                        (CASE yt_kullanici_diger_gruplari.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                FROM
                        yt_kullanici
                        INNER JOIN yt_kullanici_diger_gruplari ON yt_kullanici_diger_gruplari.yt_kullanici_id = yt_kullanici.id
                        INNER JOIN yt_grup ON yt_grup.id = yt_kullanici_diger_gruplari.yt_grup_id
                    WHERE NOT(yt_kullanici.yt_grup_id=1 OR yt_kullanici.yt_grup_id=2) AND (yt_kullanici_diger_gruplari.id = ? )";
    try {
        $listItems = $GLOBALS['db']->fetchRow($ItemsSQL, $diger_grup_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);
    $listItemsTitle = array(
        'kullanici_adi' => 'Kullanıcı Adı',
        'adi' => 'Adı',
        'soyadi' => 'Soyadı',
        'aciklamasi' => 'Ünvanı',
        'giris_tipi' => 'Giriş Türü',
        'grup' => 'Grubu',
        'aktif_mi' => 'Aktif Mi?');
    echo '<div class="box box-primary"> <table  class="table table-striped table-bordered table-hover table-condensed"><tbody>';
    foreach ($listItems as $key => $value) {
        if ($key == "resim") {
            if ($value != "") {
                echo '<tr><td colspan="2" style="text-align:center"><img src="' . BASE_URL . 'upload/user_images/' . $value . '.jpg" alt="Kullanıcı Resmi" style="width:100px;height:100px;"</td></tr>';
            } else {
                echo '<tr><td colspan="2" style="text-align:center"><img src="' . BASE_URL . 'upload/user_images/public.png" alt="Kullanıcı Resmi" style="width:250px;height:250px;"</td></tr>';
            }
        } else {
            echo '<tr><th>' . $listItemsTitle[$key] . '</th><td>' . $value . '</td></tr>';
        }
    }
    echo '</tbody></table></div>';
} else if (isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
    $rules = array(
        'kullanici_adi' => 'required',
        'grup_id' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $data = array(
            'yt_kullanici_id' => mdecrypt($_POST['kullanici_adi'], $_SESSION['key']),
            'yt_grup_id' => mdecrypt($_POST['grup_id'], $_SESSION['key']),
            'aktif_mi' => 1
        );
        try {
            log::islem_aciklamasi_kaydi("Diğer Grup Tanımlama", "Yeni Diğer Grup Ekleme", YT_INSERT);
            $GLOBALS['db']->insert('yt_kullanici_diger_gruplari', $data, null);
            adminLTE_redirect(false, "Diğer Grup Eklendi.", "Diğer Grup Eklendi.", "success", 1000000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Diğer Grup Eklendi.", "Diğer Grup Eklenemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php");
        }
    } else {
        adminLTE_redirect(false, "Eksik Veri", $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $rules = array(
        'grup_id' => 'required',
        'grup_aktif_mi' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);
    $data = array(
        'yt_grup_id' => mdecrypt($_POST['grup_id'], $_SESSION['key']),
        'aktif_mi' => $_POST['grup_aktif_mi']);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $guncellenecek_id = mdecrypt($_POST['update'], $_SESSION['key']);
        $where["id = ?"] = $guncellenecek_id;
        try {
            log::islem_aciklamasi_kaydi("Diğer Grup Tanımlama", "Diğer Grup Güncelleme", YT_UPDATE);
            $GLOBALS['db']->update('yt_kullanici_diger_gruplari', $data, $where);
            adminLTE_redirect(false, "Diğer Grup Güncellendi.", "Diğer Grup Güncellendi.", "success", 1000000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Diğer Grup Güncellemedi.", "Diğer Grup Güncellemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php");
        }
    } else {
        adminLTE_redirect(false, "Eksik Veri", $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
    //PAGE ADMİN VE SİLME YETKİSİ VARSA SİLER
} elseif (isset($_POST['remove']) && in_array(YT_PAGEADMIN, $sayfaIslemleriId) && in_array(YT_DELETE, $sayfaIslemleriId)) {//güncelleme işlemi
    $diger_grup_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    try {
        log::islem_aciklamasi_kaydi("Diğer Grup Tanımlama", "Diğer Grup Silme", YT_DELETE);
        $where["id = ?"] = $diger_grup_id;
//        var_dump($_POST['remove']);
//var_dump($diger_grup_id);die();
        $GLOBALS['db']->delete('yt_kullanici_diger_gruplari', $where);

        adminLTE_redirect(false, "Silme İşlemi", "Diğer Grup Silindi.", "success", 1000000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_redirect(false, "Silme İşlemi", "Diğer Grup Silinmedi.", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız...", "danger", 1000000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php");
}
?>

