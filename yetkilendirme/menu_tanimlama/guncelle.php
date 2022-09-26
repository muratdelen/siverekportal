<?php
if (isset($_POST['update']) && $_POST['update'] != '') {

    $update_id = mdecrypt($_POST['update'], $_SESSION['key']);
    $ItemsSQL = "SELECT * FROM yt_menu WHERE id = ? ";
    $menu_bilgisi = $GLOBALS['db']->fetchRow($ItemsSQL, $update_id);
    htmlspecialchar_array($menu_bilgisi);
} else {
    adminLTE_redirect(true, "Güncelleme Yetkiniz Yoktur.", "warning", 100000, BASE_URL . "yetkilendirme/menu_tanimlama/index.php");
}
?>

<div class="col-md-12 box box-primary" id="yeni_kayit_ekrani_div">
    <form class="form-horizontal" method="post"  id="form_menu_guncelle" action="postPage.php">
        <div class="box-header">
            <a class="btn bg-orange margin pull-right" type="cancel" href="index.php"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>   
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="menu_name">Menu Adı</label>
            <div class="col-sm-8">
                <input type="text" id="menu_name" name = "menu_name" value="<?= $menu_bilgisi->adi ?>" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="page_url">Sayfa Url</label>
            <div class="col-sm-8">
                <input type="text" id="page_url" name = "page_url" value="<?= $menu_bilgisi->sayfa_url ?>" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="menu_target">Menu Tıklanması(Target)</label>
            <div class="col-sm-8">
                <select class="form-control select2"  id="menu_target" name="menu_target" style="width: 100%;">
                    <option value='target="_self"'  <?= $menu_bilgisi->target == 'target="_self"' ? 'selected' : '' ?>>_self</option>
                    <option value='target="_blank"' <?= $menu_bilgisi->target == 'target="_blank"' ? 'selected' : '' ?>>_blank</option>
                    <option value='target="_parent"' <?= $menu_bilgisi->target == 'target="_parent"' ? 'selected' : '' ?>>_parent</option>
                    <option value='target="_top"' <?= $menu_bilgisi->target == 'target="_top"' ? 'selected' : '' ?>>_top</option>
                    <option value='target="framename"' <?= $menu_bilgisi->target == 'target="framename"' ? 'selected' : '' ?>>framename</option>
                </select>
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="link">Tıklanma Linki</label>
            <div class="col-sm-8">
                <input type="text" id="link" name = "link" value="<?= $menu_bilgisi->link ?>" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="main_menu">Ana Menü</label>
            <div class="col-sm-8">
                <select class="form-control select2"  id="main_menu" name="main_menu" style="width: 100%;">
                    <option value="">Ana Menu</option>
                    <?php
                    if ($menu_bilgisi->ana_menu_id != "" && $menu_bilgisi->ana_menu_id != NULL) {
                        echo get_menu_items_option_html_selected($menu_bilgisi->ana_menu_id);
                    } else {
                        echo get_menu_items_option_html();
                    }
                    ?>
                </select>
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="menu_left_icon"><a href="http://fontawesome.io/icons/" target="_blank" title="Görüntülemek İçin Tıklayınız.">Yeni Menu Sol İcon </a></label>
            <div class="col-sm-8">
                <input type="text" id="menu_left_icon" name = "menu_left_icon" value="<?= htmlentities($menu_bilgisi->sol_icon) ?>" placeholder=" Giriniz" class="form-control">
            </div>  
        </div> 
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="menu_right_icon"><a href="http://fontawesome.io/icons/" target="_blank" title="Görüntülemek İçin Tıklayınız.">Yeni Menu Sağ İcon</a></label>
            <div class="col-sm-8">
                <input type="text" id="menu_right_icon" name = "menu_right_icon" value="<?= htmlentities($menu_bilgisi->sag_icon) ?>" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="order">Menu Sıralaması</label>
            <div class="col-sm-8">
                <input type="text" id="order" name = "order" value="<?= $menu_bilgisi->order_id ?>" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="disariya_acik_mi">Dışarıya Açık mı?</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="disariya_acik_mi" onchange="$('.ui-tooltip').remove();"  name = "disariya_acik_mi">
                    <option value="0" <?= $menu_bilgisi->disariya_acik_mi == 0 ? 'selected' : '' ?> >Hayır</option>
                    <option value="1" <?= $menu_bilgisi->disariya_acik_mi == 1 ? 'selected' : '' ?> >Evet</option>
                </select>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="menu_dili">Menu Dili</label>
            <div class="col-sm-8">
                <input type="text" id="menu_dili" name = "menu_dili" value="<?= $selected_language ?>" placeholder=" Giriniz" class="form-control">
            </div>  
        </div>
        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="aktif_mi">Menu Aktif mi?</label>
            <div class="col-sm-8">
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="aktif_mi" onchange="$('.ui-tooltip').remove();"  name = "aktif_mi">
                    <option value="0" <?= $menu_bilgisi->aktif_mi == 0 ? 'selected' : '' ?> >Pasif</option>
                    <option value="1" <?= $menu_bilgisi->aktif_mi == 1 ? 'selected' : '' ?> >Aktif</option>
                </select>
            </div>
        </div>        
        <div class="col-sm-12 form-group form-group-sm">
            <button type="submit" name="update" value="<?= $_POST['update']; ?>" class="btn bg-olive btn-block"><span class="fa fa-pencil-square-o"></span> <?= __("menu Güncelle") ?></button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#form_menu_guncelle').validate({
            rules: {
                menu_name: {required: true},
                menu_dili: {required: true}
            },
            messages: {
                menu_name: {required: "Menu Adı Girilmelidir. "},
                menu_dili: {required: "dil Girilmelidir. "}
            }
        });
    });
</script>