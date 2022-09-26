
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
            <li class="active"><?php echo __("Grup Şeması") ?></li>
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
                                <a href="?new" class="btn bg-purple" >YENİ ALT GRUP EKLE</a>  
                        </div>';
                }
                if (in_array(YT_QUERY, $sayfaIslemleriId)) {//eğer sorgula butonuna basıldı ise sor
                    ?>
                    <div  class="col-md-12 box box-primary" id="sorgulama_ekrani_div">
                        <form class="form-horizontal" id="ihale_sorgula" method="GET" action="">
                            <div class="form-group form-group-sm">
                            </div>    
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="grup">Ana Grup Adı</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="grup" name="grup">
                                        <option value=''>Seçiniz</option>
                                        <?php
                                        $grupSQL = "SELECT id, adi,aciklama FROM yt_grup WHERE NOT(id=1 OR id=2 OR id=3) ";
                                        try {
                                            $gruplar = $db->fetchAll($grupSQL);
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($gruplar);
                                        foreach ($gruplar as $grup) {
                                            $id_sifreli = mcrypt($grup->id, $_SESSION['key']);
                                            echo "<option value='$id_sifreli'  title='$grup->aciklama'";
                                            echo (isset($grup->id) ? (isset($_GET['grup']) ? ($_GET['grup'] == $id_sifreli ? 'selected' : null) : null) : null);
                                            echo ">$grup->adi</option>";
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </div>                                       
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" name="Sorgula" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-search"></span> <?= __("Kullanıcı Detayı Göster") ?></button>
                            </div> 
                        </form>
                    </div>
                    <?php
                    if (isset($_GET['Sorgula'])) {
                        $grup_id = mdecrypt($_GET['grup'], $_SESSION['key']);
                        if ($_GET['grup'] == "") {
                            $ItemsSQL = "CALL alt_grup_getir( ?, ? )";
                            try {
                                $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, array(-1, 10));
                                htmlspecialchar_array($listItems);
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            }
                        } else {
                            $ItemsSQL = "CALL alt_grup_getir( ?, ? )";
                            try {
                                $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, array($grup_id, 0));
                                htmlspecialchar_array($listItems);
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            }
                        }
                        if ((isset($listItems)) && !empty($listItems)) {
                            if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
                                $Buttons = array('details' => 'Ayrıntı', 'update' => 'Güncelle', 'remove' => 'Sil');
                                $ButtonsUrls = array('details' => 'postPage.php', 'update' => 'index.php', 'remove' => 'postPage.php');
                            } else {
                                $Buttons = array('details' => 'Ayrıntı', 'update' => 'Güncelle');
                                $ButtonsUrls = array('details' => 'postPage.php', 'update' => 'index.php');
                            }

                            if ($_GET['grup'] == "") {
                                $options2 = array(
                                    //zorunlu parametreler
                                    'tableHeaders' => array('Ana Grup', 'Alt Grup Adı', 'Alt Grup Açıklaması', 'Grup Şemasi Aktif Mi?'),
                                    //zorunlu olmayan parametreler
                                    //        'id' => 'example2' , // optional
                                    'order' => array(0, 'asc'),
                                    //        'tableFooters' => array('id','e-Mailss','Şehir','İşlem','İşlem','İşlem'), // optional
                                    //        'filters' => array(array('Gecko', 'Trident', 'KHTML', 'Misc', 'Presto', 'Webkit', 'Tasman'),'text','text','text','text'),
                                    //yerel parametreler
                                    'tableData' => $listItems,
                                    'processButtons' => array(
                                        'hasButton' => true,
                                        'buttonPostPage' => 'index.php',
                                        'buttons' => $Buttons,
                                        'buttonUrls' => $ButtonsUrls),
                                    'buttons' => array("excel", "pdf")
                                );
                            } else {
                                $options2 = array(
                                    //zorunlu parametreler
                                    'tableHeaders' => array('Alt Grup Derecesi', 'Grup Adı', 'Açıklaması'),
                                    //zorunlu olmayan parametreler
                                    //        'id' => 'example2' , // optional
                                    'order' => array(0, 'asc'),
                                    //        'tableFooters' => array('id','e-Mailss','Şehir','İşlem','İşlem','İşlem'), // optional
                                    //        'filters' => array(array('Gecko', 'Trident', 'KHTML', 'Misc', 'Presto', 'Webkit', 'Tasman'),'text','text','text','text'),
                                    //yerel parametreler
                                    'tableData' => $listItems,
                                    'processButtons' => array(
                                        'hasButton' => true,
                                        'buttonPostPage' => 'index.php',
                                        'buttons' => $Buttons,
                                        'buttonUrls' => $ButtonsUrls),
                                    'buttons' => array("excel", "pdf")
                                );
                            }

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
                            adminLTE_alert(true, "Kayıt Bulunamadı", "Sorgulamada kayıt bulunamadı. ", "warning");
                        }
                    }
                }
            }
            ?>
        </div>
    </section>
</div>
