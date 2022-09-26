

<div  class="col-md-8" id="sorgulama_ekrani_div">
    <div class="box" >
        <div >

            <div class="box box-solid box-default">
                <h4 class="box-title" style="margin-left: 5px;">Sözleşme İşlemleri - Sorgulama</h4>
                <form class="form-horizontal" method="post" action="">
                    <div class="form-group form-group-sm">
                    </div>    
                    <div class="form-group form-group-sm">
                        <label class="col-sm-3 control-label" for="formGroupInputSmall">T.C. / Vergi Numarası</label>
                        <div class="col-sm-5">
                            <input class="form-control" name="sorgu_tc_vergi_numarasi" type="text" id="formGroupInputSmall">
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label class="col-sm-3 control-label" for="formGroupInputSmall">Gerçek/Tüzel Kişi Adı</label>
                        <div class="col-sm-5">
                            <input class="form-control" type="text" name="sorgu_gercek_tuzel_kisi_adi" id="formGroupInputSmall" >

                        </div>
                        <div class="form-group form-group-sm">
                            <input class="btn btn-primary" type="submit"  id="btn_sorgula" onclick="func_acma_kapama()" name="Sorgula" value="Sorgula"/>
                        </div> 
                    </div>

                </form>
            </div>
        </div>
    </div>


    <?php
    if (isset($_POST['Sorgula'])) {
        if (isset($_POST['Sorgula']) && ((isset($_POST['sorgu_tc_vergi_numarasi']) && $_POST['sorgu_tc_vergi_numarasi'] != '') || (isset($_POST['sorgu_gercek_tuzel_kisi_adi']) && $_POST['sorgu_gercek_tuzel_kisi_adi'] != ''))) {
            $sorgulama_ekranindan_gelen = '';
            if (isset($_POST['sorgu_tc_vergi_numarasi']) && $_POST['sorgu_tc_vergi_numarasi'] != '') {
                $sorgulama_ekranindan_gelen .= ' and tc_vergi_no="' . $_POST['sorgu_tc_vergi_numarasi'] . '"';
            }
            if (isset($_POST['sorgu_gercek_tuzel_kisi_adi']) && $_POST['sorgu_gercek_tuzel_kisi_adi'] != '') {
                $sorgulama_ekranindan_gelen .= ' and kisi.adi like "%' . $_POST['sorgu_gercek_tuzel_kisi_adi'] . '%"';
            }

            if (isset($_POST['Sorgula'])) {
                $ItemsSQL = 'SELECT 
                                kira_sozlesme.id as id,
                    
                                kisi.adi as kisi_adi,
                                tasinmaz.adi as tasinmaz_adi,
                                kira_sozlesme.sozlesme_turu,
                                kira_sozlesme.kira_baslangic_tarihi,
                                kira_sozlesme.kira_bitis_tarihi
                                FROM kira_sozlesme
                                INNER JOIN kisi ON kisi.id = kira_sozlesme.kisi_id
                                INNER JOIN tasinmaz ON tasinmaz.id = kira_sozlesme.tasinmaz_id
                                where 
                                kira_sozlesme.aktif_mi=1' . $sorgulama_ekranindan_gelen;
//            echo $ItemsSQL;
                try {
                    $listBoxItems = $GLOBALS['db']->fetchAll($ItemsSQL);
                } catch (Zend_Db_Exception $ex) {
                    log::DB_hata_kaydi_ekle(__FILE__, $ex);ekle("Veritabanı Hatası", $hata_Zend_Db_Exception->getCode(), $hata_Zend_Db_Exception->getMessage(), $sayfa_url, $satir_numarası); }
//                var_dump($listBoxItems);
//                                    foreach ($listBoxItems as $listBoxItem) {
//                                        echo '<option value="' . $listBoxItem->id . '">' . $listBoxItem->adi . '</option>';
                if ((isset($listBoxItems)) && !empty($listBoxItems)) {

                    $options2 = array(
                        //zorunlu parametreler
                        'tableHeaders' => array('Kişi', 'Taşınmaz', 'Sözleşme Türü', 'Başlangıç Tarihi', 'Bitiş Tarihi', 'İşlemler'),
                        'buttonPostPage' => 'index.php',
                        //zorunlu olmayan parametreler
//        'id' => 'example2' , // optional
                        'order' => array(0, 'asc'),
//        'tableFooters' => array('id','e-Mailss','Şehir','İşlem','İşlem','İşlem'), // optional
//        'filters' => array(array('Gecko', 'Trident', 'KHTML', 'Misc', 'Presto', 'Webkit', 'Tasman'),'text','text','text','text'),
                        //yerel parametreler
                        'tableData' => $listBoxItems,
                    );


                    try {
                        $dtableServer = new DataTable($options2);
                    } catch (Exception $ex) {
                        die($ex->getMessage());
                    }
                    ?>
                </div> <div class="col-md-12">
                    <div class="box">
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
    } else {
        echo '<div class="col-md-8">';
        adminLTE_alert('"T.C. / Vergi Numarası" veya "Gerçek/Tüzel Kişi Adı" kısımlarından birini yazarak sorgulama yapınız. ', "warning");
        echo '</div>';
//echo '"T.C. / Vergi Numarası" veya "Gerçek/Tüzel Kişi Adı" kısımlarından birini yazarak sorgulama yapınız. '; 
    }
}
?> 

<div class="col-md-8">
    <form class="form-horizontal" method="post" action="">
        <input class="btn btn-primary" type="submit" id="btn_yeni_kayit_ekle" name="YeniKayit" value="Yeni Kayit Ekle">
    </form>

</div>
</div>