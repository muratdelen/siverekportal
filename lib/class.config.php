<?php

define('CDN_DIR', '/siverekportal/cdn');

class configuration {
    //put your code here 

    /**
     * 
     * @global strıng $BASE_URL
     */
    function __construct() {
        //sayfanın anadizini ayarlanmaktadır.
    }

    /*
     * php sayfaları için gerekli olan plugin desteği sağlar
     * sayfada html sonuna eklenir
     */

    static function getPageHeaderBefore($page_service_name) {

        $return_string = '<!--Bu uygulama bilgisayar Mühendisi Murat DELEN tarafından yapılmıştır.-->';

        switch ($page_service_name) {
            case "mainAssets":
                $return_string .= ' <meta charset="utf-8">';
                $return_string .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
                $return_string .= '<link rel="Shortcut Icon" href="' . CDN_DIR . '/favicon/favicon.ico" type="image/x-icon">';
//                $return_string .= '<link rel="apple-touch-icon" sizes="57x57" href="' . CDN_DIR . '/favicon/apple-icon-57x57.png">
//                                    <link rel="apple-touch-icon" sizes="60x60" href="' . CDN_DIR . '/favicon/apple-icon-60x60.png">
//                                    <link rel="apple-touch-icon" sizes="72x72" href="' . CDN_DIR . '/favicon/apple-icon-72x72.png">
//                                    <link rel="apple-touch-icon" sizes="76x76" href="' . CDN_DIR . '/favicon/apple-icon-76x76.png">
//                                    <link rel="apple-touch-icon" sizes="114x114" href="' . CDN_DIR . '/favicon/apple-icon-114x114.png">
//                                    <link rel="apple-touch-icon" sizes="120x120" href="' . CDN_DIR . '/favicon/apple-icon-120x120.png">
//                                    <link rel="apple-touch-icon" sizes="144x144" href="' . CDN_DIR . '/favicon/apple-icon-144x144.png">
//                                    <link rel="apple-touch-icon" sizes="152x152" href="' . CDN_DIR . '/favicon/apple-icon-152x152.png">
//                                    <link rel="apple-touch-icon" sizes="180x180" href="' . CDN_DIR . '/favicon/apple-icon-180x180.png">
//                                    <link rel="icon" type="image/png" sizes="192x192"  href="' . CDN_DIR . '/favicon/android-icon-192x192.png">
//                                    <link rel="icon" type="image/png" sizes="32x32" href="' . CDN_DIR . '/favicon/favicon-32x32.png">
//                                    <link rel="icon" type="image/png" sizes="96x96" href="' . CDN_DIR . '/favicon/favicon-96x96.png">
//                                    <link rel="icon" type="image/png" sizes="16x16" href="' . CDN_DIR . '/favicon/favicon-16x16.png">
//                                    <link rel="manifest" href="' . CDN_DIR . '/favicon/manifest.json">
//                                    <meta name="msapplication-TileColor" content="#ffffff">
//                                    <meta name="msapplication-TileImage" content="' . CDN_DIR . '/favicon/ms-icon-144x144.png">
//                                    <meta name="theme-color" content="#ffffff">';
                $return_string .= '<title>Siverek Portal </title>';
//    <!-- Tell the browser to be responsive to screen width -->
                $return_string .= '<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">';
//    <!-- Theme style Reset-->
                $return_string .= '<link rel="stylesheet" type="text/css" href="' . CDN_DIR . '/dist/css/normalize.css"/>';
//    <!-- Bootstrap 3.3.5 -->
                $return_string .= '<link rel="stylesheet" href="' . ASSETS_DIR . '/bootstrap/css/bootstrap.min.css"/>';
//    <!-- Font Awesome -->
                $return_string .= ' <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>';
//    <!-- Ionicons -->
                $return_string .= ' <link rel="stylesheet" href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"/>';
//    <!-- Theme style -->
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/dist/css/AdminLTE.css"/>';
//    <!-- AdminLTE Skins. Choose a skin from the css/skins
//         folder instead of downloading all of them to reduce the load. -->
//                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/dist/css/skins/_all-skins.min.css"/>';

                $return_string .= '<script src="' . CDN_DIR . '/plugins/jQuery/jQuery-2.1.4.min.js"></script>';
                $return_string .= '<script src="' . CDN_DIR . '/bootstrap/js/bootstrap.min.js"></script>';
                $return_string .= '<link rel="stylesheet" type="text/css" href="' . CDN_DIR . '/plugins/jQuery-Flag/jquery.flagstrap.css"/>';
                $return_string .= '<script src="' . CDN_DIR . '/plugins/bootstrap-tour/js/bootstrap-tour.js"></script>';
                $return_string .= '<link rel="stylesheet" type="text/css" href="' . CDN_DIR . '/plugins/bootstrap-tour/css/bootstrap-tour.css"/>';
                //    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
//    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                $return_string .= '<!--[if lt IE 9]>
                <script src="' . CDN_DIR . '/dist/js/html5shiv.min.js"></script>
                <script src="' . CDN_DIR . '/dist/js/respond.min.js"></script>
                 <![endif]-->';
//                $return_string .= '<style>
//                                    .btn-file {
//                                        position: relative;
//                                        overflow: hidden;
//                                    }
//                                    .btn-file input[type=file] {
//                                        position: absolute;
//                                        top: 0;
//                                        right: 0;
//                                        min-width: 100%;
//                                        min-height: 100%;
//                                        font-size: 100px;
//                                        text-align: right;
//                                        filter: alpha(opacity=0);
//                                        opacity: 0;
//                                        outline: none;
//                                        background: white;
//                                        cursor: inherit;
//                                        display: block;
//                                    }
//                                    </style>';
                break;
            case "jQueryUI":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/jQueryUI/jquery-ui.css"/>';
                break;
            case "Slider":
                $return_string .= ' <link rel="stylesheet" type="text/css" href="' . CDN_DIR . '/template/css/slider.css"/>';
                break;
            case "iCheck":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/iCheck/flat/blue.css"/';
//                <!-- iCheck for checkboxes and radio inputs -->
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/iCheck/all.css"/>';
                break;
            case "Morris chart":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/morris/morris.css"/>';
                break;
            case "jvectormap":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/jvectormap/jquery-jvectormap-1.2.2.css"/>';
                break;
            case "Date Picker":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/datepicker/datepicker3.css"/>';
                break;
            case "DateTimePicker":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/datetimepicker/bootstrap-datetimepicker.min.css"/>';
                break;
            case "Daterange picker":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/daterangepicker/daterangepicker-bs3.css"/>';
                break;
            case "bootstrap wysihtml5":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"/>';
                break;
            case "fullCalendar 2.2.5":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/fullcalendar/fullcalendar.min.css"/>
                <link rel="stylesheet" href="' . CDN_DIR . '/plugins/fullcalendar/fullcalendar.print.css" media="print"/>';
                break;
            case "Bootstrap Color Picker":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/colorpicker/bootstrap-colorpicker.min.css"/>';
                break;
            case "Bootstrap time Picker":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/timepicker/bootstrap-timepicker.min.css"/>';
                break;
            case "DataTable Bootstrap":
                $return_string .= '
                                <link rel="stylesheet" href="' . CDN_DIR . '/plugins/datatables2/dataTables.responsive.css">
                                <link rel="stylesheet" href="' . CDN_DIR . '/plugins/datatables/dataTables.bootstrap.css">';
                break;
            case "Select2":
                $return_string .= '<link rel="stylesheet" href="' . CDN_DIR . '/plugins/select2/select2.min.css"/>';
                break;
            case "Nestable":
                $return_string .= '<link rel="stylesheet" type="text/css" href="' . CDN_DIR . '/plugins/nestable/style.css"/>';
                break;
            default:
                break;
        }


        echo $return_string;
    }

    static function getPageHeaderAfter($page_service_name) {
        global $selected_language;
        $return_string = '';
        switch ($page_service_name) {
            case "mainAssets":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/jQuery-Flag/jquery.flagstrap.js"></script>';
                $return_string .= '<script>
      $("#selected-country").flagStrap({
            buttonSize: "btn-sm",
            countries: {
               "TR": "Türkçe",
//               "AU": "Australia",
               "GB": "English",
//               "US": "United States",
//               "CN": "China",
//               "EG": "Egypt",
//               "IR": "Iran, Islamic Republic of",
//               "IQ": "Iraq",
//               "IT": "Italy",
//               "RU": "Russian Federation",
           }
//            ,
//            onSelect: function (value, element) {
//                console.log(value);
//            },
//            placeholder: {
//                value: "TR",
//                text: "Please select a country"
//            }
        });
            $(document).ready(function(){
      $(\'[data-toggle="tooltip"]\').tooltip();  
});
        </script>';
                $return_string .= '<script>$(document).ready(function(){$(\'[data-toggle="tooltip"]\').tooltip();});</script>';
                $logo_file_directory = LOCALE_DIR . "/_logo_images/logo_" . $selected_language . ".png";
                if (file_exists($logo_file_directory)) {
                    $return_string .= '<style> .main-header .logo {background:url(' . BASE_URL . 'locale/_logo_images/logo_' . $selected_language . '.png) left no-repeat;}</style>';
                } else {
                    $return_string .= '<style> .main-header .logo {background:url(' . BASE_URL . 'locale/_logo_images/logo.png) left no-repeat;}</style>';
                }
                $return_string .= '<div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade" id="lte-modal-container" role="dialog">
                                    <div class="modal-dialog modal-lg" id="lte-modal">
                                        <div class="modal-content">
                                            <div class="modal-header" id="lte-modal-header"><button aria-hidden="true" class="close" data-dismiss="modal" type="button">&times;</button>
                                                <h4 class="modal-title" id="lte-modal-title"></h4>
                                            </div>
                                            <div class="modal-body" id="lte-modal-body">
                                            
                                            </div>
                                            <div class="modal-footer"><button class="btn btn-default" data-dismiss="modal" type="button">' . __('Kapat') . '</button></div>
                                        </div>
                                    </div>
                                </div>';
                break;
            case "jQuery 2.1.4":
                //        <!-- jQuery 2.1.4 -->
//                $return_string .= '<script src="' . CDN_DIR . '/plugins/jQuery/jQuery-2.1.4.min.js"></script>';
                break;
            case "jQueryMobile":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/jQuery-Mobile/jquery.mobile-1.4.5.js"></script>';
                break;
            case "jQueryUI":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/jQueryUI/jquery-ui.js"></script>';
                break;
            case "jQuery UI 1.11.4":
                $return_string .= '<script src="' . CDN_DIR . '/dist/js/jquery-ui.min.js"></script>';
                break;
            case "jQueryFlag":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/jQuery-Flag/jquery.flagstrap.js"></script>';
                break;
            case "Bootstrap 3.3.5":
                //    <!-- Bootstrap 3.3.5 -->
//                $return_string .= '<script src="' . CDN_DIR . '/bootstrap/js/bootstrap.min.js"></script>';
                break;
            case "Morris.js charts":
                $return_string .= '<script src="' . CDN_DIR . '/dist/js/raphael-min.js"></script>
                <script src="' . CDN_DIR . '/plugins/morris/morris.min.js"></script>';
                break;
            case "Sparkline":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/sparkline/jquery.sparkline.min.js"></script>';
                break;
            case "jvectormap":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
                <script src="' . CDN_DIR . '/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>';
                break;
            case "jQuery Knob Chart":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/knob/jquery.knob.js"></script>';
                break;
            case "daterangepicker":
                $return_string .= '<script src="' . CDN_DIR . '/dist/js/moment.min.js"></script>
                 <script src="' . CDN_DIR . '/plugins/daterangepicker/daterangepicker.js"></script>';
                break;
            case "datepicker":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/datepicker/bootstrap-datepicker.js"></script>';
                break;
            case "DateTimePicker":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/datetimepicker/bootstrap-datetimepicker.min.js"></script>';
                break;
            case "Bootstrap WYSIHTML5":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>';
                break;
            case "VivaGraphJS":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/VivaGraphJS/vivagraph.min.js"></script>';
                break;
            case "Slimscroll":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/slimScroll/jquery.slimscroll.min.js"></script>';
                break;
            case "FastClick":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/fastclick/fastclick.min.js"></script>';
                break;
            case "fullCalendar 2.2.5":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/fullcalendar/moment.min.js"></script>
                 <script src="' . CDN_DIR . '/plugins/fullcalendar/fullcalendar.min.js"></script>';
                break;
            case "DataTables":
                $return_string .= '
                <script src="' . CDN_DIR . '/plugins/datatables2/jquery.dataTables.min.js"></script>
                <script src="' . CDN_DIR . '/plugins/datatables2/dataTables.min.js"></script>
                <script src="' . CDN_DIR . '/plugins/datatables2/dataTables.responsive.js"></script>';
                $return_string .= '
                 <script src="' . CDN_DIR . '/plugins/datatables/dataTables.bootstrap.min.js"></script>
                 <script src="' . CDN_DIR . '/plugins/datatables/fnPagingInfo.js"></script>';

                break;
            case "DataTables2":
                $return_string .= '<link rel="stylesheet" type="text/css" href="' . CDN_DIR . '/plugins/datatables2/dataTables.bootstrap.min.css">
                <link rel="stylesheet" type="text/css" href="' . CDN_DIR . '/plugins/datatables2/responsive.bootstrap.min.css">';

                $return_string .= '
                <script src="' . CDN_DIR . '/plugins/datatables2/jquery.dataTables.min.js"></script>
                <script src="' . CDN_DIR . '/plugins/datatables2/dataTables.bootstrap.min.js"></script>
                <script src="' . CDN_DIR . '/plugins/datatables2/dataTables.responsive.min.js"></script>
                <script src="' . CDN_DIR . '/plugins/datatables2/responsive.bootstrap.min.js"></script>
                <script src="' . CDN_DIR . '/plugins/datatables/fnPagingInfo.js"></script>';
                break;
            case "DataTablesColumnFilter":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/datatables/jquery.dataTables.columnFilter.js"></script>';
                break;
            case "Select2":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/select2/select2.full.min.js"></script>';
                break;
            case "InputMask":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/input-mask/jquery.inputmask.js"></script>
                 <script src="' . CDN_DIR . '/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
                 <script src="' . CDN_DIR . '/plugins/input-mask/jquery.inputmask.extensions.js"></script>';
                break;
            case "jQuery-Mask":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/jQuery-Mask/jquery.mask.js"></script>';
                break;
            case "date-range-picker":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/daterangepicker/moment.min.2.10.2.js"></script>
                 <script src="' . CDN_DIR . '/plugins/daterangepicker/daterangepicker.js"></script>';
                break;
            case "bootstrap color picker":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>';
                break;
            case "bootstrap time picker":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/timepicker/bootstrap-timepicker.min.js"></script>';
                break;
            case "Jquery Validation":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/jquery-validation/jquery.validate.min.js"></script>';
                $return_string .= '<script src="' . CDN_DIR . '/plugins/jquery-validation/localization/messages_tr.js"></script>';
                break;
            case "iCheck":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/iCheck/icheck.min.js"></script>';
                break;
            case "AdminLTE App":
                $return_string .= '<script src="' . CDN_DIR . '/dist/js/app.min.js"></script>';
                break;
            case "AdminLTE dashboard demo":
                $return_string .= '<script src="' . CDN_DIR . '/dist/js/pages/dashboard.js"></script>';
                break;
            case "AdminLTE for demo purposes":
                $return_string .= '<script src="' . CDN_DIR . '/dist/js/demo.js"></script>';
                break;
            case "ckeditor":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/ckeditor/ckeditor.js"></script>';
                break;
            case "Nestable":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/nestable/jquery.nestable.js"></script>';
                break;
            case "Webcam":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/webcamjs/webcam.js"></script>';
                break;
            case "Full Screen":
                $return_string .= '<script src="' . CDN_DIR . '/plugins/FullScreen/jquery.fullscreen-0.4.1.min.js"></script>';
                break;
            default:
                break;
        }


        echo $return_string;
    }

}

?>