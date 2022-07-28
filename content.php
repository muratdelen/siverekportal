<div class="content-wrapper">
    <!-- Main content -->
    <div class="container-fluid">
        <?php
//        if ($girisYapanKullaniciGrupId != -1) {
        include_once 'assets/TemplateSlider.php';
//        }
        echo '<div class="content-header"></div>';
        //adminLTE_alert(FALSE, "Yasal Uyarı", "BU SİTEDEKİ VERİLER BİLGİLENDİRME AMAÇLI DÜZENLENMİŞ OLUP BAŞKA AMAÇLA KULLANILAMAZ.", "info");
//        $kisiler = $GLOBALS['db']->fetchAll("SELECT yt_kullanici_id, tc_no FROM sks_sozlesmeli_personel");
//        foreach ($kisiler as $key => $kisi) {
//            $sifre = md5(crypt($kisi->tc_no, $kisi->tc_no));
//            $GLOBALS['db']->fetchRow("UPDATE yt_kullanici SET sifre = ? WHERE id = ? ", array($sifre, $kisi->yt_kullanici_id));
//        } 

        try {
            $iskan_bekleyenler_ruhsatlar = $db->fetchAll("SELECT ruhsat_no, adi_soyadi, ada_parsel, DATE_FORMAT(s_ruhsat_bilgileri.ruhsat_tarihi,'%d/%m/%Y') AS ruhsat_tarihi FROM s_ruhsat_bilgileri WHERE aktif_mi AND iskan_verildi_mi = '0' AND ruhsat_tarihi BETWEEN (CURRENT_DATE() - INTERVAL 2 MONTH) AND CURRENT_DATE() ORDER BY ruhsat_no DESC");
            $iskan_ruhsatlar = $db->fetchAll("SELECT ruhsat_no, adi_soyadi, ada_parsel, DATE_FORMAT(s_ruhsat_bilgileri.iskan_ruhsat_tarihi,'%d/%m/%Y') AS iskan_ruhsat_tarihi FROM s_ruhsat_bilgileri WHERE aktif_mi AND iskan_verildi_mi AND iskan_ruhsat_tarihi BETWEEN (CURRENT_DATE() - INTERVAL 2 MONTH) AND CURRENT_DATE() ORDER BY ruhsat_no DESC");
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
//        htmlspecialchar_obj($iskan_ruhsatlar);
        ?>
        <div class="col-md-12 alert-info ">
            <div class="box box-success">
                <div class="box-header with-border" style="text-align: center;">
                    <h3 class="box-title" >İmar Ruhsatı Bekleyenler ...</h3>
                    <table border="1" cellpadding="1" cellspacing="1" class=" table table-striped table-bordered table-hover table-condensed" style="text-align:center;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;"><strong>Ruhsat Tarihi</strong></th>
                                <th style="text-align: center;"><strong>Ruhsat No</strong></th>
                                <th style="text-align: center;"><strong>Adı Soyadı</strong></th>
                                <th style="text-align: center;"><strong>Ada/Parsel</strong></th>
                            </tr>
                            <?php
                            foreach ($iskan_bekleyenler_ruhsatlar as $ruhsat) {
                                echo '<tr><td>' . $ruhsat->ruhsat_tarihi . '</td><td>' . $ruhsat->ruhsat_no . '</td><td style="text-align: left;">' . $ruhsat->adi_soyadi . '</td><td>' . $ruhsat->ada_parsel . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 alert-success ">
            <div class="box box-success">
                <div class="box-header with-border" style="text-align: center;">
                    <h3 class="box-title" >İmar Ruhsatı Çıkanlar</h3>
                    <table border="1" cellpadding="1" cellspacing="1" class=" table table-striped table-bordered table-hover table-condensed" style="text-align:center;">
                        <tbody>
                            <tr>
                                <th style="text-align: center;"><strong>İskan Tarihi</strong></th>
                                <th style="text-align: center;"><strong>Ruhsat No</strong></th>
                                <th style="text-align: center;"><strong>Adı Soyadı</strong></th>
                                <th style="text-align: center;"><strong>Ada/Parsel</strong></th>
                            </tr>
                            <?php
                            foreach ($iskan_ruhsatlar as $ruhsat) {
                                echo '<tr><td>' . $ruhsat->iskan_ruhsat_tarihi . '</td><td>' . $ruhsat->ruhsat_no . '</td><td style="text-align: left;">' . $ruhsat->adi_soyadi . '</td><td>' . $ruhsat->ada_parsel . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>       

    </div><!-- /.content -->
</div>
