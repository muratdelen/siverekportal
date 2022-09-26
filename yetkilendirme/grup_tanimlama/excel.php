<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Grup Tanımlama Ekranı");
    $excel_sender->toExcelFromArray(array('Grup Adı', 'Açıklaması', 'Aktif Mi?'), "header", "Grup Tanımlama Ekranı");
    if (in_array(YT_PAGEADMIN, $sayfaIslemleriId)) {
        $ItemsSQL = "SELECT  yt_grup.adi, yt_grup.aciklama, (CASE yt_grup.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                                          FROM yt_grup WHERE NOT(id=1 OR id=2 OR id=3)";
        $excel_sender->toExcelFromSqlZend($ItemsSQL);
    } else {
        $grup_id = mdecrypt($_POST['grup'], $_SESSION['key']);
        $ItemsSQL = "SELECT  yt_grup.adi, yt_grup.aciklama, (CASE yt_grup.aktif_mi WHEN 1 THEN 'Aktif' WHEN 0 THEN 'Pasif' END) AS aktif_mi
                                         FROM yt_grup WHERE NOT(id=1 OR id=2 OR id=3) AND id = ? ";
        $excel_sender->toExcelFromSqlZend($ItemsSQL, $grup_id);
    }
    $excel_sender->toExcelFinish();
}
?>