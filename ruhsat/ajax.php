<?php

require_once '../lib/config.php';
require_once '../lib/functions.php';
require_once '../lib/input_filter.php';

$validator = new InputFilterClass();
$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

if (in_array(YT_QUERY, $sayfaIslemleriId)) {
 $rules = array(
        'izin_baslangic_tarihi' => 'required',
        'izin_bitis_tarihi' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        try {
            $izin_bilgisi = $GLOBALS['db']->fetchRow("SELECT isgunu_hesapla(?, ?) AS sonuc", array(convertDateFormatBasicDefault($_POST['izin_baslangic_tarihi']), convertDateFormatBasicDefault($_POST['izin_bitis_tarihi'])));
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        echo $izin_bilgisi->sonuc;
    } 
}
?>

