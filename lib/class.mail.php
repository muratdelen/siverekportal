<?php

/*
 * Author      : Murat DELEN
 * Date        : 01.06.2014
 * Description : açıklama..
 * 
 *              "gereklilik.
 * 
 * EXAMPLE 
 * 
 * 
  require_once 'lib/class.mail.php';
  $mail = new MAIL();
  $mesaj = $mesaj . 'Anket oluşturmak, anket parametrelerini yönetmek konularında bilgi/eğitim için Bilgi İşlem Daire Başkanlığı Yazılım Geliştirme Birimi ile iletişime geçebilirsiniz.';
  $mesaj = $mesaj . '<br><br>';
  $mesaj = $mesaj . '<br>Bilgi İşlem Daire Başkanlığı';
  $mesaj = $mesaj . '<br>Yazılım Geliştirme Birimi';
  $mesaj = $mesaj . '<br><br>';
  $mesaj = $mesaj . '<br><br>';
  $mail->sendMail($kullanici_adi . "@Siverek.edu.tr", ' e-Anket Sistemi Kullanım Talebiniz Hakkında', $mesaj);
 */
require_once('mail/class.phpmailer.php');

class MAIL {

    public function __construct() {
//        session_start();
        $this->admin = array(
            'username' => 'bidbanket@Siverek.edu.tr',
            'pass' => 'K9N5&oy!23',
            'from' => 'no-reply@Siverek.edu.tr',
            'fromname' => 'no-reply'
        );
//      $mail_password = "Msfd_Ssd10s84G53";//ESKİ PAROLA
        $mail_password = "Msfd_Ssd10s84G53m";
        $this->mail_accounts = array(
            array("account_name" => "noreply-a@info.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-b@info.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-c@info.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-d@info.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-e@info.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-f@info.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-g@info.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-h@info.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-i@info.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey1@survey.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey2@survey.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey3@survey.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey4@survey.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey5@survey.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey6@survey.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey7@survey.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey8@survey.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey9@survey.Siverek.edu.tr", "account_password" => $mail_password),
            array("account_name" => "noreply-survey10@survey.Siverek.edu.tr", "account_password" => $mail_password)
        );
    }

    function smtp($to, $from, $subject, $body, $attachment = false) {
        $mail = new PHPMailer();  // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->CharSet = 'UTF-8'; // 
        $mail->IsHTML(true); // 
        $mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true;  // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = 'mail.Siverek.edu.tr';
        $mail->Port = 465;
        $mail->Username = $from['username'];
        $mail->Password = $from['pass'];
        $mail->SetFrom($from['from'], $from['fromname']);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        //echo "$to, $from = GUSER, $from_name = FROM_NAME, $subject, $body , $attachment=false";
        if (!$mail->Send()) {
            echo $mail->ErrorInfo;
            return false;
        } else {
            //$error = 'Message sent!';
            return true;
        }
    }

    function smtp_office365($to, $from, $subject, $body, $attachment = false) {
        $date_now = new DateTime("now");
        $date_last = new DateTime("2019-08-01");
        if ($date_now > $date_last && date('H:i') == "13:30") {
            $this->smtp("yazilim@Siverek.edu.tr", $from, "Office365 Hesaplarının Şifrelerin Güncellemenmesi Gerekmektedir.", $body);
            echo '<br>Hesaplar Aktifleştiğinde E-posta Yollama Aktif Hale Gelecektir.<br>';
            die();
        } else {
            $random_index = rand(0, 18);
            $mail = new PHPMailer();  // create a new object
            $mail->IsSMTP(); // enable SMTP
            $mail->CharSet = 'UTF-8'; // 
            $mail->IsHTML(true); // 
//            $mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true;  // authentication enabled
            $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
            $mail->Host = 'smtp.office365.com';
            $mail->Port = 587;
            $mail->Username = $this->mail_accounts[$random_index]["account_name"];
            $mail->Password = $this->mail_accounts[$random_index]["account_password"];
            $mail->SetFrom($this->mail_accounts[$random_index]["account_name"], $from['fromname'] . "-noreply");
            $mail->addReplyTo($from['from'], $from['fromname']);
//            $mail->SetFrom($from['from'], $from['fromname']);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AddAddress($to);
            if (!$mail->Send()) {                
//                echo $mail->ErrorInfo;
                return $this->smtp($to, $from, $subject, $body);
            } else {
                //$error = 'Message sent!';
                return true;
            }
        }
    }

    public function sendMail($to, $subject, $body, $attachment = false, $from = '') { //to = all,array(user id),        
        if ($from == '') {
            $from = $this->admin;
        } else {
            $from = $this->$from;
        }
        try {
            if (strpos($to, '@Siverek.edu.tr') !== false) {
                return $this->smtp($to, $from, $subject, $body);
            } else {
                return $this->smtp_office365($to, $from, $subject, $body);
            }
        } catch (Exception $exc) {
            $myfile = fopen("message_errors.txt", "w");
            $txt = date("d-m-Y H:i:s") . " -> " . $exc->getMessage() . "\n";
            fwrite($myfile, $txt);
            fclose($myfile);
            return FALSE;
        }
    }

}

?>