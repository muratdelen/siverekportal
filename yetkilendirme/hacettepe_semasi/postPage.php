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
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $guncellenecek_id = mdecrypt($_POST['update'], $_SESSION['key']);
    $where["BIRIMKOD = ?"] = $guncellenecek_id;
    try {
        log::islem_aciklamasi_kaydi("hu_organizasyon_semasi", "aktif", YT_UPDATE);
        $GLOBALS['db']->update('hu_organizasyon_semasi', array('AKTIF' => 1), $where);
        adminLTE_redirect(false, "İşlem Başarılı", "Aktif", "success", 1000000, BASE_URL . "yetkilendirme/Siverek_semasi/index.php");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_redirect(false, "İşlem Başarısız.", "İşlem Başarısız.", "danger", 1000000, BASE_URL . "yetkilendirme/Siverek_semasi/index.php");
    }
} elseif (isset($_POST['remove'])&& in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
   $guncellenecek_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    $where["BIRIMKOD = ?"] = $guncellenecek_id;
    try {
        log::islem_aciklamasi_kaydi("hu_organizasyon_semasi", "Pasif", YT_UPDATE);
        $GLOBALS['db']->update('hu_organizasyon_semasi', array('AKTIF' => 0), $where);
        adminLTE_redirect(false, "İşlem Başarılı", "Pasif", "success", 1000000, BASE_URL . "yetkilendirme/Siverek_semasi/index.php");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_redirect(false, "İşlem Başarısız.", "İşlem Başarısız.", "danger", 1000000, BASE_URL . "yetkilendirme/Siverek_semasi/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız...", "danger", 1000000, BASE_URL . "yetkilendirme/Siverek_semasi/index.php");
}
?>

