<?php
//$OpenamLoginControl = TRUE;
//include_once 'lib/openmaLoginControl.php';
require_once 'lib/config.php';
require_once 'lib/functions.php';
//require_once 'lib/LdapTransactions.php';
//require_once 'lib/input_filter.php';
//require_once 'lib/class.user.php';
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
        //SAYFA ÖNCESİ YÜKLENMESİ GEREKENLER
//
//ilk yüklenmede yüklenmesi gerekenler

        configuration::getPageHeaderBefore("mainAssets");
        configuration::getPageHeaderBefore("jQueryUI");
//görsel açıdan daha güzel radio checkbox butonlar için kullanılır. 
//        configuration::getPageHeaderBefore("iCheck");
////grafiksel görüntü için kullanılır.
////https://morrisjs.github.io/morris.js/
//        configuration::getPageHeaderBefore("Morris chart");
////Dünya haritası için kullanılır.
//        http://jvectormap.com/examples/world-gdp/
//        configuration::getPageHeaderBefore("jvectormap");
////jquery date picker için kullanılır.
//        configuration::getPageHeaderBefore("Date Picker");
////daha farklı bir datepicker için kullanılır.
////http://www.daterangepicker.com/
//        configuration::getPageHeaderBefore("Daterange picker");
////daha gelişmiş bootstrap özelliklei için kullanılır.
//        configuration::getPageHeaderBefore("bootstrap wysihtml5");
////günlük işlerin gösterilebileceği büyük bir takvim
////http://fullcalendar.io/
//        configuration::getPageHeaderBefore("fullCalendar 2.2.5");
////datepicker farklı bir gösterimi
////http://www.daterangepicker.com/
//        configuration::getPageHeaderBefore("daterange picker");
//// renk seçimi için kullanılır.
////http://mjolnic.com/bootstrap-colorpicker/
//        configuration::getPageHeaderBefore("Bootstrap Color Picker");
////zamanı seçmek için kullanılır.
////http://jdewit.github.io/bootstrap-timepicker/
//        configuration::getPageHeaderBefore("Bootstrap time Picker");
////daha fonksiyonlu selectbox için kullanılır.
////https://select2.github.io/examples.html
//        configuration::getPageHeaderBefore("Select2");
        ?>
        <style>   
            #portal-frame{
                height:100%;
                width:100%
            }
            .sutun_ic {
                background:#fff;
                height:100px;
                -webkit-box-shadow: 0 0 2px 1px rgba(0,0,0,0.2);
                box-shadow: 0 0 2px 1px rgba(0,0,0,0.2);
                margin: 1% 0 1% 1%;
            }
            .sutun_ic{
                padding:10px 10px 10px 75px;
                height:150px;
                display:flex;align-items:center;
            }
            .sutun_baslik{
                font-size:18px;
            }
            .sutun_ozet{
                font-size:12px;
            }
            .icon_eposta{
                background: #fff url(upload/images/icon_eposta.png) left center no-repeat;
            }
            .icon_eposta2{
                background:#fff url(upload/images/icon_eposta2.png) left center no-repeat;
            }
            .icon_sifre{
                background:#fff url(upload/images/icon_sifre.png) left center no-repeat;
            }
            .icon_sifreunut{
                background:#fff url(upload/images/icon_sifreunut.png) left center no-repeat;
            }
            .icon_sms{
                background:#fff url(upload/images/icon_sms.png) left center no-repeat;
            }
            .icon_huys{
                background:#fff url(upload/images/icon_huys.png) left center no-repeat;
            }
            .icon_yazilim{
                background:#fff url(upload/images/icon_yazilim.png) left center no-repeat;
            }
            .icon_sorun{
                background:#fff url(upload/images/icon_sorun.png) left center no-repeat;
            }
            .icon_sss{
                background:#fff url(upload/images/icon_sss.png) left center no-repeat;
            }
            .icon_arama{
                background:#fff url(upload/images/icon_arama.png) left center no-repeat;
            }
            .icon_tel{
                background:#fff url(upload/images/icon_tel.png) left center no-repeat;
            }
            .icon_user{
                background:#fff url(upload/images/icon_user.png) left center no-repeat;
            }


        </style>


        <style>
            .popover-navigation{
                border: 2px solid #c1c1c1;
            }
            .tour-step-background {
                background: transparent;
                border: 2px solid #f6a828;
            }
            .tour-backdrop {
                opacity:0.4;
            }
        </style>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php include_once 'assets/header.php'; ?>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <?php include_once 'assets/menu.php'; ?>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <?php
        include_once 'content.php';
        ?>
        <!-- /.content-wrapper -->

        <?php include_once 'assets/footer.php'; ?>


        <!-- Control Sidebar -->
        <?php // include_once 'assets/rightsidebar.php';   ?>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <?php
//SAYFA SONUNDA YÜKLENMESİ GEREKENLER
//
//  jquery ui gerektiğinde kullanılır.
    configuration::getPageHeaderAfter("mainAssets");
    configuration::getPageHeaderAfter("jQueryUI");
//grafik için kullanılır.
////https://morrisjs.github.io/morris.js/
//    configuration::getPageHeaderAfter("Morris.js charts");
////grafik için kullanılır
////http://omnipotent.net/jquery.sparkline/examples/simple.html
//    configuration::getPageHeaderAfter("Sparkline");
////Dünya haritası için kullanılır.
////http://jvectormap.com/examples/world-gdp/
//    configuration::getPageHeaderAfter("jvectormap");
////grafiksel görüntü için kullanılır.
////http://anthonyterrien.com/knob/
//    configuration::getPageHeaderAfter("jQuery Knob Chart");
////daha farklı bir datepicker için kullanılır.
////http://www.daterangepicker.com/
//    configuration::getPageHeaderAfter("daterangepicker");
////daha gelişmiş bootstrap özelliklei için kullanılır.
//    configuration::getPageHeaderAfter("Bootstrap WYSIHTML5");
////üst menü sabitlemek için kullanılır.
////http://jsfiddle.net/rgmrw/11/
//    configuration::getPageHeaderAfter("Slimscroll");
////    tıklanmasını sağlamak için kullanılır.
////http://ftlabs.github.io/fastclick/examples/layer.html
//    configuration::getPageHeaderAfter("FastClick");
////günlük işlerin gösterilebileceği büyük bir takvim
////http://fullcalendar.io/
//    configuration::getPageHeaderAfter("fullCalendar 2.2.5");
////datatable kullanımı içindir.
//    configuration::getPageHeaderAfter("DataTables");
////datatable filtremek içindir.
//    configuration::getPageHeaderAfter("DataTablesColumnFilter");
////         daha fonksiyonlu selectbox için kullanılır.
////       https://select2.github.io/examples.html
//    configuration::getPageHeaderAfter("Select2");
////textbox gibi girişleri filtrelemek için kullanılır
//    configuration::getPageHeaderAfter("InputMask");
////datepicker farklı bir gösterimi
////http://www.daterangepicker.com/
//    configuration::getPageHeaderAfter("date-range-picker");
//// renk seçimi için kullanılır.
////http://mjolnic.com/bootstrap-colorpicker/
//    configuration::getPageHeaderAfter("bootstrap color picker");
////zamanı seçmek için kullanılır.
////http://jdewit.github.io/bootstrap-timepicker/
//    configuration::getPageHeaderAfter("bootstrap time picker");
////görsel açıdan daha güzel radio checkbox butonlar için kullanılır.
//    configuration::getPageHeaderAfter("iCheck");
// admin lte kullanımı içindir.
    configuration::getPageHeaderAfter("AdminLTE App");
//    configuration::getPageHeaderAfter("AdminLTE dashboard demo");
//    configuration::getPageHeaderAfter("AdminLTE for demo purposes");
    ?>

</html>

<!--Kullanıcıya Uyarı verilmesi için kullanılır-->
<?php
if (isset($_SESSION["kullanici_adi"])) {
    include_once 'assets/alert.php';
}
?>

