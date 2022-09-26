
<?php
//EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
?>
<div class="content-wrapper">    
    <section class="content-header">
        <h1>
            <small><?php echo __("YETKİLENDİRME") ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-cloud"></i><?php echo __("Admin") ?></a></li>
            <li class="active"><?php echo __("Kullanıcı İşlemleri") ?></li>
        </ol>
    </section>
    <section class="container-fluid">
        <div class="row">
            <?php
            if (in_array(YT_UPDATE, $sayfaIslemleriId)) {
                require_once 'guncelle.php';
            }
            ?>
        </div>
    </section>
</div>
