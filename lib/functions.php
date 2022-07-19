<?php

function get_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function url_origin($s, $use_forwarded_host = false) {
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on' );
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . ( ( $ssl ) ? 's' : '' );
    $port = $s['SERVER_PORT'];
    $port = ( (!$ssl && $port == '80' ) || ( $ssl && $port == '443' ) ) ? '' : ':' . $port;
    $host = ( $use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST']) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null );
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url($s, $use_forwarded_host = false) {
    return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}

/**
 * 
 * @param string $gonderilecek_eposta
 * @param ınt $uygulama_id
 * @param string $eposta_basligi
 * @param string $eposta_icerigi
 * @param string $gonderen_eposta
 * @param string $gorunecek_ad
 * @return ınt -2 ->bu domaine izin verilmedi. epostabildirim.hacette üzerinden eklenmesi gerekiyor -1 bu eposta gönderilmesi engellendi -> 0 -> işlem yapılmadı 1 -> kaydetme işlemi başarılı

 *  örnek:
 *  sender_email("muratdelen", 0, "bu bir denemedir11", "deneme içeriği ");
 * */
function sender_email($gonderilecek_eposta, $uygulama_id, $eposta_basligi, $eposta_icerigi, $gonderen_eposta = "", $gorunecek_ad = "") {

    $basari_durumu = 0;
    $_config['db_eposta'] = array(
        'host' => '127.0.0.1',
        'dbname' => 'portal',
        'username' => 'portal',
        'password' => '',
        'adapterNamespace' => 'ZendExtended'
    );
    try {
        $db_eposta = Zend_Db::factory('db', $_config['db_eposta']);
        $db_eposta->getConnection();
        $db_eposta->query("set names utf8");
        $db_eposta->setFetchMode(ZEND_DB::FETCH_OBJ);
    } catch (Exception $ex) {
        return 0;
    }
    $domain_adi = $_SERVER['HTTP_HOST'];
    if (strpos($gonderilecek_eposta, "@Siverek.edu.tr") !== false) {//Siverek EPOSTA İLE
    } else if (strpos($gonderilecek_eposta, "@") === false) {// sadece kullanıcı adı yazılmış epostayı da biz yazalım
        $gonderilecek_eposta = $gonderilecek_eposta . "@Siverek.edu.tr";
    } else {//DIŞARIDAKİ EPOSTAYA GÖNDERİLECEKTİR.
    }
    if ($gonderen_eposta == "") {
        $gonderen_eposta = "info@" . $domain_adi;
    }
    if ($gorunecek_ad == "") {
        $gorunecek_ad = "noreply@" . $domain_adi;
    }
    try {
        $sonuc = $db_eposta->fetchRow("SELECT eposta_gonder(?, ?, ?, ?, ?, ?, ?, ?) AS basari", array($domain_adi, $uygulama_id, uniqid(), $gonderen_eposta, $gorunecek_ad, $gonderilecek_eposta, $eposta_basligi, $eposta_icerigi));
        if (isset($sonuc->basari)) {
            $basari_durumu = $sonuc->basari;
        }
    } catch (Zend_Db_Exception $ex) {
        var_dump($ex->getMessage());
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $db_eposta->closeConnection();
    return $basari_durumu;
}

function select_sender_email($gonderilen_eposta = "", $uygulama_id = "") {
    $where = "";
    $where_arr = array($_SERVER['HTTP_HOST']);
    if ($gonderilen_eposta != "") {
        $where .= " AND st_eposta_bilgisi.eposta_gonderilecek = ? ";
        array_push($where_arr, $gonderilen_eposta);
    }
    if ($uygulama_id != "") {
        $where .= " AND st_eposta_bilgisi.uygulama_id = ? ";
        array_push($where_arr, $uygulama_id);
    }
    $basari_durumu = 0;
    $_config['db_eposta'] = array(
        'host' => '127.0.0.1',
        'dbname' => 'portal',
        'username' => 'portal',
        'password' => '',
        'adapterNamespace' => 'ZendExtended'
    );
    try {
        $db_eposta = Zend_Db::factory('db', $_config['db_eposta']);
        $db_eposta->getConnection();
        $db_eposta->query("set names utf8");
        $db_eposta->setFetchMode(ZEND_DB::FETCH_OBJ);
    } catch (Exception $ex) {
        return 0;
    }
    try {
        $sonuc = $db_eposta->fetchAll("
                    SELECT
                            st_eposta_bilgisi.gonderen_eposta_adresi,
                            st_eposta_bilgisi.uygulama_id,
                            st_eposta_bilgisi.gonderen_gorunecek_ad,
                            st_eposta_bilgisi.eposta_gonderilecek,
                            st_eposta_bilgisi.eposta_basligi,
                            st_eposta_bilgisi.eposta_icerigi,
                            st_eposta_bilgisi.gonderme_zamani,
                            st_eposta_bilgisi.gonderim_tamamlandi_mi
                    FROM st_eposta_bilgisi LEFT JOIN st_eposta_izinli_domainler ON st_eposta_bilgisi.st_eposta_izinli_domainler_id = st_eposta_izinli_domainler.id
                    WHERE st_eposta_izinli_domainler.domain_adi = ? " . $where, $where_arr);
        return $sonuc;
    } catch (Zend_Db_Exception $ex) {
        var_dump($ex->getMessage());
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $db_eposta->closeConnection();
    return 0;
}

/**
 * 
 * @param string $gonderilecek_eposta
 * @param ınt $uygulama_id
 * @param string $eposta_basligi
 * @param string $eposta_icerigi
 * @param string $gonderen_eposta
 * @param string $gorunecek_ad
 * @param string $banner
 * @param string $baslik
 * @return ınt -2 ->bu domaine izin verilmedi. epostabildirim.hacette üzerinden eklenmesi gerekiyor -1 bu eposta gönderilmesi engellendi -> 0 -> işlem yapılmadı 1 -> kaydetme işlemi başarılı
 */
function sender_email_special($gonderilecek_eposta, $uygulama_id, $eposta_basligi, $eposta_icerigi, $gonderen_eposta = "", $gorunecek_ad = "", $banner = "", $baslik = "") {
    $banner_html = "";
    $baslik_html = "";
    if ($banner != "") {
        $banner_html = '
                <tr>
                    <th><a href="//' . $_SERVER['HTTP_HOST'] . '" target="_blank"><img src="' . $banner . '" alt="logo"></a></th>
                </tr>';
    }
    if ($baslik != "") {
        $baslik_html = '
                <tr>
                    <th><h3 style="text-align:center;">' . $baslik . '</h3></th>
                </tr>';
    }
    $sender_mail_html = '<table>
                          ' . $banner_html . '
                          ' . $baslik_html . '
                          <tr style="background-color: #f2f2f2">
                            <td>' . $eposta_icerigi . '</td>
                          </tr>
                        </table>';
    return sender_email($gonderilecek_eposta, $uygulama_id, $eposta_basligi, $sender_mail_html, $gonderen_eposta, $gorunecek_ad);
}

function adminLTE_redirect($is_modal, $alert_header, $alert_message, $alert_type, $is_remove_time = 1000000, $yonlendirilecek_url = "") {
    $_SESSION['adminLTE_alert'] = array($is_modal, $alert_header, $alert_message, $alert_type, $is_remove_time);
    if ($yonlendirilecek_url == "") {
        header("location:" . $_SERVER['PHP_SELF']);
        die();
    } else {
        header("location:" . $yonlendirilecek_url);
        die();
    }
}

//MENU FUNCTION
function get_menu_items_option_html_for_public() {
    global $selected_language;
    $menuItemsSQL = 'SELECT * FROM yt_menu WHERE yt_menu.disariya_acik_mi AND ISNULL(yt_menu.ana_menu_id) AND language = ? ';
    try {
        $menuItems = $GLOBALS['db']->fetchAll($menuItemsSQL, $selected_language);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    $menu_order = 0;
    foreach ($menuItems as $MenuItem) {
        $menu_order++;
        $id_sifreli = mcrypt($MenuItem->id, $_SESSION['key']);
        $menu_html .= '<option value="' . $id_sifreli . '" title="' . $MenuItem->link . '">' . $MenuItem->adi . '</option>' . get_submenu_items_option_html($MenuItem->id);
    }
    return $menu_html;
}

function get_menu_items_option_html_for_public_selected($menu_id) {
    global $selected_language;
    $menuItemsSQL = 'SELECT * FROM yt_menu WHERE yt_menu.disariya_acik_mi AND ISNULL(yt_menu.ana_menu_id) AND language = ? ';
    try {
        $menuItems = $GLOBALS['db']->fetchAll($menuItemsSQL, $selected_language);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    $menu_order = 0;
    foreach ($menuItems as $MenuItem) {
        $menu_order++;
        $id_sifreli = mcrypt($MenuItem->id, $_SESSION['key']);
        $selected = ($menu_id == $MenuItem->id ? " selected " : "");
        $menu_html .= '<option value="' . $id_sifreli . '" title="' . $MenuItem->link . '" ' . $selected . '>' . $MenuItem->adi . '</option>' . get_submenu_items_option_html_selected($menu_id, $MenuItem->id);
    }
    return $menu_html;
}

function get_menu_items_option_html() {
    global $selected_language;
    $menuItemsSQL = 'SELECT * FROM yt_menu WHERE ISNULL(yt_menu.ana_menu_id) AND language = ? ';
    try {
        $menuItems = $GLOBALS['db']->fetchAll($menuItemsSQL, $selected_language);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    $menu_order = 0;
    foreach ($menuItems as $MenuItem) {
        $menu_order++;
        $id_sifreli = mcrypt($MenuItem->id, $_SESSION['key']);
        $menu_html .= '<option value="' . $id_sifreli . '" title="' . $MenuItem->link . '">' . $MenuItem->adi . '</option>' . get_submenu_items_option_html($MenuItem->id);
    }
    return $menu_html;
}

function get_menu_items_option_html_selected($menu_id) {
    global $selected_language;
    $menuItemsSQL = 'SELECT * FROM yt_menu WHERE ISNULL(yt_menu.ana_menu_id) AND language = ? ';
    try {
        $menuItems = $GLOBALS['db']->fetchAll($menuItemsSQL, $selected_language);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    $menu_order = 0;
    foreach ($menuItems as $MenuItem) {
        $menu_order++;
        $id_sifreli = mcrypt($MenuItem->id, $_SESSION['key']);
        $selected = ($menu_id == $MenuItem->id ? " selected " : "");
        $menu_html .= '<option value="' . $id_sifreli . '" title="' . $MenuItem->link . '" ' . $selected . '>' . $MenuItem->adi . '</option>' . get_submenu_items_option_html_selected($menu_id, $MenuItem->id);
    }
    return $menu_html;
}

function get_submenu_items_option_html($menu_id, $subicon = "&nbsp;&nbsp;") {
    global $selected_language;
    //EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
    $subMenuItemsSQL = 'SELECT * FROM yt_menu WHERE yt_menu.ana_menu_id = ? AND language = ?';
    try {
        $subMenuItems = $GLOBALS['db']->fetchAll($subMenuItemsSQL, array($menu_id, $selected_language));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    $menu_order = 0;
    $subicon .= "&nbsp;&nbsp;";
    foreach ($subMenuItems as $subMenuItem) {
        $menu_order++;
        $id_sifreli = mcrypt($subMenuItem->id, $_SESSION['key']);
        $menu_html .= '<option value="' . $id_sifreli . '" title="' . $subMenuItem->link . '">' . $subicon . $subMenuItem->adi . '</option>' . get_submenu_items_option_html($subMenuItem->id, $subicon);
    }
    return $menu_html;
}

function get_submenu_items_option_html_selected($selected_menu_id, $menu_id, $subicon = "&nbsp;&nbsp;") {
    global $selected_language;
    //EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
    $subMenuItemsSQL = 'SELECT * FROM yt_menu WHERE yt_menu.ana_menu_id = ? AND language = ?';
    try {
        $subMenuItems = $GLOBALS['db']->fetchAll($subMenuItemsSQL, array($menu_id, $selected_language));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    $menu_order = 0;
    $subicon .= "&nbsp;&nbsp;";
    foreach ($subMenuItems as $subMenuItem) {
        $menu_order++;
        $id_sifreli = mcrypt($subMenuItem->id, $_SESSION['key']);
        $selected = ($selected_menu_id == $subMenuItem->id ? " selected " : "");
        $menu_html .= '<option value="' . $id_sifreli . '" title="' . $subMenuItem->link . '" ' . $selected . '>' . $subicon . $subMenuItem->adi . '</option>' . get_submenu_items_option_html_selected($selected_menu_id, $subMenuItem->id, $subicon);
    }
    return $menu_html;
}

function fatal_handler() {
    $errfile = "unknown file";
    $errstr = "shutdown";
    $errno = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();

    if ($error !== NULL) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];

        if ($errno == 1) {
            $_SESSION['error'] = _('Bağlantı zaman aşımına uğradı tekrar deneyiniz.');
//            $_SESSION['error'] = format_error($errno, $errstr, $errfile, $errline);
        } else {
//                $_SESSION['error'] = _('Hata oluştu lütfen tekrar deneyiniz...');
            $_SESSION['error'] = $errstr;
        }
        if (isset($_POST['nationality'])) {
            if ($_POST['nationality'] == 'int') {
                header("location:./?nationality=int");
            }
            header("location:./");
        }
    }
}

function format_error($errno, $errstr, $errfile, $errline) {
    $trace = print_r(debug_backtrace(false), true);

    $content = "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
    $content .= "<tr valign='top'><td><b>Error</b></td><td><pre>$errstr</pre></td></tr>";
    $content .= "<tr valign='top'><td><b>Errno</b></td><td><pre>$errno</pre></td></tr>";
    $content .= "<tr valign='top'><td><b>File</b></td><td>$errfile</td></tr>";
    $content .= "<tr valign='top'><td><b>Line</b></td><td>$errline</td></tr>";
    $content .= "<tr valign='top'><td><b>Trace</b></td><td><pre>$trace</pre></td></tr>";
    $content .= '</tbody></table>';

    return $content;
}

function en_lowercase($sData) {

    return strtolower(tr_to_en($sData));
}

function tr_to_en($sData) {
    $newphrase = $sData;

    $newphrase = str_replace("Ü", "U", $newphrase);
    $newphrase = str_replace("Ş", "S", $newphrase);
    $newphrase = str_replace("Ğ", "G", $newphrase);
    $newphrase = str_replace("Ç", "C", $newphrase);
    $newphrase = str_replace("İ", "I", $newphrase);
    $newphrase = str_replace("Ö", "O", $newphrase);
    $newphrase = str_replace("ü", "u", $newphrase);
    $newphrase = str_replace("ş", "s", $newphrase);
    $newphrase = str_replace("ç", "c", $newphrase);
    $newphrase = str_replace("ı", "i", $newphrase);
    $newphrase = str_replace("ö", "o", $newphrase);
    $newphrase = str_replace("ğ", "g", $newphrase);

    return $newphrase;
}

function get_validator_errors($validatorHandle) {
//                return $v['rule'];

    foreach ($validatorHandle as $v) {
        return $v['rule'];
    }
}

function en_uppercase($sData) {

    return strtoupper(tr_to_en($sData));
}

function csrfguard_generate_token() {

    if (function_exists("hash_algos") and in_array("sha512", hash_algos())) {
        $token = hash("sha512", mt_rand(0, mt_getrandmax()));
    } else {
        $token = ' ';
        for ($i = 0; $i < 128; ++$i) {
            $r = mt_rand(0, 35);
            if ($r < 26) {
                $c = chr(ord('a') + $r);
            } else {
                $c = chr(ord('0') + $r - 26);
            }
            $token .= $c;
        }
    }

    unset($_SESSION['token']);
    $_SESSION['token'] = $token;
    return $token;
}

function csrfguard_isvalid_token($token) {

    if ($token == NULL) {
        return FALSE;
    }
    $sesToken = $_SESSION['token'];
    unset($_SESSION['token']);

    if (isset($sesToken)) {
        if ($sesToken == $token) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

function startsWith($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}

function convertDateFormatBasic($old_date) {

    $date = str_replace('/', '-', $old_date);

    $timestamp = strtotime($date);

    $new_date = date('d-m-Y', $timestamp);

    return $new_date;
}

function convertDateFormatBasicSlah($old_date) {

    $date = str_replace('/', '-', $old_date);

    $timestamp = strtotime($date);

    $new_date = date('d/m/Y', $timestamp);

    return $new_date;
}

function convertDateFormat($old_date) {

    $date = str_replace('/', '-', $old_date);

    $timestamp = strtotime($date);

    $new_date = date('d-m-Y H:i:s', $timestamp);

    return $new_date;
}

function convertDateFormatBasicDefault($old_date) {

    $date = str_replace('/', '-', $old_date);

    $timestamp = strtotime($date);

    $new_date = date('Y-m-d', $timestamp);

    return $new_date;
}

function convertDateFormatDefault($old_date) {

    $date = str_replace('/', '-', $old_date);

    $timestamp = strtotime($date);
    ;

    $new_date = date('Y-m-d H:i:s', $timestamp);

    return $new_date;
}

function redirect($url, $message = '') {
    global $_config;

    //eğer $message parametresi null değilse header php içindeki msgbox divivnin içinde görünmesi için değişkeni session a ata...
    if ($message) {
        $_SESSION['msgBox'] = $message;
    }

    //url '/' ise site root una ata...
    if ($url[0] == '/') {
        $url = preg_replace("!^{$_config['baseUrl']}!", '', $url);
        $url = ltrim($url, '/');
        $url = $_config['baseUrl'] . '/' . $url;
    }

    //eğer url null ise son gezilen sayfayı al ve url 'ye ata...
    if (is_null($url)) {
        $url = getLastUrl();
    }

    if (!$_SESSION['urlHistory'])
        $_SESSION['urlHistory'] = array();


    // Redirect edilen sayfayı stack'ten çıkart, tekrar
    // redirect edilirse bir öncekine gidebilsin.Yoksa
    // redirect loop oluyor.
    foreach ($_SESSION['urlHistory'] as $key => $val) {
        if ($val == $url) {
            unset($_SESSION['urlHistory'][$key]);
            break;
        }
    }

    //header("Status: 200");
    header("Location: $url");

    $GLOBALS['__redirected'] = true;
    exit;
}

function parametrik_secenekler_getir($parametre) {

    $ItemsSQL = 'CALL parametrik_secenekler_getir(?) ';
    try {
        $listBoxItems = $GLOBALS['db']->fetchAll($ItemsSQL, array($parametre));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $return = '';
    foreach ($listBoxItems as $listBoxItem) {
        $return .= '<option value="' . $listBoxItem->id . '">' . $listBoxItem->tanim . '</option>';
    }

    return $return;
}

function get_prepared_sql($table_name, $data, $type, $where = NULL) {

    switch ($type) {
        case 'INSERT': case 'insert' :
            $binds = $columns = '(';
            $sql = NULL;

            foreach ($data as $key => $value) {
                $columns .= $key . ',';
                $binds .= '?,';
            }
            $columns = rtrim($columns, ',') . ')';
            $binds = rtrim($binds, ',') . ')';
            $sql = "INSERT INTO $table_name $columns VALUES $binds" . (isset($where) ? $where : '');
            break;
        case 'UPDATE': case 'update' :
            $columns = '';
            foreach ($data as $key => $value) {
                $columns .= $key . '= ? ,';
            }
            $columns = rtrim($columns, ',');
            $sql = "UPDATE $table_name SET $columns " . (isset($where) ? $where : '');
            break;
    }
    return $sql;
}

function validate_id($table, $id, $where = NULL) {

    if (!isset($where)) {
        $where = 'WHERE id = ?';
    }

    $validateIdSQL = "SELECT id FROM $table WHERE id = ? ";
    try {
        $result = $GLOBALS['db']->fetchRow($validateIdSQL, $id);
        if (isset($result->id)) {
            return true;
        }
        return false;
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

/**
 * ************************************  function htmlspecialchar_array()  *****************************
 * Dönen değerler içerisindeki html tabanlı  karakterleri tarayıcının yorumlamasını engeller.
 * @param - array -  $variable - özel karakterlerden temizlenecek dizi
 */
function htmlspecialchar_array(&$variable) {
    if (is_array($variable)) {
        foreach ($variable as &$value) {
            if (!is_array($value)) {
                if (is_string($value)) {
                    $value = htmlspecialchars($value);
                }
            } else {
                htmlspecialchar_array($value);
            }
        }
    } else {
        if (is_string($variable)) {
            $variable = htmlspecialchars($variable);
        }
    }
}

/**
 * ************************************  function htmlspecialchar_obj()  *****************************
 * Dönen değerler içerisindeki özel karakterleri tarayıcının yorumlamasını engeller.
 * @param - object -  $variable - özel karakterlerden temizlenecek obje
 */
function htmlspecialchar_obj($variable) {
    foreach ($variable as $value) {
        $value = htmlspecialchar_array($value);
    }
}

function parametrik_secenekler_getir_selected($parametre, $selected, $hide = array()) {

    $ItemsSQL = 'CALL parametrik_secenekler_getir(?) ';
    try {
        $listBoxItems = $GLOBALS['db']->fetchAll($ItemsSQL, array($parametre));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $return = '';
    foreach ($listBoxItems as $listBoxItem) {

        if (!in_array($listBoxItem->id, $hide)) {

            if ($listBoxItem->id == $selected) {
                $return .= '<option value="' . $listBoxItem->id . '" selected>' . $listBoxItem->tanim . '</option>';
            } else {
                $return .= '<option value="' . $listBoxItem->id . '">' . $listBoxItem->tanim . '</option>';
            }
        }
    }

    return $return;
}

function parametrik_secenek_idleri_getir($parametre, $hide = array()) {

    $ItemsSQL = 'CALL parametrik_secenekler_getir(?) ';
    try {
        $listBoxItems = $GLOBALS['db']->fetchAll($ItemsSQL, array($parametre));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $return = '';

    foreach ($listBoxItems as $listBoxItem) {

        if (!in_array($listBoxItem->id, $hide)) {
            $return .= $listBoxItem->id . '-';
        }
    }
    return rtrim($return, '-');
}

function tl_to_db($value) {
    return str_replace(array('.', ','), array('', '.'), $value);
}

function db_to_tl($value) {
    return str_replace('.', ',', $value);
}

function id_ile_parametrik_secenek_getir($paramedrik_secenek_idsi) {
    $ItemSQL = "SELECT tanim FROM parametrik_secenekler WHERE id = ? ";
    try {
        $Item = $GLOBALS['db']->fetchRow($ItemSQL, $paramedrik_secenek_idsi);
        if (isset($Item)) {
            return $Item->tanim;
        }
        return false;
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
}

function mcrypt($data, $key) {
    return $data;
    $algorithm = MCRYPT_BLOWFISH;
    $mode = MCRYPT_MODE_ECB;
    $iv = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, $mode), MCRYPT_DEV_URANDOM);
    $encrypted_data = mcrypt_encrypt($algorithm, $key, $data, $mode, $iv);
    $encrypted_data = mcrypt_encrypt($algorithm, $key, $data, $mode);
//        return mUrlEncode(base64_encode($encrypted_data));
    return base64_encode($encrypted_data);
}

function mdecrypt($encrypted_data, $key) {
    return $encrypted_data;
//        $encrypted_data = mUrlEncode($encrypted_data);
    $encrypted_data = ($encrypted_data);
    $algorithm = MCRYPT_BLOWFISH;
    $mode = MCRYPT_MODE_ECB;
    $iv = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, $mode), MCRYPT_DEV_URANDOM);
    $decrypted_data = base64_decode($encrypted_data);
    return rtrim(mcrypt_decrypt($algorithm, $key, $decrypted_data, $mode, $iv), "\0");
}

function mUrlEncode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
}

function setFormKey() {
    $form_control_key = "muratdelen";
    $form_control_key .= uniqid();
    $_SESSION["form_control_key"] = $form_control_key;
    $form_control_key = "<input type='hidden' name='form_control_key' value='$form_control_key'/>";
    return $form_control_key;
}

function getFormKey() {
    $form_control_key = $_SESSION["form_control_key"];
    $form_control_key = "<input type='hidden' name='form_control_key' value='$form_control_key'/>";
    return $form_control_key;
}

function controlFormKey() {
    if ($_SESSION["form_control_key"] == $_POST["form_control_key"]) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function ControlSecurity($encrypted_data) {
    $key = "ftCp6ndFeN1UJwKx1sru4HC" . date("dmY");
    $algorithm = MCRYPT_BLOWFISH;
    $mode = MCRYPT_MODE_ECB;
    $iv = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, $mode), MCRYPT_DEV_URANDOM);
    $decrypted_data = base64_decode($encrypted_data);
    $data = rtrim(mcrypt_decrypt($algorithm, $key, $decrypted_data, $mode, $iv), "\0");
    if (strpos($data, 'muratdelen') !== false) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function CreateSecurity() {
    $data = uniqid() . "muratdelen" . uniqid();
    $key = "ftCp6ndFeN1UJwKx1sru4HC" . date("dmY");
    $algorithm = MCRYPT_BLOWFISH;
    $mode = MCRYPT_MODE_ECB;
    $iv = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, $mode), MCRYPT_DEV_URANDOM);
    $encrypted_data = mcrypt_encrypt($algorithm, $key, $data, $mode, $iv);
    return base64_encode($encrypted_data);
}

?>