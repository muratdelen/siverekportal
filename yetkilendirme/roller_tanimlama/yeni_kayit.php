
<div class="col-md-12 box box-primary" id="yeni_kayit_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_rol_ekle" action="postPage.php">        
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>    
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="adi">Rol Adı</label>
            <div class="col-sm-8">
                <input type="text" id="adi" name="adi" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>       
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="aciklama">Rol Açıklaması</label>
            <div class="col-sm-8">
                <input type="text" id="aciklama" name="aciklama" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>                
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="insert" value="Kaydet" class="btn bg-olive btn-block"><span class="fa fa-floppy-o"></span> <?= __("Rol Kaydet") ?></button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#form_rol_ekle').validate({
            rules: {
                adi: {required: true},
                aciklama: {required: true}
            },
            messages: {
                adi: {required: "Rol Adı Girilmelidir. "},
                aciklama: {required: "Rol Açıklaması Girilmelidir. "}
            }
        });
    });
</script>