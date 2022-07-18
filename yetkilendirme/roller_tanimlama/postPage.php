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
    $rol_sayfa_yetkileri = mdecrypt($_POST['detailId'], $_SESSION['key']);
    $ItemsSQL = "SELECT
                    yt_menu.adi AS menuler,
                    yt_sayfa_islemleri.adi AS yetkiler,
                    yt_sayfa_islemleri.aciklama
                    FROM
                    yt_rol_sayfa_yetkileri
                    INNER JOIN yt_menu ON yt_menu.id = yt_rol_sayfa_yetkileri.yt_menu_id
                    INNER JOIN yt_sayfa_islemleri ON yt_sayfa_islemleri.id = yt_rol_sayfa_yetkileri.yt_sayfa_islemleri_id
                    WHERE yt_rol_sayfa_yetkileri.aktif_mi AND yt_sayfa_islemleri.aktif_mi 
                    AND yt_rol_sayfa_yetkileri.id = ? 
                    ORDER BY yt_rol_sayfa_yetkileri.yt_menu_id";

    try {
        $listItems = $GLOBALS['db']->fetchRow($ItemsSQL, $rol_sayfa_yetkileri);
        htmlspecialchar_array($listItems);
        $listItemsTitle = array(
            'menuler' => 'Menu Adı',
            'yetkiler' => 'Verilen Yetki',
            'aciklama' => 'Aciklama');
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
            log::islem_aciklamasi_kaydi("Rol Tanımlama", "Yeni Rol Ekleme", 1000000, YT_INSERT);
            $GLOBALS['db']->insert('yt_rol', $data, null);
            adminLTE_redirect(false,"Rol Eklendi.","Rol Eklendi.", "success", 1000000, BASE_URL . "yetkilendirme/roller_tanimlama/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false,"Rol Eklenemedi.","Rol Eklenemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/roller_tanimlama/index.php");
        }
    } else {
        adminLTE_redirect("Eksik Veri",$validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/roller_tanimlama/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $rol_id = mdecrypt($_POST['update'], $_SESSION['key']);
    $rules = array(
        'rol_adi' => 'required',
        'rol_aciklama' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $data = array(
            'adi' => $_POST['rol_adi'],
            'aciklama' => $_POST['rol_aciklama'],
            'aktif_mi' => $_POST['aktif_mi'],
        );
        $where["id = ?"] = $rol_id;
        try {
            log::islem_aciklamasi_kaydi("Rol Tanımlama", "Rol Güncelleme", YT_UPDATE);
            $GLOBALS['db']->update('yt_rol', $data, $where);
            adminLTE_redirect(false,"Rol Güncellendi.","Rol Güncellendi.", "success", 1000000, BASE_URL . "yetkilendirme/roller_tanimlama/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false,"Rol Güncellemedi.","Rol Güncellemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/roller_tanimlama/index.php");
        }
    } else {
        adminLTE_redirect("Eksik Veri",$validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/roller_tanimlama/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['remove']) && in_array(YT_DELETE, $sayfaIslemleriId)) {//güncelleme işlemi
    $silinecek_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    try {
        $where["id = ?"] = $silinecek_id;
        log::islem_aciklamasi_kaydi("Rol Tanımlama", "Rol Silme", YT_DELETE);
        $GLOBALS['db']->update('yt_rol_sayfa_yetkileri', array('aktif_mi' => 0), $where);
        adminLTE_redirect(false,"Rol Yetkisi Silindi.","Rol Yetkisi Silindi.", "success", 1000000, BASE_URL . "yetkilendirme/roller_tanimlama/index.php");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_redirect(false,"Rol Silinmedi.", "Rol Silinmedi.", "danger", 1000000, BASE_URL . "yetkilendirme/roller_tanimlama/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız...", "danger", 1000000, BASE_URL . "yetkilendirme/roller_tanimlama/index.php");
}
?>

