<div class="col-md-12 box box-primary" id="ekle_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_ruhsat_ekle" action="postPage.php">
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
ORDER BY id DESC");
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_obj($ruhsatlar);
                    foreach ($ruhsatlar as $ruhsat) {
                        $id_sifreli = mcrypt($ruhsat->id, $_SESSION['key']);
                        echo "<option value='$id_sifreli' title='$ruhsat->adi_soyadi' ";
                        echo ">$ruhsat->ruhsat_no</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi_soyadi">Ad Soyad</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="adi_soyadi" name="adi_soyadi" value="" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ada_parsel">Ada Parsel</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ada_parsel" name="ada_parsel" value="" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="blok_no">Blok No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="blok_no" name="blok_no" value="" >
            </div>
        </div>
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="insert" value="" class="btn bg-olive btn-block"><span class="fa fa-floppy-o"></span>Asansör Bilgisi Ekle</button>
        </div> 
    </form>
</div>

<script>

    $(document).ready(function () {
        $('#form_ruhsat_ekle').validate({
            rules: {
                ruhsat_no: {required: true},
                adi_soyadi: {required: true}
            },
            messages: {
                ruhsat_no: {required: "Ruhsat no giriniz. "},
                adi_soyadi: {required: "Ruhsat sahibi giriniz. "}
            }
        });
    });
</script>