
<?php
require_once '../lib/config.php';
if (isset($_SESSION["kullanici_adi"])) {
    header('location:' . BASE_URL);
}
require_once '../lib/class.user.php';
$user_cls = new user();

$message = '';
$error_class = "";
$error_captcha_class = "";
if (isset($_GET['logout'])) {
    $user_cls->destroy_user();
    $message = 'Çıkış Yapıldı.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //post ile veri gelmişse
        if ((strlen($_POST['username']) > 0) && (strlen($_POST['password']) > 0)) {
            $auth = $user_cls->auth_user($_POST['username'], $_POST['password']);
            if (isset($auth['error'])) {//yanlış kullanıcı adı ve şifre hatası
                $error_class = '  error_border ';
                $message = $auth['error'];
                $_SESSION["login_error"] = TRUE;
            } else {
                unset($_SESSION["login_error"]);
            }
        } else {
            $error_class = '  error_border ';
            $message = 'Lütfen kullanıcı adı ve şifrenizi giriniz.';
        }   
}
if (isset($_SESSION["grup_id"])) {
    if ($_SESSION["grup_id"] == 5) {
        header('location:' . BASE_URL . 'personel_izin_alma/index.php');
    } elseif ($_SESSION["grup_id"] == 6) {
        header('location:' . BASE_URL . 'personel_izin_islemleri/izin_onay.php');
    } else {
        header('location:' . BASE_URL . 'admin');
    }
}
//$loginToken = csrfguard_generate_token();
?>

<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
    <link rel="shortcut icon" href="images/fav_icon.gif" />
    <title> Siverek Oturum Sistemi </title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo ASSETS_DIR ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="<?php echo ASSETS_DIR; ?>/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Custom CSS -->
        <link href="css/login.css" rel="stylesheet">
            </head>

            <body>
            <div id="header_div"><a href="//www.siverek.bel.tr" target="_blank">Siverek Belediyesi</a> :: <a href="#" target="_blank">Bilgi İşlem</a></div>
            <form method="post" action="" role="login" id="login-form">

                <div>
                    <div class="form-group" style="text-align: center">
                        <img src="../locale/_logo_images/logo.png" class="img-responsive">
                    </div>

                    <div class="form-group">
                        <label for="username" > Kullanıcı Adı </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="std-no" type="text" class="form-control <?php echo $error_class; ?>" name="username" value="<?= isset($_POST["username"]) ? $_POST["username"] : ''; ?>" placeholder="Kullanıcı Adı">                                        
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="password" >Parola</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                            <input  id="identity-no" type="password" class="form-control <?php echo $error_class; ?>" value="<?= isset($_POST["password"]) ? $_POST["password"] : ''; ?>" name="password" placeholder="Parola">
                        </div>
                    </div>

                    <div <?php echo $error_captcha_class; ?>>

                    </div>
                    <button type="submit" 
                            class="g-recaptcha btn btn-danger"
                            data-callback="verifyCallback"
                            name="insert" value="Kaydet" > <i class="glyphicon glyphicon-log-in"></i> Sisteme Giriş Yap </button>

                    <div class="error_div">
                        <label class="error-message"> <?php echo $message; ?></label>
                    </div>
                    <div id="bottom-sec" >
                        2022 © Siverek Belediyesi 
                    </div>
                </div>

            </form>


            <div id="footer_div">
                <!--<p>Bilgi:</p>-->
                <p>Giriş yapabilmek için Bilgi İşlem Müdürlüğü'nden kullanıcı adı ve şifre alınmalıdır.</p>
                <p>Version 1.0</p>
            </div>
            </body>
            </html>

