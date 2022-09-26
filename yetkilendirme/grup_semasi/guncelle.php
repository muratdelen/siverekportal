<?php
if (isset($_POST['update']) && $_POST['update'] != '') {

    $update_id = mdecrypt($_POST['update'], $_SESSION['key']);
    $ItemsSQL = "SELECT yt_grup.adi, yt_grup.aciklama, yt_grup_semasi.alt_grup_id, yt_grup_semasi.aktif_mi FROM yt_grup_semasi
                    INNER JOIN yt_grup ON yt_grup.id = yt_grup_semasi.yt_grup_id
                    WHERE NOT(yt_grup.id=1 OR yt_grup.id=2 OR yt_grup.id=3) AND yt_grup_semasi.id = ? ";
    $grup_bilgisi = $GLOBALS['db']->fetchRow($ItemsSQL, $update_id);
    htmlspecialchar_array($grup_bilgisi);
//var_dump($grup_bilgisi);die();
} else {
    adminLTE_redirect(true, "Güncelleme Yetkiniz Yoktur.", "warning", 100000, BASE_URL . "yetkilendirme/grup_semasi/index.php");
}
?>
<div class="col-md-12 box box-primary" id="guncelleme_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_alt_grup_guncelle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi">Ana Grup Adı</label>
            <div class="col-sm-8">
                <input type="text" id="username" name="adi" value="<?= $grup_bilgisi->adi ?>" readonly class="form-control">
            </div>  
        </div>        
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="aciklama">Ana Grup Tanımlaması</label>
            <div class="col-sm-8">
                <input type="text" id="aciklama" name="aciklama" value="<?= $grup_bilgisi->aciklama ?>" readonly class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="alt_grup">Alt Grup Adı</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="alt_grup" name="alt_grup">
                    <option value=''>Seçiniz</option>
                    <?php
                    $grupSQL = "SELECT id, adi,aciklama FROM yt_grup WHERE NOT(id=1 OR id=2 OR id=3) ";
                    try {
                        $gruplar = $db->fetchAll($grupSQL);
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_obj($gruplar);
                    foreach ($gruplar as $grup) {
                        $id_sifreli = mcrypt($grup->id, $_SESSION['key']);
                        echo "<option value='$id_sifreli'  title='$grup->aciklama'";
                        echo (isset($grup->id) ? (isset($grup_bilgisi->alt_grup_id) ? ($grup->id == $grup_bilgisi->alt_grup_id ? 'selected' : null) : null) : null);
                        echo ">$grup->adi</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="alt_grup_aktif_mi">Kullanıcı Aktif Mi?</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="alt_grup_aktif_mi" onchange="$('.ui-tooltip').remove();"  name = "alt_grup_aktif_mi">
                    <option value="0" <?= $grup_bilgisi->aktif_mi == 0 ? 'selected' : '' ?> >Pasif</option>
                    <option value="1" <?= $grup_bilgisi->aktif_mi == 1 ? 'selected' : '' ?> >Aktif</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="update" value="<?= $_POST['update']; ?>" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("Alt Grup Güncelle") ?></button>
        </div> 
    </form>
</div>


</div>
</section>

<script>
    $(document).ready(function () {
        $('#form_alt_grup_guncelle').validate({
            rules: {
                alt_gup: {required: true},
                alt_grup_aktif_mi: {required: true}
            },
            messages: {
                username: {required: "Alt Grup Seçiniz. "},
                name: {required: "Aktif veya Pasif Seçiniz. "}
            }
        });
    });
</script>
</script>