<?php

require_once '../lib/config.php';
require_once '../lib/functions.php';
require_once '../lib/input_filter.php';

$validator = new InputFilterClass();
$_POST = $validator->sanitize($_POST); // Daha güvenli olması için bir kontrol yapılıyor.

if (in_array(YT_QUERY, $sayfaIslemleriId)) {
 $rules = array(
        'ruhsat_no' => 'required'
    );
    $validated = $validator->validate($_POST, $rules);

    if ($validated === TRUE) {//işlem sorunsuz yapılabilir
        try {
            $sonuc = $GLOBALS['db']->fetchRow("SELECT id FROM s_ruhsat_bilgileri WHERE ruhsat_no = ? ",$_POST["ruhsat_no"]);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        if(isset($sonuc->id)){
            echo 0;
        }else{
            echo 1;
        }
    } 
}
?>

