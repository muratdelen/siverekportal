<?php
require_once('../login/recaptchalib.php');
$siteKey = '6Lcm8g4TAAAAAIhZUtRLgnoDsU8cEC2neiMbCScS';
$secret = '6Lcm8g4TAAAAAEQsdFkOT2wE0553PtQmuD_QRvKD';
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>

            <small><?= __("ADMİN") ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-cloud"></i><?= __("Siverek Portal") ?></a></li>
            <li class="active"><?php
                if (isset($_GET['add']) && in_array(YT_INSERT, $sayfaIslemleriId)) {
                    echo __("Sayfa Ekleme");
                }//eğer güncelleme butonuna basıldı ise
                else if (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    echo __("Sayfa Güncelleme");
                } else {
                    echo __("Sayfa Görüntüleme");
                }
                ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <section class="content-header"></section>

                <?php
//yeni kayıt butonunana basıldı ise
                if (isset($_GET['add']) && in_array(YT_INSERT, $sayfaIslemleriId)) {
                    require_once 'page_new.php';
                }//eğer güncelleme butonuna basıldı ise
                else if (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    require_once 'page_update.php';
                } else {//normal görüntüleme
                    echo '<div class="row">
                            <div  class="col-md-12 box box-purple" id="sorgulama_ekrani_div">';
                    if (in_array(YT_INSERT, $sayfaIslemleriId)) {
                        echo '<div class="col-md-8" style="	margin-bottom: 10px;">
                            <form class="form-horizontal" method="GET" action="">
                                <input class="btn bg-green" type="submit" id="btn_yeni_kayit_ekle" name="add" value="YENİ SAYFA EKLE">
                            </form>  
                        </div>';
                    }
                    if (in_array(YT_QUERY, $sayfaIslemleriId)) {//eğer sorgula butonuna basıldı ise sor
                        ?>
                                <form class="form-horizontal" method="get" action="">
                                    <div class="form-group form-group-sm">
                                    </div>   
                                    <div class="form-group form-group-sm">
                                        <label class="col-sm-2 control-label" for="pages"><?= __("Sayfa Adı") ?></label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 select2-hidden-accessible"  onchange="$('#get-items').click();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="pages" name="pages">
                                                <option value=''>Listelenecek Sayfa Seçiniz</option>
                                                <?php
                                                $pagesSQL = "SELECT st_pages.id, st_pages.page_name FROM st_pages";
                                                try {
                                                    $pages = $db->fetchAll($pagesSQL);
                                                } catch (Zend_Db_Exception $ex) {
                                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                                }
                                                htmlspecialchar_obj($pages);
                                                foreach ($pages as $page) {
                                                    $id_sifreli = mcrypt($page->id, $_SESSION['key']);
                                                    echo "<option value='$id_sifreli' ";
                                                    echo (isset($page->id) ? (isset($_GET['pages']) ? ($_GET['pages'] == $id_sifreli ? 'selected' : null) : null) : null);
                                                    echo ">$page->page_name</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 form-group form-group-sm">
                                        <button type="submit" id="get-items" name="Sorgula" class="btn bg-purple btn-block"><span class="glyphicon glyphicon-search"></span> <?= __("Sayfa Ara") ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>



                        <?php
//                            if (isset($_GET['Sorgula']) && ((isset($_GET['sorgu_tc_vergi_numarasi']) && $_GET['sorgu_tc_vergi_numarasi'] != '') || (isset($_GET['sorgu_gercek_tuzel_kisi_adi']) && $_GET['sorgu_gercek_tuzel_kisi_adi'] != ''))) {
                        if (isset($_GET['Sorgula'])) {
                            if ($_GET['pages'] == "") {
                                $ItemsSQL = "SELECT st_pages.id, st_pages.page_name, st_pages.page_url, st_pages.page_language, st_pages.page_country, (CASE st_pages.is_active WHEN 1 THEN 'Yayındadır' WHEN 0 THEN 'Yayından Kaldırıldı' END) AS aktif_mi FROM st_pages";
                                try {
                                    $listItems = $GLOBALS['db']->fetchAll($ItemsSQL);
                                } catch (Zend_Db_Exception $ex) {
                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                }
                            } else {
                                $ItemsSQL = "SELECT st_pages.id, st_pages.page_name, st_pages.page_url, st_pages.page_language, st_pages.page_country, (CASE st_pages.is_active WHEN 1 THEN 'Yayindadır' WHEN 0 THEN 'Yayından Kaldırıldı' END) AS aktif_mi FROM st_pages
                                        WHERE st_pages.id = ? ";
                                $page_id = mdecrypt($_GET['pages'], $_SESSION['key']);
                                try {
                                    $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $page_id);
                                } catch (Zend_Db_Exception $ex) {
                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                }
                            }
                            htmlspecialchar_array($listItems);
                            if ((isset($listItems)) && !empty($listItems)) {

                                if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
                                    $Buttons = array('update' => 'Güncelle', 'remove' => 'Sil');
                                    $ButtonsUrls = array('new_tab' => 'preview.php', 'update' => 'index.php', 'remove' => 'postPage.php');
                                } else {
                                    $Buttons = array('update' => 'Güncelle');
                                    $ButtonsUrls = array('new_tab' => 'preview.php', 'update' => 'index.php');
                                }
                                $options2 = array(
                                    //zorunlu parametreler
                                    'tableHeaders' => array('Sayfa Adı', 'Sayfa Url', 'Sayfa Dili', 'Sayfa Ülkesi', 'Yayında Mıdır?'),
                                    //zorunlu olmayan parametreler
                                    //        'id' => 'example2' , // optional
                                    'order' => array(0, 'asc'),
                                    'tableFooters' => array('Sayfa Adı', 'Sayfa Url', 'Sayfa Dili', 'Sayfa Ülkesi', 'Yayında Mıdır?'), // optional
                                    'filters' => array('text', 'text', 'text', 'text', 'text'),
                                    //yerel parametreler
                                    'tableData' => $listItems,
                                    'processButtons' => array(
                                        'hasButton' => true,
                                        'buttonPostPage' => 'index.php',
                                        'buttons' => $Buttons,
                                        'buttonUrls' => $ButtonsUrls)
//                                    ,
//                                    'buttons' => array("excel", "pdf")
                                );
                                try {
                                    $dtableServer = new DataTable($options2);
                                } catch (Zend_Db_Exception $ex) {
                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                }
                                ?>
                                <div class="col-md-12">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Sorgulama Sonucu</h3>
                                            <?php
                                            echo $dtableServer->get_data_table();
                                            echo $dtableServer->get_datatable_script();
                                            ?>     </div><!-- /.box-body -->
                                    </div>
                                </div>
                                <?php
                            } else {
                                adminLTE_alert(false, __("Sorgulama Sonucu"), __("Sorgulamada kayıt bulunamadı!"), "warning");
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>

</div><!-- /.content -->
