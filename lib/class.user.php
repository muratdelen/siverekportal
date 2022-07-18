<?php

require_once '../lib/class.LdapForLogin.php';

class user {

    // logActivities sınıfının refereansı


    function __construct() {
        
    }

    /**
     * kullanıcı giriş ve çıkışını veritabanında "log_login" tablosuna kaydeder
     * 
     * -veritabanı kullanma class ve veritabınında "giris_log_ekle" fonksiyonu bulunmalıdır.
     */
    public function giris_log_ekle($kullanici_id, $baslik, $aciklamasi, $log_tipi) {
        $ip = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $createLogSQL = "INSERT INTO log_giris  VALUES ( ? , ? , ? , ? , ? , ? , ? , ?)";
        $logInfo = array(NULL, $log_tipi, $kullanici_id, $baslik, $aciklamasi, $ip, date('Y-m-d H:i:s'), 1);
        try {
            $GLOBALS['db']->fetchAll($createLogSQL, $logInfo);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }

        return TRUE;
    }

    /*
     * Sisteme giriş için kullanılan fonksiyon.
     * Sisteme ldap  ve veritabanı olmak üzere iki şekilde erişilebilir.
     */

    public function open_ma_user($kullanici_adi) {
        global $pageUrl, $girisYapanKullaniciGrupId, $kullaniciId;
        // Girilen kullanıcı adı sistemde var mı
        $checkuserSQL = 'SELECT * FROM yt_kullanici WHERE kullanici_adi = ?';
        $department = "";
        try {
            $checkuser = $GLOBALS['db']->fetchRow($checkuserSQL, $kullanici_adi);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            return array('error' => "Sistemsel hata oluştu");
        }

        if (isset($checkuser->giris_tipi) && $checkuser->aktif_mi) {//VERİTABANINDA OLAN KULLANICILAR
            $user_info = array(
                'id' => $checkuser->id,
                'kullanici_adi' => $kullanici_adi,
                'adi' => $checkuser->adi,
                'soyadi' => $checkuser->soyadi,
                'aciklamasi' => $checkuser->aciklamasi,
                'grup_id' => $checkuser->yt_grup_id
            );
            
            $this->set_session_vars($user_info);
            $this->giris_log_ekle($checkuser->id, "Giriş Admin", "OpenMA Kullanılarak Giriş Yapıldı.", 5);
            $department = "Admin";
            if ($pageUrl == "/login/index.php") {
                header('location:' . BASE_URL);
                die();
                exit();
            }
        }
        return $department;
    }

    /*
     * Sisteme giriş için kullanılan fonksiyon.
     * Sisteme ldap  ve veritabanı olmak üzere iki şekilde erişilebilir.
     */

    public function auth_user($kullanici_adi, $sifre) {
        $kullanici_adi = str_replace("@Siverek.edu.tr", "", $kullanici_adi);
        // Girilen kullanıcı adı sistemde var mı
        $checkuserSQL = 'SELECT * FROM yt_kullanici WHERE kullanici_adi = ?';

        try {
            $checkuser = $GLOBALS['db']->fetchRow($checkuserSQL, $kullanici_adi);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
            return array('error' => "Sistemsel hata oluştu");
        }
        if (!isset($checkuser->giris_tipi)) {//VERİTABANINDA OLMAYAN KULLANICILAR
            return array('error' => "Erişim yetkiniz yoktur");
            $ldapHandler = new LdapTransactions();
            $authResponse = $ldapHandler->auth_user($kullanici_adi, $sifre);

            // Ldap'tan dönen cevapta kullanıcı adı varsa erişim sağlanmıştır
            if (isset($authResponse['username'])) {
                $userInfoLdap = $ldapHandler->get_user_info($kullanici_adi);
                $user_info = array(
                    'id' => 0,
                    'kullanici_adi' => $authResponse['username'],
                    'adi' => $userInfoLdap['name'],
                    'soyadi' => $userInfoLdap['surname'],
                    'grup_id' => 0
                );

                $this->set_session_vars($user_info);
                $this->giris_log_ekle(0, "Giriş Admin", "LDAP Kullanılarak Giriş Yapıldı.", 1);
            } else {
                //Erişim sağlanamadıysa Ldap'tan gelen cevap dönderilir
                $this->giris_log_ekle(0, "Hatalı Giriş Admin LDAP", $authResponse["error"], 2);
                return $authResponse;
            }
            header('location:' . BASE_URL . 'admin');
        } else if ($checkuser->aktif_mi) {//Girilen kullanıcı adı sistemde varsa erişim tipine göre parola kontrol edilecek.
            switch ($checkuser->giris_tipi) {
                //Erişim tipi 1 ise ldap kullanıcısıdır.
                case 1:

                    $ldapHandler = new LdapTransactions();
                    $authResponse = $ldapHandler->auth_user($kullanici_adi, $sifre);
                    // Ldap'tan dönen cevapta kullanıcı adı varsa erişim sağlanmıştır
                    if (isset($authResponse['username'])) {
                        $user_info = array(
                            'id' => $checkuser->id,
                            'kullanici_adi' => $authResponse['username'],
                            'adi' => $checkuser->adi,
                            'soyadi' => $checkuser->soyadi,
                            'aciklamasi' => $checkuser->aciklamasi,
                            'grup_id' => $checkuser->yt_grup_id
                        );

                        $this->set_session_vars($user_info);
                        $this->giris_log_ekle($checkuser->id, "Giriş Admin", "LDAP Kullanılarak Giriş Yapıldı.", 5);
                    } else {
                        //Erişim sağlanamadıysa Ldap'tan gelen cevap dönderilir
                        $this->giris_log_ekle($checkuser->id, "Hatalı Giriş Admin LDAP", $authResponse["error"], 6);
                        return $authResponse;
                    }
                    break;
                case 2:
                    /*
                     * Erişim tipi 2 ise veritabanı kullanıcısıdır.
                     * Veritabanında şifreler crypt metodu kullanılarak saklanır.
                     * Metodun salt kısmı yine şifrenin kendisidir.
                     */

                    if ($checkuser->sifre === md5(crypt($sifre, $sifre))) {
                        // Girilen şifre veritabanındaki ile eşleşirse kullanıcının session değişkenleri oluşturulur.   
                        $user_info = array(
                            'id' => $checkuser->id,
                            'kullanici_adi' => $checkuser->kullanici_adi,
                            'adi' => $checkuser->adi,
                            'soyadi' => $checkuser->soyadi,
                            'aciklamasi' => $checkuser->aciklamasi,
                            'grup_id' => $checkuser->yt_grup_id
                        );
                        $this->set_session_vars($user_info);
                        $this->giris_log_ekle($checkuser->id, "Giriş Admin", "Veritabanı Kullanılarak Giriş Yapıldı.", 7);
                    } else {
                        //Şifre eşleşmiyorsa hata dönderilir.
                        $this->giris_log_ekle($checkuser->id, "Hatalı Giriş Admin Veritabanı", "Hatalı  kullanıcı adı veya şifre!", 8);
                        return array('error' => "Hatalı  kullanıcı adı veya şifre![Sistemden Tanımlı]");
                    }
                    break;

                default:
                    //Default olarak hata mesajı dönderilir.
                    $this->giris_log_ekle($checkuser->id, "Giriş Admin", "Sistemden erişim tipi belirtilmemiş kullanıcı!", 9);
                    return array('error' => "Sistemden erişim tipi belirtilmemiş kullanıcı!");
            }

            // Erişim sağlanmışsa auth_log tablosuna log kaydı yapılır ve anasayfaya yönlendirilir. 
//            $this->logHandler->auth_log(_LOGIN_, $_POST['kullanici_adi'], 1);
            header('location:' . BASE_URL);
        } else {// KULLANICI PASİF HALE GETİRİLMİŞTİR. 
            // Girilen kullanıcı adı sistemde pasif olmuştur normal kullanıcı gibi giriş yapacaktır.
            return array('error' => "Erişim yetkiniz pasif hale getirilmiştir.");
            $ldapHandler = new LdapTransactions();
            $authResponse = $ldapHandler->auth_user($kullanici_adi, $sifre);

            // Ldap'tan dönen cevapta kullanıcı adı varsa erişim sağlanmıştır
            if (isset($authResponse['username'])) {
                $userInfoLdap = $ldapHandler->get_user_info($kullanici_adi);
                $user_info = array(
                    'id' => 0,
                    'kullanici_adi' => $authResponse['username'],
                    'adi' => $userInfoLdap['name'],
                    'soyadi' => $userInfoLdap['surname'],
                    'grup_id' => 0
                );

                $this->set_session_vars($user_info);
                $this->giris_log_ekle($checkuser->id, "Giriş Pasif Admin", "LDAP Kullanılarak Giriş Yapıldı.", 3);
            } else {
                //Erişim sağlanamadıysa Ldap'tan gelen cevap dönderilir
                $this->giris_log_ekle($checkuser->id, "Hatalı Giriş Pasif Admin LDAP", $authResponse["error"], 4);
                return $authResponse;
            }
//            header('location:' . BASE_URL . 'home');
        }
    }

    /*
     * Sistemden çıkış için kullanılan fonksiyon.
     */

    public function destroy_user() {
        global $kullaniciId;
//        if (sizeof($_SESSION) !== 0) {
//            $this->logHandler->auth_log(_LOGOUT_, $_SESSION['kullanici_adi'], 1);
//        }
        if (isset($_SESSION["kullanici_adi"])) {
            $this->giris_log_ekle($kullaniciId, "Çıkış", "Çıkış Yapıldı.", 0);
        }
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

        // login sayfasına yönlendir.
//        header("Location: " . BASE_URL . "/login");
    }

    /*
     * Session değişkenlerini oluşturmak için kullanılan fonksiyon.
     */

    private function set_session_vars(array $sessionVars) {

//      if (!isset($_SESSION)) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
//        $_SESSION["post_log_active"] = TRUE;
        $_SESSION['kullanici_adi'] = $sessionVars['kullanici_adi'];

        if (!isset($sessionVars['adi']) && !isset($sessionVars['soyadi'])) {
            $_SESSION['kullanici'] = $sessionVars['kullanici_adi'];
        } else {
            $_SESSION['kullanici'] = $sessionVars['adi'] . ' ' . $sessionVars['soyadi'];
        }

        $_SESSION['kullanici_id'] = $sessionVars['id'];
        $_SESSION['key'] = uniqid();
//        $_SESSION['fkey'] = md5(uniqid(rand(), true));

        $_SESSION['grup_id'] = $girisYapanKullaniciGrupId=$sessionVars['grup_id'];
//        $_SESSION['resim'] = $sessionVars['resim'];
        if (isset($sessionVars['aciklamasi'])) {//bunların belli bir rolü vardır.
            $_SESSION['kullanici_görevi'] = $sessionVars['aciklamasi'];
        } else {//dışarıdaki kullanıcılar bunların herhangi bir rolü yoktur
            $_SESSION['kullanici_görevi'] = "Görev Tanımlanmamıştır.";
        }
    }

}

?>