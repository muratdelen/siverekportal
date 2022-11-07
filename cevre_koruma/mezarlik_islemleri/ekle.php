
<div class="col-md-12 box box-primary" id="ekle_ekrani_div">
    <form class="form-horizontal" method="POST"  enctype="multipart/form-data" id="form_Hakediş_ekle" action="postPage.php">
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
                <input class="form-control " type="text" id="ada_parsel" name="ada_parsel" value="" >
            </div>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yatan_kisi_sayisi">yatan_kisi_sayisi</label>
            <div class="col-sm-8">
                <input class="form-control " type="number" id="yatan_kisi_sayisi" name="yatan_kisi_sayisi" value="" >
            </div>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="toplam_kisi_sayisi">toplam_kisi_sayisi</label>
            <div class="col-sm-8">
                <input class="form-control " type="number" id="toplam_kisi_sayisi" name="toplam_kisi_sayisi" value="" >
            </div>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi">Adı</label>
            <div class="col-sm-8">
                <input class="form-control " type="text" id="ad" name="ad" value="" >
            </div>
        </div>         
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="soyadi">Soyadı</label>
            <div class="col-sm-8">
                <input class="form-control " type="text" id="soyad" name="soyad" value="" >
            </div>
        </div>                           
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="tc_kimlik_no">Tc Kimlik No</label>
            <div class="col-sm-8">
                <input class="form-control number_tc" type="text" id="tc_kimlik_no" name="tc_kimlik_no" value="" >
            </div>
        </div>          
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="telefon">telefon</label>
            <div class="col-sm-8">
                <input class="form-control telefon" type="text" id="telefon" name="telefon" value="" >
            </div>
        </div>        
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="yuklenen_dosya">Tapu Dosyası</label>
            <div class="col-sm-8">
                <input class="form-control" type="file" id="yuklenen_dosya" name="yuklenen_dosya" value="" >
            </div>
        </div> 
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="insert" value="" class="btn bg-olive btn-block"><span class="fa fa-floppy-o"></span>Mezarlık Bilgisi Ekle</button>
        </div> 
    </form>
</div>

<script>

    $(document).ready(function () {
        $('#form_Hakediş_ekle').validate({
            rules: {
                Hakediş_adi: {required: true}

            },
            messages: {
                Hakediş_adi: {required: "Hakediş adını giriniz. "}

            }
        });

    });


</script>