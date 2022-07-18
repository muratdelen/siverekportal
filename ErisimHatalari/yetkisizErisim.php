<!--
Author: Murat DELEN
Author URL: http://muratdelen.com
-->

<?php
//veritabanından anket bilgileri alınmaktadır.

//require_once '../lib/popup_login.php';
include_once "../lib/config.php";
require_once '../lib/class/connect_mysql.php';
//require_once '../include/class/connect_mysql.php';
//$cnn = new connect_mysql();
//$login_error_message =
//$anket = $cnn->select_row_mysql("anket", "*", "anket_doldurma_kodu='".$_GET["kod"]."'");
//$cnn->mysql_connection_close();


   ?>  

<!DOCTYPE HTML>
<html>
    <head>
    <title>Siverek Elektronik Anket</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900' rel='stylesheet' type='text/css'>
             <?php
        configuration::getPageHeader("Talep");
        ?>
            </head>
            <body>
                <!-- header --><!-- header -->
            <div class="container">
                  <?php require_once '../resources/Temaplateheader.php'; ?>

<!-- feature -->
        <div class="feature">
        <div class="col-md-12 our-right">
            <h4>YETKİSİZ ERİŞİM SAĞLADIĞINIZ. Lütfen Yetki Talebinde Bulununuz.</h4>
            <h1 style="text-align: center;"><img title="Kullanmaya İzniniz Olmayan Bir Sayfa Erişim Talebiniz Red Edildi." src="<?php echo yetkisiz_erisim;?>"  /></h1>
            
                <div class="row">
                    <div class="col-md-12 rst">
                            <div class="contact-form_grid">
                                <form method="post"  id="talep_form" action="<?php echo BASE_URL; ?>Anasayfa">
                                  <input type="submit" value="Anasayfa Geri Dön"/>
                                </form>
                            </div>

                    </div>
                </div>
<!--                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m22!1m12!1m3!1d3339.8053262270205!2d32.73559157496515!3d39.8699819376886!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m7!1i0!3e6!4m0!4m3!3m2!1d39.8711382!2d32.735999!5e1!3m2!1str!2s!4v1431093132268" frameborder="0" style="border:0"></iframe>
                </div>-->
     
            </div>	
            <div class="clearfix"> </div>

         </div>	
                                                    <!-- feature -->

</div>
<!-- popular -->	

	<!-- footer -->
   <?php
        configuration::getFooter();
        configuration::getLoginHtml("../Anasayfa");
?>
	<!-- footer -->
       
</body>
</html>

   <?php
if (!isset($_SESSION['kullanici_adi'])) {
    echo '<script>login_show();</script>';
}