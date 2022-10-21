
<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . 'lib/functions.php';
require_once PROJECT_DIR . 'lib/input_filter.php';
require_once PROJECT_DIR . 'lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(6, $sayfaIslemleriId)) {
//    if ((isset($_POST['sorgu_tc_vergi_numarasi']) && $_POST['sorgu_tc_vergi_numarasi'] != '') || (isset($_POST['sorgu_gercek_tuzel_kisi_adi']) && $_POST['sorgu_gercek_tuzel_kisi_adi'] != '')) {

    $ItemsSQL = "SELECT grubu, tipi, kullanici_adi, adi, soyadi, baslik, aciklama, link, zaman, yeni_mi, aktif_mi FROM log_uyari_view  ";

    $listItemsTitle = array('Giriş Tipi','Grubu','Kullanıcı Adı','Adı','Soyadı', 'Başlık', 'Açıklama', 'Link', 'Zaman', 'Okundu Bilgisi','aktif_mi');
    $sender = new myexcel("uyari_loglari_" . (date('d-m-Y H:i:s')));
    $sender->start();
    $sender->printTitle = "Kullanıcı Uyarı Kayıtları";
    $sender->printDescription = "Bu çıktı  " . $_SESSION["kullanici"] . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde çıkarılmıştır.";
    $sender->FromSqlToExcel($ItemsSQL, $listItemsTitle);
}
?>
 
