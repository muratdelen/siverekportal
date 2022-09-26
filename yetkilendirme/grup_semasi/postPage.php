<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';
date_default_timezone_set('Europe/Istanbul');

//var_dump($_POST);
//die();
$validator = new InputFilterClass();

$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.
if (isset($_POST['detailId']) && in_array(YT_QUERY, $sayfaIslemleriId)) {
    $grup_semasi_id = mdecrypt($_POST['detailId'], $_SESSION['key']);
    $ItemsSQL = "SELECT yt_grup.adi, yt_grup.aciklama, 
                    (SELECT yt_grup.adi FROM yt_grup WHERE yt_grup.id = yt_grup_semasi.alt_grup_id) AS alt_grup_adi,
                    (SELECT yt_grup.aciklama FROM yt_grup WHERE yt_grup.id = yt_grup_semasi.alt_grup_id) AS alt_grup_aciklamasi
                    FROM yt_grup_semasi
                    INNER JOIN yt_grup ON yt_grup.id = yt_grup_semasi.yt_grup_id
                    WHERE NOT(yt_grup.id=1 OR yt_grup.id=2 OR yt_grup.id=3) AND yt_grup_semasi.id = ?";

    try {
        $listItems = $GLOBALS['db']->fetchRow($ItemsSQL, $grup_semasi_id);
        htmlspecialchar_array($listItems);
        $listItemsTitle = array(
            'adi' => 'Grup Adı',
            'aciklama' => 'Grup Açıklaması',
            'alt_grup_adi' => 'Alt Grup Adı',
            'alt_grup_aciklamasi' => 'Alt Grup Açıklaması');
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    foreach ($listItems as $value) {
        
    }
    echo '<div class="box box-primary"> <table  class="table table-striped table-bordered table-hover table-condensed"><tbody>';
    foreach ($listItems as $key => $value) {
        echo '<tr><th>' . $listItemsTitle[$key] . '</th><td>' . $value . '</td></tr>';
    }
    echo '</tbody></table></div>';
} else if (isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
    $rules = array(
        'grup' => 'required',
        'alt_grup' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $ana_grup_id = mdecrypt($_POST['grup'], $_SESSION['key']);
        $alt_grup_id = mdecrypt($_POST['alt_grup'], $_SESSION['key']);
        $data = array(
            'yt_grup_id' => $ana_grup_id,
            'alt_grup_id' => $alt_grup_id,
            'aktif_mi' => 1
        );
        try {
            log::islem_aciklamasi_kaydi("Alt Grup Tanımlama", "Normal Kullanıcı Alt Grup Ekleme", YT_INSERT);
            $GLOBALS['db']->insert('yt_grup_semasi', $data, null);
            adminLTE_redirect(false, "Alt Grup Eklendi.", "Alt Grup Eklendi.", "success", 1000000, BASE_URL . "yetkilendirme/grup_semasi/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Alt Grup Eklenemedi.", "Alt Grup Eklenemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/grup_semasi/index.php");
        }
    } else {
        adminLTE_redirect(false, "Eksik Veri", $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/grup_semasi/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $grup_semasi_id = mdecrypt($_POST['update'], $_SESSION['key']);
    $alt_grup_id = mdecrypt($_POST['alt_grup'], $_SESSION['key']);
    $rules = array(
        'alt_grup' => 'required',
        'alt_grup_aktif_mi' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $data = array(
            'alt_grup_id' => $alt_grup_id,
            'aktif_mi' => $_POST['alt_grup_aktif_mi']
        );
        $where["id = ?"] = $grup_semasi_id;
        try {
            log::islem_aciklamasi_kaydi("Alt Grup Tanımlama", "Normal Kullanıcı Alt Grup Ekleme", YT_INSERT);
            $GLOBALS['db']->update('yt_grup_semasi', $data, $where);
            adminLTE_redirect(false, "Alt Grup Güncellendi.", "Alt Grup Güncellendi.", "success", 1000000, BASE_URL . "yetkilendirme/grup_semasi/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Alt Grup Güncellemedi.", "Alt Grup Güncellemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/grup_semasi/index.php");
        }
    } else {
        adminLTE_redirect(false, "Eksik Veri", $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/grup_semasi/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['remove']) && in_array(YT_PAGEADMIN, $sayfaIslemleriId) && in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {//güncelleme işlemi
    $silecek_alt_grup_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    try {
        $where["id = ?"] = $silecek_alt_grup_id;
        log::islem_aciklamasi_kaydi("Alt Grup Tanımlama", "Alt Grup Silme", YT_INSERT);
        $GLOBALS['db']->delete('yt_grup_semasi', $where);
        adminLTE_redirect(false, "Silme İşlemi", "Alt Grup Silindi.", "success", 1000000, BASE_URL . "yetkilendirme/grup_semasi/index.php");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_redirect(false, "Silme İşlemi", "Alt Grup Silinmedi.", "danger", 1000000, BASE_URL . "yetkilendirme/grup_semasi/index.php");
    }
} else {
    var_dump($_POST);
//    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız...", "danger", 1000000, BASE_URL . "yetkilendirme/grup_semasi/index.php");
}
?>

