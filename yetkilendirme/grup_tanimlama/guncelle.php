<?php
if (isset($_POST['update']) && $_POST['update'] != '') {

    $update_id = mdecrypt($_POST['update'], $_SESSION['key']);
    $ItemsSQL = "SELECT id,adi, aciklama, aktif_mi FROM yt_grup WHERE NOT(id=1 OR id=2 OR id=3) AND id = ? ";
    $grup_bilgisi = $GLOBALS['db']->fetchRow($ItemsSQL, $update_id);
    htmlspecialchar_array($grup_bilgisi);
//var_dump($grup_bilgisi);die();
} else {
    adminLTE_redirect(true, "Güncelleme Yetkiniz Yoktur.", "warning", 100000, BASE_URL . "yetkilendirme/grup_tanimlama/index.php");
}
?>
<div class="col-md-12 box box-primary" id="guncelleme_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_grup_guncelle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-3 control-label" for="adi">Grup Adı</label>
            <div class="col-sm-7">
                <input type="text" id="username" name="adi" value="<?= $grup_bilgisi->adi ?>" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>        
        <div class="form-group form-group-sm">
            <label class="col-sm-3 control-label" for="aciklama">Tanımlaması</label>
            <div class="col-sm-7">
                <input type="text" id="aciklama" name="aciklama" value="<?= $grup_bilgisi->aciklama ?>" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-3 control-label" for="grup_aktif_mi">Grup Aktif Mi?</label>
            <div class="col-sm-7">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="grup_aktif_mi" onchange="$('.ui-tooltip').remove();"  name = "grup_aktif_mi">
                    <option value="0" <?= $grup_bilgisi->aktif_mi == 0 ? 'selected' : '' ?> >Pasif</option>
                    <option value="1" <?= $grup_bilgisi->aktif_mi == 1 ? 'selected' : '' ?> >Aktif</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="update" value="<?= $_POST['update']; ?>" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("Grup Güncelle") ?></button>
        </div>
    </form>
</div>


</div>
</section>

<script>
    $(document).ready(function () {
        $('#form_grup_guncelle').validate({
            rules: {
                username: {required: true},
                name: {required: true},
                surname: {required: true},
//                grup_aktif_mi: {required: true},
                password: {minlength: 4},
                groups_id: {required: true}
            },
            messages: {
                username: {required: "Grup Adı Girilmelidir. "},
                name: {required: "Soyadı Girilmelidir. "},
                surname: {required: "Soyadı Girilmelidir. "},
//                grup_aktif_mi: {required: "Aktif veya Pasif Seçilmelidir. "},
                groups_id: {required: "Grupnı Grubunu Seçiniz. "}
            }
        });
    });
</script>
</script>