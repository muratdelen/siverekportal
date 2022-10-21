<?php

require_once '../lib/config.php';
require_once '../lib/functions.php';
require_once '../lib/input_filter.php';

$validator = new InputFilterClass();

//$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.
if (isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
    $rules = array(
        'page_name' => 'required',
        'page_url' => 'required'
    );

    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        //SAYFA EKLEME
        $ItemsSQL = "SELECT st_pages.id FROM st_pages WHERE st_pages.page_url = ? AND st_pages.page_language = ?";
        try {
            $mypages = $GLOBALS['db']->fetchRow($ItemsSQL, array($_POST['page_url'],"tr"));
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        if ($mypages == NULL) {
            if (strpos($_POST['slider_header'], "<h1>") || strpos($_POST['slider_header'], "<h2>") || strpos($_POST['slider_header'], "<h3>") || strpos($_POST['slider_header'], "<h4>") || strpos($_POST['slider_header'], "<h5>") || strpos($_POST['slider_header'], "<h6>")) {
                
            } else {
                $_POST['slider_header'] = "<h4>" . $_POST['slider_header'] . "</h4>";
            }

            $data = array(
                'page_language' => $selected_language,
                'page_country' => $selected_country,
                'page_name' => $_POST['page_name'],
                'page_url' => "?pages=" . $_POST['page_url'],
//                'yt_menu_id' => -1,
                'menu_header_color' => "",
                'slider_header' => $_POST['slider_header'],
                'slider_title' => $_POST['slider_title'],
                'slider_image_url' => $_POST['slider_image_url'],
                'slider_video_url' => $_POST['slider_video_url'],
                'slider_video_description' => $_POST['slider_video_description'],
                'is_show_slider' => 0,
                'is_show_send_email' => 0,
                'is_show_maps' => 0,
                'is_show_language' => 0,
                'page_content' => $_POST['page_content'],
                'is_active' => 1,
            );
            try {
                log::islem_aciklamasi_kaydi("Sayfa Tanımlama", "Yeni Sayfa Ekleme", YT_INSERT);
                $GLOBALS['db']->insert('st_pages', $data, null);
                adminLTE_redirect(false, __("Ekleme Sonucu"), __("Sayfa Eklendi"), "success", 1000000, BASE_URL . "admin/index.php");
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                adminLTE_redirect(false, __("Ekleme Sonucu"), __("Sayfa Eklenemedi!"), "danger", 1000000, BASE_URL . "admin/index.php");
            }
        } else {
            adminLTE_redirect(false,__("Ekleme Sonucu"), "Sayfa Eklenemedi. << " . $_POST['page_url'] . " >> Url Önceden Girilmiştir.", "danger", 1000000, BASE_URL . "admin/index.php");
        }
    } else {
        adminLTE_redirect(false,__("Ekleme Sonucu"), $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "admin/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $rules = array(
        'page_name' => 'required',
        'page_url' => 'required',
        'is_active' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $page_id = mdecrypt($_POST['update'], $_SESSION['key']);
        if (strstr($_POST['slider_header'], "<h1>") || strstr($_POST['slider_header'], "<h2>") || strstr($_POST['slider_header'], "<h3>") || strstr($_POST['slider_header'], "<h4>") || strstr($_POST['slider_header'], "<h5>") || strstr($_POST['slider_header'], "<h6>")) {
            
        } else {
            $_POST['slider_header'] = "<h4>" . $_POST['slider_header'] . "</h4>";
        }
        $data = array(
            'page_language' => $selected_language,
            'page_country' => $selected_country,
            'page_name' => $_POST['page_name'],
            'page_url' => "?pages=" . $_POST['page_url'],
//            'yt_menu_id' => -1,
            'menu_header_color' => "",
            'slider_header' => $_POST['slider_header'],
            'slider_title' => $_POST['slider_title'],
            'slider_image_url' => $_POST['slider_image_url'],
            'slider_video_url' => $_POST['slider_video_url'],
            'slider_video_description' => $_POST['slider_video_description'],
            'is_show_slider' => 0,
            'is_show_send_email' => 0,
            'is_show_maps' => 0,
            'is_show_language' => 0,
            'page_content' => $_POST['page_content'],
            'is_active' => $_POST['is_active'],
        );
        $page_url = "/index.php/?page=" . $_POST["page_url"];
        $where["id = ?"] = $page_id;
        try {
            log::islem_aciklamasi_kaydi("Sayfa Tanımlama", "Sayfa Güncelleme", YT_UPDATE);
            $GLOBALS['db']->update('st_pages', $data, $where);
            adminLTE_redirect(false,__("Güncelleme Sonucu"), "Sayfa Güncellendi.", "success", 1000000, BASE_URL . "admin/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false,__("Güncelleme Sonucu"), "Sayfa Güncellemedi.", "danger", 1000000, BASE_URL . "admin/index.php");
        }
    } else {
        adminLTE_redirect(false,__("Güncelleme Sonucu"), $validator->get_readable_errors(true), "warning", 1000000, BASE_URL . "admin/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} else if (isset($_POST['remove']) && in_array(YT_DELETE, $sayfaIslemleriId)) {//SİLME İŞLEMİ
    $page_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    try {
        log::islem_aciklamasi_kaydi("Tanımlanan Sayfaları Listeleme", "Sayfa Silindi.", YT_DELETE);
        $where["id = ?"] = $page_id;
        $GLOBALS['db']->delete('st_pages', $where);
        adminLTE_redirect(false,__("Silme Sonucu"), "Sayfa Silindi.", "success", 1000000, BASE_URL . "admin/index.php");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_redirect(false,__("Silme Sonucu"),  "Sayfa Silinmedi.","Sayfa Silinmedi.", "danger", 1000000, BASE_URL . "admin/index.php");
    }
} else {
    adminLTE_redirect(false, __("Silme Sonucu"),"Yetkiniz dahilinde olmayan bir işlem yapamazsınız...", "danger", 1000000, BASE_URL . "admin/index.php");
}
?>

