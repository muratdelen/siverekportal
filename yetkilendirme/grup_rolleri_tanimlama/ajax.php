<?php

require_once '../../lib/config.php';
require_once '../../lib/functions.php';
require_once '../../lib/input_filter.php';
date_default_timezone_set('Europe/Istanbul');

//var_dump($_POST);
//die();
$validator = new InputFilterClass();

$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

switch ($_POST["post_type"]) {
    case 0://MENU listeleme
        if (in_array(YT_INSERT, $sayfaIslemleriId)) {//kaydetme işlemi
            $secilen_kullanici_id = mdecrypt($_POST['secilen_grup'], $_SESSION['key']);
            $SelectedRoles = array();
            $ItemsSQL = "SELECT yt_grup_rolleri.yt_rol_id FROM yt_grup_rolleri WHERE yt_grup_rolleri.aktif_mi AND yt_grup_rolleri.yt_grup_id = ? ";
            try {
                $roles = $GLOBALS['db']->fetchAll($ItemsSQL, $secilen_kullanici_id);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            foreach ($roles as $value) {
                array_push($SelectedRoles, $value->yt_rol_id);
            }
            $ItemsSQL = "SELECT yt_rol.id, yt_rol.adi, yt_rol.aciklama FROM yt_rol WHERE aktif_mi";
            try {
                $RolItems = $GLOBALS['db']->fetchAll($ItemsSQL);
            } catch (Zend_Db_Exception $ex) {
                log::DB_hata_kaydi_ekle(__FILE__, $ex);
            }
            $rol_html = "";
            foreach ($RolItems as $RolItem) {
                if (in_array($RolItem->id, $SelectedRoles)) {
                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->aciklama . '" selected >' . $RolItem->adi . '</option>';
                } else {
                    $rol_html .= '<option value="' . $RolItem->id . '" title="' . $RolItem->aciklama . '">' . $RolItem->adi . '</option>';
                }
            }
            echo json_encode($rol_html);
        }
        break;    
}
?>

