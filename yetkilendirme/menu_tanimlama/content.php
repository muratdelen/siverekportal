
<?php
include_once '../../lib/DataTable.php';
//EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
?>
<div class="content-wrapper">    
    <section class="content-header">
        <h1>
            <small><?php echo __("YETKİLENDİRME") ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-cloud"></i><?php echo __("Admin") ?></a></li>
            <li class="active"><?php echo __("Menu Tanımlama İşlemleri") ?></li>
        </ol>
    </section>
    <section class="container-fluid">
        <div class="row">
            <?php
//yeni kayıt butonunana basıldı ise
            if (isset($_GET['new'])) {
                if (in_array(YT_INSERT, $sayfaIslemleriId)) {
                    require_once 'yeni_kayit.php';
                }
            }//eğer güncelleme butonuna basıldı ise
            else if (isset($_POST['update']) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
                if (in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    require_once 'guncelle.php';
                }
            } else {
                if (in_array(YT_INSERT, $sayfaIslemleriId)) {
                    echo '<div class="col-md-8" style="	margin-bottom: 10px;">
                                <a href="?new" class="btn bg-purple" >YENİ MENU EKLE</a>  
                        </div>';
                }
                if (in_array(YT_QUERY, $sayfaIslemleriId)) {//eğer sorgula butonuna basıldı ise sor
                    ?>
                    <div  class="col-md-12 box box-primary" id="sorgulama_ekrani_div">
                        <form class="form-horizontal" id="ihale_sorgula" method="GET" action="">   
                            <div class="form-group form-group-sm"></div> 
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="menu">Menu Adı</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" onchange="$('#get-items').click();$('.ui-tooltip').remove();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="menu" name="menu">
                                        <option value=''>Seçiniz</option>
                                        <?php
                                        if (isset($_GET['menu']) && $_GET['menu'] != "") {
                                            $menu_id = mdecrypt($_GET['menu'], $_SESSION['key']);
                                            echo get_menu_items_option_html_selected($menu_id);
                                        } else {
                                            echo get_menu_items_option_html();
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" id="get-items" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-search"></span> <?= __("Menu Detayı Göster") ?></button>
                            </div>
                        </form>
                    </div>
                    <?php
                    if (isset($_GET['menu'])) {
                        if ($_GET['menu'] == "") {
                            $ItemsSQL = "SELECT
                                            (SELECT yt_menu.adi FROM yt_menu WHERE yt_menu.id = selected_menu.ana_menu_id) AS main_menu,
                                            selected_menu.id, selected_menu.adi, selected_menu.sayfa_url, (CASE selected_menu.disariya_acik_mi WHEN 1 THEN '" . __("Dışarıya Açık") . "' WHEN 0 THEN '" . __("Sadece Girişli") . "' END ) AS disariya_acik_mi, selected_menu.`language`, (CASE selected_menu.aktif_mi WHEN 1 THEN '" . __("Aktif") . "' WHEN 0 THEN '" . __("Pasif") . "' END) AS aktif_mi
                                        FROM yt_menu selected_menu";
                            try {
                                $listItems = $GLOBALS['db']->fetchAll($ItemsSQL);
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            }
                        } else {
                            $menu_id = mdecrypt($_GET['menu'], $_SESSION['key']);
                            $ItemsSQL = "SELECT
                                            (SELECT yt_menu.adi FROM yt_menu WHERE yt_menu.id = selected_menu.ana_menu_id) AS main_menu,
                                            selected_menu.id, selected_menu.adi, selected_menu.sayfa_url, (CASE selected_menu.disariya_acik_mi WHEN 1 THEN '" . __("Dışarıya Açık") . "' WHEN 0 THEN '" . __("Sadece Girişli") . "' END ) AS disariya_acik_mi, selected_menu.`language`, (CASE selected_menu.aktif_mi WHEN 1 THEN '" . __("Aktif") . "' WHEN 0 THEN '" . __("Pasif") . "' END) AS aktif_mi
                                        FROM yt_menu selected_menu WHERE id = ? ";
                            try {
                                $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $menu_id);
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            }
                        }
                        htmlspecialchar_array($listItems);

                        if ((isset($listItems)) && !empty($listItems)) {
                            if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
                                $Buttons = array('details' => 'Ayrıntı', 'update' => 'Güncelle', 'remove' => 'Sil');
                                $ButtonsUrls = array('details' => 'postPage.php', 'update' => 'index.php', 'remove' => 'postPage.php');
                            } else {
                                $Buttons = array('details' => 'Ayrıntı', 'update' => 'Güncelle');
                                $ButtonsUrls = array('details' => 'postPage.php', 'update' => 'index.php');
                            }
                            $options2 = array(
                                //zorunlu parametreler
                                'tableHeaders' => array('Ana Menu', 'Menu Adı', 'Sayfa Url', 'Dışarıya Açık mı?', 'Dili', 'Aktif mi?'),
                                //zorunlu olmayan parametreler
                                //        'id' => 'example2' , // optional
                                'order' => array(0, 'asc'),
                                'tableFooters' => array('Ana Menu', 'Menu Adı', 'Sayfa Url', 'Dışarıya Açık mı?', 'Dili', 'Aktif mi?'), // optional
                                'filters' => array('text', 'text', 'text', 'text', 'text', 'text'),
                                //yerel parametreler
                                'tableData' => $listItems,
                                'processButtons' => array(
                                    'hasButton' => true,
                                    'buttonPostPage' => 'index.php',
                                    'buttons' => $Buttons,
                                    'buttonUrls' => $ButtonsUrls),
                                'buttons' => array("excel", "pdf")
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
                            adminLTE_alert('Sorgulamada kayıt bulunamadı. ', "warning");
                        }
                    }
                }
                 echo '<div  class="col-md-12 box box-primary">
                        <div class="col-md-3"><button type="button" id="menu-expand-all" class="btn btn-block btn-info">' . __("Alt Menuleri Aç") . '</button></div>
                        <div class="col-md-3"><button type="button" id="menu-collapse-all" class="btn btn-block btn-warning">' . __("Alt Menuleri Kapat") . '</button></div>   
                         <div class="col-md-12"></div>   
                         <div class="dd center" id="menu-dd">
                            ' . GetMenuOLItemsHtml() . '
                        </div>
                        <div class="col-md-12 form-group form-group-sm"><button type="button" id="menu-save" class="btn btn-success btn-block"><span class="fa fa-floppy-o"></span>' . __("Menu Sıralamasını Kaydet") . '</button> </div>         
                    </div>';
            }
            ?>

        </div>
    </section>
</div>