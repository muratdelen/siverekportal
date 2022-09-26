
<div class="col-md-12 box box-primary" id="yeni_kayit_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_kullanici_diger_grup_ekle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="kullanici_adi">Kullanıcı Adı</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();
                        $('#kisi_adi_soyadi').val($('#kullanici_adi  option:selected').attr('title'))" style="width: 100%;" tabindex="-1" aria-hidden="true" id="kullanici_adi" name="kullanici_adi">
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
                        echo (isset($id_sifreli) ? (isset($_POST['kullanici_adi']) ? ($_POST['kullanici_adi'] == $id_sifreli ? 'selected' : null) : null) : null);
                        echo ">$kisi->kullanici_adi</option>";
                    }
                    ?>
                </select>
            </div> 
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="kisi_adi_soyadi">Kullanıcı Adı Soyadı</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="kisi_adi_soyadi" name="kisi_adi_soyadi" value="<?php echo isset($_POST['kisi_adi_soyadi']) ? $_POST['kisi_adi_soyadi'] : ''; ?>" readonly >
            </div>
        </div>  
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="grup_id">Grubu</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="$('.ui-tooltip').remove();" id="grup_id" name = "grup_id">
                    <option value="">Seçiniz</option>
                    <?php
                    $grupSQL = "SELECT yt_grup.id, yt_grup.adi, yt_grup.aciklama FROM yt_grup WHERE NOT( yt_grup.id = 1 OR yt_grup.id = 2)";
                    try {
                        $gruplar = $db->fetchAll($grupSQL);
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_obj($gruplar);
                    foreach ($gruplar as $grup) {
                        $id_sifreli = mcrypt($grup->id, $_SESSION['key']);
                        echo "<option value='$id_sifreli' title='$grup->aciklama' >$grup->adi</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="insert" value="Kaydet" class="btn bg-olive btn-block"><span class="fa fa-floppy-o"></span> <?= __("Kullanıcı Kaydet") ?></button>
        </div> 
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#form_kullanici_diger_grup_ekle').validate({
            rules: {
                kullanici_adi: {required: true},
                adi: {required: true},
                soyadi: {required: true},
//                aciklamasi: {required: true},
                sifre: {required: true, minlength: 4},
                grup_id: {required: true}
            },
            messages: {
                kullanici_adi: {required: "Kullanıcı Adı Girilmelidir. "},
                adi: {required: "Soyadı Girilmelidir. "},
                soyadi: {required: "Soyadı Girilmelidir. "},
//                aciklamasi: {required: "Tebligat Adresi Girilmelidir. "},
                sifre: {required: "Kullanıcının Sistem Şifresi Girilmelidir. "},
                grup_id: {required: "Kullanıcını Grubunu Seçiniz. "}
            }
        });
    });
</script>