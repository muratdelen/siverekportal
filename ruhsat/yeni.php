<?php
try {
    $izin_bilgisi = $GLOBALS['db']->fetchRow("SELECT kalan_izin_gun_sayisi, yt_kullanici_id FROM sks_sozlesmeli_personel WHERE aktif_mi AND kalan_izin_gun_sayisi > 0 AND  yt_kullanici_id = ?", $kullaniciId);
} catch (Zend_Db_Exception $ex) {
    log::DB_hata_kaydi_ekle(__FILE__, $ex);
}
if (!isset($izin_bilgisi->kalan_izin_gun_sayisi)) {
    adminLTE_redirect(true, "İzniniz Kalmadı.", "Sistemden kullanabileceğiniz izniniz bulunmamaktadır.", "warning", 100000, BASE_URL . "personel_izin_alma/index.php");
} else {
    $izin_sonucu = $GLOBALS['db']->fetchRow("SELECT id FROM sks_izin_basvurusu WHERE aktif_mi AND basvuru_durumu = '0' AND  yt_kullanici_id = ?", $kullaniciId);
    if (isset($izin_sonucu->id)) {
        adminLTE_redirect(true, "İzin Bekleniyor.", "Onaylanması gereken bir izniniz bulunmaktadır.", "warning", 100000, BASE_URL . "personel_izin_alma/index.php");
    }
    $toplam_kalan_izin_gun_sayisi = $izin_bilgisi->kalan_izin_gun_sayisi;
}
?>
<div class="col-md-12 box box-primary" id="yeni_kayit_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_izin_ekle" action="postPage.php">       
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>   
        <h3 class="btn btn-block"><img  height="200px;" onerror="this.src='/upload/user_images/public.png'" alt="User Image" src="/upload/user_images/<?= $kullaniciAdi ?>.jpg"></h3>
        <h3 id="kalan-gun-sayisi-aciklamasi" style=" text-align: center;">Kalan İzin Sayısı: <?= $toplam_kalan_izin_gun_sayisi ?> </h3>  
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="izin_baslangic_tarihi">Başlangıç Tarihi</label>
            <div class="col-sm-4">
                <input type="text" class="date" id="izin_baslangic_tarihi" onchange="izin_degisimi()" required name="izin_baslangic_tarihi" >
            </div>
            <label class="col-sm-2 control-label" for="izin_bitis_tarihi">Bitiş Tarihi</label>
            <div class="col-sm-4">
                <input type="text" class="date" id="izin_bitis_tarihi" onchange="izin_degisimi()" required name="izin_bitis_tarihi" >
            </div>
        </div>   
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="izin_gerekcesi">İzin Gerekçesi</label>
            <div class="col-sm-8">
                <textarea type="text" id="izin_gerekcesi" name = "izin_gerekcesi" placeholder=" Giriniz" class="form-control"></textarea >
            </div>  
        </div>   
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="insert" value="<?= mcrypt($kullaniciId, $_SESSION['key']); ?>" class="btn bg-olive btn-block"><span class="fa fa-floppy-o"></span> <?= __("İzin Başvurusu Yap") ?></button>
        </div> 
    </form>
</div>

<script>
    function parseDate(str) {
        var mdy = str.split('/');
        return  new Date(mdy[1] + '/' + mdy[0] + '/' + mdy[2]);
    }

    function daydiff(first, second) {
        return Math.round((second - first) / (1000 * 60 * 60 * 24));
    }
    function izin_degisimi() {
        var str_uyari = '';
        if ($('#izin_baslangic_tarihi').val() == '') {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            $('#izin_baslangic_tarihi').val(today);
        }
        if ($('#izin_bitis_tarihi').val() == '') {
            $('#izin_bitis_tarihi').val($('#izin_baslangic_tarihi').val());
        }
        var toplam_gun_sayisi =<?= $toplam_kalan_izin_gun_sayisi ?>;
        var first_date_str = $('#izin_baslangic_tarihi').val();
        var second_date_str = $('#izin_bitis_tarihi').val();
        var first_date = parseDate(first_date_str);
        var second_date = parseDate(second_date_str);
        if (first_date > second_date) {
            $('#izin_bitis_tarihi').val($('#izin_baslangic_tarihi').val());
            second_date_str = first_date_str;
            second_date = first_date;
        }
//        var alinan_izin_gun_sayisi = daydiff(first_date, second_date) + 1;

        $.post("ajax.php", {izin_baslangic_tarihi: first_date_str, izin_bitis_tarihi: second_date_str})
                .done(function (alinan_izin_gun_sayisi) {
                    var kalan_gun_sayisi = toplam_gun_sayisi - alinan_izin_gun_sayisi;
                    var str_uyari = '';
                    if (kalan_gun_sayisi >= 0) {
                        str_uyari = '<div class="alert-success">Toplam İzin Günü( ' + toplam_gun_sayisi + ' ) - Alinan İzin Günü( ' + alinan_izin_gun_sayisi + ' ) = Kalan İzin Günü( ' + kalan_gun_sayisi + ' )</div>';
                        $('#kalan-gun-sayisi-aciklamasi').html(str_uyari);
                        return true;
                    } else {
                        str_uyari = '<div class="alert-danger"> Toplam İzin Günü( ' + toplam_gun_sayisi + ' ) - Alinan İzin Günü( ' + alinan_izin_gun_sayisi + ' ) = Kalan İzin Günü( ' + kalan_gun_sayisi + ' ) Olamaz!</div>';
                        $('#kalan-gun-sayisi-aciklamasi').html(str_uyari);
                        return false;
                    }
                });
    }
    $(document).ready(function () {
        $('#form_izin_ekle').validate({
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