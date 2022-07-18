
<?php
include_once '../../lib/DataTable.php';
//EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
?>

<script>
    function get_selected_value(value) {
        var secilen = $(value).val();
        $('#Siverek_semasi').val(secilen);
        $('#Siverek_semasi').change();
        $('#select-dialog').modal('hide');
    }
</script>

<style>
    .dataTable td:nth-child(2){            
        color:#e08e0b;
    }
</style>
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
            <div  class="col-md-12 box box-primary" id="sorgulama_ekrani_div">
                <form class="form-horizontal" method="GET" action="">
                    <div class="form-group form-group-sm"></div>    
                    <div class="form-group form-group-sm">
                        <label class="col-sm-2 control-label" for="Siverek_semasi">Adı</label>
                        <div class="col-sm-8">
                            <select class="form-control select2 select2-hidden-accessible" onchange="$('.ui-tooltip').remove();
                                                    $('#sema_organizasyon').val($('#Siverek_semasi  option:selected').attr('title'))" style="width: 100%;" tabindex="-1" aria-hidden="true" id="Siverek_semasi" name="Siverek_semasi">
                                <option value=''>Seçiniz</option>
                                <?php
                                $Siverek_semasi_birimlerSQL = "SELECT BIRIMKOD AS id, BOLUMAD AS ad, ORGKODU AS orgkodu, DUZEY1, DUZEY2, DUZEY3, DUZEY4, DUZEY5, DUZEY6, DUZEY7 FROM hu_organizasyon_semasi ORDER BY ORGKODU";
                                try {
                                    $Siverek_semasi_birimler = $db->fetchAll($Siverek_semasi_birimlerSQL);
                                } catch (Zend_Db_Exception $ex) {
                                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                }
                                htmlspecialchar_obj($Siverek_semasi_birimler);
                                foreach ($Siverek_semasi_birimler as $birim) {
                                    $id_sifreli = mcrypt($birim->id, $_SESSION['key']);
                                    echo "<option value='$id_sifreli'  title='$birim->orgkodu - $birim->DUZEY1 / $birim->DUZEY2 / $birim->DUZEY3 / $birim->DUZEY4 / $birim->DUZEY5 / $birim->DUZEY6 / $birim->DUZEY7'";
                                    echo (isset($birim->id) ? (isset($_GET['Siverek_semasi']) ? ($_GET['Siverek_semasi'] == $id_sifreli ? 'selected' : null) : null) : null);
                                    echo ">$birim->ad</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <span class="input-group-btn col-sm-1" style="padding:0;">
                            <button type="button" class="btn btn-warning btn-flat"  data-toggle="modal" data-target="#select-dialog" title="Tablodan Seçmek İçin Tıklayınız."><i class="fa fa-table" aria-hidden="true"></i></button>
                        </span>
                    </div>
                    <div class="form-group form-group-sm">
                        <label class="col-sm-2 control-label" for="sema_organizasyon">Organizasyon - Seviye</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="sema_organizasyon" name="sema_organizasyon" value="<?php echo isset($_GET['sema_organizasyon']) ? $_GET['sema_organizasyon'] : ''; ?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>


<div class="modal fade in" id="select-dialog" tabindex="-1"  aria-labelledby="selectedModalLabel">
    <div class="modal-dialog"  style="width: 90%;height: 100%">Seçim
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="selectedModalLabel"><i class="icon-zoom-in"></i>  Detay </h4>
            </div>
            <div class="modal-body">
                <?php
                $ItemsSQL = "SELECT BIRIMKOD AS id, BOLUMAD AS ad, ORGKODU AS orgkodu, DUZEY1, DUZEY2, DUZEY3, DUZEY4, DUZEY5, DUZEY6, DUZEY7 FROM hu_organizasyon_semasi WHERE AKTIF ORDER BY ORGKODU";
                try {
                    $listItems = $GLOBALS['db']->fetchAll($ItemsSQL);
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex);
                }
                htmlspecialchar_array($listItems);
                if ((isset($listItems)) && !empty($listItems)) {
//                            $Buttons = array('update' => 'Aktif', 'remove' => 'Pasif');
//                            $ButtonsUrls = array('update' => 'postPage.php', 'remove' => 'postPage.php');
                    $options2 = array(
                        //zorunlu parametreler
                        'tableHeaders' => array('Ad', 'Organizasyon', 'Duzey1', 'Duzey2', 'Duzey3', 'Duzey4', 'Duzey5', 'Duzey6', 'Duzey7'),
                        //zorunlu olmayan parametreler
                        //        'id' => 'example2' , // optional
                        'order' => array(2, 'asc'),
                        'tableFooters' => array('Ad', 'Organizasyon', 'Duzey1', 'Duzey2', 'Duzey3', 'Duzey4', 'Duzey5', 'Duzey6', 'Duzey7'), // optional
                        'filters' => array('text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text'),
                        //yerel parametreler
                        'tableData' => $listItems,
//                                'processButtons' => array(
//                                    'hasButton' => true,
//                                    'buttonPostPage' => 'index.php',
//                                    'buttons' => $Buttons,
//                                    'buttonUrls' => $ButtonsUrls)
                    );
                    try {
                        $dtableServer = new DataTable($options2);
                    } catch (Zend_Db_Exception $ex) {
                        log::DB_hata_kaydi_ekle(__FILE__, $ex);
                    }
                }
                ?>
                <div class="box-header with-border">
                    <?php
                    echo $dtableServer->get_data_table_selected();
                    echo $dtableServer->get_datatable_script_selected();
                    ?>     
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</section>
</div>
