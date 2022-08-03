<?php
if (isset($_POST['update']) && $_POST['update'] != '') {
    $update_id = mdecrypt($_POST['update'], $_SESSION['key']);
    try {
        $SQL_cumlesi = "SELECT id, s_ruhsat_bilgileri_id, adi_soyadi, ada_parsel, blok_no FROM s_asansor_uygulama_projeleri WHERE id = ?";
        $ruhsat_asansor_bilgisi = $GLOBALS['db']->fetchRow($SQL_cumlesi, $update_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
//    var_dump($update_id, $ruhsat_asansor_bilgisi);
//die();
} else {
    adminLTE_redirect(true, "Güncelleme Yetkiniz Yoktur.", "Güncelleme Yetkiniz Yoktur.", "warning", 100000, BASE_URL . "ruhsat/index.php");
}
?>
<div class="col-md-12 box box-primary" id="guncelleme_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_ruhsat_guncelle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat">Ruhsat No</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat" name="ruhsat">
                    <option value=''>Ruhsat Seçiniz</option>
                    <?php
                    try {
                        $ruhsatlar = $db->fetchAll("SELECT id, ruhsat_no, adi_soyadi FROM s_ruhsat_bilgileri WHERE aktif_mi AND NOT ISNULL(ruhsat_no) 
AND id IN (SELECT s_ruhsat_bilgileri_id FROM s_asansor_uygulama_projeleri) 
ORDER BY ruhsat_no DESC");
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_obj($ruhsatlar);
                    foreach ($ruhsatlar as $ruhsat) {
                        $id_sifreli = mcrypt($ruhsat->id, $_SESSION['key']);
                        echo "<option value='$id_sifreli' title='$ruhsat->adi_soyadi' ";
                        echo ($ruhsat->id == $ruhsat_asansor_bilgisi->s_ruhsat_bilgileri_id ? 'selected' : null) ;
                        echo ">$ruhsat->ruhsat_no</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi_soyadi">Ad Soyad</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="adi_soyadi" name="adi_soyadi" value="<?= $ruhsat_asansor_bilgisi->adi_soyadi ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ada_parsel">Ada Parsel</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ada_parsel" name="ada_parsel" value="<?= $ruhsat_asansor_bilgisi->ada_parsel ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="blok_no">Blok No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="blok_no" name="blok_no" value="<?= $ruhsat_asansor_bilgisi->blok_no ?>" >
            </div>
        </div>
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="update" value="<?= $_POST['update']; ?>" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> Asansör Bilgisini Güncelle</button>
        </div>         
    </form>
</div>


</div>
</section>

<script>

    $(document).ready(function () {
        $('#form_ruhsat_guncelle').validate({
            rules: {
                ruhsat: {required: true},
                adi_soyadi: {required: true}
            },
            messages: {
                ruhsat: {required: "Ruhsat no seçiniz. "},
                adi_soyadi: {required: "Ruhsat sahibi giriniz. "}
            }
        });
    });
</script>