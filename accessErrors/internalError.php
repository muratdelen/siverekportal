<?php
require_once '../lib/config.php';
require_once '../lib/functions.php';
//require_once '../lib/LdapTransactions.php';
require_once '../lib/input_filter.php';
//require_once '../lib/class.user.php';
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
//        configuration::getPageHeaderBefore("iCheck");
//        configuration::getPageHeaderBefore("Morris chart");
//        configuration::getPageHeaderBefore("jvectormap");
//        configuration::getPageHeaderBefore("Date Picker");
//        configuration::getPageHeaderBefore("Daterange picker");
//        configuration::getPageHeaderBefore("bootstrap wysihtml5");
//        configuration::getPageHeaderBefore("fullCalendar 2.2.5");
        ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php include_once '../assets/header.php'; ?>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <?php include_once '../assets/menu.php'; ?>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    500
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Ana Sayfa</a></li>
                    <li><a href="#">Erişim Hatası</a></li>
                    <li class="active">Sunucu hatası</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="error-page">
                    <h2 class="headline text-yellow">500</h2>
                    <div class="error-content">
                        <h3><i class="fa fa-warning text-yellow"></i> Sayfa bulunamadı.</h3>
                        <p>
                            Aradığın sayfayı bulamadık. <br>
                            Aradığın sayfayı <u><a href="<?php echo BASE_URL; ?>">burayı</a></u> tıklatarak bulabilirsin.
                        </p>
                        <form class="search-form">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
                                    </div>
                            </div><!-- /.input-group -->
                        </form>
                    </div><!-- /.error-content -->
                </div><!-- /.error-page -->
            </section><!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include_once '../assets/footer.php'; ?>


        <!-- Control Sidebar -->
        <?php // include_once '../assets/rightsidebar.php'; ?>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <?php
    configuration::getPageHeaderAfter("mainAssets");
    configuration::getPageHeaderAfter("jQuery 2.1.4");
//    configuration::getPageHeaderAfter("jQuery UI 1.11.4");
//    echo '<script>$.widget.bridge("uibutton", $.ui.button);</script>'; 
    configuration::getPageHeaderAfter("Bootstrap 3.3.5");
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
//    configuration::getPageHeaderAfter("fullCalendar 2.2.5");
    ?>

</html>
<?php 