<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Rol Tanımlama Ekranı");
    $excel_sender->toExcelFromArray(array('Menuler', 'Yetkiler', 'Açıklama'), "header", "Rol Tanımlama Ekranı");
    $rol_id = mdecrypt($_POST['secilen_rol'], $_SESSION['key']);
    $ItemsSQL = "SELECT
                                        yt_menu.adi AS menuler,
                                        yt_sayfa_islemleri.adi AS yetkiler,
                                        yt_sayfa_islemleri.aciklama
                                        FROM
                                        yt_rol_sayfa_yetkileri
                                        INNER JOIN yt_menu ON yt_menu.id = yt_rol_sayfa_yetkileri.yt_menu_id
                                        INNER JOIN yt_sayfa_islemleri ON yt_sayfa_islemleri.id = yt_rol_sayfa_yetkileri.yt_sayfa_islemleri_id
                                        WHERE yt_rol_sayfa_yetkileri.aktif_mi AND yt_sayfa_islemleri.aktif_mi 
                                        AND yt_rol_sayfa_yetkileri.yt_rol_id = ? 
                                        ORDER BY yt_rol_sayfa_yetkileri.yt_menu_id";
    $excel_sender->toExcelFromSqlZend($ItemsSQL, $rol_id);
    $excel_sender->toExcelFinish();
}
?>