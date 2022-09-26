<?php
require_once '../../lib/config.php';
require_once PROJECT_DIR . '/lib/functions.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
require_once PROJECT_DIR . '/lib/DataTable.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        configuration::getPageHeaderBefore("mainAssets");
//        configuration::getPageHeaderBefore("iCheck");
//        configuration::getPageHeaderBefore("Morris chart");
//        configuration::getPageHeaderBefore("jvectormap");
//        configuration::getPageHeaderBefore("Date Picker");
//        configuration::getPageHeaderBefore("Daterange picker");
//        configuration::getPageHeaderBefore("bootstrap wysihtml5");
        configuration::getPageHeaderBefore("DataTable Bootstrap");
        ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php require_once PROJECT_DIR. '/assets/header.php'; ?>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <?php require_once PROJECT_DIR. '/assets/menu.php'; ?>
                <!-- /.sidebar -->
            </aside>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <?php
        require_once  'content.php';
        ?>
        <!-- /.content-wrapper -->

        <?php require_once PROJECT_DIR. '/assets/footer.php'; ?>


        <!-- Control Sidebar -->
        <?php // require_once PROJECT_DIR. '/assets/rightsidebar.php'; ?>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <?php
        configuration::getPageHeaderAfter("mainAssets");
//    configuration::getPageHeaderAfter("jQuery UI 1.11.4");
//    echo '<script>$.widget.bridge("uibutton", $.ui.button);</script>'; 
//    configuration::getPageHeaderAfter("Morris.js charts");
//    configuration::getPageHeaderAfter("Sparkline");
//    configuration::getPageHeaderAfter("jvectormap");
//    configuration::getPageHeaderAfter("jQuery Knob Chart");
//    configuration::getPageHeaderAfter("daterangepicker");
//    configuration::getPageHeaderAfter("Bootstrap WYSIHTML5");
//    configuration::getPageHeaderAfter("Slimscroll");
    configuration::getPageHeaderAfter("FastClick");
    configuration::getPageHeaderAfter("AdminLTE App");
//    configuration::getPageHeaderAfter("AdminLTE dashboard demo");
    configuration::getPageHeaderAfter("AdminLTE for demo purposes");
    configuration::getPageHeaderAfter("DataTables2");
    configuration::getPageHeaderAfter("DataTablesColumnFilter");
    ?>
</body>
</html>
<!--Kullanıcıya Uyarı verilmesi için kullanılır-->
<?php
if (isset($_SESSION["kullanici_adi"])) {
    require_once PROJECT_DIR. '/assets/alert.php';
}
?>