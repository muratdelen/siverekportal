
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
            <li class="active"><?php echo __("Kullanıcı Rol Tanımlama İşlemleri") ?></li>
        </ol>
    </section>
    <section class="container-fluid">
        <div class="row">
            <?php
//yeni kayıt butonunana basıldı ise
            if (in_array(YT_QUERY, $sayfaIslemleriId)) {//eğer sorgula butonuna basıldı ise sor
                ?>
                <div  class="col-md-12 box box-primary" id="sorgulama_ekrani_div">
                    <form class="form-horizontal" id="kullanici_role_ekleme_silme" method="post" action="">
                        <div class="form-group form-group-sm">
                        </div>    
                        <div class="form-group form-group-sm">
                            <label class="col-sm-2 control-label" for="secilen_kullanici">Kullanıcı Adı</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();
                                        $('#kisi_adi_soyadi').val($('#secilen_kullanici  option:selected').attr('title'));
                                        secilen_kullanici_icin_rol_yukle();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="secilen_kullanici" name="secilen_kullanici">
                                    <option value=''>Seçiniz</option>
                                    <?php
                                    $kisilerSQL = "SELECT yt_kullanici.id, yt_kullanici.kullanici_adi, yt_kullanici.adi, yt_kullanici.soyadi FROM yt_kullanici WHERE NOT(yt_kullanici.yt_grup_id=1 OR yt_kullanici.yt_grup_id=2)";
                                    try {
                                        $kisiler = $db->fetchAll($kisilerSQL);
                                    } catch (Zend_Db_Exception $ex) {
                                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                    }
                                    htmlspecialchar_obj($kisiler);
                                    foreach ($kisiler as $kisi) {
                                        $id_sifreli = mcrypt($kisi->id, $_SESSION['key']);
                                        echo "<option value='$id_sifreli'  title='$kisi->adi $kisi->soyadi'";
                                        echo (isset($id_sifreli) ? (isset($_POST['secilen_kullanici']) ? ($_POST['secilen_kullanici'] == $id_sifreli ? 'selected' : null) : null) : null);
                                        echo ">$kisi->kullanici_adi</option>";
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div> 
                        <div class="form-group form-group-sm">
                            <label class="col-sm-2 control-label" for="kisi_adi_soyadi">Kullanıcı Adı Soyadı</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="kisi_adi_soyadi" name="kisi_adi_soyadi" readonly value="<?php echo isset($_POST['kisi_adi_soyadi']) ? $_POST['kisi_adi_soyadi'] : ''; ?>" >
                            </div>                                     
                        </div>                       
                        <div class="col-sm-12 form-group form-group-sm">
                            <button type="submit" name="Sorgula" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-search"></span> <?= __("Yetki Detayı Göster") ?></button>
                        </div>                        
                        <div  class="col-md-12 box box-default"></div>
                        <div class="form-group form-group-sm">
                            <label class="col-sm-2 control-label" for="kullaniciya_tanimlanan_roller">Eklenen Kullanıcı Rolleri </label>
                            <div class="col-sm-8">
                                <select class = "form-control select2" id = "kullaniciya_tanimlanan_roller" name="kullaniciya_tanimlanan_roller[]" multiple = "multiple" data-placeholder = "Rol Seçiniz." style = "width: 100%;" onchange="$('.ui-tooltip').remove();">
                                    <option value=''>Seçiniz</option>
                                    <?php
                                    $rolSQL = "SELECT yt_rol.id, yt_rol.adi, yt_rol.aciklama FROM yt_rol WHERE aktif_mi ";
                                    try {
                                        $roller = $db->fetchAll($rolSQL);
                                    } catch (Zend_Db_Exception $ex) {
                                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                    }
                                    htmlspecialchar_obj($roller);
                                    foreach ($roller as $rol) {
                                        $id_sifreli = mcrypt($rol->id, $_SESSION['key']);
                                        echo "<option value='$id_sifreli'  title='$rol->aciklama'>$rol->adi</option>";
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>                             
                        <div class="col-sm-12 form-group form-group-sm">
                            <button type="submit" name="KullaniciRolGuncelle" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("KULLANICI ROLÜ GÜNCELLE") ?></button>
                        </div>                     
                        <div  class="col-md-12 box box-default"></div>
                        <div class="form-group form-group-sm">
                            <label class="col-sm-2 control-label" for="secilen_menu">Menuler</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();
                                        secilen_kullanici_icin_sayfa_yetkileri_yukle();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="secilen_menu" name="secilen_menu">
                                    <option value=''>Seçiniz</option>
                                    <?php echo get_menu_items_option_html(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label class="col-sm-2 control-label" for="kullanici_yetki_ekle">Eklenecek Kullanıcı Yetki İşlemleri </label>
                            <div class="col-sm-8">
                                <select class = "form-control select2" id = "kullanici_yetki_ekle" name="kullanici_yetki_ekle[]" multiple = "multiple" data-placeholder = "Yetki Seçiniz." style = "width: 100%;" onchange="$('.ui-tooltip').remove();">
                                    <option value=''>Seçiniz</option>
                                    <?php
                                    $rolSQL = "SELECT yt_sayfa_islemleri.id, yt_sayfa_islemleri.adi, yt_sayfa_islemleri.aciklama FROM yt_sayfa_islemleri WHERE aktif_mi ";
                                    try {
                                        $roller = $db->fetchAll($rolSQL);
                                    } catch (Zend_Db_Exception $ex) {
                                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                    }
                                    htmlspecialchar_obj($roller);
                                    foreach ($roller as $rol) {
                                        $id_sifreli = mcrypt($rol->id, $_SESSION['key']);
                                        echo "<option value='$id_sifreli'  title='$rol->aciklama'>$rol->adi</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label class="col-sm-2 control-label" for="kullanici_yetki_cikart">Çıkartılacak Kullanıcı Yetki İşlemleri</label>
                            <div class="col-sm-8">
                                <select class = "form-control select2" id = "kullanici_yetki_cikart" name="kullanici_yetki_cikart[]" multiple = "multiple" data-placeholder = "Yetki Seçiniz." style = "width: 100%;" onchange="$('.ui-tooltip').remove();">
                                    <option value=''>Seçiniz</option>
                                    <?php
                                    $rolSQL = "SELECT yt_sayfa_islemleri.id, yt_sayfa_islemleri.adi, yt_sayfa_islemleri.aciklama FROM yt_sayfa_islemleri WHERE aktif_mi ";
                                    try {
                                        $roller = $db->fetchAll($rolSQL);
                                    } catch (Zend_Db_Exception $ex) {
                                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                    }
                                    htmlspecialchar_obj($roller);
                                    foreach ($roller as $rol) {
                                        $id_sifreli = mcrypt($rol->id, $_SESSION['key']);
                                        echo "<option value='$id_sifreli'  title='$rol->aciklama'>$rol->adi</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 form-group form-group-sm">
                            <button type="submit" name="YetkiEkleCikart" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("KULLANICI YETKİLERİ GÜNCELLE") ?></button>
                        </div>                        
                    </form>
                </div> 
                <?php
                if (isset($_POST['KullaniciRolGuncelle']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    if ($_POST['secilen_kullanici'] == "") {
                        adminLTE_alert(true, "Kullanıcı Adı", "Lütfen Kullanıcı Seçiniz.", "warning");
                    } else {
                        $secilen_kullanici = mdecrypt($_POST['secilen_kullanici'], $_SESSION['key']);
                        $result = 1;
                        $ItemsSQL = "DELETE FROM yt_kullanici_rolleri WHERE aktif_mi AND yt_kullanici_rolleri.yt_kullanici_id = ? ";
                        try {
                            log::islem_aciklamasi_kaydi("Kullanıcı Rolleri Tanımlama", "Kullanıcı Rolleri Güncelleme", YT_UPDATE);
                            $roller = $GLOBALS['db']->fetchAll($ItemsSQL, $secilen_kullanici);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            $result = -1;
                        }
                        if (isset($_POST["kullaniciya_tanimlanan_roller"])) {
                            foreach ($_POST["kullaniciya_tanimlanan_roller"] as $secilen_rol) {
                                $data = array(
                                    'yt_kullanici_id' => $secilen_kullanici,
                                    'yt_rol_id' => $secilen_rol,
                                    'aktif_mi' => 1
                                );
                                try {
                                    $GLOBALS['db']->insert('yt_kullanici_rolleri', $data, null);
                                } catch (Zend_Db_Exception $ex) {
                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                    $result = -1;
                                }
                            }
                        }

                        if ($result == 1) {
                            adminLTE_alert(false, "Kaydetme İşlemi", "Başarılı ile kaydedildi", "success");
                        } else {
                            adminLTE_alert(false, "Kaydetme İşlemi", "Kaydedilmedi. <br>Veritabanı Hatası.", "danger");
                        }
                    }
                }

                //BURADAN KULLANICIYA YETKİ VERİLİR VEYA ÇIKARILIR
                if (isset($_POST['YetkiEkleCikart']) && in_array(YT_INSERT, $sayfaIslemleriId)) {
                    if ($_POST['secilen_menu'] == NULL) {
                        adminLTE_alert(false, "Menu Seçilmedi", "Lütfen Menu Seçiniz.", "warning");
                    } else {
                        $secilen_kullanici = mdecrypt($_POST['secilen_kullanici'], $_SESSION['key']);
                        $secilen_menu = mdecrypt($_POST['secilen_menu'], $_SESSION['key']);
                        $result = 1;
                        $ItemsSQL = "DELETE FROM yt_kullanici_sayfa_yetkileri WHERE aktif_mi AND yt_kullanici_sayfa_yetkileri.yt_kullanici_id = ?  AND yt_kullanici_sayfa_yetkileri.yt_menu_id = ? ";
                        try {
                            log::islem_aciklamasi_kaydi("Kullanıcı Sayfa Yetkileri Tanımlama", "Kullanıcı Sayfa Yetkileri Ekleme - Çıkartma", YT_UPDATE);
                            $roller = $GLOBALS['db']->fetchAll($ItemsSQL, array($secilen_kullanici, $secilen_menu));
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            $result = -1;
                        }
                        if (isset($_POST["kullanici_yetki_ekle"])) {
                            foreach ($_POST["kullanici_yetki_ekle"] as $secilen_sayfa_islem_yetkisi) {
                                $data = array(
                                    'yt_kullanici_id' => $secilen_kullanici,
                                    'yt_menu_id' => $secilen_menu,
                                    'yt_sayfa_islemleri_id' => $secilen_sayfa_islem_yetkisi,
                                    'eklensin_mi' => 1,
                                    'aktif_mi' => 1
                                );
                                try {
                                    $GLOBALS['db']->insert('yt_kullanici_sayfa_yetkileri', $data, null);
                                } catch (Zend_Db_Exception $ex) {
                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                    $result = -1;
                                }
                            }
                        }
                        if (isset($_POST["kullanici_yetki_cikart"])) {
                            foreach ($_POST["kullanici_yetki_cikart"] as $secilen_sayfa_islem_yetkisi) {
                                $data = array(
                                    'yt_kullanici_id' => $secilen_kullanici,
                                    'yt_menu_id' => $secilen_menu,
                                    'yt_sayfa_islemleri_id' => $secilen_sayfa_islem_yetkisi,
                                    'eklensin_mi' => 0,
                                    'aktif_mi' => 1
                                );
                                try {
                                    $GLOBALS['db']->insert('yt_kullanici_sayfa_yetkileri', $data, null);
                                } catch (Zend_Db_Exception $ex) {
                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                    $result = -1;
                                }
                            }
                        }
                        if ($result == 1) {
                            adminLTE_alert(false, "Kaydetme İşlemi", "Başarılı ile kaydedildi", "success");
                        } else {
                            adminLTE_alert(false, "Kaydetme İşlemi", "Kaydedilmedi. <br>Veritabanı Hatası.", "danger");
                        }
                    }
                }
                if ((isset($_POST['Sorgula']) || isset($_POST['KullaniciRolGuncelle']) || isset($_POST['YetkiEkleCikart'])) && in_array(YT_QUERY, $sayfaIslemleriId)) {
                    $secilen_kullanici = mdecrypt($_POST['secilen_kullanici'], $_SESSION['key']);
                    //SONUÇ OLARAK YETKİLER
                    $ItemsSQL = "CALL kullanici_yetkilerini_goster ( ? )";
                    try {
                        $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $secilen_kullanici);
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_array($listItems);

                    if ((isset($listItems)) && !empty($listItems)) {

//                        $Buttons = array('details' => 'Ayrıntı', 'remove' => 'Sil');
//                        $ButtonsUrls = array('details' => 'postPage.php', 'remove' => 'postPage.php');

                        $options2 = array(
                            //zorunlu parametreler
                            'id' => "sonuc_table",
                            'tableHeaders' => array('Menuler', 'Yetkiler', 'Açıklama'),
                            //zorunlu olmayan parametreler
                            //        'id' => 'example2' , // optional
                            'order' => array(0, 'asc'),
                            'tableFooters' => array('Menuler', 'Yetkiler', 'Açıklama'),
                            'filters' => array('text', 'text', 'text'),
//yerel parametreler
                            'tableData' => $listItems,
//                            'processButtons' => array(
//                                'hasButton' => true,
//                                'buttonPostPage' => 'index.php',
//                                'buttons' => $Buttons,
//                                'buttonUrls' => $ButtonsUrls),
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
                                    <h3 class="box-title">TANIMLANAN YETKİLER</h3>
                                    <?php
                                    echo $dtableServer->get_data_table();
                                    echo $dtableServer->get_datatable_script();
                                    ?>     </div><!-- /.box-body -->
                            </div>
                        </div>
                        <?php
                    } else {
                        adminLTE_alert(true, "Yetki", "Yetkisi Bulunmamaktadır. ", "warning");
                    }
//---SONUÇ OLARAK YETKİLER
//
                    //EKLENEN YETKİLER
                    $ItemsSQL = "SELECT yt_kullanici_sayfa_yetkileri.id, yt_menu.adi AS menuler, yt_sayfa_islemleri.adi AS yetkiler, yt_sayfa_islemleri.aciklama
                                    FROM yt_kullanici_sayfa_yetkileri
                                    INNER JOIN yt_menu ON yt_menu.id = yt_kullanici_sayfa_yetkileri.yt_menu_id
                                    INNER JOIN yt_sayfa_islemleri ON yt_sayfa_islemleri.id = yt_kullanici_sayfa_yetkileri.yt_sayfa_islemleri_id
                                    WHERE yt_kullanici_sayfa_yetkileri.aktif_mi
                                    AND yt_kullanici_sayfa_yetkileri.yt_kullanici_id = ?
                                    AND yt_kullanici_sayfa_yetkileri.eklensin_mi 
                                    ORDER BY yt_menu.order_id;";
                    try {
                        $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $secilen_kullanici);
                        htmlspecialchar_array($listItems);
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }

                    if ((isset($listItems)) && !empty($listItems)) {
                        $options = array(
                            //zorunlu parametreler
                            'id' => "eklenenler_table",
                            'tableHeaders' => array('Menuler', 'Yetkiler', 'Açıklama'),
                            'tableFooters' => array('Menuler', 'Yetkiler', 'Açıklama'),
                            'filters' => array('text', 'text', 'text'),
                            'order' => array(0, 'asc'),
                            'tableData' => $listItems
//                                ,
//                            'buttons' => array("excel", "pdf")
                        );
                        try {
                            $dtableServer = new DataTable($options);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
                        ?>
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">EKLENENLER YETKİLER</h3>
                                    <?php
                                    echo $dtableServer->get_data_table();
                                    echo $dtableServer->get_datatable_script();
                                    ?>     </div><!-- /.box-body -->
                            </div>
                        </div>
                        <?php
                    }
                    //ÇIKARILAN YETKİLER
                    $ItemsSQL = "SELECT yt_kullanici_sayfa_yetkileri.id, yt_menu.adi AS menuler, yt_sayfa_islemleri.adi AS yetkiler, yt_sayfa_islemleri.aciklama
                                    FROM yt_kullanici_sayfa_yetkileri
                                    INNER JOIN yt_menu ON yt_menu.id = yt_kullanici_sayfa_yetkileri.yt_menu_id
                                    INNER JOIN yt_sayfa_islemleri ON yt_sayfa_islemleri.id = yt_kullanici_sayfa_yetkileri.yt_sayfa_islemleri_id
                                    WHERE yt_kullanici_sayfa_yetkileri.aktif_mi
                                    AND yt_kullanici_sayfa_yetkileri.yt_kullanici_id = ?
                                    AND NOT yt_kullanici_sayfa_yetkileri.eklensin_mi 
                                    ORDER BY yt_menu.order_id;";
                    try {
                        $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $secilen_kullanici);
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_array($listItems);

                    if ((isset($listItems)) && !empty($listItems)) {
                        $options = array(
                            //zorunlu parametreler
                            'id' => "cikarilanlar_table",
                            'tableHeaders' => array('Menuler', 'Yetkiler', 'Açıklama'),
                            'tableFooters' => array('Menuler', 'Yetkiler', 'Açıklama'),
                            'filters' => array('text', 'text', 'text'),
                            'order' => array(0, 'asc'),
                            'tableData' => $listItems
//                                ,
//                            'buttons' => array("excel", "pdf")
                        );
                        try {
                            $dtableServer = new DataTable($options);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
                        ?>
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">ÇIKARILAN YETKİLER</h3>
                                    <?php
                                    echo $dtableServer->get_data_table();
                                    echo $dtableServer->get_datatable_script();
                                    ?>     </div><!-- /.box-body -->
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        $('#kullanici_role_ekleme_silme').validate({
            rules: {
                secilen_kullanici: {required: true}
            },
            messages: {
                secilen_kullanici: {required: "Kullanıcı Adı Girilmelidir. "}
            }
        });
    });

    function secilen_kullanici_icin_rol_yukle() {
        $('.ui-tooltip').remove();
        $('#user_role_description').val($('#user_role  option:selected').attr('title'))
        var formData = new Array();
        formData.push({name: 'post_type', value: 0}, {name: 'secilen_kullanici', value: $("#secilen_kullanici").val()});
        $.ajax({
            async: false,
            url: 'ajax.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var str_menu_option = $.parseJSON(data);
                $("#kullaniciya_tanimlanan_roller").html(str_menu_option);
                $("#kullaniciya_tanimlanan_roller").change();
            }
        });

    }
    secilen_kullanici_icin_rol_yukle();
    function secilen_kullanici_icin_sayfa_yetkileri_yukle() {
        $('.ui-tooltip').remove();
        var formData = new Array();
        formData.push({name: 'post_type', value: 1}, {name: 'secilen_kullanici', value: $("#secilen_kullanici").val()}, {name: 'secilen_menu', value: $("#secilen_menu").val()});
        $.ajax({
            async: false,
            url: 'ajax.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var str_menu_option = $.parseJSON(data);
                $("#kullanici_yetki_ekle").html(str_menu_option);
                $("#kullanici_yetki_ekle").change();
            }
        });
        formData.push({name: 'post_type', value: 2}, {name: 'secilen_kullanici', value: $("#secilen_kullanici").val()}, {name: 'secilen_menu', value: $("#secilen_menu").val()});
        $.ajax({
            async: false,
            url: 'ajax.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var str_menu_option = $.parseJSON(data);
                $("#kullanici_yetki_cikart").html(str_menu_option);
                $("#kullanici_yetki_cikart").change();
            }
        });

    }
    secilen_kullanici_icin_sayfa_yetkileri_yukle();
</script>


