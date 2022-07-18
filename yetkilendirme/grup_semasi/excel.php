
<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $_POST['grup'] = mdecrypt($_POST['grup'], $_SESSION['key']);
    $ItemsSQL = "CALL alt_grup_getir( ?, ? )";
    try {
        $listItems = $GLOBALS['db']->fetchAll($ItemsSQL, array($_POST['grup'], 1));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    htmlspecialchar_array($listItems);
    $listItemsTitle = array(
        'ana_grup' => 'Ana Grup Adı',
        'adi' => 'Grup Adı',
        'aciklama' => 'Açıklaması');

    $sender = new myexcel("GrupŞeması_" . (date('d-m-Y H:i:s')));
    $sender->start();
    $sender->printTitle = "Grup Şeması Sorgulama Ekranı";
    $sender->printDescription = "Bu çıktı  " . $kullanici . " tarafından " . date('d-m-Y') . " Tarihinde " . date('H:i:s') . " saatinde alınmıştır.";
    $sender->FromArrayToExcel($listItems, $listItemsTitle);
}
?>
 
