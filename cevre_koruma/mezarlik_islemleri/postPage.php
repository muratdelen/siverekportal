<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';

echo '<pre>';
var_dump($_POST);
//var_dump(filter_input_array(INPUT_POST));
//var_dump(filter_input(INPUT_POST, 'ihale_adi'));
// $data = [
//     ihale_adi => 
//     filter_input(INPUT_POST, 'ihale_adi')
//     ];
// die();
$validator = new InputFilterClass();
//$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.


if (isset($_POST['detailId']) && in_array(YT_QUERY, $sayfaIslemleriId)) {
    $dosya_id = mdecrypt($_POST['detailId'], $_SESSION['key']);
    try {
        $SQL_cumlesi = "SELECT id, ad, aciklama, dosya_url FROM iys_cevre_koruma/mezarlik_islemleri WHERE aktif_mi AND id = ?";
        $dosya_bilgisi = $GLOBALS['db']->fetchRow($SQL_cumlesi, $dosya_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    echo '<div class="row">
    <div  class="col-md-12 box box-purple">        
        <embed src="' . $dosya_bilgisi->dosya_url . '" style="width:100vh;height: 500px" />
    </div>
</div>';
} else if (isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
  
    $rules = array(
        'mezarlik_adi' => 'required'
    );
    $validated = $validator->validate($_FILES, $rules);
    if ($validated === TRUE) {//işlem sorunsuz yapılabilir        
        $ada_parsel = trim($_POST["ada_parsel"]);
        if (isset($_FILES['yuklenen_dosya'])) {
            $target_dir = PROJECT_DIR . "/upload/yuklenen_cevre_koruma/mezarlik_islemleri/cevre_koruma_ve_kontrol/$ada_parsel/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $errors = array();
            $file_name = $_FILES['yuklenen_dosya']['name'];
            $file_size = $_FILES['yuklenen_dosya']['size'];
            $file_tmp = $_FILES['yuklenen_dosya']['tmp_name'];
            $file_type = $_FILES['yuklenen_dosya']['type'];
            $file_ext = strtolower(end(explode('.', $_FILES['yuklenen_dosya']['name'])));

            $expensions = array("pdf", "doc", "docx", "xls", "xlsx");

            if (in_array($file_ext, $expensions) === false) {
                $errors[] = "Desteklenen Formatlar dışında yükleme yapılmaz.";
            }

            if ($file_size > 209715200) {
                $errors[] = 'Dosya 200 MB fazla olamaz';
            }

            if (empty($errors) == true) {
                move_uploaded_file($file_tmp, $target_dir . $file_name);
            }
            $data = array(
                "adi" => trim($_POST["adi"]),
                "soyadi" => trim($_POST["soyadi"]),
                "tc_kimlik_no" => trim($_POST["tc_kimlik_no"]),
                "telefon" => trim($_POST["telefon"]),
                "tapu_belge_no" => trim($_POST["tapu_belge_no"]),
                "mezarlik_adi" => trim($_POST["mezarlik_adi"]),
                "ada_parsel" => trim($_POST["ada_parsel"]),
                "yatan_kisi_sayisi" => trim($_POST["yatan_kisi_sayisi"]),
                "toplam_kisi_sayisi" => trim($_POST["aciklama"]),
                "aciklama" => trim($_POST["toplam_kisi_sayisi"]),
                "tapu_dosya_url" => "/portal//upload/yuklenen_cevre_koruma/mezarlik_islemleri/cevre_koruma_ve_kontrol/$ada_parsel/" . $file_name,
                "ekleyen_kullanici" => $kullaniciId
            );
            try {
                log::islem_aciklamasi_kaydi("Dosya Bilgileri", "Dosya Bilgileri Ekleme", YT_INSERT);
                $eklen_id = $GLOBALS['db']->insert('cevre_mezarlik_bilgileri', $data, null);
                adminLTE_redirect(false, __("Ekleme Sonucu"), __("Dosya Bilgileri Eklendi"), "success", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                adminLTE_redirect(false, __("Ekleme Sonucu"), __("Dosya Bilgileri Eklenemedi!"), "danger", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
            }
        } else {
            adminLTE_redirect(false, __("Ekleme Sonucu"), __("Dosya Sunucuya Eklenemedi!"), "danger", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
        }
    } else {
        adminLTE_redirect(false, $validator->get_readable_errors(true), "warning", BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $rules = array(
        'yuklenen_dosya' => 'required'
    );
    $validated = $validator->validate($_FILES, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $ada_parsel = trim($_POST["ada_parsel"]);
        if (isset($_FILES['yuklenen_dosya'])) {
            $target_dir = PROJECT_DIR . "/upload/yuklenen_cevre_koruma/mezarlik_islemleri/cevre_koruma_ve_kontrol/$ada_parsel/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $errors = array();
            $file_name = $_FILES['yuklenen_dosya']['name'];
            $file_size = $_FILES['yuklenen_dosya']['size'];
            $file_tmp = $_FILES['yuklenen_dosya']['tmp_name'];
            $file_type = $_FILES['yuklenen_dosya']['type'];
            $file_ext = strtolower(end(explode('.', $_FILES['yuklenen_dosya']['name'])));

            $expensions = array("pdf", "doc", "docx", "xls", "xlsx");

            if (in_array($file_ext, $expensions) === false) {
                $errors[] = "Desteklenen Formatlar dışında yükleme yapılmaz.";
            }

            if ($file_size > 209715200) {
                $errors[] = 'Dosya 200 MB fazla olamaz';
            }

            if (empty($errors) == true) {
                move_uploaded_file($file_tmp, $target_dir . $file_name);
            }
            $data = array(
                "adi" => trim($_POST["adi"]),
                "soyadi" => trim($_POST["soyadi"]),
                "tc_kimlik_no" => trim($_POST["tc_kimlik_no"]),
                "telefon" => trim($_POST["telefon"]),
                "tapu_belge_no" => trim($_POST["tapu_belge_no"]),
                "mezarlik_adi" => trim($_POST["mezarlik_adi"]),
                "ada_parsel" => trim($_POST["ada_parsel"]),
                "yatan_kisi_sayisi" => trim($_POST["yatan_kisi_sayisi"]),
                "toplam_kisi_sayisi" => trim($_POST["aciklama"]),
                "aciklama" => trim($_POST["toplam_kisi_sayisi"]),
                "tapu_dosya_url" => "/portal//upload/yuklenen_cevre_koruma/mezarlik_islemleri/cevre_koruma_ve_kontrol/$ada_parsel/" . $file_name,
                "guncelleme_zamani" => $kullaniciId
            );
            try {
                log::islem_aciklamasi_kaydi("Dosya Yükleme", "Dosya Güncelleme", YT_INSERT);
                $GLOBALS['db']->update('cevre_mezarlik_bilgileri', $data, $where);
                adminLTE_redirect(true, "Dosya Başvurusu", "Dosya Güncelleme İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                adminLTE_redirect(false, "Dosya Başvurusu", "Dosya güncellerken bir hata oluştu.", "danger", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
            }
        } else {
            adminLTE_redirect(false, __("Güncelleme Sonucu"), __("Dosya Sunucuya Eklenemedi!"), "danger", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
        }
    } else {
        adminLTE_redirect(false, $validator->get_readable_errors(true), "warning", BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (in_array(YT_DELETE, $sayfaIslemleriId) && isset($_POST['remove'])) {//güncelleme işlemi
    $silinecek_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
        try {
            log::islem_aciklamasi_kaydi("Dosya Bilgileri", "Dosya Bilgisi Silme", YT_DELETE);
            $data = array('aktif_mi' => 0);
            $where["id = ?"] = $silinecek_id;
            try {
                $GLOBALS['db']->update('iys_cevre_koruma/mezarlik_islemleri', array('aktif_mi' => 0), $where);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            adminLTE_redirect(true, "Dosya Başvurusu", "Dosya Silme İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "Dosya Başvurusu", "Dosya silerken bir hata oluştu.", "danger", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
        }
    } else {
        adminLTE_redirect(true, "Silme Yapamazsınız.", "Silmek İçin Yetkiniz Yoktur.", "danger", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
    }
} else {
//    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız.", "danger", 1000000, BASE_URL . "cevre_koruma/mezarlik_islemleri/index.php");
}
?>

