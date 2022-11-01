
<?php
require_once '../../lib/config.php';
require_once '../../lib/functions.php';

//$_POST = unserialize($_POST["params"]);
$html_pdf = "Bir aksilik oldu veriler yüklenmedi.";
if (isset($_POST['print1']) && in_array(YT_PDF, $sayfaIslemleriId)) {
    $secilen_ruhsat = mdecrypt($_POST['print1'], $_SESSION['key']);
    $ItemsSQL = "SELECT
                                                s_ruhsat_bilgileri.id, 
                                                s_ruhsat_bilgileri.ruhsat_no, 
                                                (CASE s_ruhsat_bilgileri.iskan_verildi_mi WHEN 1 THEN 'İskan Verildi' WHEN 0 THEN 'İskan Yok' END) AS iskan_verildi_mi, 
                                                DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,  
                                                s_ruhsat_bilgileri.adi_soyadi, 
                                                s_ruhsat_bilgileri.ruhsat_cinsi, 
                                                s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
                                                s_ruhsat_bilgileri.fenni_mesul, 
                                                DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
                                                s_ruhsat_bilgileri.mahallesi, 
                                                s_ruhsat_bilgileri.bulten_no, 
                                                s_ruhsat_bilgileri.ada_parsel, 
                                                s_ruhsat_bilgileri.yibf_no, 
                                                s_ruhsat_bilgileri.yapi_alani
                                        FROM
                                                s_ruhsat_bilgileri
                                                WHERE aktif_mi AND id = ? ";
    try {
        $secilen_ruhsat_bilgileri = $GLOBALS['db']->fetchRow($ItemsSQL, $secilen_ruhsat);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }

    $html_pdf = '<table>
<tr><td colspan="2" align="center"><strong>TC</strong> </td></tr>
<tr><td colspan="2" align="center"><strong>SİVEREK BELEDİYESİ</strong> </td></tr>
<tr><td colspan="2" align="center"> <strong>İMAR VE ŞEHİRCİLİK MÜDÜRLÜĞÜ</strong></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="left">
Siverek Belediye Meclisinin 07.10.2015 tarih ve 2464 sayılı belediye gelirler kanunun 96 maddesinin a- fırkasının 2. paragrafında belirlenen vergi ve harçlara ilişkin kanuna istinaden belirlenen, en az ve en çok sınırlarını aşmamak şartıyla vergi ve harçlara ilişkin tarifelerin 21. maddesindeki imar hizmetlerinin 1. ve 2. başlığına göre harç alınmasına arz ederim.
</td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="right"><strong>CANER ALTUNDAĞ </strong></td></tr>
<tr><td colspan="2" align="right"><strong>İmar ve Şehircilik Müd. V</strong></td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="left">Ekspertiz için imar planı örneği, ruhsat, yapı, kullanma izin belgesi örneği, proje vb. evrakların verilmesi-75 TL</td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="left><strong>Taşınmazın Sahibi</strong>:&nbsp;' . $secilen_ruhsat_bilgileri->adi_soyadi . '</td></tr>
<tr><td colspan="2" align="left"><strong>Taşınmazın Adresi</strong>:&nbsp;' . $secilen_ruhsat_bilgileri->mahallesi . '</td></tr>
<tr><td colspan="2" align="left"><strong>Ada/Parsel No</strong>:&nbsp;&nbsp;' . $secilen_ruhsat_bilgileri->ada_parsel . '</td></tr>
<tr><td colspan="2" align="left"><strong>Ruhsat Tarihi</strong>:&nbsp;&nbsp;' . $secilen_ruhsat_bilgileri->ruhsat_tarihi . '</td></tr>
<tr><td colspan="2" align="left"><strong>Ruhsat No</strong>:&nbsp;&nbsp;' . $secilen_ruhsat_bilgileri->ruhsat_no . '</td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="center"><hr></td></tr>
<tr><td colspan="2" align="left">NOT: Harcı yatırılacak kaleme "X" işareti koyunuz.</td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="left"><strong>ADI SOYADI :</strong></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="left"><strong>İLETİŞİM :</strong></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="right"><strong>GAYRİMENKUL ŞİRKETİNİN</strong></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="right"><strong>ADI:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="right"><strong>VERGİ NO:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="center"> <strong>PERSONEL ADI SOYADI</strong> </td></tr>
<tr><td colspan="2" align="center"> <strong>' . $kullanici . '</strong> </td></tr>
<tr><td colspan="2" align="center"><strong>İMZA</strong> </td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="center"> <strong></strong> </td></tr>
</table>';
}
?>

<?php
require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';
include_once '../../lib/DataTable.php';
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
        <?php include_once '../../assets/header.php'; ?>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <?php include_once '../../assets/menu.php'; ?>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <small><?= __("İmar Müdürlüğü") ?></small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="/"><i class="fa fa-cloud"></i><?= __("Ruhsat İşlemleri") ?></a></li>
                    <li class="active">Ekspertiz Raporu Hazırlama</li>
                </ol>
            </section>

            <!-- Main content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <section class="content-header"></section>
                        <div class="box box-primary">
                            <h4 class="box-title" style="margin-left: 5px;">Ekspertiz Raporu</h4>
                            <div class="box box-default"></div>
                            <div class="box-header with-border">
                                <form class="form-horizontal" method="post"  id="form_insert" action="ekspertiz_pdf.php">    

                                    <div class="form-group form-group-sm">
                                        <!--<label class="col-sm-2 control-label" for="ekpertiz_raporu">Ekspertiz Raporu</label>-->
                                        <div class="col-sm-12">
                                            <textarea class="form-control "  id="ekpertiz_raporu" name="ekpertiz_raporu" rows="10" cols="80"><?= htmlentities($html_pdf) ?></textarea>
                                        </div>  
                                    </div>      
                                    <div class="box-footer">
                                        <button class="btn bg-orange margin pull-right" type="cancel" onclick="window.location = 'index.php?sorgula&ruhsat_id=<?= (mcrypt($secilen_ruhsat_bilgileri->id, $_SESSION['key'])) ?>';return false;">Ruhsat Bilgilerine Dön</button>
                                        <input class="btn bg-olive margin pull-right" type="submit" value="Pdf Olarak Çıktı Al"/> 
                                        <input   type="hidden" name = "ekspertiz" value="<?= $_POST['print1'] ?>"/>
                                        <input   type="hidden" name = "dosya_adi" value="<?= $secilen_ruhsat_bilgileri->ruhsat_no. "_".$secilen_ruhsat_bilgileri->adi_soyadi?>"/>
                                    </div>
                                    <div class="form-group form-group-sm"></div>  
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div><!-- /.content -->

        <!-- /.content-wrapper -->

        <?php include_once '../../assets/footer.php';
        ?>


        <!-- Control Sidebar -->
        <?php // include_once '../assets/rightsidebar.php';   ?>
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
                                                var ThemeCkeditor = CKEDITOR.replace('ekpertiz_raporu');
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
                                                        $('#file_viewer').val("https://skspersonel.Siverek.edu.tr" + file.url);
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
            require_once '../../assets/alert.php';
            