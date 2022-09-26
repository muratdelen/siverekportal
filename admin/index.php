<?php

require_once '../lib/config.php';
require_once PROJECT_DIR . '/lib/functions.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
include_once PROJECT_DIR . '/lib/DataTable.php';
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
        configuration::getPageHeaderBefore("Color Picker");
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

        <?php
        include_once 'content.php';
        ?>
        <!-- /.content-wrapper -->

        <?php include_once '../assets/footer.php';
        ?>


        <!-- Control Sidebar -->
        <?php // include_once '../assets/rightsidebar.php';  ?>
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
    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="uploader/css/elfinder.min.css">
        <link rel="stylesheet" type="text/css" href="uploader/css/theme.css">

            <!-- elFinder JS (REQUIRED) -->
            <script src="uploader/js/elfinder.min.js"></script>

            <!-- GoogleDocs Quicklook plugin for GoogleDrive Volume (OPTIONAL) -->
            <!--<script src="js/extras/quicklook.googledocs.js"></script>-->

            <!-- elFinder translation (OPTIONAL) -->
            <script src="uploader/js/i18n/elfinder.<?= $selected_language ?>.js"></script>

            <script>
      try {
            $(".select2").select2();
            CKEDITOR.config.contentsCss = [CKEDITOR.basePath + 'contents.css', CKEDITOR.basePath + 'bootstrap/css/bootstrap.min.css'];
            CKEDITOR.config.allowedContent = true;
            CKEDITOR.config.language = '<?= $selected_language ?>';
            CKEDITOR.config.simpleImageBrowserURL = "<?= ASSETS_DIR ?>admin/upload/index.php";
            CKEDITOR.config.filebrowserBrowseUrl = "<?= ASSETS_DIR ?>admin/upload/index.php";
            CKEDITOR.config.allowedContent = true;
            CKEDITOR.dtd.$removeEmpty['i'] = false;
            CKEDITOR.config.extraAllowedContent = 'p[*] h1[*] h2[*] h3[*] h4[*] span[*] div[*] img[*] a[*]';
            var ThemeCkeditor = CKEDITOR.replace('page_content');
        } catch (err) {

        }
             
                $('.elfinder_upload_button').click(function () {
                    $('<div id="elfinder_editor" />').dialogelfinder({
                        url: 'uploader/php/connector.php',
                        getFileCallback: function (file) {
                            $('#elfinder_editor').dialogelfinder('close');
        //                    $('#elfinder_editor').closest('.elfinder').val(file.path);
        //                    console.log($('#editor').closest('.elfinder-input'));
                            $("#elfinder_editor").remove();
                            $('.elfinder_upload_url').val(file.url);
                        }
                    });
                });
                
                
               
                 $("#file_viewer_button").click(function () {
            $('<div id="elfinder_editor" />').dialogelfinder({
                url: '/upload/uploader/php/connector.php',
                getFileCallback: function (file) {
                    $('#elfinder_editor').dialogelfinder('close');
                    //                    $('#elfinder_editor').closest('.elfinder').val(file.path);
                    //                    console.log($('#editor').closest('.elfinder-input'));
                    $("#elfinder_editor").remove();
                    $('#file_viewer').val("https://skspersonel.Siverek.edu.tr"+file.url);
                    $("#file_viewer").change();
                }
            });
        });

        $("#file_viewer").change(function () {
            if ($.trim($("#file_viewer").val()) !== "") {
                var ckeditor_html = ThemeCkeditor.getData() + '<iframe src="//docs.google.com/gview?url=' + $("#file_viewer").val() + '&embedded=true" class="change-border-color" style="width:100%; height: 200vh; border-style:solid; border-width: 5px 0 5px 0;" frameborder="0"></iframe>';
                ThemeCkeditor.setData(ckeditor_html);
            }
        });
            </script>
            </html>
            <?php
            require_once '../assets/alert.php';
            