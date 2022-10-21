<?php
if (isset($_POST['insert']) && $_POST['insert'] != '') {
    $insert_id = mdecrypt($_POST['insert'], $_SESSION['key']);
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
        $ruhsat_bilgisi = $GLOBALS['db']->fetchRow($SQL_cumlesi, $insert_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    // var_dump($update_id, $ruhsat_bilgisi);
//die();
}

$yeni_ruhsat_no = date("Y") . "/";
$yeni_iskan_no = date("Y") . "/";
try {
    $son_ruhsat_bilgisi = $db->fetchRow("SELECT deger FROM s_degiskenler WHERE aktif_mi AND degisken = 'son_ruhsat_no'");
    $son_iskan_bilgisi = $db->fetchRow("SELECT deger FROM s_degiskenler WHERE aktif_mi AND degisken = 'son_iskan_no'");
    $yeni_ruhsat_no .= $son_ruhsat_bilgisi->deger;
    $yeni_iskan_no .= $son_iskan_bilgisi->deger;
} catch (Zend_Db_Exception $ex) {
    log::DB_hata_kaydi_ekle(__FILE__, $ex);
}
?>
<div class="col-md-12 box box-primary" id="ekle_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_ruhsat_ekle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div> 
        <div class="form-group form-group-sm alert-info">
            <label class="col-sm-2 control-label" for="son_ruhsat_no">Son Ruhsat No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="son_ruhsat_no" name="ruhsat_no" value="<?= $son_ruhsat_bilgisi->deger ?>" >
            </div>
        </div> 
        <div class="form-group form-group-sm alert-info">
            <label class="col-sm-2 control-label" for="son_iskan_no">Son İskan No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="son_iskan_no" name="son_iskan_no" value="<?= $son_iskan_bilgisi->deger ?>" >
            </div>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_no">Ruhsat No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ruhsat_no" name="ruhsat_no" value="<?= (isset($ruhsat_bilgisi->ruhsat_no) ? $ruhsat_bilgisi->ruhsat_no : $yeni_ruhsat_no) ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi_soyadi">Ad Soyad</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="adi_soyadi" name="adi_soyadi" value="<?= (isset($ruhsat_bilgisi->adi_soyadi) ? $ruhsat_bilgisi->adi_soyadi : "") ?>" >
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_cinsi">Ruhsat Cinsi</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ruhsat_cinsi" name="ruhsat_cinsi" value="<?= (isset($ruhsat_bilgisi->ruhsat_cinsi) ? $ruhsat_bilgisi->ruhsat_cinsi : "") ?>" >
            </div>
        </div>  
        <?php
        if (isset($ruhsat_bilgisi->ruhsat_verilis_amaci)) {
            ?>
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
            <?php } else {
            ?>

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
            <?php
        }
        ?>
        <!--        <div class="form-group form-group-sm">
                    <label class="col-sm-2 control-label" for="ruhsat_verilis_amaci">Ruhsat Veriliş Amacı</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" id="ruhsat_verilis_amaci" name="ruhsat_verilis_amaci" value="" >
                    </div>
                </div>        -->
        <?php
        if (isset($ruhsat_bilgisi->fenni_mesul)) {
            ?>

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
            <?php
        } else {
            ?>

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
            <?php
        }
        ?>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_tarihi">Ruhsat Tarihi</label>
            <div class="col-sm-8">
                <input class="form-control date" type="text" id="ruhsat_tarihi" name="ruhsat_tarihi" value="<?= (isset($ruhsat_bilgisi->ruhsat_tarihi) ? $ruhsat_bilgisi->ruhsat_tarihi : "") ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="mahallesi">Mahallesi</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="mahallesi" name="mahallesi" value="<?= (isset($ruhsat_bilgisi->mahallesi) ? $ruhsat_bilgisi->mahallesi : "") ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="bulten_no">Bulten No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="bulten_no" name="bulten_no" value="<?= (isset($ruhsat_bilgisi->bulten_no) ? $ruhsat_bilgisi->bulten_no : "") ?>"  >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ada_parsel">Ada Parsel</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="ada_parsel" name="ada_parsel" value="<?= (isset($ruhsat_bilgisi->ada_parsel) ? $ruhsat_bilgisi->ada_parsel : "") ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yibf_no">YİBF No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="yibf_no" name="yibf_no" value="<?= (isset($ruhsat_bilgisi->yibf_no) ? $ruhsat_bilgisi->yibf_no : "") ?>" >
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yapi_alani">Ölçüsü</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="yapi_alani" name="yapi_alani"  value="<?= (isset($ruhsat_bilgisi->yapi_alani) ? $ruhsat_bilgisi->yapi_alani : "") ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_ruhsat_tarihi">İskan Tarihi</label>
            <div class="col-sm-8">
                <input class="form-control date" type="text" id="iskan_ruhsat_tarihi" name="iskan_ruhsat_tarihi" value="<?= (isset($ruhsat_bilgisi->iskan_ruhsat_tarihi) ? $ruhsat_bilgisi->iskan_ruhsat_tarihi : "") ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_verildi_mi">İskan İşlemi</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="iskan_verildi_mi" name="iskan_verildi_mi">
                    <option value='-1' selected>Ruhsat Bilgileri Ekleme / Güncelleme İşlemi</option>
                    <option value='0' <?= (isset($ruhsat_bilgisi->iskan_verildi_mi) && iskan_verildi_mi === 0 ? "selected" : "") ?>>İskan Yok</option>
                    <option value='1' <?= (isset($ruhsat_bilgisi->iskan_verildi_mi) && iskan_verildi_mi === 1 ? "selected" : "") ?> >İskan Verildi</option>
                </select>
            </div>
        </div>  
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_no">İskan No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="iskan_no" name="iskan_no" value="<?= (isset($ruhsat_bilgisi->iskan_no) ? $ruhsat_bilgisi->iskan_no : $yeni_iskan_no) ?>" >
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="iskan_bulten_no">İskan Bülten No</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="iskan_bulten_no" name="iskan_bulten_no" value="<?= (isset($ruhsat_bilgisi->iskan_bulten_no) ? $ruhsat_bilgisi->iskan_bulten_no : "") ?>" >
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="ruhsat_aktif_mi">İnşaat Ruhsat Süreci</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat_aktif_mi" name="ruhsat_aktif_mi">
                    <option value='-1'>Onay Bekliyor/Başvuru Yapıldı</option>
                    <option value='1' selected >Ruhsat Verildi</option>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="aciklama">Açıklama</label>
            <div class="col-sm-8">
                <textarea id="aciklama" name="aciklama" rows="10" cols="80"></textarea>
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
        $("#ruhsat_no").change(function () {
            //$("#ruhsat_no").val(mUrlEncode($("#ruhsat_no").val()));
            var formData = new Array();
            formData.push({name: 'ruhsat_no', value: $("#ruhsat_no").val()});
            $.ajax({
//                async: true,
                url: 'ajax.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data == 1) {
                        adminLTE_alert_remove();
                        adminLTE_alert(false, "Ruhsat Adı", "Ruhsat Adı Kullanılabilir.", "success");
                    } else {
                        adminLTE_alert_remove();
                        adminLTE_alert(false, "Ruhsat Adı", "Bu Ruhsat Önceden Girilmiştir. Lütfen Ruhsat Adını Değiştiriniz.", "danger");
                    }
                }
            });
        });

    });


</script>