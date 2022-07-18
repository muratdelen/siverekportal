
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
            <li class="active"><?php echo __("Rol İşlemleri") ?></li>
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
            else if (isset($_POST['update']) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
                if (in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    require_once 'guncelle.php';
                }
            } else {
                if (in_array(YT_INSERT, $sayfaIslemleriId)) {
                    echo '<div class="col-md-2" style="	margin-bottom: 10px;">
                                <a href="?new" class="btn bg-purple" >YENİ ROL EKLE</a>  
                        </div>';
                }
                if (in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    echo '<div class="col-md-2" style="	margin-bottom: 10px;">
                            <form class="form-horizontal" method="POST" action="">
                                <input class="btn btn-info" type="submit" id="btn_kayit_guncelle" name="update" value="ROL GÜNCELLE">
                            </form>  
                        </div>';
                }
                if (in_array(YT_QUERY, $sayfaIslemleriId)) {//eğer sorgula butonuna basıldı ise sor
                    ?>
                    <div  class="col-md-12 box box-primary" id="sorgulama_ekrani_div">
                        <form class="form-horizontal" method="POST" action="">
                            <div class="form-group form-group-sm"></div>   
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="secilen_rol">Rol Adı</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();
                                            $('#rol_aciklama').val($('#secilen_rol  option:selected').attr('title'));
                                            secilen_role_icin_sayfa_yetkileri_yukle();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="secilen_rol" name="secilen_rol">
                                        <option value=''>Seçiniz</option>
                                        <?php
                                        $rolSQL = "SELECT id, adi,aciklama FROM yt_rol ";
                                        try {
                                            $roller = $db->fetchAll($rolSQL);
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($roller);
                                        foreach ($roller as $rol) {
                                            $id_sifreli = mcrypt($rol->id, $_SESSION['key']);
                                            echo "<option value='$id_sifreli'  title='$rol->aciklama'";
                                            echo (isset($id_sifreli) ? (isset($_POST['secilen_rol']) ? ($_POST['secilen_rol'] == $id_sifreli ? 'selected' : null) : null) : null);
                                            echo ">$rol->adi</option>";
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="rol_aciklama">Rol Açıklaması</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="rol_aciklama" name="rol_aciklama" readonly value="<?php echo isset($_POST['rol_aciklama']) ? $_POST['rol_aciklama'] : ''; ?>" >
                                </div>
                            </div>                                                       
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" name="Sorgula" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-search"></span> <?= __("Yetki Detayı Göster") ?></button>
                            </div>
                            <div  class="col-md-12 box box-default"></div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="secilen_menu">Menuler</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();
                                            secilen_role_icin_sayfa_yetkileri_yukle();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="secilen_menu" name="secilen_menu">
                                        <option value=''>Seçiniz</option>
                                        <?php echo get_menu_items_option_html(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="roller">Sayfa Yetkileri </label>
                                <div class="col-sm-8">
                                    <select class = "form-control select2" id = "yetkiler" name="yetkiler[]" multiple = "multiple" data-placeholder = "Rol Seçiniz." style = "width: 100%;" onchange="$('.ui-tooltip').remove();">
                                        <option value=''>Seçiniz</option>
                                        <?php
                                        $rolesSQL = "SELECT id, adi, aciklama FROM yt_sayfa_islemleri WHERE aktif_mi ";
                                        try {
                                            $sayfa_yekileri = $db->fetchAll($rolesSQL);
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($sayfa_yekileri);
                                        foreach ($sayfa_yekileri as $sayfa_yetkisi) {
                                            $id_sifreli = mcrypt($sayfa_yetkisi->id, $_SESSION['key']);
                                            echo "<option value='$id_sifreli'  title='$sayfa_yetkisi->aciklama'>$sayfa_yetkisi->adi</option>";
                                        }
                                        ?>
                                    </select>
                                </div>  
                            </div>
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" name="YetkiGuncelle" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("YETKİLERİ GÜNCELLE") ?></button>
                            </div>
                        </form>
                    </div>
                    <?php
                    if (isset($_POST['YetkiGuncelle']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {
                        if ($_POST['secilen_rol'] == "") {
                            adminLTE_alert(false, "Rol Seçilmedi", "Lütfen Rol Seçiniz.", "warning");
                        } else if ($_POST['secilen_menu'] == "") {
                            adminLTE_alert(false, "Menu Seçilmedi", "Lütfen Menu Seçiniz.", "warning");
                        } else {
                            $result = 1;
                            $secilen_rol = mdecrypt($_POST['secilen_rol'], $_SESSION['key']);
                            $secilen_menu = mdecrypt($_POST['secilen_menu'], $_SESSION['key']);
                            $ItemsSQL = "DELETE FROM yt_rol_sayfa_yetkileri 
                                            WHERE aktif_mi AND yt_rol_sayfa_yetkileri.yt_rol_id = ? AND yt_rol_sayfa_yetkileri.yt_menu_id = ?";
                            try {
                                $roles = $GLOBALS['db']->fetchAll($ItemsSQL, array($secilen_rol, $secilen_menu));
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                $result = -1;
                            }
                            if (isset($_POST["yetkiler"])) {
                                foreach ($_POST["yetkiler"] as $secilen_sayfa_yetkileri) {
                                    $sayfa_islemleri_id = mdecrypt($secilen_sayfa_yetkileri, $_SESSION['key']);
                                    $data = array(
                                        'yt_rol_id' => $secilen_rol,
                                        'yt_menu_id' => $secilen_menu,
                                        'yt_sayfa_islemleri_id' => $sayfa_islemleri_id,
                                        'aktif_mi' => 1
                                    );
                                    try {
                                        $GLOBALS['db']->insert('yt_rol_sayfa_yetkileri', $data, null);
                                    } catch (Zend_Db_Exception $ex) {
                                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        $result = -1;
                                    }
                                }
                            }

                            if ($result == 1) {
                                adminLTE_alert(false, "Kaydetme İşlemi", "Başarılı ile kaydedildi", "success");
                            } else if ($result == -1) {
                                adminLTE_alert(false, "Kaydetme İşlemi", "Kaydedilmedi. <br>Veritabanı Hatası.", "danger");
                            }
                        }
                    } else if (isset($_POST['YetkiGuncelle'])) {
                        adminLTE_alert(false, "Yetki Sınırlaması", "Güncelleme Yetkiniz Bulunmamaktadır.", "warning");
                    }

                    if (isset($_POST['Sorgula']) || isset($_POST['YetkiGuncelle'])) {
                        $rol_id = mdecrypt($_POST['secilen_rol'], $_SESSION['key']);
                        $ItemsSQL = "SELECT
                                        yt_rol_sayfa_yetkileri.id,
                                        yt_menu.adi AS menuler,
                                        yt_sayfa_islemleri.adi AS yetkiler,
                                        yt_sayfa_islemleri.aciklama
                                        FROM
                                        yt_rol_sayfa_yetkileri
                                        INNER JOIN yt_menu ON yt_menu.id = yt_rol_sayfa_yetkileri.yt_menu_id
                                        INNER JOIN yt_sayfa_islemleri ON yt_sayfa_islemleri.id = yt_rol_sayfa_yetkileri.yt_sayfa_islemleri_id
                                        WHERE yt_rol_sayfa_yetkileri.aktif_mi AND yt_sayfa_islemleri.aktif_mi 
                                        AND yt_rol_sayfa_yetkileri.yt_rol_id = ? 
                                        ORDER BY yt_rol_sayfa_yetkileri.yt_menu_id";
                        try {
                            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $rol_id);
                            htmlspecialchar_array($listItems);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }

                        if ((isset($listItems)) && !empty($listItems)) {

                            $Buttons = array('details' => 'Ayrıntı', 'remove' => 'Sil');
                            $ButtonsUrls = array('details' => 'postPage.php', 'remove' => 'postPage.php');

                            $options2 = array(
                                //zorunlu parametreler
                                'tableHeaders' => array('Menuler', 'Yetkiler', 'Açıklama'),
                                //zorunlu olmayan parametreler
                                //        'id' => 'example2' , // optional
                                'order' => array(0, 'asc'),
                                'tableFooters' => array('Menuler', 'Yetkiler', 'Açıklama'),
                                'filters' => array('text', 'text', 'text'),
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

<script>
    $(document).ready(function () {
        $('#rol_ekleme_guncelleme').validate({
            rules: {
                secilen_rol: {required: true},
                //                secilen_menu: {required: true}
            },
            messages: {
                secilen_rol: {required: "Rol Seçiniz. "},
                //                secilen_menu: {required: "Menu Seçiniz. "}
            }
        });
    });

    //    function rolleri_yukle() {
    //        $('.ui-tooltip').remove();
    //        $('#kisi_adi_soyadi').val($('#username  option:selected').attr('title'))
    //        var formData = new Array();
    //        formData.push({name: 'post_type', value: 0}, {name: 'username', value: $("#username").val()});
    //        $.ajax({
    //            async: false,
    //            url: 'ajax.php',
    //            type: 'POST',
    //            data: formData,
    //            success: function (data) {
    //                var str_menu_option = $.parseJSON(data);
    //                $("#user_role").html(str_menu_option);
    //                $("#user_role").change();
    //            }
    //        });
    //
    //
    //    }
    function secilen_role_icin_sayfa_yetkileri_yukle() {
        $('.ui-tooltip').remove();
        $('#user_role_description').val($('#user_role  option:selected').attr('title'))
        var formData = new Array();
        formData.push({name: 'post_type', value: 0}, {name: 'secilen_rol', value: $("#secilen_rol").val()}, {name: 'secilen_menu', value: $("#secilen_menu").val()});
        $.ajax({
            async: false,
            url: 'ajax.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var str_menu_option = $.parseJSON(data);
                $("#yetkiler").html(str_menu_option);
                $("#yetkiler").change();
            }
        });
        //        var formData = new Array();
        //        formData.push({name: 'post_type', value: 2}, {name: 'username', value: $("#username").val()}, {name: 'user_role', value: $("#user_role").val()});
        //        $.ajax({
        //            async: false,
        //            url: 'ajax.php',
        //            type: 'POST',
        //            data: formData,
        //            success: function (data) {
        //                var str_menu_option = $.parseJSON(data);
        //                $("#user_role_proccess_except").html(str_menu_option);
        //                $("#user_role_proccess_except").change();
        //            }
        //        });

    }
    secilen_role_icin_sayfa_yetkileri_yukle();

</script>


