
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>

            <small><?= __("LOG KAYITLARI") ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-cloud"></i><?= __("Çıktı") ?></a></li>
            <li class="active"><?= __("Çıktı Doğrulama Sayfası"); ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <section class="content-header"></section>
                    <?php
                    $print_id = mdecrypt($_GET["print"], "muratdelen");
                    $pagesSQL = "SELECT log_cikti.tipi, yt_kullanici.kullanici_adi, yt_kullanici.adi, yt_kullanici.soyadi, yt_grup.adi AS grup_adi, log_cikti.baslik, log_cikti.aciklama, log_cikti.sayfa_url, log_cikti.ip, date_format(log_cikti.zaman,'%d/%m/%Y %H:%i:%s') AS zaman FROM log_cikti 
                            LEFT JOIN yt_kullanici ON yt_kullanici.id = log_cikti.yt_kullanici_id 
                            LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id WHERE log_cikti.id = ? ";
                    try {
                        $print = $db->fetchRow($pagesSQL, $print_id);
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    if (isset($print->tipi)) {
                        adminLTE_alert(false, __("Sistemde Kayıt Yok"), __($print->baslik . " çıktısı " . $print->adi . " " . $print->soyadi . " tarafından alınmıştır(" . $print->zaman . ")."), "success");
                    } else {
                        adminLTE_alert(false, __("Sistemde Kayıt Yok"), __("Sistemde kayıt bulunamadı!"), "warning");
                    }
                    ?>
            </div>
        </div>
    </div>
</div>


