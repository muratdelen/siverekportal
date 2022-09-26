<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';

function menu_update($menu_id, $menu_parent_id, $order_id) {
    $is_success = 1;
    $data = array(
        'ana_menu_id' => $menu_parent_id,
        'order_id' => $order_id
    );
    $where["id = ?"] = $menu_id;
    try {
        $GLOBALS["db"]->update('yt_menu', $data, $where);
        log::islem_aciklamasi_kaydi("Menu Tanımlama", "Menu Güncelleme", YT_UPDATE);
    } catch (Zend_Db_Exception $ex) {
        var_dump($ex);
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
        $is_success = 0;
    }
    return $is_success;
}

$update_menus_data = $_POST["new_menu"];
$is_success = 0;
foreach ($update_menus_data as $order_id => $uptade_menu_data) {
    $menu_id = mdecrypt($uptade_menu_data["id"], $_SESSION['key']);
    $is_success = menu_update($menu_id, NULL, $order_id);
//        echo(" \n Menu_Parent$order_id->" . $menu_id);
    if (isset($uptade_menu_data["children"])) {
        $is_success = update_sub_menu($menu_id, NULL, $uptade_menu_data["children"]);
    }
}

function update_sub_menu($parent_menu_id, $parent_page_id, $update_menus_data) {
    $is_success = 0;
    foreach ($update_menus_data as $order_id => $uptade_menu_data) {
        if (strpos($uptade_menu_data["id"], "pages_") !== FALSE) {
            $page_id = str_replace("pages_", "", $uptade_menu_data["id"]);
            $page_id = mdecrypt($page_id, $_SESSION['key']);
            $is_success = page_update($page_id, $parent_menu_id, $parent_page_id, $order_id);
//            echo(" \n Page_Children$order_id->" . $page_id);
            if (isset($uptade_menu_data["children"])) {
                $is_success = update_sub_menu(NULL, $page_id, $uptade_menu_data["children"]);
            }
        } else {
            $menu_id = mdecrypt($uptade_menu_data["id"], $_SESSION['key']);
            $is_success = menu_update($menu_id, $parent_menu_id, $order_id);
//            echo(" \n Menu_Children$order_id->" . $menu_id);
            if (isset($uptade_menu_data["children"])) {
                $is_success = update_sub_menu($menu_id, NULL, $uptade_menu_data["children"]);
            }
        }
    }
    return $is_success;
}

echo $is_success;
