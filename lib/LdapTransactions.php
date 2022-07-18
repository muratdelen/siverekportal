<?php

require_once 'config.php';

class LdapTransactions {

    //put your code here
    private $uid;
    private $password;

    const _EQUAL_ = 0;
    const _STARTS_WITH_ = 1;
    const _ENDS_WITH_ = 2;
    const _CONTAINS_ = 3;

    function __construct() {

        $this->uid = "cn=Directory Manager";
        $this->password = ""; //$_SERVER['_np8h2DSybSZdpyv_userpassword'];
    }

    // bind to host
    private function ldap_connect() {

        $options = array(
            'host' => 'ds-lh2.Siverek.edu.tr',
            'port' => 389,
            'username' => $this->uid,
            'password' => $this->password,
            'baseDn' => 'dc=Siverek,dc=edu,dc=tr');

        $ldap = new Zend_Ldap($options);
        try {

            $ldap->bind();
            return $ldap;
        } catch (Zend_Ldap_Exception $zle) {
            return array('error' => $this->format_ldap_error($zle));
        }
    }

    // end function ldap_connect
// end user_exists

    public function update_user($username, $entry) {

        $escapedUsername = $this->escape_input($username);
        $ldap = $this->ldap_connect();

        if (is_object($ldap)) {

            try {

                $dn = 'uid=' . $escapedUsername . ',ou=People,o=Siverek.edu.tr,dc=Siverek,dc=edu,dc=tr';
                $ldap->update($dn, $entry);
            } catch (Exception $zle) {

                return array('error' => $this->format_ldap_error($zle));
            }
        } else {

            return $ldap;
        }
    }

// end search_user
    // fetch user info
    public function get_user_info($uid) {

        $escapedUid = $this->escape_input($uid);
        $ldap = $this->ldap_connect();

        if (is_object($ldap)) {

            try {
                $attributes = $ldap->getEntry('uid=' . $escapedUid . ',ou=People,o=Siverek.edu.tr,dc=Siverek,dc=edu,dc=tr');

                if ($attributes !== NULL) {

                    $responseArray = array(
                        'username' => (isset($attributes['uid'][0]) ? $attributes['uid'][0] : ' - '),
                        'isim' => (isset($attributes['givenname'][0]) ? $attributes['givenname'][0] : ' - '),
                        'soyisim' => (isset($attributes['sn'][0]) ? $attributes['sn'][0] : ' - '),
                        'tc' => (isset($attributes['employeeid'][0]) ? $attributes['employeeid'][0] : ' - '),
                        'kota' => (isset($attributes['mailquota'][0]) ? $attributes['mailquota'][0] : ' - '),
                        'telefon' => (isset($attributes['telephonenumber'][0]) ? $attributes['telephonenumber'][0] : ' - '),
                        'e-posta' => (isset($attributes['mailalternateaddress'][0]) ? $attributes['mailalternateaddress'][0] : ' - '),
                        'bolum-birim' => (isset($attributes['description'][0]) ? $attributes['description'][0] : ' - '),
                        'sifre-sifirlama' => (isset($attributes['mailsieverulesource'][0]) ? $attributes['mailsieverulesource'][0] : ' - '),
                        'eposta-engel' => (isset($attributes['mailallowedserviceaccess'][0]) ? $attributes['mailallowedserviceaccess'][0] : NULL),
                        'proxy-engel' => (isset($attributes['proxyblocked'][0]) ? $attributes['proxyblocked'][0] : NULL)
                    );
                } else {

                    return $attributes;
                }


                return $responseArray;
            } catch (Exception $zle) {

                return array('error' => $this->format_ldap_error($zle));
            }
        } else {

            return $ldap;
        }
    }

// auth ldap user
    public function auth_user($uid, $password) {



        $escapedUid = $this->escape_input($uid);

        $options = array(
            'host' => 'ds-lh2.Siverek.edu.tr',
            'port' => 389,
            'username' => 'uid=' . $escapedUid . ',ou=People,o=Siverek.edu.tr,dc=Siverek,dc=edu,dc=tr',
            'password' => $password,
            'baseDn' => 'dc=Siverek,dc=edu,dc=tr');

        $ldap = new Zend_Ldap($options);

        try {

            $ldap->bind();

            $userInfo = $this->get_user_info($uid);
        } catch (Zend_Ldap_Exception $zle) {

            return array('error' => $this->format_ldap_error($zle));
        }// end try-catch 
        // Girilen kullanıcı adı sistemde var mı
        $checkuserSQL = 'SELECT *
                                FROM kullanicilar WHERE kullanici_adi = ?';

        try {
            $checkuser = $GLOBALS['db']->fetchRow($checkuserSQL, $uid);
        } catch (Zend_Db_Exception $ex) {
            return array('error' => $ex->getCode());
        }

        if (isset($checkuser->yetkisi)) {
            $userInfo['rol'] = $checkuser->yetkisi;
        } else {
            $userInfo['rol'] = NULL;
        }


        return $userInfo;
    }

// end auth_user
//    escape ldap inputs
    private function escape_input($string, $dn = null) {
        $escapeDn = array('\\', '*', '(', ')', "\x00");
        $escape = array('\\', ',', '=', '+', '<', '>', ';', '"', '#');

        $search = array();
        if ($dn === null) {
            $search = array_merge($search, $escapeDn, $escape);
        } elseif ($dn === false) {
            $search = array_merge($search, $escape);
        } else {
            $search = array_merge($search, $escapeDn);
        }

        $replace = array();
        foreach ($search as $char) {
            $replace[] = sprintf('\\%02x', ord($char));
        }

        return str_replace($search, $replace, $string);
    }

    // end escape_input
//convert ldap errors to human readable
    private function format_ldap_error($error) {

        $errorDescription = NULL;
        switch ($error->getCode()) {

            case 19:
                $errorDescription = _("Hesap geçici süreyle durdurulmuştur.");
                break;

            case 32:
                $errorDescription = _("Eşleşme hatası.");
//              Arama filtresi hatalı.
                break;

            case 49:

                $errorDescription = _("Hatalı kullanıcı adı veya şifre!");

                if (strpos($error->getMessage(), 'password expired!') !== false) {
                    $errorDescription = _("Şifrenizin kullanım süresi dolmuştur!");
                }

                break;

            case 81:
                $errorDescription = _("Sunucuya bağlanılamadı.");
                break;

            case 532:
                $errorDescription = _("Kullanılan şifrenin süresi dolmuştur.");
                break;

            case 731:
                $errorDescription = _("Kullanılan hesabın süresi dolmuştur.");
                break;
            default :
                $errorDescription = _("HATA : ") . $error->getCode();
                break;
        }
        return $errorDescription;
    }

// end format_ldap_error
}

// end class ldap



