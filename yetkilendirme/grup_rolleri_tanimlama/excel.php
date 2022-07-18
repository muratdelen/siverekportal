<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
 $grup_id = mdecrypt($_POST['secilen_grup'], $_SESSION['key']);
    $excel_sender = new PhpExcelReaderWriter("Grup Rolleri Tanımlama");
    $excel_sender->toExcelFromArray(array('Grup Adı', 'Menuler', 'Yetkiler', 'Açıklama'), "header", "Grup Rolleri Ekranı");
    try {
        $listItems = $GLOBALS['db']->fetchAll("CALL grup_yetkilerini_goster ( ? )", $grup_id);
        $selected_group = $GLOBALS['db']->fetchRow("SELECT yt_grup.adi FROM yt_grup WHERE yt_grup.id = ? ", $grup_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_save_error_information(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);   
    foreach ($listItems as $value) {
        $value->id = $selected_group->adi;
        $excel_sender->toExcelFromArray($value, "normal");
    }
    $excel_sender->toExcelFinish();
}
?> 