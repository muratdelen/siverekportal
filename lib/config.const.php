<?php

ini_set("display_errors", 0);

$GoogleSiteKey = '6LdGwkYUAAAAAI4E-LhN5EES0lHVI6FuG-5vazxv';
$GoogleSecret = '6LdGwkYUAAAAANbD7ihRyPBBAoGNQE7JrreL9b_r';
date_default_timezone_set('Europe/Istanbul');
// root dizini
define('BASE_URL', '/siverekportal/');
//define('BASE_URL', '/tema/'); //for localhost
// Menu dizini
define('BASE_URL_MENU', '/siverekportal');
//define('BASE_URL_MENU', '/tema'); //for localhost
// Tema bulunan dizin
define('ASSETS_DIR', '/siverekportal/assets');
//define('ASSETS_DIR', '/tema/assets'); //for localhost
define('PDF_URL', '/siverekportal');

//TRANSLATE
//// define Constants For Translate
define('PROJECT_DIR', $_SERVER['DOCUMENT_ROOT']."/siverekportal/");
define('LOCALE_DIR', PROJECT_DIR .'/locale');
//define('IMAGE_DIRECTORY', BASE_URL . '/locale/_logo_images/');

//KİŞİSEL BİLGİSAYARDA ÇALIŞMASI İÇİN
define("APPLICATION_PATH", realpath('../lib/'));
define("CURRENT_PATH", getcwd());
$paths = array(APPLICATION_PATH, CURRENT_PATH);

set_include_path(implode($paths, PATH_SEPARATOR));

$libDir = realpath(dirname(__FILE__));

set_include_path('.' . PATH_SEPARATOR . $libDir . PATH_SEPARATOR ."$libDir" . PATH_SEPARATOR . get_include_path());

require 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Ldap');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Mail');

$_config['db'] = array( 
    'host' => '127.0.0.1',
    'dbname' => 'siverekportal',
    'username' => 'root',
    'password' => '',
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

?>