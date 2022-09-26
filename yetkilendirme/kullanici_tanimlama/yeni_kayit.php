
<div class="col-md-12 box box-primary" id="yeni_kayit_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_kullanici_ekle" action="postPage.php">       
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>  
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="kullanici_adi">Kullanıcı Adı</label>
            <div class="col-sm-8">
                <input type="text" id="kullanici_adi" name = "kullanici_adi" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>       
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi">Adı</label>
            <div class="col-sm-8">
                <input type="text" id="adi" name = "adi" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>         
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="soyadi">Soyadı</label>
            <div class="col-sm-8">
                <input type="text" id="soyadi" name = "soyadi" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>       
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="aciklamasi">Görevi</label>
            <div class="col-sm-8">
                <input type="text" id="aciklamasi" name = "aciklamasi" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="giris_tipi">Giriş Tipi</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="giris_tipi" onchange="$('.ui-tooltip').remove();"  name = "giris_tipi">
                    <option value="1" title="Email İçin Kullanılan Şifre Geçerlidir" selected>Siverek E-mail</option>
                    <option value="2" title="Burada Yazdığınız Şifre Geçerlidir.">Sistem</option>
                </select>
            </div>
        </div>   
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="sifre" title="Sistem Seçildiğinde kullanılacak Şifredir.">Şifresi</label>
            <div class="col-sm-8">
                <input type="text" id="sifre" name = "sifre" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="grup_id">Grubu</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="$('.ui-tooltip').remove();" id="grup_id" name = "grup_id">
                    <option value="">Seçiniz</option>
                    <?php
                    if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
                        $grupSQL = "SELECT yt_grup.id, yt_grup.adi, yt_grup.aciklama FROM yt_grup ";
                    } else {
                        $grupSQL = "SELECT yt_grup.id, yt_grup.adi, yt_grup.aciklama FROM yt_grup WHERE NOT( yt_grup.id = 1 OR yt_grup.id = 2)";
                    }
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
        $('#form_kullanici_ekle').validate({
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