
<?php

require_once '../lib/config.php';
require_once '../lib/functions.php';
require_once '../lib/class.pdf.php';

//$_POST = unserialize($_POST["params"]);

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
//    var_dump($secilen_ruhsat, $secilen_ruhsat_bilgileri);die();
    $pdf_class = new myPDF("Ekspertiz" . (date('d-m-Y H:i:s')));

//    $pdf_class->pageIsVertical = TRUE; // BU YORUM KALKTIĞINDA SAYFA YATAY OLACAKTIR.
    $pdf_class->barcodeVisible = FALSE; // BU YORUM KALKTIĞINDA BARCODE KALKACAKTIR.
    $pdf_class->headerVisible = FALSE;// BU YORUM KALKTIĞINDA HEADER YOK OLACAK
    $pdf_class->footerVisible = FALSE;// BU YORUM KALKTIĞINDA FOOTER YOK OLACAK 
//        $pdf_class->fontName = "times"; //"dejavusans";
    $pdf_class->fontSize = 12;
    $pdf_class->headerTitle = "Ekspertiz Çıktısı";
    $pdf_class->headerSubString = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
//    $pdf_class->barcodeUrl = PDF_URL . str_replace("pdf.php", "", $_SERVER['PHP_SELF']); // BARCODE ÇIKTI ALINAN SAYFA URL EKLİYOR.
//    $pdf_class->FromSqlToPdf($listItems, $listItemsTitle);


    $html_pdf = '<table>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="center"><strong>TC</strong> </td></tr>
<tr><td colspan="2" align="center"><strong>SİVEREK BELEDİYESİ</strong> </td></tr>
<tr><td colspan="2" align="center"> <strong>İMAR VE ŞEHİRCİLİK MÜDÜRLÜĞÜ</strong></td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="left">
Siverek Belediye Meclisinin 07.10.2015 tarih ve 2464 sayılı belediye gelirler kanunun 96 maddesinin a- fırkasının 2. paragrafında belirlenen vergi ve harçlara ilişkin kanuna istinaden belirlenen, en az ve en çok sınırlarını aşmamak şartıyla vergi ve harçlara ilişkin tarifelerin 21. maddesindeki imar hizmetlerinin 1. ve 2. başlığına göre harç alınmasına arz ederim
</td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="right"><strong>CANER ALTUNDAĞ </strong></td></tr>
<tr><td colspan="2" align="right"><strong>İmar ve Şehircilik Müd. V</strong></td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="left">Ekspertiz için imar planı örneği, ruhsat, yapı, kullanma izin belgesi örneği, proje vb. evrakların verilmesi-75 TL</td></tr>
<tr><td></td><td></td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="left><strong>Taşınmazın Sahibi</strong>:&nbsp;'.$secilen_ruhsat_bilgileri->adi_soyadi.'</td></tr>
<tr><td colspan="2" align="left"><strong>Taşınmazın Adresi</strong>:&nbsp;'.$secilen_ruhsat_bilgileri->mahallesi.'</td></tr>
<tr><td colspan="2" align="left"><strong>Ada/Parsel No</strong>:&nbsp;&nbsp;'.$secilen_ruhsat_bilgileri->ada_parsel.'</td></tr>
<tr><td colspan="2" align="left"><strong>Ruhsat Tarihi</strong>:&nbsp;&nbsp;'.$secilen_ruhsat_bilgileri->ruhsat_tarihi.'</td></tr>
<tr><td colspan="2" align="left"><strong>Ruhsat No</strong>:&nbsp;&nbsp;'.$secilen_ruhsat_bilgileri->ruhsat_no.'</td></tr>
<tr><td colspan="2" align="left">Ekspertiz için imar planı örneği, ruhsat, yapı, kullanma izin belgesi örneği, proje vb. evrakların verilmesi-75 TL</td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="left><strong>Taşınmazın Sahibi</strong>:&nbsp;'.$secilen_ruhsat_bilgileri->adi_soyadi.'</td></tr>
<tr><td colspan="2" align="left"><strong>Taşınmazın Adresi</strong>:&nbsp;'.$secilen_ruhsat_bilgileri->mahallesi.'</td></tr>
<tr><td colspan="2" align="left"><strong>Ada/Parsel No</strong>:&nbsp;&nbsp;'.$secilen_ruhsat_bilgileri->ada_parsel.'</td></tr>
<tr><td colspan="2" align="left"><strong>Ruhsat Tarihi</strong>:&nbsp;&nbsp;'.$secilen_ruhsat_bilgileri->ruhsat_tarihi.'</td></tr>
<tr><td colspan="2" align="left"><strong>Ruhsat No</strong>:&nbsp;&nbsp;'.$secilen_ruhsat_bilgileri->ruhsat_no.'</td></tr>
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
<tr><td colspan="2" align="center"><strong>İMZA</strong> </td></tr>
<tr><td></td><td></td></tr>
<tr><td colspan="2" align="center"> <strong></strong> </td></tr>
</table>';
//      echo $html_pdf;
    $pdf_class->FromHtmlToPdf($html_pdf);
}
?>
 
