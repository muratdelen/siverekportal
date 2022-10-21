<?php

//die("Bakım Yapılıyor...");

ob_start();

ini_set("display_errors", 1);
//var_dump($sayfaIslemleriId);
require_once 'config.const.php'; 
require_once('translate/gettext.inc');
include_once 'class.config.php';
//include_once 'yetki_kontrolu.php';
//require_once 'openamLoginControl.php';






try {
    if (!isset($_SESSION)) {
        session_start();
    }
} catch (Exception $exc) {
    
}
if (isset($_GET["logout"])) {
          global $kullaniciId;
        // Tüm session değişkenlerini sil.
        $_SESSION = array();

        // Eğer cookie değişkenleri varsa sil
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }
        // Sessionu sonlandır.
        session_destroy();
    header("location:/siverekportal");
    die();
}
//Dil ayarlanıyor

$selected_language = (isset($_SESSION["selected_language"])) ? $_SESSION["selected_language"] : 'tr';
$selected_country = (isset($_SESSION["selected_country"])) ? $_SESSION["selected_country"] : 'TR';


if (isset($_GET["lang"])) {
    if ($_GET["lang"] == 'en') {
        $selected_language = $_SESSION["selected_language"] = 'en';
        $selected_country = $_SESSION["selected_country"] = 'GB';
    } elseif ($_GET["lang"] == 'tr') {
        $selected_language = $_SESSION["selected_language"] = 'tr';
        $selected_country = $_SESSION["selected_country"] = 'TR';
    }
} else
if (isset($_POST["selected-country-language"])) {
    $selected_language = $_SESSION["selected_language"] = $_POST["selected-country-language"];
    $selected_country = $_SESSION["selected_country"] = $_POST["selected-country"];
} else if (isset($_SESSION["selected_language"])) {
    $selected_language = $_SESSION["selected_language"];
    if (isset($_SESSION["selected_country"])) {
        $selected_country = $_SESSION["selected_country"];
    }
}

//TRANSLATE
//// define Constants For Translate
//$supported_locales = array('en_US','tr_TR', 'sr_CS', 'de_CH');
$encoding = 'UTF-8';

$locale = $selected_language . "_" . $selected_country;
// gettext setup
//$text_domains[$domain]->codeset = $encoding;
_setlocale(LC_MESSAGES, $locale);
// Set the text domain as 'messages'
//$domain = 'messages';
_bindtextdomain('messages', LOCALE_DIR);
//var_dump($text_domains);
_bind_textdomain_codeset('messages', $encoding);
_textdomain('messages');
header("Content-type: text/html; charset=$encoding");


//açılan sayfa url
$pageUrl = "";
//kullanıcı giriş yapmış ise kullanıcı adı
$kullaniciAdi = "Giriş Yapmamış Kullanıcı";
$kullanici = "Giriş Yapmamış Kullanıcı";
$kullaniciId = 0;
//bağlanan kişinin ip adresi
$Ip = "";
//dışarıdan giriş yapılmadan erişilecek sayfalar listesi
$girissizErisilecekSayfalar = array('/index.php', '/login/index.php', '/login/index_.php', '/accessErrors/forbid.php', '/accessErrors/internalError.php', '/accessErrors/notfound.php', '/accessErrors/unauthorized.php', '/cron/dakikalik.php');
// giriş yapan kullanıcılar için herkesin erişebileceği sayfalar.[sistemsel sayfalar]
$ErisilecekSayfalar = array('/index.php', '/online/index.php', '/online/ajax.php', '/loglist/alert/index.php', '/loglist/alert/_table_content.php', '/assets/post_get_alert.php', '/personel_arama/index.php', '/numara_arama/index.php', '/bilgiguncelleme/index.php', '/hesap/index.php', '/bilgiguncelleme/kayit.php', '/bilgi_guncelleme/nation.php', '/bilgiguncelleme/_info.php', '/bilgiguncelleme/_kayit.php', '/bilgiguncelleme/_index.php', '/spamkontrol/index.php', '/accessErrors/forbid.php', '/accessErrors/internalError.php', '/accessErrors/notfound.php', '/accessErrors/unauthorized.php');
// giriş yapan kullanıcılar için herkesin erişebileceği sayfalar.[sistemsel sayfalar]
$ErisimKaydiTutulmayacakSayfalar = array('/index.php', '/assets/alert.php', '/assets/post_get_alert.php', '/assets/post_settings.php', '/accessErrors/internalError.php');
//sonradan düzenlenecektir
$sayfaIslemleriId = array(
//    1 //Görüntüleme
//    ,
//    2 //Insert
//    ,
//    3 //Update
//    ,
//    4 //Delete
//    ,
//    5 //Sorgulama & DataTable Görüntüleme
//    ,
//    6 //DataTable Excel aktarma
//    ,
//    7 //DataTable PDF aktarma
//    ,
//    8 //Yazdır
//    ,
//    9 //PageAdmin
);
//kullanıcı grup role Id
$girisYapanKullaniciGrupId = -1; //hiç giriş yapmamış kullanıcı
//Yazılımcılar için kod logları tutma
//LOGLAMAM DEĞİŞKENLERİ
//Bunlara atanan değerler ile sayfa sonunda loglama yapılır.

$islemLogId = 0; //yapılan işlem adı sonradan kullanıcıya gösterebilmek için
$sayfadaYapilanIslemId = -2; //yapılan işlem
//$islemBasligi = ""; //yapılan işlem adı sonradan kullanıcıya gösterebilmek için
//$islemYapilanTabloAdi = ""; //yapılan işlem tablo adı
//$islemYapilanTabloId = ""; //yapılan tablo id değeri
//$islemAciklamasi = ""; //yapılan işlem detayı veya sql cümlesi
//$islemGecmisi = ""; //eğer yapılan değişiklikten öncesini buraya kaydetmek isteniyorsa kaydedilir.
//kullanıcı adı ayarlanıyor
if (isset($_SESSION["kullanici_adi"])) {
    $kullaniciAdi = $_SESSION["kullanici_adi"];
}
//kullanıcı adı ayarlanıyor
if (isset($_SESSION["kullanici"])) {
    $kullanici = $_SESSION["kullanici"];
}
//kullanıcı adı ayarlanıyor
if (isset($_SESSION["kullanici_id"])) {
    $kullaniciId = $_SESSION["kullanici_id"];
}
$Ip = "";
//bağlanan kişinin ip adresi bağlanıyor
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $Ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $Ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $Ip = $_SERVER['REMOTE_ADDR'];
}

//if($Ip != '10.80.1.77'){
//    echo 'MURAT DELEN TARAFINDAN ERİŞİME GEÇİCİ OLARAK KAPATILDI. HATA İLE İLGİLİ ÇALIŞMA YAPILMAKTADIR.';
//    die();
//}else{
//    ini_set("display_errors", 1);
//}
//localhost için $pageUrl ayarlanıyor
if (BASE_URL == "/") {
    $pageUrl = $_SERVER['PHP_SELF'];
} else {
    $pageUrl = substr_replace($_SERVER['PHP_SELF'], "/", 0, strlen(BASE_URL));
}
//aynı sayfada birden fazla sayfa include edildiğinde kullanılır.
//bu şekilde o sayfada hangi php sayfası include edildiğinin kotrolü yapılır.
//buradaki ayarlamaları yaparken bu şekilde yetkisiz erişim engellenir.
//Örnek: /islemler/index.php?page=1 
//olarak kaydedildiğinde sistem bu sayfanın bu şekilde erişimine izin verdiğini belirtir.
// /islemler/index.php erişimde yetkisiz erişim hatası verir.
if (isset($_GET["page"])) {//aynı index sayfası içinde birden fazla sayfa eklemek için kullanılabilir.
    $pageUrl .= "?page=" . $_GET["page"];
}

//Group role belirlenmişse $girisYapanKullaniciGrupId değişkeni ayarlanır.
if (isset($_SESSION["grup_id"])) {
    $girisYapanKullaniciGrupId = $_SESSION["grup_id"];
}

include_once 'class.log.php';

/*
 * BURADA YETKİ KONTROLÜ YAPILMAKTADIR.
 */
if (in_array($pageUrl, $girissizErisilecekSayfalar)) {
    
} else if (isset($_SESSION["kullanici_adi"]) && in_array($pageUrl, $ErisilecekSayfalar)) {
    //Erişim Kayıtları Tutulmaktadır.
    if (!in_array($pageUrl, $ErisimKaydiTutulmayacakSayfalar)) {
        if (isset($_SESSION["kullanici_adi"])) {
            log::get_yapildiginda_erisim_kaydi();
        }
    }
} else {
    try {
        $ItemsSQL = 'CALL sayfa_yetkilerini_getir( ? , ? ) ';
        $sql_sayfaIslemleri = $GLOBALS['db']->fetchAll($ItemsSQL, array($kullaniciId, $pageUrl));
        if (isset($sql_sayfaIslemleri)) {
            foreach ($sql_sayfaIslemleri as $value) {
                array_push($sayfaIslemleriId, $value->id);
            }
        }
        if (count($sayfaIslemleriId) == 0) {//Engellendi
            if (isset($_SESSION["kullanici_adi"])) {
//                header("location:" . BASE_URL . "accessErrors/forbid.php");
            } else {
//                $url_all = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
//                $url = parse_url($url_all);
//                $redicrect_url = "";
//                if (isset($url["query"])) {
//                    $redicrect_url = $url_all . "&openam";
//                } else {
//                    $redicrect_url = $url_all . "?openam";
//                }
//                header("location:" . $redicrect_url);
                header("location:" . BASE_URL . "login");
            }
            exit();
            die();
        }
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
}

include_once 'uyari.php';

if (isset($_SESSION["kullanici_adi"]) && isset($_FILES["user-image-upload"])) {
    include 'upload.php';
}
?>