
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>

            <small><?= __("İmar Müdürlüğü") ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-cloud"></i><?= __("Ruhsat İşlemleri") ?></a></li>
            <li class="active"><?php
                if (isset($_GET['add']) && in_array(YT_INSERT, $sayfaIslemleriId)) {
                    echo __("Ruhsat Ekleme");
                }//eğer güncelleme butonuna basıldı ise
                else if (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    echo __("Ruhsat Güncelleme");
                } else {
                    echo __("Ruhsat Görüntüleme");
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
                    require_once 'ekle.php';
                }//eğer güncelleme butonuna basıldı ise
                else if (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    require_once 'guncelle.php';
                } else if (isset($_GET['ekspertiz_pdf']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    require_once 'pdf.php';
                } else {//normal pdf
                    echo '<div class="row">
                            <div  class="col-md-12 box box-purple" id="sorgulama_ekrani_div">';
                    if (in_array(YT_INSERT, $sayfaIslemleriId)) {
                        echo '<div class="col-md-8" style="	margin-bottom: 10px;">
                            <form class="form-horizontal" method="GET" action="?add">
                                <input class="btn bg-green" type="submit" id="btn_yeni_kayit_ekle" name="add" value="YENİ RUHSAT EKLE">
                            </form>  
                        </div>';
                    }
                    if (in_array(YT_QUERY, $sayfaIslemleriId)) {//eğer sorgula butonuna basıldı ise sor
                        ?>
                        <form class="form-horizontal" method="get" action="">
                            <div class="form-group form-group-sm">
                            </div>   
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="ruhsatlar">Ruhsat No</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible"  onchange="$('#get-items').click();" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat_no" name="ruhsat_no">
                                        <option value=''>Listelenecek Ruhsat Seçiniz</option>
                                        <?php
                                        try {
                                            $ruhsatlar = $db->fetchAll("SELECT id, ruhsat_no FROM s_ruhsat_bilgileri WHERE aktif_mi AND NOT ISNULL(ruhsat_no)");
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($ruhsatlar);
                                        foreach ($ruhsatlar as $ruhsat) {
                                            $id_sifreli = mcrypt($ruhsat->id, $_SESSION['key']);
                                            echo "<option value='$id_sifreli' ";
                                            echo (isset($ruhsat->id) ? (isset($_GET['ruhsat_no']) ? ($_GET['ruhsat_no'] == $id_sifreli ? 'selected' : null) : null) : null);
                                            echo ">$ruhsat->ruhsat_no</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" id="get-items" name="Sorgula" class="btn bg-purple btn-block"><span class="glyphicon glyphicon-search"></span> <?= "Ruhsat Bilgilerini Getir" ?></button>
                            </div>
                        </form>
                        <hr>                            
                        <form class="form-horizontal" method="get" action="">
                            <div class="form-group form-group-sm">
                            </div>   
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="ruhsatlar">Ruhsat Cinsi</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat_cinsi" name="ruhsat_cinsi">
                                        <option value=''>Ruhsat Cinsi Seçiniz</option>
                                        <?php
                                        try {
                                            $ruhsatlar = $db->fetchAll("SELECT cinsi FROM s_ruhsat_bilgileri GROUP BY cinsi");
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($ruhsatlar);
                                        foreach ($ruhsatlar as $ruhsat) {
                                            echo "<option value='$ruhsat->cinsi' ";
                                            echo (isset($ruhsat->cinsi) ? (isset($_GET['ruhsat_cinsi']) ? ($_GET['ruhsat_cinsi'] == $ruhsat->cinsi ? 'selected' : null) : null) : null);
                                            echo ">$ruhsat->cinsi</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="adi_soyadi">Ad Soyad</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="adi_soyadi" name="adi_soyadi" value="<?php echo isset($_GET['adi_soyadi']) ? $_GET['adi_soyadi'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="ruhsat_tarihi">Ruhsat Tarihi</label>
                                <div class="col-sm-8">
                                    <input class="form-control date" type="text" id="ruhsat_tarihi" name="ruhsat_tarihi" value="<?php echo isset($_GET['ruhsat_tarihi']) ? $_GET['ruhsat_tarihi'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="mahallesi">Mahallesi</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="mahallesi" name="mahallesi" value="<?php echo isset($_GET['mahallesi']) ? $_GET['mahallesi'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="bulten_no">Bulten No</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="bulten_no" name="bulten_no" value="<?php echo isset($_GET['bulten_no']) ? $_GET['bulten_no'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="ada_parsel">Ada Parsel</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="ada_parsel" name="ada_parsel" value="<?php echo isset($_GET['ada_parsel']) ? $_GET['ada_parsel'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="yibf_no">YİBF No</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="yibf_no" name="yibf_no" value="<?php echo isset($_GET['yibf_no']) ? $_GET['yibf_no'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="iskan_verildi_mi">İskan Verildi Mi?</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="iskan_verildi_mi" name="iskan_verildi_mi">
                                        <option value=''>İskan Durumu Seçiniz</option>
                                        <option value='1' <?= ((isset($_GET['iskan_verildi_mi']) && ($_GET['iskan_verildi_mi'] === 1) ? 'selected' : '')) ?> >İskan Var</option>
                                        <option value='0' <?= ((isset($_GET['iskan_verildi_mi']) && ($_GET['iskan_verildi_mi'] === 0) ? 'selected' : '')) ?> >İskan Yok</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" id="get-items" name="Sorgula" class="btn bg-purple btn-block"><span class="glyphicon glyphicon-search"></span> <?= "Ruhsat Bilgilerini Getir" ?></button>
                            </div>
                        </form>
                    </div>
                </div>



                <?php
                if (isset($_GET['Sorgula'])) {
                    if (trim($_GET['ruhsat_no']) == "") {
                        $ruhsat_where_string = "";
                        $ruhsat_where = array();
                        if (trim($_GET['ruhsat_cinsi']) !== "") {
                            $ruhsat_where_string .= " AND cinsi = ? ";
                            array_push($ruhsat_where, trim($_GET['ruhsat_cinsi']));
                        }
                        if (trim($_GET['iskan_verildi_mi']) !== "") {
                            $ruhsat_where_string .= " AND iskan_verildi_mi = ? ";
                            array_push($ruhsat_where, trim($_GET['iskan_verildi_mi']));
                        }
                        if (trim($_GET['adi_soyadi']) !== "") {
                            $ruhsat_where_string .= " AND adi_soyadi LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_GET['adi_soyadi']) . "%");
                        }
                        if (trim($_GET['ruhsat_tarihi']) !== "") {
                            $ruhsat_where_string .= " AND ruhsat_tarihi = ? ";
                            array_push($ruhsat_where, convertDateFormatBasicDefault($_GET['ruhsat_tarihi']));
                        }
                        if (trim($_GET['mahallesi']) !== "") {
                            $ruhsat_where_string .= " AND mahallesi LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_GET['mahallesi']) . "%");
                        }
                        if (trim($_GET['bulten_no']) !== "") {
                            $ruhsat_where_string .= " AND bulten_no LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_GET['bulten_no']) . "%");
                        }
                        if (trim($_GET['ada_parsel']) !== "") {
                            $ruhsat_where_string .= " AND ada_parsel LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_GET['ada_parsel']) . "%");
                        }
                        if (trim($_GET['yibf_no']) !== "") {
                            $ruhsat_where_string .= " AND yibf_no LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_GET['yibf_no']) . "%");
                        }
                        $ItemsSQL = "SELECT
                                            s_ruhsat_bilgileri.id, 
                                            s_ruhsat_bilgileri.ruhsat_no, 
                                            (CASE s_ruhsat_bilgileri.iskan_verildi_mi WHEN 1 THEN 'İskan Verildi' WHEN 0 THEN 'İskan Yok' END) AS iskan_verildi_mi, 
                                            DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,  
                                            s_ruhsat_bilgileri.adi_soyadi, 
                                            s_ruhsat_bilgileri.cinsi, 
                                            s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
                                            s_ruhsat_bilgileri.fenni_mesul, 
                                            DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
                                            s_ruhsat_bilgileri.mahallesi, 
                                            s_ruhsat_bilgileri.bulten_no, 
                                            s_ruhsat_bilgileri.ada_parsel, 
                                            s_ruhsat_bilgileri.yibf_no, 
                                            s_ruhsat_bilgileri.olcusu
                                    FROM
                                            s_ruhsat_bilgileri
                                            WHERE aktif_mi " . $ruhsat_where_string . " LIMIT 1000";
                        try {
                            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $ruhsat_where);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
//                        var_dump($listItems, $ItemsSQL);
                    } else {
                        $ItemsSQL = "SELECT
                                                s_ruhsat_bilgileri.id, 
                                                s_ruhsat_bilgileri.ruhsat_no, 
                                                (CASE s_ruhsat_bilgileri.iskan_verildi_mi WHEN 1 THEN 'İskan Verildi' WHEN 0 THEN 'İskan Yok' END) AS iskan_verildi_mi, 
                                                DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi,  
                                                s_ruhsat_bilgileri.adi_soyadi, 
                                                s_ruhsat_bilgileri.cinsi, 
                                                s_ruhsat_bilgileri.ruhsat_verilis_amaci, 
                                                s_ruhsat_bilgileri.fenni_mesul, 
                                                DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi,  
                                                s_ruhsat_bilgileri.mahallesi, 
                                                s_ruhsat_bilgileri.bulten_no, 
                                                s_ruhsat_bilgileri.ada_parsel, 
                                                s_ruhsat_bilgileri.yibf_no, 
                                                s_ruhsat_bilgileri.olcusu
                                        FROM
                                                s_ruhsat_bilgileri
                                                WHERE aktif_mi AND id = ? ";
                        $ruhsat_id = mdecrypt($_GET['ruhsat_no'], $_SESSION['key']);
                        try {
                            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $ruhsat_id);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
                    }
                    htmlspecialchar_array($listItems);
                    if ((isset($listItems)) && !empty($listItems)) {

                        if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
                            $Buttons = array('update' => 'Güncelle', 'print1' => 'Ekspertiz', 'remove' => 'Sil');
                            $ButtonsUrls = array('new_tab' => 'preview.php', 'update' => 'index.php', 'print1' => 'pdf.php', 'remove' => 'postPage.php');
                        } else {
                            $Buttons = array('update' => 'Güncelle', 'print1' => 'Ekspertiz');
                            $ButtonsUrls = array('new_tab' => 'preview.php', 'update' => 'index.php', 'print1' => 'pdf.php');
                        }
                        $options2 = array(
                            //zorunlu parametreler
                            'tableHeaders' => array('RUHSAT NO', 'İskan', 'İskan Ruhsat Tarihi', 'Adı Soyadı', 'Cinsi', 'Ruhsat Veriliş Amacı', 'Fenni Mesul', 'Ruhsat Tarihi', 'Mahallesi', 'Bülten No', 'Ada/Parsel', 'YİBF No', 'Ölçüsü'),
                            //zorunlu olmayan parametreler
                            //        'id' => 'example2' , // optional
                            'order' => array(0, 'asc'),
                            'tableFooters' => array('RUHSAT NO', 'İskan', 'İskan Ruhsat Tarihi', 'Adı Soyadı', 'Cinsi', 'Ruhsat Veriliş Amacı', 'Fenni Mesul', 'Ruhsat Tarihi', 'Mahallesi', 'Bülten No', 'Ada/Parsel', 'YİBF No', 'Ölçüsü'), // optional
                            'filters' => array('text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text'),
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
