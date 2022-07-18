<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';

$validator = new InputFilterClass();

$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

if (isset($_POST['detailId']) && in_array(YT_QUERY, $sayfaIslemleriId)) {
    $secilen_grup_id = mdecrypt($_POST['detailId'], $_SESSION['key']);
    $ItemsSQL = "SELECT adi, aciklama, (CASE yt_grup.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                                           FROM yt_grup WHERE NOT(id=1 OR id=2 OR id=3) AND id = ? ";
    try {
        $listItems = $GLOBALS['db']->fetchRow($ItemsSQL, $secilen_grup_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);
    $listItemsTitle = array(
        'adi' => 'Grup Adı',
        'aciklama' => 'Grup Açıklaması',
        'aktif_mi' => 'Aktif Mi?');
    echo '<div class="box box-primary"> <table  class="table table-striped table-bordered table-hover table-condensed"><tbody>';
    foreach ($listItems as $key => $value) {
        echo '<tr><th>' . $listItemsTitle[$key] . '</th><td>' . $value . '</td></tr>';
    }
    echo '</tbody></table></div>';
} else if (isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
    $rules = array(
        'adi' => 'required',
        'aciklama' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $data = array(
            'adi' => $_POST['adi'],
            'aciklama' => $_POST['aciklama'],
            'aktif_mi' => 1
        );
        try {
            log::islem_aciklamasi_kaydi("Grup Tanımlama", "Yeni Grup Ekleme", YT_INSERT);
            $GLOBALS['db']->insert('yt_grup', $data, null);
            adminLTE_redirect(false, "Grup Eklendi.", "Grup Eklendi.", "success", 1000000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Grup Eklenemedi.", "Grup Eklenemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php");
        }
    } else {
        adminLTE_redirect(false, "Eksik Veri", $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $grup_id = mdecrypt($_POST['update'], $_SESSION['key']);
    $rules = array(
        'adi' => 'required',
        'aciklama' => 'required',
        'grup_aktif_mi' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $data = array(
            'adi' => $_POST['adi'],
            'aciklama' => $_POST['aciklama'],
            'aktif_mi' => $_POST['grup_aktif_mi']
        );
        $where["id = ?"] = $grup_id;
        try {
            log::islem_aciklamasi_kaydi("Grup Tanımlama", "Grup Güncelleme", YT_UPDATE);
            $GLOBALS['db']->update('yt_grup', $data, $where);
            adminLTE_redirect(false, "Grup Güncellendi.", "Grup Güncellendi.", "success", 1000000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Grup Güncellemedi.", "Grup Güncellemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php");
        }
    } else {
        adminLTE_redirect(false, "Eksik Veri", $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['remove']) && in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {//güncelleme işlemi
    $grup_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    try {
        log::islem_aciklamasi_kaydi("Grup Tanımlama", "Grup Silme", YT_DELETE);
        $where["id = ?"] = $grup_id;
        $GLOBALS['db']->delete('yt_grup', $where);
        adminLTE_redirect(false, "Silme İşlemi", "Grup Silindi.", "success", 1000000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_redirect(false, "Silme İşlemi", "Grup Silinmedi.", "danger", 1000000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız...", "danger", 1000000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php");
}
?>

