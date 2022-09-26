<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';

$validator = new InputFilterClass();

//$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

if (isset($_POST['detailId']) && in_array(YT_QUERY, $sayfaIslemleriId)) {
    $secilen_menu_id = mdecrypt($_POST['detailId'], $_SESSION['key']);
    $ItemsSQL = "SELECT
                         (SELECT yt_menu.adi FROM yt_menu WHERE yt_menu.id = selected_menu.ana_menu_id) AS main_menu, selected_menu.adi, selected_menu.sayfa_url, selected_menu.disariya_acik_mi, selected_menu.`language`, selected_menu.aktif_mi
                                        FROM yt_menu selected_menu WHERE id = ? ";
    try {
        $listItems = $GLOBALS['db']->fetchRow($ItemsSQL, $secilen_menu_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);
    $listItemsTitle = array(
        'adi' => 'Menu Adı',
        'main_menu' => 'Ana menu Adı',
        'sayfa_url' => 'Sayfa Url',
        'disariya_acik_mi' => 'Dışarıya Açık mı?',
        'language' => 'Dili',
        'aktif_mi' => 'Aktif Mi?');
    echo '<div class="box box-primary"> <table  class="table table-striped table-bordered table-hover table-condensed"><tbody>';
    foreach ($listItems as $key => $value) {
        echo '<tr><th>' . $listItemsTitle[$key] . '</th><td>' . $value . '</td></tr>';
    }
    echo '</tbody></table></div>';
} else if (isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
    $rules = array(
        'menu_name' => 'required',
        'menu_dili' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $data = array(
            'adi' => $_POST['menu_name'],
            'sayfa_url' => $_POST['page_url'],
            'target' => $_POST['menu_target'],
            'link' => $_POST['link'],
            'ana_menu_id' => $_POST['main_menu'] == "" ? NULL : mdecrypt($_POST['main_menu'], $_SESSION['key']),
            'menu_class' => $_POST['main_menu'] == "" ? 'class="treeview"' : "",
            'sol_icon' => $_POST['menu_left_icon'],
            'sag_icon' => $_POST['menu_right_icon'],
            'order_id' => $_POST['order'],
            'disariya_acik_mi' => $_POST['disariya_acik_mi'],
            'language' => $_POST['menu_dili'],
            'aktif_mi' => $_POST['aktif_mi']
        );
        try {
            log::islem_aciklamasi_kaydi("Menu Tanımlama", "Yeni Menu Ekleme", YT_INSERT);
            $GLOBALS['db']->insert('yt_menu', $data, null);
            adminLTE_redirect(false, "Menu Eklendi.", "Menu Eklendi.", "success", 1000000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Menu Eklenemedi.", "Menu Eklenemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php");
        }
    } else {
        adminLTE_redirect(false, "Eksik Veri", $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $menu_id = mdecrypt($_POST['update'], $_SESSION['key']);
    $rules = array(
        'menu_name' => 'required',
        'menu_dili' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $data = array(
            'adi' => $_POST['menu_name'],
            'sayfa_url' => $_POST['page_url'],
            'target' => $_POST['menu_target'],
            'link' => $_POST['link'],
            'ana_menu_id' => $_POST['main_menu'] == "" ? NULL : mdecrypt($_POST['main_menu'], $_SESSION['key']),
            'menu_class' => $_POST['main_menu'] == "" ? 'class="treeview"' : "",
            'sol_icon' => $_POST['menu_left_icon'],
            'sag_icon' => $_POST['menu_right_icon'],
            'order_id' => $_POST['order'],
            'disariya_acik_mi' => $_POST['disariya_acik_mi'],
            'language' => $_POST['menu_dili'],
            'aktif_mi' => $_POST['aktif_mi']
        );
        $where["id = ?"] = $menu_id;
        try {
            log::islem_aciklamasi_kaydi("Menu Tanımlama", "Menu Güncelleme", YT_UPDATE);
            $GLOBALS['db']->update('yt_menu', $data, $where);
            adminLTE_redirect(false, "Menu Güncellendi.", "Menu Güncellendi.", "success", 1000000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Menu Güncellemedi.", "Menu Güncellemedi.", "danger", 1000000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php");
        }
    } else {
        adminLTE_redirect(false, "Eksik Veri", $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['remove']) && in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {//güncelleme işlemi
    $menu_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    try {
        log::islem_aciklamasi_kaydi("Menu Tanımlama", "Menu Silme", YT_DELETE);
        $where["id = ?"] = $menu_id;
        $GLOBALS['db']->delete('yt_menu', $where);
        adminLTE_redirect(false, "Silme İşlemi", "Menu Silindi.", "success", 1000000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_redirect(false, "Silme İşlemi", "Menu Silinmedi.", "danger", 1000000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız...", "danger", 1000000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php");
}
?>

