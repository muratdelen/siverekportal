
<div class="col-md-12 box box-primary" id="yeni_kayit_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_alt_grup_ekle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="grup">Ana Grup Adı</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="grup" name="grup">
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
                        echo (isset($grup->id) ? (isset($_POST['grup']) ? ($_POST['grup'] == $id_sifreli ? 'selected' : null) : null) : null);
                        echo ">$grup->adi</option>";
                    }
                    ?>
                </select>
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
                        echo (isset($grup->id) ? (isset($_POST['grup']) ? ($_POST['grup'] == $id_sifreli ? 'selected' : null) : null) : null);
                        echo ">$grup->adi</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="insert" value="Kaydet" class="btn bg-olive btn-block"><span class="fa fa-floppy-o"></span> <?= __("Alt Grup Kaydet") ?></button>
        </div> 
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#form_alt_grup_ekle').validate({
            rules: {
                grup: {required: true},
                alt_grup: {required: true}
            },
            messages: {
                grup: {required: "Ana Grup Seçilmelidir."},
                alt_grup: {required: "Alt Grup Seçilmelidir."}
            }
        });
    });
</script>