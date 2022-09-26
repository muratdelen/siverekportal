<?php

/**
 * Description of LdapTransactions
 *
 * @author taha
 */
class LdapTransactions {

    //put your code here
    private $uid;
    private $password;

    const _EQUAL_ = 0;
    const _STARTS_WITH_ = 1;
    const _ENDS_WITH_ = 2;
    const _CONTAINS_ = 3;

    function __construct() {

    }
//$_SERVER['_np8h2DSybSZdpyv_userpassword']
    // bind to host
    private function ldap_connect() {

        $options = array(
            'host' => 'ds-lh2.Siverek.edu.tr',
            'port' => 389,
            'username' => 'uid=' . $this->uid . ',ou=People,o=Siverek.edu.tr,dc=Siverek,dc=edu,dc=tr',
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

// end search_user
    // fetch user info
    public function get_user_info($uid) {

        $escapedUid = $this->escape_input($uid);
        $ldap = $this->ldap_connect();

        if (is_object($ldap)) {

            try {
                $attributes = $ldap->getEntry('uid=' . $escapedUid . ',ou=People,o=Siverek.edu.tr,dc=Siverek,dc=edu,dc=tr');

                if ($attributes !== NULL) {
                    $personal_title = "idari";
                    $personal_title = trim($attributes['personaltitle'][0]);
                    if ($attributes['mailhost'][0] == 'akd-lh.Siverek.edu.tr') {// çalışan akademik - idari kişiler
                        $personal_titles = array("ARASTIRMA GOREVLISI", "UZMAN", "OKUTMAN", "EGITIM OGRETIM PLANLAMACISI", "CEVIRICI", "OGRETIM GOREVLISI"
                            , "YARDIMCI DOCENT", "DOCENT", "PROFESOR");
                        $akd = "2"; //idari
                        if (in_array($personal_title, $personal_titles)) {
                            $akd = "1"; //akademik
                            $personal_title = "akademik";
                        }
                    } else {
                        $akd = "3"; //ogrenci
                        $personal_title = "ögrenci";
                    }
                    $responseArray = array(
                        'username' => (isset($attributes['uid'][0]) ? $attributes['uid'][0] : ' - '),
                        'name' => (isset($attributes['givenname'][0]) ? $attributes['givenname'][0] : ' - '),
                        'surname' => (isset($attributes['sn'][0]) ? $attributes['sn'][0] : ' - '),
                        'tc' => (isset($attributes['employeeid'][0]) ? $attributes['employeeid'][0] : ' - '),
                        'mail-quota' => (isset($attributes['mailquota'][0]) ? $attributes['mailquota'][0] : ' - '),
                        'phone' => (isset($attributes['telephonenumber'][0]) ? $attributes['telephonenumber'][0] : ' - '),
                        'mail-alternate-address' => (isset($attributes['mailalternateaddress'][0]) ? $attributes['mailalternateaddress'][0] : ' - '),
                        'description' => (isset($attributes['description'][0]) ? $attributes['description'][0] : ' - '),
                        'eposta-block' => (isset($attributes['mailallowedserviceaccess'][0]) ? $attributes['mailallowedserviceaccess'][0] : NULL),
                        'proxy-block' => (isset($attributes['proxyblocked'][0]) ? $attributes['proxyblocked'][0] : NULL),
                        'personal_type' => $akd,
                        'personal_title' => $personal_title
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
        $this->uid = $escapedUid;
        $this->password = $password;
        $options = array(
            'host' => 'ds-lh2.Siverek.edu.tr',
            'port' => 389,
            'username' => 'uid=' . $escapedUid . ',ou=People,o=Siverek.edu.tr,dc=Siverek,dc=edu,dc=tr',
            'password' => $password,
            'baseDn' => 'dc=Siverek,dc=edu,dc=tr');

        $ldap = new Zend_Ldap($options);

        try {

            $ldap->bind();

            return $this->get_user_info($uid);
        } catch (Zend_Ldap_Exception $zle) {

            return array('error' => $this->format_ldap_error($zle));
        }// end try-catch 
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
                $errorDescription = "Hesap geçici süreyle durdurulmuştur.";
                break;

            case 32:
                 $errorDescription = "Hatalı Kullanıcı adı!";
//                $errorDescription = "Eşleşme hatası.";
//              Arama filtresi hatalı.
                break;
            case 34://ldap sözdizim hatası
                $errorDescription = "Sistemsel Hata.";
//              Arama filtresi hatalı.
                break;
            case 49:

                $errorDescription = "Hatalı kullanıcı adı veya şifre!";

                if (strpos($error->getMessage(), 'password expired!') !== false) {
                    $errorDescription = "Şifrenizin kullanım süresi dolmuştur!";
                }

                break;

            case 81:
                $errorDescription = "Sunucuya bağlanılamadı.";
                break;

            case 532:
                $errorDescription = "Kullanılan şifrenin süresi dolmuştur.";
                break;

            case 731:
                $errorDescription = "Kullanılan hesabın süresi dolmuştur.";
                break;
            case 1054:
                $errorDescription = "Belirtilen etki alanı yok veya bağlantı kurulamadı";
                break;
            default :
                $errorDescription = "HATA : " . $error->getCode();
                break;
        }
        return $errorDescription;
    }

// end format_ldap_error
}

// end class ldap



