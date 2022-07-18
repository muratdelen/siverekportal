<?php
require_once '../../lib/config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    <title>Theme Uploader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />

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

                    
                    function getUrlParam(paramName) {
                        var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i');
                        var match = window.location.search.match(reParam);

                        return (match && match.length > 1) ? match[1] : '';
                    }

                    $().ready(function () {
                        var funcNum = getUrlParam('CKEditorFuncNum');

                        var elf = $('#theme-uploader').elfinder({
                            url: 'php/connector.php',
                            lang: '<?= $selected_language ?>',
                            getFileCallback: function (file) {
                                window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                                elf.destroy();
                                window.close();
                            },
                            resizable: false
                        }).elfinder('instance');
                    });
                </script>
                </head>
                <body>

                    <!-- Element where elFinder will be created (REQUIRED) -->
                <div id="theme-uploader"></div>

                </body>
                </html>