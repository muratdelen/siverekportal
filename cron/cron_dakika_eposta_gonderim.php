<?php

//<!--
//Author: Murat DELEN
//Author URL: http://muratdelen.com
//Yazılımın Tasarlanmasından Geliştirme Sürecine Kadar Tamamıyla Bilgisayar Mühendisi Murat DELEN Tarafından Yapılmıştır.
//Gösterdiğim Emeğe Saygı Duyulduğu Sürece Yeni Eserler Vererek Ülkeme Katkı Sunmanın Onurunu Yaşayacağım.
//buraya gelen emailleri gönderimi yapmaktadır.
//-->
$mail = new MAIL();
try {
    $email_gonderilecekler_listesi = $GLOBALS['db']->fetchall("
       SELECT
            st_eposta_bilgisi.id,
            (SELECT st_eposta_izinli_domainler.domain_adi FROM st_eposta_izinli_domainler WHERE st_eposta_izinli_domainler.id = st_eposta_bilgisi.st_eposta_izinli_domainler_id) AS domain_adi ,
            st_eposta_bilgisi.st_eposta_izinli_domainler_id,
            st_eposta_bilgisi.gonderen_eposta_adresi,
            st_eposta_bilgisi.gonderen_gorunecek_ad,
            st_eposta_bilgisi.eposta_gonderilecek,
            st_eposta_bilgisi.eposta_basligi,
            st_eposta_bilgisi.eposta_icerigi,
            st_eposta_bilgisi.gonderme_zamani,
            st_eposta_istemeyenler_listesi.kontrol_kodu
FROM
st_eposta_bilgisi
LEFT JOIN st_eposta_istemeyenler_listesi ON st_eposta_bilgisi.eposta_gonderilecek = st_eposta_istemeyenler_listesi.eposta_gonderilecek
WHERE gonderim_tamamlandi_mi = '-1' AND NOT st_eposta_istemeyenler_listesi.eposta_gonderilmesin_mi LIMIT 10");
} catch (Zend_Db_Exception $ex) {
    log::DB_hata_kaydi_ekle(__FILE__, $ex);
}
//EMAİL YOLLANIYOR..
foreach ($email_gonderilecekler_listesi as $email_gonderim_bilgisi) {
    $encry_domain_id = mcrypt($email_gonderim_bilgisi->st_eposta_izinli_domainler_id, "epostabildirim_muratdelen");
    $encry_domain_id = urlencode($encry_domain_id);
    $email_yollamasını_engelleme_linki = "https://portal.Siverek.edu.tr/eposta_istemiyorum/?kod=" . $email_gonderim_bilgisi->kontrol_kodu . "&eposta=" . $email_gonderim_bilgisi->eposta_gonderilecek . "&domain=" . $encry_domain_id;
    $email_yollamasını_engelleme_iptal_linki = "https://portal.Siverek.edu.tr/eposta_istemiyorum/?iptal&kod=" . $email_gonderim_bilgisi->kontrol_kodu . "&eposta=" . $email_gonderim_bilgisi->eposta_gonderilecek . "&domain=" . $encry_domain_id;
    $mail->admin["from"] = $email_gonderim_bilgisi->gonderen_eposta_adresi;
    $mail->admin["fromname"] = $email_gonderim_bilgisi->gonderen_gorunecek_ad;
    $mesaj = '
            <table style="border: 2px solid #ddd;">
                <tr style="background-color: #f2f2f2">
                    <td style="border: 2px solid #ddd;">' . $email_gonderim_bilgisi->eposta_icerigi . '</td>
                </tr>
                <tr>
                   <td  style="background-color:#f2dede; border: 2px solid #ebccd1;font-weight: bold;"><a style="color:#a94442;" href="' . $email_yollamasını_engelleme_linki . '" target="_blank">' . $email_gonderim_bilgisi->domain_adi . ' uygulamasından E-posta almak istemiyorsanız bu linki tıklayınız (If you do not want to receive e-mail please click this link).</a> </td>
                </tr>
                <tr>
                    <td  style="background-color:#dff0d8; border: 2px solid #d6e9c6;font-weight: bold;"><a style="color:#3c763d;" href="' . $email_yollamasını_engelleme_iptal_linki . '" target="_blank">' . $email_gonderim_bilgisi->domain_adi . ' uygulamasından tekrar E-posta almak istiyorsanız bu linki tıklayınız (If you do want to receive e-mail please click this link).</a> </td>
                </tr>
            </table>
            ';
    $email_gonderme_islemi_basarili_mi = $mail->sendMail($email_gonderim_bilgisi->eposta_gonderilecek, $email_gonderim_bilgisi->eposta_basligi, $mesaj);
    if ($email_gonderme_islemi_basarili_mi) {
        try {//Başarılı gönderildiyse veritabanında bilgi güncellenecektir
            $GLOBALS['db']->fetchRow("UPDATE st_eposta_bilgisi SET gonderim_tamamlandi_mi = '1', gonderme_zamani = NOW() WHERE id = ? ", $email_gonderim_bilgisi->id);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    } else {
        try {//Başarısız gönderildiyse veritabanında bilgi güncellenecektir
            $GLOBALS['db']->fetchRow("UPDATE st_eposta_bilgisi SET gonderim_tamamlandi_mi = '0', gonderme_zamani = NOW() WHERE id = ? ", $email_gonderim_bilgisi->id);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }
}
?>