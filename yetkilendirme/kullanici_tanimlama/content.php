
<?php
include_once '../../lib/DataTable.php';
//EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
?>
<div class="content-wrapper">    
    <section class="content-header">
        <h1>
            <small><?php echo __("YETKİLENDİRME") ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-cloud"></i><?php echo __("Admin") ?></a></li>
            <li class="active"><?php echo __("Kullanıcı İşlemleri") ?></li>
        </ol>
    </section>
    <section class="container-fluid">
        <div class="row">
            <?php
//yeni kayıt butonunana basıldı ise
            if (isset($_GET['new'])) {
                if (in_array(YT_INSERT, $sayfaIslemleriId)) {
                    require_once 'yeni_kayit.php';
                }
            }//eğer güncelleme butonuna basıldı ise
            else if (isset($_GET['update'])) {
                if (in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    require_once 'guncelle.php';
                }
            } else {//normal görüntüleme
                if (in_array(YT_INSERT, $sayfaIslemleriId)) {
                    echo '<div class="col-md-8" style="	margin-bottom: 10px;">
                                <a href="?new" class="btn bg-purple" >YENİ KULLANICI EKLE</a>  
                        </div>';
                }
                if (in_array(YT_QUERY, $sayfaIslemleriId)) {//eğer sorgula butonuna basıldı ise sor
                    ?>
                    <div  class="col-md-12 box box-primary" id="sorgulama_ekrani_div">
                        <form class="form-horizontal" method="GET" action="">
                            <div class="form-group form-group-sm"></div>    
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="kullanici_adi">Kullanıcı Adı</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();
                                            $('#kisi_adi_soyadi').val($('#kullanici_adi  option:selected').attr('title'));$('#get-items').click();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="kullanici_adi" name="kullanici_adi">
                                        <option value=''>Seçiniz</option>
                                        <?php
                                        if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
                                            $kisilerSQL = "SELECT yt_kullanici.id, yt_kullanici.kullanici_adi, yt_kullanici.adi, yt_kullanici.soyadi FROM yt_kullanici ORDER BY yt_kullanici.kullanici_adi";
                                        } else {
                                            $kisilerSQL = "SELECT yt_kullanici.id, yt_kullanici.kullanici_adi, yt_kullanici.adi, yt_kullanici.soyadi FROM yt_kullanici WHERE NOT(yt_kullanici.yt_grup_id=1 OR yt_kullanici.yt_grup_id=2) ORDER BY yt_kullanici.kullanici_adi";
                                        }
                                        try {
                                            $kisiler = $db->fetchAll($kisilerSQL);
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($kisiler);
                                        foreach ($kisiler as $kisi) {
                                            echo "<option value='$kisi->kullanici_adi'  title='$kisi->adi $kisi->soyadi'";
                                            echo (isset($kisi->kullanici_adi) ? (isset($_GET['kullanici_adi']) ? ($_GET['kullanici_adi'] == $kisi->kullanici_adi ? 'selected' : null) : null) : null);
                                            echo ">$kisi->kullanici_adi</option>";
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="kisi_adi_soyadi">Kullanıcı Adı Soyadı</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="kisi_adi_soyadi" name="kisi_adi_soyadi" value="<?php echo isset($_GET['kisi_adi_soyadi']) ? $_GET['kisi_adi_soyadi'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" id="get-items" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-search"></span> <?= __("Kullanıcı Detayı Göster") ?></button>
                            </div>
                        </form>
                    </div>
                    <?php
//                            if (isset($_GET['Sorgula']) && ((isset($_GET['sorgu_tc_vergi_numarasi']) && $_GET['sorgu_tc_vergi_numarasi'] != '') || (isset($_GET['sorgu_gercek_tuzel_kisi_adi']) && $_GET['sorgu_gercek_tuzel_kisi_adi'] != ''))) {
                    if (isset($_GET['kullanici_adi'])) {
                        if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
                            $ItemsSQL = "SELECT
                                            yt_kullanici.id,
                                            yt_kullanici.kullanici_adi,
                                            yt_kullanici.adi,
                                            yt_kullanici.soyadi,
                                            yt_kullanici.aciklamasi,
                                            yt_grup.adi AS grup,
                                            (CASE yt_kullanici.giris_tipi WHEN 1 THEN 'Siverek E-posta' WHEN 2 THEN 'Sistem' END) AS giris_tipi ,
                                            '' AS resim,
                                            (CASE yt_kullanici.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                                        FROM yt_kullanici
                                            LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id
                                        WHERE yt_kullanici.kullanici_adi = ? OR yt_kullanici.adi LIKE ? OR yt_kullanici.soyadi LIKE ?  ";
                        } else {
                            $ItemsSQL = "SELECT
                                            yt_kullanici.id,
                                            yt_kullanici.kullanici_adi,
                                            yt_kullanici.adi,
                                            yt_kullanici.soyadi,
                                            yt_kullanici.aciklamasi,
                                            yt_grup.adi AS grup,
                                            (CASE yt_kullanici.giris_tipi WHEN 1 THEN 'Siverek E-posta' WHEN 2 THEN 'Sistem' END) AS giris_tipi ,
                                            '' AS resim,
                                            (CASE yt_kullanici.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                                        FROM yt_kullanici
                                            LEFT JOIN yt_grup ON yt_grup.id = yt_kullanici.yt_grup_id
                                        WHERE NOT(yt_kullanici.yt_grup_id=1 OR yt_kullanici.yt_grup_id=2) AND (yt_kullanici.kullanici_adi = ? OR yt_kullanici.adi LIKE ? OR yt_kullanici.soyadi LIKE ? ) ";
                        }
                        $kullanici_adi = $_GET['kullanici_adi'];
                        try {
                            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, array($kullanici_adi, "%" . $_GET['kisi_adi_soyadi'] . "%", "%" . $_GET['kisi_adi_soyadi'] . "%"));
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
                        htmlspecialchar_array($listItems);
                        foreach ($listItems as $value) {
                            $value->resim = '<img src="' . BASE_URL . 'upload/user_images/' . $value->kullanici_adi . '.jpg"  onerror="this.src=' . "'" . BASE_URL . 'upload/user_images/public.png' . "'" . '" alt="image" style="width:50px;height:50px;">';
                        }
                        if ((isset($listItems)) && !empty($listItems)) {
                            if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
                                $Buttons = array('details' => 'Ayrıntı', 'update_get' => 'Güncelle', 'remove' => 'Sil');
                                $ButtonsUrls = array('details' => 'postPage.php', 'update_get' => 'index.php', 'remove' => 'postPage.php');
                            } else {
                                $Buttons = array('details' => 'Ayrıntı', 'update_get' => 'Güncelle');
                                $ButtonsUrls = array('details' => 'postPage.php', 'update_get' => 'index.php');
                            }
                            $options2 = array(
                                //zorunlu parametreler
                                'tableHeaders' => array('Kullanıcı Adı', 'Adı', 'Soyadı', 'Ünvanı', 'Grubu', 'Giriş Türü', 'Resim', 'Aktif Mi?'),
                                //zorunlu olmayan parametreler
                                //        'id' => 'example2' , // optional
                                'order' => array(0, 'asc'),
                                'tableFooters' => array('Kullanıcı Adı', 'Adı', 'Soyadı', 'Ünvanı', 'Grubu', 'Giriş Türü', 'Resim', 'Aktif Mi?'), // optional
                                'filters' => array('text', 'text', 'text', 'text', 'text', 'text', 'text', 'text'),
                                //yerel parametreler
                                'tableData' => $listItems,
                                'processButtons' => array(
                                    'hasButton' => true,
                                    'buttonPostPage' => 'index.php',
                                    'buttons' => $Buttons,
                                    'buttonUrls' => $ButtonsUrls),
                                'buttons' => array("excel", "pdf")
                            );
                            try {
                                $dtableServer = new DataTable($options2);
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            }
                            ?>
                            <div class="col-md-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Sorgulama Sonucu</h3>
                                        <?php
                                        echo $dtableServer->get_data_table();
                                        echo $dtableServer->get_datatable_script();
                                        ?>     </div><!-- /.box-body -->
                                </div>
                            </div>
                            <?php
                        } else {
                            adminLTE_alert(true, "Kayıt Bulunamadı", "Sorgulamada kayıt bulunamadı. ", "warning");
                        }
                    }
                }
            }
            ?>
        </div>
    </section>
</div>
