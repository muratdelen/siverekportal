<?php
if (isset($_POST['update']) && $_POST['update'] != '') {
    $update_id = mdecrypt($_POST['update'], $_SESSION['key']);
    try {
        $SQL_cumlesi = "SELECT
	s_ruhsat_bilgileri.id, 
	s_ruhsat_bilgileri.ruhsat_no, 
	(CASE s_ruhsat_bilgileri.iskan_verildi_mi WHEN 1 THEN 'İskan Verildi' WHEN 0 THEN 'İskan Yok' END) AS iskan_verildi_mi, 
	DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,  
	s_ruhsat_bilgileri.adi_soyadi, 
	s_ruhsat_bilgileri.cinsi, 
	s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
	s_ruhsat_bilgileri.fenni_mesul, 
	DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
	s_ruhsat_bilgileri.mahallesi, 
	s_ruhsat_bilgileri.bulten_no, 
	s_ruhsat_bilgileri.ada_parsel, 
	s_ruhsat_bilgileri.yibf_no, 
	s_ruhsat_bilgileri.olcusu
FROM
	s_ruhsat_bilgileri
	WHERE aktif_mi AND id = ?";
        $ruhsat_bilgisi = $GLOBALS['db']->fetchRow($SQL_cumlesi, $update_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
//var_dump($update_id,$ruhsat_bilgisi);die();
} else {
    adminLTE_redirect(true, "Güncelleme Yetkiniz Yoktur.", "Güncelleme Yetkiniz Yoktur.", "warning", 100000, BASE_URL . "ruhsat/index.php");
}
?>
<div class="col-md-12 box box-primary" id="guncelleme_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_izin_guncelle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div> 
        <h3 style=" text-align: center;" class="alert-info"> İzin Onaylanması İçin Bekleniyor ... </h3>  
        <h3 id="kalan-gun-sayisi-aciklamasi" style=" text-align: center;"> Onaylanana / Red Edilene Kadar Güncelleme Yapılabilir. </h3>  
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="izin_baslangic_tarihi">Başlangıç Tarihi</label>
            <div class="col-sm-4">
                <input type="text" class="date" id="izin_baslangic_tarihi" onchange="izin_degisimi()"   value=" <?= $izin_bilgisi->baslangic_tarihi ?>" required name="izin_baslangic_tarihi" >
            </div>
            <label class="col-sm-2 control-label" for="izin_bitis_tarihi">Bitiş Tarihi</label>
            <div class="col-sm-4">
                <input type="text" class="date" id="izin_bitis_tarihi" onchange="izin_degisimi()"  value=" <?= $izin_bilgisi->bitis_tarihi ?>" required name="izin_bitis_tarihi" >
            </div>
        </div>   
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="izin_gerekcesi">İzin Gerekçesi</label>
            <div class="col-sm-8">
                <textarea type="text" id="izin_gerekcesi" name = "izin_gerekcesi" placeholder=" Giriniz" class="form-control"><?= $izin_bilgisi->basvuru_nedeni ?></textarea >
            </div>  
        </div> 
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="update" value="<?= $_GET['update']; ?>" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("İzin Başvurusu Güncelle") ?></button>
        </div>         
    </form>
</div>


</div>
</section>

<script>
   
    $(document).ready(function () {
        izin_degisimi();
        $('#form_izin_guncelle').validate({
            rules: {
                izin_baslangic_tarihi: {required: true},
                izin_bitis_tarihi: {required: true},
                izin_gerekcesi: {required: true}
            },
            messages: {
                izin_baslangic_tarihi: {required: "İzin Başlanacak Gün Giriniz. "},
                izin_bitis_tarihi: {required: "İzin Bitecek Gün Giriniz. "},
                izin_gerekcesi: {required: "İzin Açıklaması Giriniz. "}
            }
        });
    });
</script>