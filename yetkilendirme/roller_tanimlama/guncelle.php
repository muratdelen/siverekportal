<div class="col-md-12 box box-primary" id="guncelleme_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_rol_guncelle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="secilen_rol">Rol Adı</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();
//                                                    $('#rol_adi').val($('#secilen_rol  option:selected').html());
//                                                    $('#rol_aciklama').val($('#secilen_rol  option:selected').attr('title'));
                        rol_bilgisi_yukle();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="secilen_rol" name="secilen_rol">
                    <option value=''>Seçiniz</option>
                    <?php
                    $rolSQL = "SELECT id, adi,aciklama FROM yt_rol ";
                    try {
                        $roller = $db->fetchAll($rolSQL);
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                    htmlspecialchar_obj($roller);
                    foreach ($roller as $rol) {
                        $id_sifreli = mcrypt($rol->id, $_SESSION['key']);
                        echo "<option value='$id_sifreli'  title='$rol->aciklama'";
                        echo (isset($id_sifreli) ? (isset($_POST['secilen_rol']) ? ($_POST['secilen_rol'] == $id_sifreli ? 'selected' : null) : null) : null);
                        echo ">$rol->adi</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="rol_adi">Seçilen Rol Adı</label>
            <div class="col-sm-8">
                <input type="text" id="rol_adi" name="rol_adi" value="" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>        
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="rol_aciklama">Açıklaması</label>
            <div class="col-sm-8">
                <input type="text" id="rol_aciklama" name="rol_aciklama" value="" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="aktif_mi">Rol Aktif Mi?</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="aktif_mi" onchange="$('.ui-tooltip').remove();"  name = "aktif_mi">
                    <option value="0" >Pasif</option>
                    <option value="1" >Aktif</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="update" value="<?= $_POST['update']; ?>" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("Grup Güncelle") ?></button>
        </div>
    </form>
</div>


</div>
</section>

<script>
    $(document).ready(function () {
        $('#form_rol_guncelle').validate({
            rules: {
                rol_adi: {required: true},
                rol_aciklama: {required: true}
            },
            messages: {
                rol_adi: {required: "Rol Adı Girilmelidir. "},
                rol_aciklama: {required: "Rol Açıklaması Girilmelidir. "}
            }
        });
    });
    function rol_bilgisi_yukle() {
        $('.ui-tooltip').remove();
        if ($('#secilen_rol').val() == "") {
            $('#rol_adi').val("");
            $('#rol_aciklama').val("");
            $('#aktif_mi').val(0);
            $('#aktif_mi').change();
            $('#update').val("");
        } else {
            var formData = new Array();
            formData.push({name: 'post_type', value: 5}, {name: 'secilen_rol', value: $("#secilen_rol").val()});
            $.ajax({
                async: false,
                url: 'ajax.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    var rol_bilgisi = $.parseJSON(data);
                    $.each(rol_bilgisi, function (key, value) {
                        $('#rol_adi').val(value["adi"]);
                        $('#rol_aciklama').val(value["aciklama"]);
                        $('#aktif_mi').val(value["aktif_mi"]);
                        $('#aktif_mi').change();
                        $('#update').val($('#secilen_rol').val());
                    });
                }
            });
        }
    }
</script>
</script>