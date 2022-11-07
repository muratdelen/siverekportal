<?php
if (isset($_POST['update']) && $_POST['update'] != '') {
    $update_id = mdecrypt($_POST['update'], $_SESSION['key']);
    try {
        $SQL_cumlesi = "SELECT
cevre_mezarlik_bilgileri.id,
cevre_mezarlik_bilgileri.adi,
cevre_mezarlik_bilgileri.soyadi,
cevre_mezarlik_bilgileri.tc_kimlik_no,
cevre_mezarlik_bilgileri.telefon,
cevre_mezarlik_bilgileri.tapu_belge_no,
cevre_mezarlik_bilgileri.tapu_dosya_url,
cevre_mezarlik_bilgileri.mezarlik_adi,
cevre_mezarlik_bilgileri.ada_parsel,
cevre_mezarlik_bilgileri.yatan_kisi_sayisi,
cevre_mezarlik_bilgileri.toplam_kisi_sayisi,
cevre_mezarlik_bilgileri.ekleyen_kullanici,
cevre_mezarlik_bilgileri.guncelleyen_kullanici,
cevre_mezarlik_bilgileri.ekleme_zamani,
cevre_mezarlik_bilgileri.guncelleme_zamani,
cevre_mezarlik_bilgileri.aktif_mi
FROM
cevre_mezarlik_bilgileri
	WHERE aktif_mi AND id = ?";
        $mezar_bilgisi = $GLOBALS['db']->fetchRow($SQL_cumlesi, $update_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
//    var_dump($update_id, $ruhsat_bilgisi);
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
            <label class="col-sm-2 control-label" for="mezarlik_adi">Mezarlık Adı</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="mezarlik_adi" name="mezarlik_adi">
                    <option value='Asliye Mezarlığı'>Asliye Mezarlığı</option>
                    <option value='Nato Mezarlığı'>Nato Mezarlığı</option>
                    <option value='Topdemir Mezarlığı'>Topdemir Mezarlığı</option>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ada_parsel">ada_parsel</label>
            <div class="col-sm-8">
                <input class="form-control " type="text" id="ada_parsel" name="ada_parsel" value="<?=$mezar_bilgisi->ada_parsel?>" >
            </div>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yatan_kisi_sayisi">yatan_kisi_sayisi</label>
            <div class="col-sm-8">
                <input class="form-control " type="number" id="yatan_kisi_sayisi" name="yatan_kisi_sayisi" value="<?=$mezar_bilgisi->yatan_kisi_sayisi?>" >
            </div>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="toplam_kisi_sayisi">toplam_kisi_sayisi</label>
            <div class="col-sm-8">
                <input class="form-control " type="number" id="toplam_kisi_sayisi" name="toplam_kisi_sayisi" value="<?=$mezar_bilgisi->toplam_kisi_sayisi?>" >
            </div>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi">Adı</label>
            <div class="col-sm-8">
                <input class="form-control " type="text" id="ad" name="ad" value="<?=$mezar_bilgisi->adi?>" >
            </div>
        </div>         
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="soyadi">Soyadı</label>
            <div class="col-sm-8">
                <input class="form-control " type="text" id="soyad" name="soyad" value="<?=$mezar_bilgisi->Soyadı?>" >
            </div>
        </div>                           
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="tc_kimlik_no">Tc Kimlik No</label>
            <div class="col-sm-8">
                <input class="form-control number_tc" type="text" id="tc_kimlik_no" name="tc_kimlik_no" value="<?=$mezar_bilgisi->tc_kimlik_no?>" >
            </div>
        </div>          
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="telefon">telefon</label>
            <div class="col-sm-8">
                <input class="form-control telefon" type="text" id="telefon" name="telefon" value="<?=$mezar_bilgisi->telefon?>" >
            </div>
        </div>        
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yuklenen_dosya">Tapu Dosyası</label>
            <div class="col-sm-8">
                <input class="form-control" type="file" id="yuklenen_dosya" name="yuklenen_dosya" value="<?=$mezar_bilgisi->tapu_dosya_url?>" >
            </div>
        </div> 
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="update" value="<?= $_POST['update']; ?>" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> Ruhsat Bilgisini Güncelle</button>
        </div>         
    </form>
</div>


</div>
</section>

<script>

    $(document).ready(function () {
        $('#form_ruhsat_guncelle').validate({
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