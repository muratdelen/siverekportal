<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $kullanici_id = mdecrypt($_POST['secilen_kullanici'], $_SESSION['key']);
    $excel_sender = new PhpExcelReaderWriter("Kullanıcı Rolleri Tanımlama");
    $excel_sender->toExcelFromArray(array('Kullanıcı Adı', 'Menuler', 'Yetkiler', 'Açıklama'), "header", "Grup Rolleri Ekranı");
    try {
        $listItems = $GLOBALS['db']->fetchAll("CALL kullanici_yetkilerini_goster ( ? )", $kullanici_id);
      $kullanici_adi = $GLOBALS['db']->fetchRow("SELECT yt_kullanici.kullanici_adi FROM yt_kullanici WHERE yt_kullanici.id = ? ", $kullanici_id);
   } catch (Zend_Db_Exception $ex) {
        log::DB_save_error_information(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);   
    foreach ($listItems as $value) {
        $value->id = $kullanici_adi->kullanici_adi;
        $excel_sender->toExcelFromArray($value, "normal");
    }
    $excel_sender->toExcelFinish();
}
?> 