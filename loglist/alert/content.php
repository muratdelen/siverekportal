<?php
if (isset($_GET["remove"])) {
    $ItemsSQL = 'UPDATE st_uyari SET aktif_mi = 0 WHERE id = ? ';
    try {
        $GLOBALS['db']->fetchAll($ItemsSQL, $_GET["remove"]);
        adminLTE_alert(false, __("Mesaj Silme İşlemi"), __("Silme Başarıyla Gerçekleşti!"), "success");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        adminLTE_alert(false, __("Mesaj Silme İşlemi"), __("Silme Gerçekleşmedi!"), "danger");
    }
}


if ($girisYapanKullaniciGrupId === 1 || $girisYapanKullaniciGrupId === 2 || $girisYapanKullaniciGrupId === 3 || $girisYapanKullaniciGrupId === 4) {
    try {
        $listdateItems = $GLOBALS['db']->fetchAll("SELECT DISTINCT  date_format(st_uyari.zaman,'%d/%m/%Y') AS zaman FROM st_uyari ORDER BY st_uyari.id DESC");
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $date_array = array();
    foreach ($listdateItems as $value) {
        array_push($date_array, $value->zaman);
    }
    $options1 = array(
//zorunlu parametreler
        'tableHeaders' => array('<i class="fa fa-paper-plane" aria-hidden="true"></i> GÖNDEREN', '<i class="fa fa-envelope" aria-hidden="true"></i> ALICI', 'Zaman', 'Yeni/Eski?', 'Mesaj İçeriği', ' '),
        'order' => array(3, 'desc'),
        'tableFooters' => array('Gelen Kullanıcı Adı', 'Gönderilen Kullanıcı Adı', 'Zaman'),
        'filters' => array('text', 'text', $date_array),
        'buttons' => array('excel'),
        'url' => '_table_content.php',
    );
} else {
    try {
        $listdateItems = $GLOBALS['db']->fetchAll("SELECT DISTINCT  date_format(st_uyari.zaman,'%d/%m/%Y') AS zaman FROM st_uyari WHERE st_uyari.kullanici_adi = ? ORDER BY st_uyari.id DESC", $kullaniciAdi);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $date_array = array();
    foreach ($listdateItems as $value) {
        array_push($date_array, $value->zaman);
    }
    $options1 = array(
//zorunlu parametreler
        'tableHeaders' => array('<i class="fa fa-paper-plane" aria-hidden="true"></i> GÖNDEREN', 'Zaman', 'Yeni/Eski?', 'Mesaj İçeriği', ' '),
        'order' => array(2, 'desc'),
        'tableFooters' => array('Gelen Kullanıcı Adı', 'Zaman'),
        'filters' => array('text', $date_array),
        'buttons' => array('excel'),
        'url' => '_table_content.php',
    );
}
?>
<div class="content-wrapper">
    <?php
    try {
        $dtableServer = new DataTable($options1);
    } catch (Exception $ex) {
        die($ex->getMessage());
    }
    ?>
    <!--Content Header (Page header)--> 
    <section class="content-header">
        <h1>
            <!--Data Tables-->
            <small>LOG KAYITLARI</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Log Kayıtları</a></li>
            <li><a href="#">Uyarı Kayıtları</a></li>
        </ol>
    </section>

    <!--Main content--> 
    <section class="content">
        <div class="row">
            <div class="col-xs-12" id="log-kayitlari">


                <div class="box box-primary">
                    <div class="box-header">
                        <!--<h3 class="box-title">Data Table With Full Features</h3>-->
                    </div> 
                    <div class="box-body">

                        <?php
                        echo '<form action="" method="POST"  onsubmit="return confirmRemove(this);"><input name="remove-all-message" value="" type="hidden"><button type="submit" class="btn btn-danger btn-block" ><span class="fa fa-remove"></span> ' . __("Sözülen Mesajları Sil") . '</button></form>';
                        if (isset($_POST["remove-all-message"])) {
                            if (isset($_SESSION["filtersWhereSql"])) {
//                                if ($girisYapanKullaniciGrupId === 1) {
//                                    $userItemsSQL = "DELETE FROM st_uyari " . $_SESSION["filtersWhereSql"];
//                                } else {
                                $userItemsSQL = "UPDATE st_uyari SET st_uyari.aktif_mi = '0' " . $_SESSION["filtersWhereSql"];
//                                }
                                try {
                                    $GLOBALS['db']->fetchAll($userItemsSQL);
                                    adminLTE_alert(false, __("Silme Sonucu"), __("Mesaj Silindi!"), "success");
                                    unset($_SESSION["filtersDeleteSql"]);
                                } catch (Zend_Db_Exception $ex) {
                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                    adminLTE_alert(false, __("Silme Sonucu"), __("Mesaj Silinemedi!"), "danger");
                                }
                            }
                        }
                        echo $dtableServer->get_data_table();
                        if ($girisYapanKullaniciGrupId === 1 || $girisYapanKullaniciGrupId === 2 || $girisYapanKullaniciGrupId === 3 || $girisYapanKullaniciGrupId === 4) {
                            try {
                                $destek_ekibi = $GLOBALS['db']->fetchAll("SELECT CONCAT(yt_kullanici.adi,' ',yt_kullanici.soyadi) AS kullanici, yt_kullanici.kullanici_adi FROM yt_kullanici WHERE yt_kullanici.yt_grup_id = 4");
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            }
                            $toplam_gelen = 0;
                            $toplan_giden = 0;
                            foreach ($destek_ekibi as $key => $destek) {
                                try {
                                    $gonderen_kisi = $GLOBALS['db']->fetchAll("SELECT COUNT(st_uyari.id) AS sayi FROM st_uyari WHERE st_uyari.aktif_mi AND st_uyari.kullanici_adi = ? GROUP BY st_uyari.baslik ", $destek->kullanici_adi);
                                    $gelen_kisi = $GLOBALS['db']->fetchAll("SELECT COUNT(st_uyari.id) AS sayi FROM st_uyari WHERE st_uyari.aktif_mi AND st_uyari.baslik = ? GROUP BY st_uyari.kullanici_adi ", $destek->kullanici_adi);
                                } catch (Zend_Db_Exception $ex) {
                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                }
                                $toplan_giden +=count($gonderen_kisi);
                                $toplam_gelen +=count($gelen_kisi);
                                if ($key % 2 == 0) {
                                    echo '<div class="col-md-6 btn-default"><i class="fa fa-paper-plane" aria-hidden="true"></i> ' . $destek->kullanici . ' mesaj gönderilen kişi sayısı: ' . count($gonderen_kisi) . '</div>';
                                    echo '<div class="col-md-6 btn-default"><i class="fa fa-envelope" aria-hidden="true"></i> ' . $destek->kullanici . ' mesaj gelen kişi sayısı ' . count($gelen_kisi) . '</div>';
                                } else {
                                    echo '<div class="col-md-6 " ><i class="fa fa-paper-plane" aria-hidden="true"></i> ' . $destek->kullanici . ' mesaj gönderilen kişi sayısı: ' . count($gonderen_kisi) . '</div>';
                                    echo '<div class="col-md-6 "><i class="fa fa-envelope" aria-hidden="true"></i> ' . $destek->kullanici . ' mesaj gelen kişi sayısı ' . count($gelen_kisi) . '</div>';
                                }
                            }

                            echo '<div class="col-md-6 btn-success" ><i class="fa fa-paper-plane" aria-hidden="true"></i> Toplam Gonderilen sayısı: ' . $toplan_giden . '</div>';
                            echo '<div class="col-md-6 btn-success"><i class="fa fa-envelope" aria-hidden="true"></i> Toplam Gelen Sayısı: ' . $toplam_gelen . '</div>';
                        }
                        ?>
                    </div> 
                </div> 
            </div> 
        </div> 
    </section> 
</div>

<?php
echo $dtableServer->get_datatable_script();
?>
<script>
//    $('tr[data-href]').on("click", function () {
//        document.location = $(this).data('href');
//    });
//    
</script>