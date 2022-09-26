<?php
if (isset($_POST['update']) && $_POST['update'] != '') {
    $update_id = mdecrypt($_POST['update'], $_SESSION['key']);
    try {
        $SQL_cumlesi = "SELECT
                                        s_ruhsat_bilgileri.id, 
                                        s_ruhsat_bilgileri.ruhsat_no, 
                                        DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,	
                                        kacak_islem_yapildi_mi, 
                                        s_ruhsat_bilgileri.bulten_no, 
                                        s_ruhsat_bilgileri.ada_parsel, 
                                        s_ruhsat_bilgileri.yibf_no,
                                        s_ruhsat_bilgileri.adi_soyadi, 
                                        s_ruhsat_bilgileri.ruhsat_cinsi, 
                                        s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
                                        s_ruhsat_bilgileri.fenni_mesul, 
                                        s_ruhsat_bilgileri.mahallesi,  
                                        s_ruhsat_bilgileri.yapi_alani, 
                                        iskan_verildi_mi, 
                                        DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
                                        s_ruhsat_bilgileri.iskan_no, 
                                        s_ruhsat_bilgileri.iskan_bulten_no, 
                                        s_ruhsat_bilgileri.kacak_islem_bilgisi,
                                        aciklama, 
                                        s_ruhsat_bilgileri.aktif_mi
                                FROM s_ruhsat_bilgileri
	WHERE aktif_mi AND id = ?";
        $ruhsat_bilgisi = $GLOBALS['db']->fetchRow($SQL_cumlesi, $update_id);
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
            <label class="col-sm-2 control-label" for="ruhsat_no">Ruhsat No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ruhsat_no" name="ruhsat_no" value="<?= $ruhsat_bilgisi->ruhsat_no ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi_soyadi">Ad Soyad</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="adi_soyadi" name="adi_soyadi" value="<?= $ruhsat_bilgisi->adi_soyadi ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_cinsi">Ruhsat Cinsi</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ruhsat_cinsi" name="ruhsat_cinsi" value="<?= $ruhsat_bilgisi->ruhsat_cinsi ?>" >
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
                    $is_not_select = true;
                    foreach ($ruhsat_verilis_amaclari as $ruhsat_verilis_amaci) {
                        echo "<option value='$ruhsat_verilis_amaci->verilis_amaci' title='$ruhsat_verilis_amaci->aciklama' ";
                        if ($ruhsat_bilgisi->ruhsat_verilis_amaci == $ruhsat_verilis_amaci->verilis_amaci) {
                            $is_not_select = false;
                            echo 'selected';
                        }
                        echo ">$ruhsat_verilis_amaci->verilis_amaci</option>";
                    }
                    if ($is_not_select && !is_null($ruhsat_bilgisi->ruhsat_verilis_amaci)) {
                        echo "<option value='$ruhsat_bilgisi->ruhsat_verilis_amaci' title='Daha önce girilen bilgidir.' selected >$ruhsat_bilgisi->ruhsat_verilis_amaci</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="fenni_mesul">Fenni Mesul/Yapı Denetim</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="fenni_mesul" name="fenni_mesul">
                    <option value=''>Listelenecek Ruhsat Seçiniz</option>
                    <?php
                    try {
                        $ydk_bilgileri = $db->fetchAll("SELECT unvan, adres FROM s_ydk_listesi WHERE aktif_mi");
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_obj($ruhsat_verilis_amaclari);
                    $is_not_select = true;
                    foreach ($ydk_bilgileri as $ydk_bilgisi) {
                        echo "<option value='$ydk_bilgisi->unvan' title='$ydk_bilgisi->adres' ";
                        if ($ruhsat_bilgisi->fenni_mesul == $ydk_bilgisi->unvan) {
                            $is_not_select = false;
                            echo 'selected';
                        }
                        echo ">$ydk_bilgisi->unvan</option>";
                    }
                    if ($is_not_select && !is_null($ruhsat_bilgisi->fenni_mesul)) {
                        echo "<option value='$ruhsat_bilgisi->fenni_mesul' title='Daha önce girilen bilgidir.' selected >$ruhsat_bilgisi->fenni_mesul</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_tarihi">Ruhsat Tarihi</label>
            <div class="col-sm-8">
                <input class="form-control date" type="text" id="ruhsat_tarihi" name="ruhsat_tarihi" value="<?= $ruhsat_bilgisi->ruhsat_tarihi ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="mahallesi">Mahallesi</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="mahallesi" name="mahallesi" value="<?= $ruhsat_bilgisi->mahallesi ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="bulten_no">Bulten No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="bulten_no" name="bulten_no" value="<?= $ruhsat_bilgisi->bulten_no ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ada_parsel">Ada Parsel</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ada_parsel" name="ada_parsel" value="<?= $ruhsat_bilgisi->ada_parsel ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yibf_no">YİBF No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="yibf_no" name="yibf_no" value="<?= $ruhsat_bilgisi->yibf_no ?>" >
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yapi_alani">Ölçüsü</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="yapi_alani" name="yapi_alani" value="<?= $ruhsat_bilgisi->yapi_alani ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_ruhsat_tarihi">İskan Tarihi</label>
            <div class="col-sm-8">
                <input class="form-control date" type="text" id="iskan_ruhsat_tarihi" name="iskan_ruhsat_tarihi" value="<?= $ruhsat_bilgisi->iskan_ruhsat_tarihi ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_verildi_mi">İskan Süreci</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="iskan_verildi_mi" name="iskan_verildi_mi">
                    <option value='1' <?= ($ruhsat_bilgisi->iskan_verildi_mi === -1 ? 'selected' : '') ?> >Onay Bekliyor/Başvuru Yapıldı</option>
                    <option value='0' <?= ($ruhsat_bilgisi->iskan_verildi_mi === 0 ? 'selected' : '') ?> >İskan Yok</option>
                    <option value='1' <?= ($ruhsat_bilgisi->iskan_verildi_mi === 1 ? 'selected' : '') ?> >İskan Verildi</option>
                </select>
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_no">İskan No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="iskan_no" name="iskan_no" value="<?= $ruhsat_bilgisi->iskan_no ?>" >
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_bulten_no">İskan Bülten No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="iskan_bulten_no" name="iskan_bulten_no" value="<?= $ruhsat_bilgisi->iskan_bulten_no ?>" >
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="kacak_islem_yapildi_mi">Kaçak İşlem Yapıldı Mı?</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="kacak_islem_yapildi_mi" name="kacak_islem_yapildi_mi">
                    <option value=''>İskan Durumu Seçiniz</option>
                    <option value='1' <?= ($ruhsat_bilgisi->kacak_islem_yapildi_mi === 1 ? 'selected' : '') ?> >Kaçak İşlem Yapıldı</option>
                    <option value='0' <?= ($ruhsat_bilgisi->kacak_islem_yapildi_mi === 0 ? 'selected' : '') ?> >Kaçak Yok</option>
                </select>
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="kacak_islem_bilgisi">Kaçak İşlem Bilgisi</label>
            <div class="col-sm-8">
                <textarea id="kacak_islem_bilgisi" name="kacak_islem_bilgisi" rows="10" cols="80"><?= htmlentities($ruhsat_bilgisi->kacak_islem_bilgisi) ?></textarea>
            </div>  
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_aktif_mi">İnşaat Ruhsat Süreci</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat_aktif_mi" name="ruhsat_aktif_mi">
                    <option value='-1' <?= ($ruhsat_bilgisi->aktif_mi === -1 ? 'selected' : '') ?> >Onay Bekliyor/Başvuru Yapıldı</option>
                    <option value='1' <?= ($ruhsat_bilgisi->aktif_mi === 1 ? 'selected' : '') ?> >Ruhsat Verildi</option>
                </select>
            </div>
        </div>
        
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="aciklama">Açıklama</label>
            <div class="col-sm-8">
                <textarea id="aciklama" name="aciklama" rows="10" cols="80"><?= htmlentities($ruhsat_bilgisi->aciklama) ?></textarea>
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