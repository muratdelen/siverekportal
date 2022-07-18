<?php

require_once '../lib/config.php';
require_once '../lib/functions.php';
require_once '../lib/input_filter.php';

$validator = new InputFilterClass();
$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

if (isset($_POST['detailId']) && in_array(YT_QUERY, $sayfaIslemleriId)) {
    $izin_id = mdecrypt($_POST['detailId'], $_SESSION['key']);
    $ItemsSQL = "SELECT
                                        yt_kullanici.kullanici_adi,
                                        yt_kullanici.adi,
                                        yt_kullanici.soyadi,
                                        yt_kullanici.aciklamasi AS onaylayan_bilgisi,
                                        ( CASE sks_izin_basvurusu.basvuru_durumu WHEN 0 THEN 'Bekliyor' WHEN 1 THEN 'Onaylandı' WHEN -1 THEN 'Red Edildi' END ) AS basvuru_durumu,
                                        date_format(sks_izin_basvurusu.onaylama_zamani,'%d/%m/%Y') AS onaylama_zamani,
                                        date_format(sks_izin_basvurusu.baslangic_tarihi,'%d/%m/%Y') AS baslangic_tarihi,
                                        date_format(sks_izin_basvurusu.bitis_tarihi,'%d/%m/%Y') AS bitis_tarihi,
                                        date_format(sks_izin_basvurusu.izin_alma_zamani,'%d/%m/%Y %H:%i:%s') AS izin_alma_zamani,
                                         date_format(sks_izin_basvurusu.izin_alma_guncelleme_zamani,'%d/%m/%Y %H:%i:%s') AS izin_alma_guncelleme_zamani,
                                        sks_izin_basvurusu.aciklama,
                                      ( CASE sks_izin_basvurusu.aktif_mi WHEN 0 THEN 'İzin İptal Edildi' WHEN 1 THEN 'İzin Aktif' END ) AS aktif_mi
                                    FROM sks_izin_basvurusu LEFT JOIN yt_kullanici ON sks_izin_basvurusu.onaylayan_yt_kullanici_id = yt_kullanici.id 
                                    WHERE sks_izin_basvurusu.id = ? ";
    try {
        $listItems = $GLOBALS['db']->fetchRow($ItemsSQL, $izin_id);
        htmlspecialchar_array($listItems);
        $listItemsTitle = array(
            'kullanici_adi' => 'Onaylayanın Resmi',
            'adi' => 'Onaylayanın Adı',
            'soyadi' => 'Onaylayanın Soyadı',
            'onaylayan_bilgisi' => 'Onaylayayın Ünvanı',
            'basvuru_durumu' => 'Başvuru Durumu',
            'onaylama_zamani' => 'Onaylama Zamanı',
            'baslangic_tarihi' => 'İzin Başlangıç Tarihi',
            'bitis_tarihi' => 'İzin Bitiş Tarihi',
            'izin_alma_zamani' => 'İzin Alma Zamanı',
            'izin_alma_guncelleme_zamani' => 'İzin Alma Güncelleme Zamanı',
            'aciklama' => 'İzin Açıklama',
            'aktif_mi' => 'Aktif Mi?');
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    echo '<div class="box box-primary"> <table  class="table table-striped table-bordered table-hover table-condensed"><tbody>';
    foreach ($listItems as $key => $value) {
        if ($key == "kullanici_adi") {
            if ($listItems->onaylama_zamani != NULL) {
                echo '<tr title="' . $listItemsTitle[$key] . '"><td colspan="2" style="text-align:center"><img src="' . BASE_URL . 'upload/user_images/' . $listItems->kullanici_adi . '.jpg"  onerror="this.src=' . "'" . BASE_URL . 'upload/user_images/public.png' . "'" . '" alt="Kullanıcı Resmi" style="width:250px;height:250px;"></td></tr>';
            }
        } else {
            echo '<tr ><th>' . $listItemsTitle[$key] . '</th><td>' . $value . '</td></tr>';
        }
    }
    echo '</tbody></table></div>';
} elseif (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {//güncelleme işlemi
    $rules = array(
        'izin_baslangic_tarihi' => 'required',
        'izin_bitis_tarihi' => 'required',
        'izin_gerekcesi' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        $guncellenecek_id = mdecrypt($_POST['update'], $_SESSION['key']);
        try {
            $islem_yapilan_izin = $GLOBALS['db']->fetchRow("SELECT basvuru_durumu FROM sks_izin_basvurusu WHERE aktif_mi AND basvuru_durumu = '0' AND id = ? ", $guncellenecek_id);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        if (isset($islem_yapilan_izin->basvuru_durumu)) {
            try {
                $izin_bilgisi = $GLOBALS['db']->fetchRow("SELECT kalan_izin_gun_sayisi, isgunu_hesapla(?, ?) AS alinan_is_gunu FROM sks_sozlesmeli_personel LEFT JOIN sks_izin_basvurusu ON sks_izin_basvurusu.yt_kullanici_id = sks_sozlesmeli_personel.yt_kullanici_id  WHERE sks_sozlesmeli_personel.aktif_mi AND kalan_izin_gun_sayisi > 0 AND sks_izin_basvurusu.id = ? ", array(convertDateFormatBasicDefault($_POST['izin_baslangic_tarihi']), convertDateFormatBasicDefault($_POST['izin_bitis_tarihi']), $guncellenecek_id));
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            $diff_days = $izin_bilgisi->kalan_izin_gun_sayisi - $izin_bilgisi->alinan_is_gunu;
            if ($diff_days >= 0) {
                $data = array(
//                    'yt_kullanici_id' => $kullaniciId,
                    'baslangic_tarihi' => convertDateFormatBasicDefault($_POST['izin_baslangic_tarihi']),
                    'bitis_tarihi' => convertDateFormatBasicDefault($_POST['izin_bitis_tarihi']),
                    'basvuru_nedeni' => $_POST['izin_gerekcesi'],
                    'izin_alma_guncelleme_zamani' => date("Y-m-d H:i:s")
                );
                $where1["id = ?"] = $guncellenecek_id;
                try {
                    log::islem_aciklamasi_kaydi("İzin Başvurusu", "Yeni İzin Güncelleme", YT_INSERT);
                    $GLOBALS['db']->update('sks_izin_basvurusu', $data, $where1);
                    $GLOBALS['db']->fetchRow("SELECT kalan_izin_ayarla( ? )", $izin_alınan_kullanici_id);
//                    $where["yt_kullanici_id = ?"] = $kullaniciId;
//                    $GLOBALS['db']->update('sks_sozlesmeli_personel', array('kalan_izin_gun_sayisi' => $diff_days), $where);
                    adminLTE_redirect(true, "İzin Başvurusu", "İzin İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "personel_izin_gecmisi/index.php");
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    adminLTE_redirect(false, "İzin Başvurusu", "İzin güncellerken bir hata oluştu.", "danger", 1000000, BASE_URL . "personel_izin_gecmisi/index.php");
                }
            } else {
                adminLTE_redirect(true, "Toplam İzniniz Yeterli Değil", "Toplam İzniniz alınan izinden küçük olamaz", "danger", 1000000, BASE_URL . "personel_izin_gecmisi/index.php");
            }
        } else {
            adminLTE_redirect(true, "Güncelleme Yapamazsınız.", "Başvuru Sonuçlandı veya İptal Edildi..", "danger", 1000000, BASE_URL . "personel_izin_gecmisi/index.php");
        }
    } else {
        adminLTE_redirect($validator->get_readable_errors(true), "warning", BASE_URL . "personel_izin_gecmisi/index.php"); //BURADA STANDART HATALAR VARDIR.
    }
} elseif (in_array(YT_DELETE, $sayfaIslemleriId) && isset($_POST['remove'])) {//güncelleme işlemi
    $silinecek_id = mdecrypt($_POST['remove'], $_SESSION['key']);
    if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
        try {
            log::islem_aciklamasi_kaydi("İzin Başvurusu", "Yeni İzin Silme", YT_DELETE);
            $data = array('aktif_mi' => 0);
            $where["id = ?"] = $silinecek_id;
            $GLOBALS['db']->update('sks_izin_basvurusu', array('aktif_mi' => 0), $where);
            try {
                $izin_bilgisi = $GLOBALS['db']->fetchRow("SELECT yt_kullanici_id FROM sks_izin_basvurusu  WHERE sks_izin_basvurusu.id = ? ", $silinecek_id);
                $GLOBALS['db']->fetchRow("SELECT kalan_izin_ayarla( ? )", $izin_bilgisi->yt_kullanici_id);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
//            $where["yt_kullanici_id = ?"] = $kullaniciId;
//            $GLOBALS['db']->update('sks_sozlesmeli_personel', array('kalan_izin_gun_sayisi' => $toplam_izin_sayisi), $where);
            adminLTE_redirect(true, "İzin Başvurusu", "İzin Silme İşlemi Başarıyla Tamamlandı.", "success", 1000000, BASE_URL . "personel_izin_gecmisi/index.php");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            adminLTE_redirect(false, "İzin Başvurusu", "İzin silerken bir hata oluştu.", "danger", 1000000, BASE_URL . "personel_izin_gecmisi/index.php");
        }
    } else {
        adminLTE_redirect(true, "Silme Yapamazsınız.", "Silmek İçin Yetkiniz Yoktur.", "danger", 1000000, BASE_URL . "personel_izin_gecmisi/index.php");
    }
} else {
    adminLTE_redirect(true, "Yetkisiz Erişim", "Yetkiniz dahilinde olmayan bir kayıt yapamazsınız.", "danger", 1000000, BASE_URL . "personel_izin_gecmisi/index.php");
}
?>

