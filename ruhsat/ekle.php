<?php
$yeni_ruhsat_no = date("Y") . "/";
try {
    $son_ruhsat_bilgisi = $db->fetchRow("SELECT deger FROM s_degiskenler WHERE aktif_mi AND degisken = 'son_ruhsat_no'");
    $yeni_ruhsat_no .= $son_ruhsat_bilgisi->deger;
} catch (Zend_Db_Exception $ex) {
    log::DB_hata_kaydi_ekle(__FILE__, $ex);
}
?>
<div class="col-md-12 box box-primary" id="ekle_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_ruhsat_ekle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_no">Ruhsat No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ruhsat_no" name="ruhsat_no" value="<?= $yeni_ruhsat_no ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi_soyadi">Ad Soyad</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="adi_soyadi" name="adi_soyadi" value="" >
            </div>
        </div>
        
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_cinsi">Ruhsat Cinsi</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat_cinsi" name="ruhsat_cinsi">
                <option value='' selected>Ruhsat Cinsi Seçiniz</option>
                <option value='MESKEN' >Mesken</option>
                    <option value='İŞYERİ'>İşyeri</option>
                    <option value='MESKEN+İŞYERİ'>Mesken+İşyeri</option>
                    <option value='AHIR'>Ahır</option>
                </select>
            </div>
        </div>  
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_verilis_amaci">Ruhsat Veriliş Amacı</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat_verilis_amaci" name="ruhsat_verilis_amaci">
                    <option value=''>Listelenecek Ruhsat Seçiniz</option>
                    <?php
                    try {
                        $ruhsat_verilis_amaclari = $db->fetchAll("SELECT verilis_amaci, aciklama FROM s_ruhsat_verilis_amaci WHERE aktif_mi");
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_obj($ruhsat_verilis_amaclari);
                    foreach ($ruhsat_verilis_amaclari as $ruhsat_verilis_amaci) {
                        echo "<option value='$ruhsat_verilis_amaci->verilis_amaci' title='$ruhsat_verilis_amaci->aciklama' ";
                        echo ">$ruhsat_verilis_amaci->verilis_amaci</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <!--        <div class="form-group form-group-sm">
                    <label class="col-sm-2 control-label" for="ruhsat_verilis_amaci">Ruhsat Veriliş Amacı</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" id="ruhsat_verilis_amaci" name="ruhsat_verilis_amaci" value="" >
                    </div>
                </div>        -->
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="fenni_mesul">Fenni Mesul/Yapı Denetim</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="fenni_mesul" name="fenni_mesul">
                    <option value=''>Listelenecek Ruhsat Seçiniz</option>
                    <?php
                    try {
                        $yapi_denetim_firmalari = $db->fetchAll("SELECT unvan, adres FROM s_ydk_listesi");
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_obj($yapi_denetim_firmalari);
                    foreach ($yapi_denetim_firmalari as $yapi_denetim_firma) {
                        echo "<option value='$yapi_denetim_firma->unvan' title='$yapi_denetim_firma->adres' ";
                        echo ">$yapi_denetim_firma->unvan</option>";
                    }
                    ?>
                </select>
                <!--<input class="form-control" type="text" id="fenni_mesul" name="fenni_mesul" value="" >-->
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_tarihi">Ruhsat Tarihi</label>
            <div class="col-sm-8">
                <input class="form-control date" type="text" id="ruhsat_tarihi" name="ruhsat_tarihi" value="" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="mahallesi">Mahallesi</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="mahallesi" name="mahallesi" value="" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="bulten_no">Bulten No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="bulten_no" name="bulten_no" value="" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ada_parsel">Ada Parsel</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ada_parsel" name="ada_parsel" value="" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yibf_no">YİBF No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="yibf_no" name="yibf_no" value="" >
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yapi_alani">Ölçüsü</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="yapi_alani" name="yapi_alani" value="" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_ruhsat_tarihi">İskan Tarihi</label>
            <div class="col-sm-8">
                <input class="form-control date" type="text" id="iskan_ruhsat_tarihi" name="iskan_ruhsat_tarihi" value="" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_verildi_mi">İskan Süreci</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="iskan_verildi_mi" name="iskan_verildi_mi">
                    <option value='-1' selected>Onay Bekliyor/Başvuru Yapıldı</option>
                    <option value='0'>İskan Yok</option>
                    <option value='1'>İskan Verildi</option>
                </select>
            </div>
        </div>  

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_aktif_mi">İnşaat Ruhsat Süreci</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat_aktif_mi" name="ruhsat_aktif_mi">
                    <option value='-1' selected>Onay Bekliyor/Başvuru Yapıldı</option>
                    <option value='1'>Ruhsat Verildi</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="insert" value="" class="btn bg-olive btn-block"><span class="fa fa-floppy-o"></span>Ruhsat Bilgisi Ekle</button>
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