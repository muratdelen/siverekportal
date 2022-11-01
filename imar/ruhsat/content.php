<?php
try {
    $son_iskan_numarasi = $db->fetchRow("SELECT DISTINCT iskan_no FROM s_ruhsat_bilgileri WHERE ruhsat_tarihi > '2022-00-00' ORDER BY cast(iskan_no as unsigned) DESC LIMIT 1");
} catch (Zend_Db_Exception $ex) {
    log::DB_hata_kaydi_ekle(__FILE__, $ex);
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <h1>
            <small>İmar Müdürlüğü</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-cloud"></i>Ruhsat İşlemleri</a></li>
            <li class="active"><?php
                if (isset($_GET['add']) && in_array(YT_INSERT, $sayfaIslemleriId)) {
                    echo __("Ruhsat Ekleme:") . $son_iskan_numarasi->iskan_no;
                }//eğer güncelleme butonuna basıldı ise
                else if (isset($_POST['update']) && in_array(YT_UPDATE, $sayfaIslemleriId)) {
                    echo __("Ruhsat Güncelleme:") . $son_iskan_numarasi->iskan_no;
                } else {
                    echo __("Ruhsat Görüntüleme:") . $son_iskan_numarasi->iskan_no;
                }
                ?>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <section class="content-header"></section>

                <?php
//yeni kayıt butonunana basıldı ise
                if (isset($_GET['add']) && in_array(YT_INSERT, $sayfaIslemleriId) || isset($_POST['insert']) && in_array(YT_INSERT, $sayfaIslemleriId)) {
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

                        <div class="box-footer">
                            <button class="btn bg-orange margin pull-right" type="cancel" onclick="window.location = 'index.php';return false;">Arama Resetle</button>
                        </div>
                        <form class="form-horizontal" method="get" action="">
                            <div class="form-group form-group-sm">
                            </div>                               
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="ruhsatlar">Ruhsat No</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible"  onchange="this.form.submit()" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat" name="ruhsat">
                                        <option value=''>Listelenecek Ruhsat Seçiniz</option>
                                        <!--<option value=''>Ruhsat No Boş Olanlar</option>-->
                                        <?php
                                        try {
                                            $ruhsatlar = $db->fetchAll("SELECT id, ruhsat_no, adi_soyadi FROM s_ruhsat_bilgileri WHERE aktif_mi AND iskan_verildi_mi = '-1' AND NOT ISNULL(ruhsat_no) GROUP BY ruhsat_no ORDER BY CAST(SUBSTR(ruhsat_no, 1, 4) AS UNSIGNED) DESC, CAST(SUBSTR(ruhsat_no, 6) AS UNSIGNED) DESC");
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($ruhsatlar);
                                        foreach ($ruhsatlar as $ruhsat) {
                                            $sifreli_id = mcrypt($ruhsat->id, $_SESSION['key']);
                                            echo "<option value='$sifreli_id' title='$ruhsat->adi_soyadi' ";
                                            echo (isset($ruhsat->id) ? (isset($_GET['ruhsat']) ? ($_GET['ruhsat'] == $sifreli_id ? 'selected' : null) : null) : null);
                                            echo ">$ruhsat->ruhsat_no</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="sorgula"/>
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" id="get-items" name="sorgula" class="btn bg-purple btn-block hidden"><span class="glyphicon glyphicon-search"></span> <?= "Ruhsat Bilgilerini Getir" ?></button>
                            </div>
                        </form>
                        <form class="form-horizontal" method="get" action="">
                            <div class="form-group form-group-sm">
                            </div>                               
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="iskan_no">İskan No</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible"  onchange="this.form.submit()" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat" name="ruhsat">
                                        <option value=''>Listelenecek İskan Seçiniz</option>
                                        <!--<option value=''>İskan No Boş Olanlar</option>-->
                                        <?php
                                        try {
                                            $ruhsatlar = $db->fetchAll("SELECT id, iskan_no, adi_soyadi FROM s_ruhsat_bilgileri WHERE aktif_mi AND iskan_verildi_mi = '1' AND NOT ISNULL(iskan_no) GROUP BY iskan_no ORDER BY id DESC");
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($ruhsatlar);
                                        foreach ($ruhsatlar as $ruhsat) {
                                            $sifreli_id = mcrypt($ruhsat->id, $_SESSION['key']);
                                            echo "<option value='$sifreli_id' title='$ruhsat->adi_soyadi' ";
                                            echo (isset($ruhsat->id) ? (isset($_GET['ruhsat']) ? ($_GET['ruhsat'] == $sifreli_id ? 'selected' : null) : null) : null);
                                            echo ">$ruhsat->iskan_no</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="sorgula"/>
                            <div class="col-sm-12 form-group form-group-sm">
                                <button type="submit" id="get-items" name="sorgula" class="btn bg-purple btn-block hidden"><span class="glyphicon glyphicon-search"></span> <?= "Ruhsat Bilgilerini Getir" ?></button>
                            </div>
                        </form>
                        <hr> 
                        <form class="form-horizontal" method="get" action="">
                            <div class="form-group form-group-sm">
                            </div>   
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="adi_soyadi">Ad Soyad</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="adi_soyadi" name="adi_soyadi" value="<?php echo isset($_GET['adi_soyadi']) ? $_GET['adi_soyadi'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="ada_parsel">Ada Parsel</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="ada_parsel" name="ada_parsel" value="<?php echo isset($_GET['ada_parsel']) ? $_GET['ada_parsel'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="bulten_no">Bulten No</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="bulten_no" name="bulten_no" value="<?php echo isset($_GET['bulten_no']) ? $_GET['bulten_no'] : ''; ?>" >
                                </div>
                            </div>                            
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="fenni_mesul">Fenni Mesul/Yapı Denetim</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="fenni_mesul" name="fenni_mesul" value="<?php echo isset($_GET['fenni_mesul']) ? $_GET['fenni_mesul'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="ruhsat_verilis_amaci">Ruhsat Veriliş Amacı</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat_verilis_amaci" name="ruhsat_verilis_amaci">
                                        <option value=''>Listelenecek Ruhsat Seçiniz</option>
                                        <?php
                                        try {
                                            $ruhsat_verilis_amaclari = $db->fetchAll("SELECT verilis_amaci, aciklama FROM s_ruhsat_verilis_amaci WHERE aktif_mi");
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($ruhsat_verilis_amaclari);
                                        foreach ($ruhsat_verilis_amaclari as $ruhsat_verilis_amaci) {
                                            echo "<option value='$ruhsat_verilis_amaci->verilis_amaci' title='$ruhsat_verilis_amaci->aciklama' ";
                                            echo ">$ruhsat_verilis_amaci->verilis_amaci</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="ruhsatlar">Ruhsat Cinsi</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="ruhsat_cinsi" name="ruhsat_cinsi">
                                        <option value=''>Ruhsat Cinsi Seçiniz</option>
                                        <?php
                                        try {
                                            $ruhsatlar = $db->fetchAll("SELECT DISTINCT ruhsat_cinsi FROM s_ruhsat_bilgileri ");
                                        } catch (Zend_Db_Exception $ex) {
                                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                                        }
                                        htmlspecialchar_obj($ruhsatlar);
                                        foreach ($ruhsatlar as $ruhsat) {
                                            echo "<option value='$ruhsat->ruhsat_cinsi' ";
                                            echo (isset($ruhsat->ruhsat_cinsi) ? (isset($_GET['ruhsat_cinsi']) ? ($_GET['ruhsat_cinsi'] == $ruhsat->ruhsat_cinsi ? 'selected' : null) : null) : null);
                                            echo ">$ruhsat->ruhsat_cinsi</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="ruhsat_tarihi">Ruhsat Tarihi</label>
                                <div class="col-sm-4">
                                    <input class="form-control date" type="text" placeholder="Başlangıç Tarihi" id="ruhsat_tarihi_baslangic" name="ruhsat_tarihi_baslangic" value="<?php echo isset($_GET['ruhsat_tarihi_baslangic']) ? $_GET['ruhsat_tarihi_baslangic'] : ''; ?>" >
                                </div>
                                <div class="col-sm-4">
                                    <input class="form-control date" type="text" placeholder="Bitiş Tarihi" id="ruhsat_tarihi_bitis" name="ruhsat_tarihi_bitis" value="<?php echo isset($_GET['ruhsat_tarihi_bitis']) ? $_GET['ruhsat_tarihi_bitis'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="mahallesi">Mahallesi</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="mahallesi" name="mahallesi" value="<?php echo isset($_GET['mahallesi']) ? $_GET['mahallesi'] : ''; ?>" >
                                </div>
                            </div>

                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="yibf_no">YİBF No</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="yibf_no" name="yibf_no" value="<?php echo isset($_GET['yibf_no']) ? $_GET['yibf_no'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="iskan_verildi_mi">İskan Süreci</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="iskan_verildi_mi" name="iskan_verildi_mi">
                                        <option value=''>İskan Durumu Seçiniz</option>
                                        <option value='-1' <?= ((isset($_GET['iskan_verildi_mi']) && ($_GET['iskan_verildi_mi'] === -1) ? 'selected' : '')) ?> >Onay Bekliyor/Başvuru Yapıldı</option>
                                        <option value='0' <?= ((isset($_GET['iskan_verildi_mi']) && ($_GET['iskan_verildi_mi'] === 0) ? 'selected' : '')) ?> >Yok</option>
                                        <option value='1' <?= ((isset($_GET['iskan_verildi_mi']) && ($_GET['iskan_verildi_mi'] === 1) ? 'selected' : '')) ?> >Var</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="aktif_mi">Ruhsat Süreci</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="aktif_mi" name="aktif_mi">
                                        <option value=''>Ruhsat Durumu Seçiniz</option>
                                        <option value='-1' <?= ((isset($_GET['aktif_mi']) && ($_GET['aktif_mi'] == -1) ? 'selected' : '')) ?> >Onay Bekliyor/Başvuru Yapıldı</option>
                                        <option value='1' <?= ((isset($_GET['aktif_mi']) && ($_GET['aktif_mi'] == 1) ? 'selected' : '')) ?> >Ruhsat Verildi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label" for="kacak_islem_yapildi_mi">Var Mı?</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="kacak_islem_yapildi_mi" name="kacak_islem_yapildi_mi">
                                        <option value=''>Kaçak Durumu Seçiniz</option>
                                        <option value='1' <?= ((isset($_GET['kacak_islem_yapildi_mi']) && ($_GET['kacak_islem_yapildi_mi'] === 1) ? 'selected' : '')) ?> >Var</option>
                                        <option value='0' <?= ((isset($_GET['kacak_islem_yapildi_mi']) && ($_GET['kacak_islem_yapildi_mi'] === 0) ? 'selected' : '')) ?> >Kaçak Yok</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 form-group form-group-sm text-center">
                                <button type="submit" id="get-items" name="sorgula" class="btn bg-purple"><span class="glyphicon glyphicon-search"></span> <?= "Ruhsat Bilgilerini Getir" ?></button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php
                if (isset($_GET['sorgula'])) {
                    ?>
                    <script>
                        $(function () {
                            document.getElementById('sorgulama_ekrani').scrollIntoView();
            //                            window.scrollTo(0, document.body.scrollHeight);
                            $('.sidebar-mini').addClass('sidebar-collapse');
                        });
                    </script>
                    <?php
                    $ItemsSQL = "SELECT
                    s_ruhsat_bilgileri.id, 
                    s_ruhsat_bilgileri.ruhsat_no, 
                    DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi_tr,	
                    s_ruhsat_bilgileri.bulten_no, 
                    s_ruhsat_bilgileri.ada_parsel,
                    s_ruhsat_bilgileri.adi_soyadi, 
                    s_ruhsat_bilgileri.fenni_mesul,
                     (CASE s_ruhsat_bilgileri.iskan_verildi_mi 
                    WHEN 1 THEN '<h6 style=\"color:green\">Var</h6>' 
                    WHEN 0 THEN '<h6 style=\"color:red\">Yok</h6>' END) AS iskan_verildi_mi,
                    s_ruhsat_bilgileri.iskan_bulten_no, 
                    s_ruhsat_bilgileri.iskan_no,
                    DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi, 
                    s_ruhsat_bilgileri.yibf_no,   
                    s_ruhsat_bilgileri.yapi_alani,
                    s_ruhsat_bilgileri.ruhsat_verilis_amaci,
                    s_ruhsat_bilgileri.ruhsat_cinsi,    
                    s_ruhsat_bilgileri.mahallesi,  
                   (CASE s_ruhsat_bilgileri.kacak_islem_yapildi_mi
                    WHEN 1 THEN '<h6 style=\"back-color:red\">Var.</h6>' 
                    WHEN 0 THEN '' END) AS kacak_islem_yapildi_mi, 
                    s_ruhsat_bilgileri.kacak_islem_bilgisi, 
                    s_ruhsat_bilgileri.aciklama";
                    if (isset($_GET["ruhsat"])) {
                        $ItemsSQL .= " FROM s_ruhsat_bilgileri WHERE s_ruhsat_bilgileri.aktif_mi AND id = ? ";
                        $ruhsat_id = mdecrypt($_GET['ruhsat'], $_SESSION['key']);
                        try {
                            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $ruhsat_id);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
                    } else if (isset($_GET['ruhsat'])) {
                        if ($_GET['ruhsat'] == "ruhsatyok") {
                            $ItemsSQL .= " FROM s_ruhsat_bilgileri WHERE s_ruhsat_bilgileri.aktif_mi AND ISNULL(ruhsat_no) ";
                            try {
                                $listItems = $GLOBALS['db']->fetchAll($ItemsSQL);
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            }
                        } else {
                            $ItemsSQL .= " FROM s_ruhsat_bilgileri WHERE s_ruhsat_bilgileri.aktif_mi AND ruhsat_no = ? ";
                            try {
                                $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $_GET['ruhsat']);
                            } catch (Zend_Db_Exception $ex) {
                                log::DB_hata_kaydi_ekle(__FILE__, $ex);
                            }
                        }
                    } else {
                        $ruhsat_where_string = "";
                        $ruhsat_where = array();
                        if (trim($_GET['ruhsat_cinsi']) !== "") {
                            $ruhsat_where_string .= " AND ruhsat_cinsi = ? ";
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
                        if (trim($_GET['ruhsat_verilis_amaci']) !== "") {
                            $ruhsat_where_string .= " AND ruhsat_verilis_amaci LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_GET['ruhsat_verilis_amaci']) . "%");
                        }
                        if (trim($_GET['ruhsat_tarihi_baslangic']) !== "" && trim($_GET['ruhsat_tarihi_bitis']) !== "") {
                            $ruhsat_where_string .= " AND ( ruhsat_tarihi BETWEEN ? AND ? ) ";
                            array_push($ruhsat_where, convertDateFormatBasicDefault($_GET['ruhsat_tarihi_baslangic']));
                            array_push($ruhsat_where, convertDateFormatBasicDefault($_GET['ruhsat_tarihi_bitis']));
                        }
                        if (trim($_GET['mahallesi']) !== "") {
                            $ruhsat_where_string .= " AND mahallesi LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_GET['mahallesi']) . "%");
                        }
                        if (trim($_GET['fenni_mesul']) !== "") {
                            $ruhsat_where_string .= " AND fenni_mesul LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_GET['fenni_mesul']) . "%");
                        }
//                        if (trim($_GET['fenni_mesul']) !== "") {
//                            $ruhsat_where_string .= " AND fenni_mesul = ? ";
//                            array_push($ruhsat_where, trim($_GET['fenni_mesul']));
//                        }
                        if (trim($_GET['bulten_no']) !== "") {
                            $ruhsat_where_string .= " AND bulten_no = ? ";
                            array_push($ruhsat_where, trim($_GET['bulten_no']));
                        }
                        if (trim($_GET['ada_parsel']) !== "") {
                            $ruhsat_where_string .= " AND ada_parsel = ? ";
                            array_push($ruhsat_where, trim($_GET['ada_parsel']));
                        }
                        if (trim($_GET['yibf_no']) !== "") {
                            $ruhsat_where_string .= " AND yibf_no LIKE ? ";
                            array_push($ruhsat_where, "%" . trim($_GET['yibf_no']) . "%");
                        }
                        if (trim($_GET['kacak_islem_yapildi_mi']) !== "") {
                            $ruhsat_where_string .= " AND kacak_islem_yapildi_mi = ? ";
                            array_push($ruhsat_where, trim($_GET['kacak_islem_yapildi_mi']));
                        }
                        if (trim($_GET['aktif_mi']) !== "") {
                            if (trim($_GET['aktif_mi']) == -1) {
                                $ItemsSQL .= " FROM s_ruhsat_bilgileri WHERE s_ruhsat_bilgileri.aktif_mi = '-1' " . $ruhsat_where_string . " ORDER BY CAST(SUBSTR(ruhsat_no, 1, 4) AS UNSIGNED) DESC, CAST(SUBSTR(ruhsat_no, 6) AS UNSIGNED) DESC";
                            } else {
                                $ItemsSQL .= " FROM s_ruhsat_bilgileri WHERE s_ruhsat_bilgileri.aktif_mi " . $ruhsat_where_string . " ORDER BY CAST(SUBSTR(ruhsat_no, 1, 4) AS UNSIGNED) DESC, CAST(SUBSTR(ruhsat_no, 6) AS UNSIGNED) DESC";
                            }
                        } else {
                            $ItemsSQL .= " FROM s_ruhsat_bilgileri WHERE s_ruhsat_bilgileri.aktif_mi " . $ruhsat_where_string . " ORDER BY CAST(SUBSTR(ruhsat_no, 1, 4) AS UNSIGNED) DESC, CAST(SUBSTR(ruhsat_no, 6) AS UNSIGNED) DESC";
                        }
                        try {
                            $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, $ruhsat_where);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
//                        echo '<pre>';
//                        var_dump($listItems, $ItemsSQL);
//                        die();
                    }
                    htmlspecialchar_array($listItems);
                    if ((isset($listItems)) && !empty($listItems)) {

                        if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
                            $Buttons = array('insert' => 'Ekle', 'update' => 'Güncelle', 'print1' => 'Ekspertiz', 'remove' => 'Sil');
                            $ButtonsUrls = array('new_tab' => 'preview.php', 'insert' => 'index.php', 'update' => 'index.php', 'print1' => 'ekspertiz.php', 'remove' => 'postPage.php');
                        } else {
                            $Buttons = array('update' => 'Güncelle', 'print1' => 'Ekspertiz');
                            $ButtonsUrls = array('new_tab' => 'preview.php', 'update' => 'index.php', 'print1' => 'ekspertiz.php');
                        }
                        $options = array(
                            //zorunlu parametreler
                            'tableHeaders' => array('RUHSAT NO', 'Ruhsat Tarihi', 'Bülten No', 'Ada/Parsel', 'Adı Soyadı', 'Fenni Mesul/YDK', 'İskan', 'İskan Bülten No', 'İskan No', 'İskan Tarihi', 'YİBF No', 'Yapı Alanı', 'Ruhsat Veriliş Amacı', 'Ruhsat Cinsi', 'Mahallesi', 'Kaçak', 'Kaçak Bilgisi', 'Açıklama'),
                            //zorunlu olmayan parametreler
                            //        'id' => 'example2' , // optional
                            'order' => array(6, 'desc'),
                            'tableFooters' => array('RUHSAT NO', 'Ruhsat Tarihi', 'Bülten No', 'Ada/Parsel', 'Adı Soyadı', 'Fenni Mesul/YDK', 'İskan', 'İskan Bülten No', 'İskan No', 'İskan Tarihi', 'YİBF No', 'Yapı Alanı', 'Ruhsat Veriliş Amacı', 'Ruhsat Cinsi', 'Mahallesi', 'Kaçak', 'Kaçak Bilgisi', 'Açıklama'), // optional
                            'filters' => array('text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text'),
                            //yerel parametreler
                            'tableData' => $listItems,
                            'processButtons' => array(
                                'hasButton' => true,
                                'buttonPostPage' => 'index.php',
                                'buttons' => $Buttons,
                                'buttonUrls' => $ButtonsUrls)
                            ,
                            'buttons' => array("excel", "pdf")
                        );
                        try {
                            $dtableServer = new DataTable($options);
                        } catch (Zend_Db_Exception $ex) {
                            log::DB_hata_kaydi_ekle(__FILE__, $ex);
                        }
                        ?>
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">sorgulama Sonucu</h3>
                                    <?php
                                    echo $dtableServer->get_data_table();
                                    echo $dtableServer->get_datatable_script();
                                    ?>     
                                </div><!-- /.box-body -->
                                <div id="sorgulama_ekrani"></div>
                            </div>
                        </div>
                        <?php
                    } else {
                        adminLTE_alert(false, __("sorgulama Sonucu"), __("sorgulamada kayıt bulunamadı!"), "warning");
                    }
                }
            }
        }
        ?>
    </div>
</div>
</div>
<script>

    function unsecuredCopyToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
        } catch (err) {
            console.error('Unable to copy to clipboard', err);
        }
        document.body.removeChild(textArea);
    }
    $(function () {
        $('.table-data').click(function () {
            var copytext = $(this).text();
            unsecuredCopyToClipboard(copytext);
            $(this).css("font-weight", "bold");
            //console.log(copytext);
            // navigator.clipboard.writeText(copytext);
        });
    });

</script>
</div><!-- /.content -->
