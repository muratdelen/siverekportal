<?php

/*
 * İp kontrolü yapılmaktadır
 */
ini_set("display_errors", 1);

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $Ip = $_SERVER['HTTP_CLIENT_IP'];
} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $Ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else if (!empty($_SERVER['REMOTE_ADDR'])) {
    $Ip = $_SERVER['REMOTE_ADDR'];
} else {
    $host2 = gethostname();
    $Ip = gethostbyname($host2);
}
define('APPLICATION_BASE_FOLDER', '');
define('APPLICATION_BASE_DIR', __DIR__ . "/.." . APPLICATION_BASE_FOLDER);

$izinVerilenIP = array('127.0.0.1', '10.80.1.77', '10.80.1.90', '10.80.1.112', '10.80.1.124', '193.140.229.241');
if (in_array($Ip, $izinVerilenIP)) {
//    if (session_status() == PHP_SESSION_NONE) {
//        session_start();
//    }
    //KİŞİSEL BİLGİSAYARDA ÇALIŞMASI İÇİN
    set_include_path(__DIR__ . "/../lib");
    require APPLICATION_BASE_DIR . '/lib/Zend/Loader.php';

    Zend_Loader::loadClass('Zend_Ldap');
    Zend_Loader::loadClass('Zend_Db');
    Zend_Loader::loadClass('Zend_Mail');
    $_config['db'] = array(
        'host' => '127.0.0.1',
        'dbname' => 'portal',
        'username' => 'portal',
        'password' => 'fxrkjVv9',
        'adapterNamespace' => 'ZendExtended'
    );

    try {
        $db = Zend_Db::factory('db', $_config['db']);
        $db->getConnection();
        $db->query("set names utf8");
        $db->setFetchMode(ZEND_DB::FETCH_OBJ);
    } catch (Exception $ex) {
        echo "VERİTABANI BAĞLANTI HATASI";
        die();
    }
} else {
    header("location:/accessErrors/forbid.php");
    die();
}
require_once APPLICATION_BASE_DIR . '/lib/functions.php';
?>