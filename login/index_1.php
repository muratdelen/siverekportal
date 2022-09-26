
<?php
require_once '../lib/config.php';
require_once '../lib/class.user.php';

$message = '';
$user_cls = new user();
$error_class = "";
$error_captcha_class = "";
if (isset($_GET['logout'])) {
    $user_cls->destroy_user();
    $message = 'Çıkış Yapıldı.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //post ile veri gelmişse
    if (isset($_SESSION["login_error"])) {
        require_once('recaptchalib.php');
        $siteKey = '6Lcm8g4TAAAAAIhZUtRLgnoDsU8cEC2neiMbCScS';
        $secret = '6Lcm8g4TAAAAAEQsdFkOT2wE0553PtQmuD_QRvKD';
        $resp = recaptcha_check_answer($secret, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if ($resp->is_valid) {
            if ((strlen($_POST['username']) > 0) && (strlen($_POST['password']) > 0)) {
                $auth = $user_cls->auth_user($_POST['username'], $_POST['password']);
                if (isset($auth['error'])) {//yanlış kullanıcı adı ve şifre hatası
                    $error_class = '  error_border ';
                    $message = $auth['error'];
                    $_SESSION["login_error"] = TRUE;
                } else {
                    unset($_SESSION["login_error"]);
                    header('location:' . BASE_URL . 'home');
                }
            } else {
                $error_class = '  error_border ';
                $message = 'Lütfen kullanıcı adı ve şifrenizi giriniz.';
            }
        } else {
            $error_captcha_class = ' class = "error_border" ';
            $message = 'Doğrulama kodu yanlış girdiniz.';
        }
    } else {
        if ((strlen($_POST['username']) > 0) && (strlen($_POST['password']) > 0)) {
            $auth = $user_cls->auth_user($_POST['username'], $_POST['password']);
            if (isset($auth['error'])) {//yanlış kullanıcı adı ve şifre hatası
                $error_class = '  error_border ';
                $message = $auth['error'];
                $_SESSION["login_error"] = TRUE;
            } else {
                unset($_SESSION["login_error"]);
                header('location:' . BASE_URL . 'home');
            }
        } else {
            $error_class = '  error_border ';
            $message = 'Lütfen kullanıcı adı ve şifrenizi giriniz.';
        }
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
            <div id="header_div"><a href="http://www.Siverek.edu.tr" target="_blank">Siverek Belediyesi</a> :: <a href="http://www.bidb.Siverek.edu.tr/" target="_blank">Bilgi İşlem Daire Başkanlığı</a>:: Gelir Takip Sistemi</div>
            <form method="post" action="" role="login" id="login-form">

                <div>
                    <div class="form-group" style="text-align: center">
                        <img src="images/logo.png" class="img-responsive">
                    </div>

                    <div class="form-group">
                        <label for="username" > Kullanıcı Adı </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="std-no" type="text" class="form-control <?php echo $error_class; ?>" name="username" value="<?= isset($_POST["username"]) ? $_POST["username"]:'' ; ?>" placeholder="Kullanıcı Adı">                                        
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="password" >Parola</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                            <input  id="identity-no" type="password" class="form-control <?php echo $error_class; ?>" value="<?= isset($_POST["password"]) ? $_POST["password"]:'' ; ?>" name="password" placeholder="Parola">
                        </div>
                    </div>

                    <div <?php echo $error_captcha_class; ?>>

                        <?php
                        if (isset($_SESSION["login_error"])) {
                            require_once('recaptchalib.php');
                            $siteKey = '6Lcm8g4TAAAAAIhZUtRLgnoDsU8cEC2neiMbCScS';
                            $secret = '6Lcm8g4TAAAAAEQsdFkOT2wE0553PtQmuD_QRvKD';
                            echo recaptcha_get_html($siteKey);
                        }
                        ?>

                    </div>
                    <button type="submit" id="btn-login" class="btn bg-purple"><i class="glyphicon glyphicon-log-in"></i> Sisteme Giriş Yap </button>

                    <div class="error_div">
                        <label class="error-message"> <?php echo $message; ?></label>
                    </div>
                    <div id="bottom-sec" >
                        2016 Version 1 © Siverek Belediyesi Bilgi İşlem Müdürlüğü
                    </div>
                </div>

            </form>


            <div id="footer_div">
                <p>Bilgi:</p>
                <p>Giriş yapabilmek için Bilgi İşlem Daire Başkanlığı'ndan elektronik posta adresi gerekmektedir.</p>
            </div>
            </body>
            </html>

