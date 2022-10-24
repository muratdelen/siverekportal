<?php
require_once '../../lib/config.php';
require_once PROJECT_DIR . 'lib/functions.php';
require_once PROJECT_DIR . 'lib/input_filter.php';
include_once PROJECT_DIR . 'lib/DataTable.php';
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
        ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php include_once ASSETS_DIR . 'header.php'; ?>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <?php include_once ASSETS_DIR . 'menu.php'; ?>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <?php
        include_once 'content.php';
        ?>
        <!-- /.content-wrapper -->

        <?php include_once ASSETS_DIR . 'footer.php';
        ?>


        <!-- Control Sidebar -->
        <?php include_once ASSETS_DIR . 'rightsidebar.php'; ?>
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
    ?>
    <script>

// $('.sidebar-mini').addClass('sidebar-collapse'); 
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

<?php
require_once ASSETS_DIR . 'alert.php';
