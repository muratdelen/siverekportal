<?php
$options1 = array(
    //zorunlu parametreler
    'tableHeaders' => array('Dosya Tipi', 'Kullanıcı Adı', 'Adı', 'Soyadı', 'Grubu', 'Başlık', 'Açıklama', 'sayfa_url', 'ip', 'Zaman'),
    'buttonPostPage' => '_table_content.php',
    //zorunlu olmayan parametreler
    'id' => 'example1', // optional
    'order' => array(0, 'asc'),
    'tableFooters' => array('Dosya Tipi', 'Kullanıcı Adı', 'Adı', 'Soyadı', 'Grubu', 'Başlık', 'Açıklama', 'sayfa_url', 'ip', 'Zaman'),
    'filters' => array('text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text'),
    'buttons' => array('excel'),
    //sunucu taraflı parametreler
    'url' => '_table_content.php',
//        'where' => ' id = 1 '
        //yerel parametreler
//        'tableData' => $criterias,
);
?>
<div class="content-wrapper">
    <?php
    try {
        $dtableServer = new DataTable($options1);
    } catch (Exception $ex) {
        die($ex->getMessage());
    }
    ?>
    <!--Content Header (Page header)--> 
    <section class="content-header">
        <h1>
            <!--Data Tables-->
            <small>LOG KAYITLARI</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Log Kayıtları</a></li>
            <li><a href="#">Çıktı Kayıtları</a></li>
        </ol>
    </section>

    <!--Main content--> 
    <section class="content">
        <div class="row">
            <div class="col-xs-12" id="log-kayitlari">


                <div class="box box-primary">
                    <div class="box-header">
                        <!--<h3 class="box-title">Data Table With Full Features</h3>-->
                    </div> 
                    <div class="box-body">

                        <?php
                        if (in_array(YT_QUERY, $sayfaIslemleriId)) {
                            echo $dtableServer->get_data_table();
                        }
                        ?>
                    </div> 
                </div> 
            </div> 
        </div> 
    </section> 
</div>

<?php
if (in_array(YT_QUERY, $sayfaIslemleriId)) {
    echo $dtableServer->get_datatable_script();
}
?>