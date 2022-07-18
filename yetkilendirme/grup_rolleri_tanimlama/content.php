
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
            <li class="active"><?php echo __("Grup Rol Tanımlama İşlemleri") ?></li>
        </ol>
    </section>
    <section class="container-fluid">
        <div class="row">
            <?php
//yeni kayıt butonunana basıldı ise
            if (in_array(YT_QUERY, $sayfaIslemleriId)) {//eğer sorgula butonuna basıldı ise sor
                ?>
                <div  class="col-md-12 box box-primary" id="sorgulama_ekrani_div">
                    <form class="form-horizontal" id="grup_role_ekleme_silme" method="post" action="">
                        <div class="form-group form-group-sm">
                        </div>    
                        <div class="form-group form-group-sm btn-primary">
                            <label class="col-sm-2 control-label" for="secilen_grup">Grup Adı</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 select2-hidden-accessible" onchange="secilen_grup_icin_rol_yukle();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="secilen_grup" name="secilen_grup">
                                    <option value=''>Seçiniz</option>
                                    <?php
                                    $grupsSQL = "SELECT id, adi,aciklama FROM yt_grup WHERE NOT(id=1 OR id=2 OR id=3) ";
                                    try {
                                        $grups = $db->fetchAll($grupsSQL);
                                    } catch (Zend_Db_Exception $ex) {
                                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                    }
                                    htmlspecialchar_obj($grups);
                                    foreach ($grups as $grup) {
                                        $id_sifreli = mcrypt($grup->id, $_SESSION['key']);
                                        echo "<option value='$id_sifreli'  title='$grup->aciklama'";
                                        echo (isset($grup->id) ? (isset($_POST['secilen_grup']) ? ($_POST['secilen_grup'] == $id_sifreli ? 'selected' : null) : null) : null);
                                        echo ">$grup->adi</option>";
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>                       
                        <div class="col-sm-12 form-group form-group-sm">
                            <button type="submit" name="Sorgula" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-search"></span> <?= __("Yetki Detayı Göster") ?></button>
                        </div>                        
                        <div class="col-md-12 box box-default form-group form-group-sm btn-primary">
                            <label class="col-sm-2 control-label" for="gruba_tanimlanan_roller">Eklenen Grup Rolleri </label>
                            <div class="col-sm-8">
                                <select class = "form-control select2" id = "gruba_tanimlanan_roller" name="gruba_tanimlanan_roller[]" multiple = "multiple" data-placeholder = "Rol Seçiniz." style = "width: 100%;" onchange="$('.ui-tooltip').remove();">
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
                            <button type="submit" name="GrupRolGuncelle" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("GRUP ROLÜ GÜNCELLE") ?></button>
                        </div>                     

                    </form>
                </div> 
                <?php
                if (isset($_POST['GrupRolGuncelle']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    if ($_POST['secilen_grup'] == "") {
                        adminLTE_alert(true, "Grup Adı", "Lütfen Grup Seçiniz.", "warning");
                    } else {
                        $secilen_grup = mdecrypt($_POST['secilen_grup'], $_SESSION['key']);
                        $result = 1;
                        $ItemsSQL = "DELETE FROM yt_grup_rolleri WHERE aktif_mi AND yt_grup_rolleri.yt_grup_id = ? ";
                        try {
                            $roller = $GLOBALS['db']->fetchAll($ItemsSQL, $secilen_grup);
                            log::islem_aciklamasi_kaydi("Grup Rolleri Tanımlama", "Grup Rolleri Güncelleme", YT_UPDATE);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            $result = -1;
                        }
                        if (isset($_POST["gruba_tanimlanan_roller"])) {
                            foreach ($_POST["gruba_tanimlanan_roller"] as $secilen_rol) {
                                $data = array(
                                    'yt_grup_id' => $secilen_grup,
                                    'yt_rol_id' => $secilen_rol,
                                    'aktif_mi' => 1
                                );
                                try {
                                    $GLOBALS['db']->insert('yt_grup_rolleri', $data, null);
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

                
                if ((isset($_POST['Sorgula']) || isset($_POST['GrupRolGuncelle'])) && in_array(YT_QUERY, $sayfaIslemleriId)) {
                    $secilen_grup = mdecrypt($_POST['secilen_grup'], $_SESSION['key']);
                    //SONUÇ OLARAK YETKİLER
                    $ItemsSQL = "CALL grup_yetkilerini_goster ( ? )";
                    try {
                        $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $secilen_grup);
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
                   

                }
            }
            ?>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        $('#grup_role_ekleme_silme').validate({
            rules: {
                secilen_grup: {required: true}
            },
            messages: {
                secilen_grup: {required: "Grup Adı Girilmelidir. "}
            }
        });
    });

    function secilen_grup_icin_rol_yukle() {
        $('.ui-tooltip').remove();
        var formData = new Array();
        formData.push({name: 'post_type', value: 0}, {name: 'secilen_grup', value: $("#secilen_grup").val()});
        $.ajax({
            async: false,
            url: 'ajax.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var str_menu_option = $.parseJSON(data);
                $("#gruba_tanimlanan_roller").html(str_menu_option);
                $("#gruba_tanimlanan_roller").change();
            }
        });

    }
    secilen_grup_icin_rol_yukle();
</script>


