
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
            <li class="active"><?php echo __("Siverek Organizasyon Şeması") ?></li>
        </ol>
    </section>
    <section class="container-fluid">
        <div class="row">
            <?php
            if (isset($_POST['update']) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
                if (in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    require_once 'guncelle.php';
                }
            } else {//normal görüntüleme
                if (in_array(YT_QUERY, $sayfaIslemleriId)) {//eğer sorgula butonuna basıldı ise sor
                    ?>
                    <div  class="col-md-12 box box-primary" id="sorgulama_ekrani_div">
                        <form class="form-horizontal" method="GET" action="">
                            <div class="form-group form-group-sm"></div>    
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="Siverek_semasi">Adı</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();
                                                    $('#sema_organizasyon').val($('#Siverek_semasi  option:selected').attr('title'));$('#get-items').click();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="Siverek_semasi" name="Siverek_semasi">
                                        <option value=''>Seçiniz</option>
                                        <?php
                                        $Siverek_semasi_birimlerSQL = "SELECT BIRIMKOD AS id, BOLUMAD AS ad, ORGKODU AS orgkodu FROM hu_organizasyon_semasi ORDER BY ORGKODU";
                                        try {
                                            $Siverek_semasi_birimler = $db->fetchAll($Siverek_semasi_birimlerSQL);
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($Siverek_semasi_birimler);
                                        foreach ($Siverek_semasi_birimler as $birim) {
                                            $id_sifreli = mcrypt($birim->id, $_SESSION['key']);
                                            echo "<option value='$id_sifreli'  title='$birim->orgkodu'";
                                            echo (isset($birim->id) ? (isset($_GET['Siverek_semasi']) ? ($_GET['Siverek_semasi'] == $id_sifreli ? 'selected' : null) : null) : null);
                                            echo ">$birim->ad</option>";
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="sema_organizasyon">Organizasyon</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="sema_organizasyon" name="sema_organizasyon" value="<?php echo isset($_GET['sema_organizasyon']) ? $_GET['sema_organizasyon'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" id="get-items"  class="btn btn-primary btn-block"><span class="glyphicon glyphicon-search"></span> <?= __("Siverek Organizasyon Şeması Detayı Göster") ?></button>
                            </div>
                        </form>
                    </div>
                    <?php
                    if (isset($_GET['Siverek_semasi'])) {
                        if ($_GET['Siverek_semasi'] == "") {
                            $ItemsSQL = "SELECT BIRIMKOD AS id, BOLUMAD AS ad, ORGKODU AS orgkodu, DUZEY1, DUZEY2, DUZEY3, DUZEY4, DUZEY5, DUZEY6, DUZEY7, DERS_VAR, AKTIF FROM hu_organizasyon_semasi ORDER BY ORGKODU";
                         try {
                            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
                        
                        } else {
                            $ItemsSQL = "SELECT BIRIMKOD AS id, BOLUMAD AS ad, ORGKODU AS orgkodu, DUZEY1, DUZEY2, DUZEY3, DUZEY4, DUZEY5, DUZEY6, DUZEY7, DERS_VAR, AKTIF FROM hu_organizasyon_semasi WHERE BIRIMKOD = ? ORDER BY ORGKODU ";
                        $Siverek_semasi = mdecrypt($_GET['Siverek_semasi'], $_SESSION['key']);
                        try {
                            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $Siverek_semasi);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
                        
                        }
                        htmlspecialchar_array($listItems);
                        if ((isset($listItems)) && !empty($listItems)) {
                                $Buttons = array('update' => 'Aktif', 'remove' => 'Pasif');
                                $ButtonsUrls = array('update' => 'postPage.php', 'remove' => 'postPage.php');
                            $options2 = array(
                                //zorunlu parametreler
                                'tableHeaders' => array('Ad', 'Organizasyon', 'Duzey1', 'Duzey2', 'Duzey3', 'Duzey4', 'Duzey5', 'Duzey6', 'Duzey7','Ders var mi?', 'Aktif Mi?'),
                                //zorunlu olmayan parametreler
                                //        'id' => 'example2' , // optional
                                'order' => array(1, 'asc'),
                                'tableFooters' => array('Ad', 'Organizasyon', 'Duzey1', 'Duzey2', 'Duzey3', 'Duzey4', 'Duzey5', 'Duzey6', 'Duzey7','Ders var mi?', 'Aktif Mi?'), // optional
                                'filters' => array('text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text'),
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
                            adminLTE_alert(true, "Kayıt Bulunamadı", "Sorgulamada kayıt bulunamadı. ", "warning");
                        }
                    }
                }
            }
            ?>
        </div>
    </section>
</div>
