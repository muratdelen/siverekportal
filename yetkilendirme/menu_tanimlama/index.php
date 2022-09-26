<?php
require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';
/**
 * NESTABLE
 * sürüklenebilir menü oluşturmak için kullanılır.
 * MENU OL OLUŞTURMA
 */
function GetMenuOLItemsHtml() {
    global $selected_language;
    $menu_html = "";
    try {
        $menuItems = $GLOBALS['db']->fetchAll("SELECT id, adi FROM yt_menu WHERE aktif_mi AND ISNULL(ana_menu_id) AND language = ? ORDER BY order_id", $selected_language);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    foreach ($menuItems as $menuItem) {
        $cryp_item = mcrypt($menuItem->id, $_SESSION['key']);
        $menu_html .= '<li class="dd-item dd3-item menu" data-id="' . $cryp_item . '"><div class="dd-handle dd3-handle"></div><div class="dd3-content"><a href="' . BASE_URL . 'yetkilendirme/menu_tanimlama/index.php?menu=' . urlencode($cryp_item) . '" title="Menü Düzenlemek İçin Tıklayınız." style="color:#367fa9;">' . $menuItem->adi . '</a></div>' . GetSubmenuOLItemsHtml($menuItem->id) . '</li>';
    }
    return '<ol class="dd-list">' . $menu_html . '</ol>';
}

function GetSubmenuOLItemsHtml($menu_id) {//üst menüden geldiğinde bunu yap
    global $selected_language;
    $menu_html = "";
    try {
        $menuItems = $GLOBALS['db']->fetchAll("SELECT id, adi FROM yt_menu WHERE aktif_mi AND language = ? AND ana_menu_id = ?  ORDER BY order_id", array($selected_language, $menu_id));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    foreach ($menuItems as $menuItem) {
        $cryp_item = mcrypt($menuItem->id, $_SESSION['key']);
        $menu_html .= '<li class="dd-item dd3-item menu" data-id="' . $cryp_item . '"><div class="dd-handle dd3-handle"></div><div class="dd3-content"><a href="' . BASE_URL . 'yetkilendirme/menu_tanimlama/index.php?menu=' . urlencode($cryp_item) . '" title="Menü Düzenlemek İçin Tıklayınız." style="color:#367fa9;">' . $menuItem->adi . '</a></div>' . GetSubmenuOLItemsHtml($menuItem->id) . '</li>';
    }
    if ($menu_html != "") {
        return '<ol class="dd-list">' . $menu_html . '</ol>';
    }
    return $menu_html;
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        configuration::getPageHeaderBefore("mainAssets");
        configuration::getPageHeaderBefore("DataTable Bootstrap");
        configuration::getPageHeaderBefore("jQueryUI");
        configuration::getPageHeaderBefore("Select2");
        configuration::getPageHeaderBefore("Nestable");
        ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php include_once '../../assets/header.php'; ?>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <?php include_once '../../assets/menu.php'; ?>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <?php
        include_once 'content.php';
        ?>
        <!-- /.content-wrapper -->

        <?php include_once '../../assets/footer.php';
        ?>


        <!-- Control Sidebar -->
        <?php include_once '../../assets/rightsidebar.php'; ?>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <?php
    configuration::getPageHeaderAfter("mainAssets");
    configuration::getPageHeaderAfter("FastClick");
    configuration::getPageHeaderAfter("Select2");

    configuration::getPageHeaderAfter("jQueryUI");
    configuration::getPageHeaderAfter("InputMask");

    configuration::getPageHeaderAfter("jQuery-Mask");
//// admin lte kullanımı içindir.
    configuration::getPageHeaderAfter("AdminLTE App");
////    configuration::getPageHeaderAfter("AdminLTE dashboard demo");
    configuration::getPageHeaderAfter("AdminLTE for demo purposes");
    configuration::getPageHeaderAfter("DataTables");
    configuration::getPageHeaderAfter("DataTablesColumnFilter");
    configuration::getPageHeaderAfter("Jquery Validation");
    configuration::getPageHeaderAfter("Nestable");
    ?>
    <script>

        $(document).ready(function ()
        {
            $('#menu-dd').nestable({ /* config options */ });
            // in init method or call 
//            var original = $('#menu').html();
//            // activate Nestable for list 1
//            $('#menu').nestable().on('change', function (e)
//            {
//                if ($('#menu > ol > .page').html() !== undefined) {
//                    $('#menu').html(original);
//                    adminLTE_alert(true, "Bu işlem Gerçekleştirilemez.", "Sayfa En Üstte Olmaz", "danger");
//                } else
//                if ($('.page').find('.menu').html() === undefined) {
//                    original = $('#menu').html();
//                } else {
//                    $('#menu').html(original);
//                    adminLTE_alert(true, "Bu işlem Gerçekleştirilemez.", "Sayfa Altında Kategori Olmaz", "danger");
//                }
//            });
            $('#menu-expand-all').click(function () {
                $('.dd').nestable('expandAll');
            });
            $('#menu-collapse-all').click(function () {
                $('.dd').nestable('collapseAll');
            });
            $('#menu-save').click(function () {
                $.post("ajax_menu_update.php", {new_menu: $('#menu-dd').nestable('serialize')})
                        .done(function (data) {
                            if (data == 1) {
                                adminLTE_alert(true, "Kategori Güncelleme", "Kategori Güncellendi.", "success");
                            } else {
                                adminLTE_alert(true, "Kategori Güncelleme", "Beklenmedik bir sorun oluştu.", "warning");
                            }
                            console.log(data);
                        });
            });
//            // activate Nestable for list 1
//            $('#menu').nestable().on('change', function (e)
//            {
//                var list = e.length ? e : $(e.target),
//                        output = list.data('output');
//                if (window.JSON) {
//                    $.post("ajax_menu_update.php", {new_menu: list.nestable('serialize')})
//                            .done(function (data) {
//                                if (data == 1) {
//                                    adminLTE_alert(false, "Kategori Güncelleme", "Kategori Güncellendi", "danger");
//                                }
//                                console.log(data);
//                            });
//                } else {
//                    adminLTE_alert(true, "Tarayıcınız Desteklememektedir", "Farklı bir tarayıcı kullanınız", "danger");
//                }
//            });
//
//            // activate Nestable for list 2
//            $('#nestable2').nestable({
//                group: 1
//            })
//                    .on('change', updateOutput);
//
//            // output initial serialised data
//            updateOutput($('#menu').data('output', $('#nestable-output')));
//            updateOutput($('#nestable2').data('output', $('#nestable2-output')));
//
//            $('#nestable-menu').on('click', function (e)
//            {
//                var target = $(e.target),
//                        action = target.data('action');
//                if (action === 'expand-all') {
//                    $('.dd').nestable('expandAll');
//                }
//                if (action === 'collapse-all') {
//                    $('.dd').nestable('collapseAll');
//                }
//            });

        });
    </script>

    <script>


        $(function () {

            $(".select2").select2();
            $(".money").mask("000.000.000.000,00", {reverse: true});
            $(".number_tc").mask("00000000000");
            $('.telefon').mask('(000) 000-0000');
            $.datepicker.setDefaults($.datepicker.regional["tr"]);
            $('.date').datepicker({
                inline: true
            });
        });
        //Datemask dd/mm/yyyy
        $(".date").inputmask("dd/mm/yyyy", {"placeholder": "gg/aa/yyyy"});
        //Datemask2 mm/dd/yyyy
//        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();



    </script>
</html>

