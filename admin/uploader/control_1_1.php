<?php
require_once '../../lib/config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    <title>elFinder 2.1.x source version with PHP connector</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />
    <?php
    configuration::getPageHeaderBefore("mainAssets");
    configuration::getPageHeaderBefore("DataTable Bootstrap");
    configuration::getPageHeaderBefore("jQueryFlag");
    configuration::getPageHeaderBefore("jQueryUI");
    ?>
    <!-- jQuery and jQuery UI (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

        <!-- elFinder CSS (REQUIRED) -->
        <link rel="stylesheet" type="text/css" href="css/elfinder.min.css">
            <link rel="stylesheet" type="text/css" href="css/theme.css">

                <!-- elFinder JS (REQUIRED) -->
                <script src="js/elfinder.min.js"></script>

                <!-- GoogleDocs Quicklook plugin for GoogleDrive Volume (OPTIONAL) -->
                <!--<script src="js/extras/quicklook.googledocs.js"></script>-->

                <!-- elFinder translation (OPTIONAL) -->
                <script src="js/i18n/elfinder.<?= $selected_language ?>.js"></script>

                <!-- elFinder initialization (REQUIRED) -->
                <script type="text/javascript" charset="utf-8">
                    // Documentation for client options:
                    // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options



                    $().ready(function () {

                        var elf = $('#theme-uploader').elfinder({
                            url: 'php/connector.php',
                            lang: '<?= $selected_language ?>'
                        }).elfinder('instance');
                    });
                </script>
                </head>
                <body>
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
                    <div class="content-wrapper">
                        <section class="content">

                            <!-- Element where elFinder will be created (REQUIRED) -->
                            <div id="theme-uploader"></div>
                            <!-- /.content-wrapper -->
                        </section>
                    </div>

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
                configuration::getPageHeaderAfter("FastClick");
                configuration::getPageHeaderAfter("Select2");
                configuration::getPageHeaderAfter("jQueryFlag");

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
                configuration::getPageHeaderAfter("ckeditor");
                configuration::getPageHeaderAfter("Color Picker");
                ?>


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

                    //$(".accordion").accordion();

                    // open the color picker when the color preview is clicked
                    $('.color-view').click(function () {
                        $(this).prev().ColorPickerShow();
                    });
                    $('#selected-country').flagStrap({
                        buttonSize: "btn-md"
                                //            ,
                                //            countries: {
                                //                "TR": "Türkiye",
                                //                "AU": "Australia",
                                //                "GB": "United Kingdom",
                                //                "US": "United States"
                                //            }
                                //            ,
                                //            onSelect: function (value, element) {
                                //                console.log(value);
                                //            },
                                //            placeholder: {
                                //                value: "TR",
                                //                text: "Please select a country"
                                //            }
                    });
                    $('#page-selected-country').flagStrap({
                        buttonSize: "btn-md"
                                //            ,
                                //            countries: {
                                //                "TR": "Türkiye",
                                //                "AU": "Australia",
                                //                "GB": "United Kingdom",
                                //                "US": "United States"
                                //            }
                                //            ,
                                //            onSelect: function (value, element) {
                                //                console.log(value);
                                //            },
                                //            placeholder: {
                                //                value: "TR",
                                //                text: "Please select a country"
                                //            }
                    });
                    // initialize the color picker
                    $('.color').ColorPicker({
                        onSubmit: function (hsb, hex, rgb, el) {
                            // hide the color picker
                            $(el).ColorPickerHide();
                            $(el).val('#' + hex);
                        },
                        onBeforeShow: function () {
                            $(this).ColorPickerSetColor(this.value);
                        },
                        onChange: function (hsb, hex, rgb, el) {
                            // populate the input with the hex value
                            $("#menu_header_color").val('#' + hex);
                            // update the color preview          
                            $('#menu_header_color').css('border-color', '#' + hex);
                        }
                    });

                    $("#page-selected-country").change(function () {
                        $('.ui-tooltip').remove();
                        var formData = new Array();
                        formData.push({name: 'post_type', value: 100}, {name: 'selected_menu_id', value: ""}, {name: 'selected-language', value: $("#flagstrap-selected-language").val()});
                        $.ajax({
                            async: false,
                            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                            type: 'POST',
                            data: formData,
                            success: function (data) {
                                var obj = $.parseJSON(data);
                                var str_menu_option = '<option value="">Ana Menu</option>' + obj;
                                $("#page_main_menu").html(str_menu_option);
                                change_main_menu();
                            }
                        });
                    });
                    function change_main_menu() {
                        $('.ui-tooltip').remove();
                        var formData = new Array();
                        formData.push({name: 'post_type', value: 100}, {name: 'selected_menu_id', value: $("#page_main_menu").val()}, {name: 'selected-language', value: $("#flagstrap-selected-language").val()});
                        $.ajax({
                            async: false,
                            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                            type: 'POST',
                            data: formData,
                            success: function (data) {
                                var obj = $.parseJSON(data);
                                var str_menu_option = '<option value="">Baştaki Menu</option>' + obj;
                                $("#page_before_menu").html(str_menu_option);
                            }
                        });
                    }


                </script>
                </html>
                <?php
                require_once '../../assets/alert.php';
                