<?php

require_once '../../lib/config.php';
require_once PROJECT_DIR . '/lib/functions.php';
require_once PROJECT_DIR . '/lib/input_filter.php';
require_once PROJECT_DIR . '/lib/class.excel.php';
$_POST = unserialize($_POST["params"]);

if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
    $excel_sender = new PhpExcelReaderWriter("Menu Tanımlama Ekranı");
    $excel_sender->toExcelFromArray(array('Ana Menu Adı', 'Menu Adı', 'Sayfa Url', 'Dışarıya Açık mı?', 'Dili', 'Aktif mi?'), "header", "Menu Tanımlama Ekranı");
    if ($_POST['menu'] == "") {
        $ItemsSQL = "SELECT
                        (SELECT yt_menu.adi FROM yt_menu WHERE yt_menu.id = selected_menu.ana_menu_id) AS main_menu,
                         selected_menu.adi, selected_menu.link, (CASE selected_menu.disariya_acik_mi WHEN 1 THEN '" . __("Dışarıya Açık") . "' WHEN 0 THEN '" . __("Sadece Girişli") . "' END ) AS disariya_acik_mi, selected_menu.`language`, (CASE selected_menu.aktif_mi WHEN 1 THEN '" . __("Aktif") . "' WHEN 0 THEN '" . __("Pasif") . "' END) AS aktif_mi
                   FROM yt_menu selected_menu";
        $excel_sender->toExcelFromSqlZend($ItemsSQL);
    } else {
        $menu_id = mdecrypt($_POST['menu'], $_SESSION['key']);
        $ItemsSQL = "SELECT
                        (SELECT yt_menu.adi FROM yt_menu WHERE yt_menu.id = selected_menu.ana_menu_id) AS main_menu,
                        selected_menu.adi, selected_menu.link, (CASE selected_menu.disariya_acik_mi WHEN 1 THEN '" . __("Dışarıya Açık") . "' WHEN 0 THEN '" . __("Sadece Girişli") . "' END ) AS disariya_acik_mi, selected_menu.`language`, (CASE selected_menu.aktif_mi WHEN 1 THEN '" . __("Aktif") . "' WHEN 0 THEN '" . __("Pasif") . "' END) AS aktif_mi
                    FROM yt_menu selected_menu WHERE id = ? ";
        $excel_sender->toExcelFromSqlZend($ItemsSQL, $menu_id);
    }
    $excel_sender->toExcelFinish();
}
?>