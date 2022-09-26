<?php
if (isset($_POST['update']) && $_POST['update'] != '') {
    $update_id = mdecrypt($_POST['update'], $_SESSION['key']);
    $SQL_cumlesi = 'SELECT
                            yt_kullanici.kullanici_adi,
                            yt_kullanici.adi,
                            yt_kullanici.soyadi,
                            yt_kullanici.aciklamasi,
                            yt_kullanici_diger_gruplari.yt_grup_id,
                            yt_kullanici_diger_gruplari.aktif_mi
                            FROM
                            yt_kullanici
                            INNER JOIN yt_kullanici_diger_gruplari ON yt_kullanici_diger_gruplari.yt_kullanici_id = yt_kullanici.id
                            INNER JOIN yt_grup ON yt_grup.id = yt_kullanici_diger_gruplari.yt_grup_id
                            WHERE NOT(yt_kullanici.yt_grup_id=1 OR yt_kullanici.yt_grup_id=2) AND (yt_kullanici_diger_gruplari.id = ? )';
    $kullanici_bilgisi = $GLOBALS['db']->fetchRow($SQL_cumlesi, $update_id);
    htmlspecialchar_array($kullanici_bilgisi);
//var_dump($kullanici_bilgisi);die();
} else {
    adminLTE_redirect(true, "Güncelleme Yetkiniz Yoktur.", "warning", 100000, BASE_URL . "yetkilendirme/kullanici_diger_gruplari/index.php");
}
?>
<div class="col-md-12 box box-primary" id="guncelleme_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_kullanici_diger_gruplari_guncelle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="kullanici_adi">Kullanıcı Adı</label>
            <div class="col-sm-8">
                <input type="text" id="kullanici_adi" name = "kullanici_adi" value="<?= $kullanici_bilgisi->kullanici_adi ?>" placeholder=" Giriniz" class="form-control" readonly >
            </div>  
        </div>       
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi">Adı</label>
            <div class="col-sm-8">
                <input type="text" id="adi" name = "adi" value="<?= $kullanici_bilgisi->adi ?>" placeholder=" Giriniz" class="form-control" readonly>
            </div>  
        </div>         
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="soyadi">Soyadı</label>
            <div class="col-sm-8">
                <input type="text" id="soyadi" name = "soyadi" value="<?= $kullanici_bilgisi->soyadi ?>" placeholder=" Giriniz" class="form-control" readonly>
            </div>  
        </div>       
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="aciklamasi">Görevi</label>
            <div class="col-sm-8">
                <input type="text" id="aciklamasi" name = "aciklamasi" value="<?= $kullanici_bilgisi->aciklamasi ?>" placeholder=" Giriniz" class="form-control" readonly>
            </div>  
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="grup_id">Grubu</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" onchange="$('.ui-tooltip').remove();" id="grup_id" name = "grup_id">
                    <option value="">Seçiniz</option>
                    <?php
                    $grupSQL = "SELECT id,adi,aciklama FROM yt_grup WHERE NOT(id = 1 OR id = 2) ";
                    try {
                        $gruplar = $db->fetchAll($grupSQL);
                    } catch (Zend_Db_Exception $ex) {
                        llog::DB_hata_kaydi_ekle(__FILE__, $ex);
                        kle("Veritabanı Hatası", $hata_Zend_Db_Exception->getCode(), $hata_Zend_Db_Exception->getMessage(), $sayfa_url, $satir_numarası);
                    }
                    htmlspecialchar_obj($grupSQL);
                    foreach ($gruplar as $grup) {
                        $id_sifreli = mcrypt($grup->id, $_SESSION['key']);
                        if ($kullanici_bilgisi->yt_grup_id == $grup->id) {
                            echo "<option value='$id_sifreli' title='$grup->aciklama' selected >$grup->adi</option>";
                        } else {
                            echo "<option value='$id_sifreli' title='$grup->aciklama' >$grup->adi</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="grup_aktif_mi">Diğer Grup Aktif Mi?</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="grup_aktif_mi" onchange="$('.ui-tooltip').remove();"  name = "grup_aktif_mi">
                    <option value="0" <?= $kullanici_bilgisi->aktif_mi == 0 ? 'selected' : '' ?> >Pasif</option>
                    <option value="1" <?= $kullanici_bilgisi->aktif_mi == 1 ? 'selected' : '' ?> >Aktif</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="update" value="<?= $_POST['update']; ?>" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("Kullanıcı Güncelle") ?></button>
        </div> 
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#form_kullanici_diger_gruplari_guncelle').validate({
            rules: {
                grup_id: {required: true}
            },
            messages: {
                grup_id: {required: "Kullanıcını Grubunu Seçiniz. "}
            }
        });
    });
</script>
</script>