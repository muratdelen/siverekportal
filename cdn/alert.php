<?php
$errors_str = "";
//EĞER DEVELOPER İSE SAYFA HATALARI GÖRÜNTÜLEYEBİLİR.
if ($girisYapanKullaniciGrupId === 1) {
    if (isset($_GET["errorlogId"])) {
        $ItemsSQL = "UPDATE log_hatalar SET aktif_mi = FALSE WHERE id = ? ";
        try {
            $alert = $GLOBALS['db']->fetchAll($ItemsSQL, $_GET["errorlogId"]);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }
    if (isset($_GET["errorlogAll"])) {
        $ItemsSQL = "UPDATE log_hatalar SET aktif_mi = FALSE";
        try {
            $alert = $GLOBALS['db']->fetchAll($ItemsSQL);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }
    ?>

                                                                <!--    <script>

                                                                        $(function () {
                                                                            var alert_list = $('#alert_error_str').html();
                                                                            $('#alert_list').html(alert_list);
                                                                            $('#alert_error_str').remove();
                                                                        });
                                                                        </script>-->

    <script>
        var is_run_alert = true;
        function set_alert()
        {
            if (is_run_alert) {
                is_run_alert = false;
                var formData = new Array();
                formData.push({name: 'post_type', value: 0});
                $.ajax({
                    async: false,
                    url: '<?php echo ASSETS_DIR; ?>/post_get_alert.php',
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        if (data == 1) {
                            is_run_alert = true;
                        }
                    }
                });
                var formData = new Array();
                formData.push({name: 'post_type', value: 1});
                $.ajax({
                    async: false,
                    url: '<?php echo ASSETS_DIR; ?>/post_get_alert.php',
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        var obj = $.parseJSON(data);
                        $.each(obj, function (key, value) {
                            if (value["hata_sayisi"] != 0) {
                                $('#error_total').html(value["hata_sayisi"]);
                                $('#error_header_title').html('<a href=".?errorlogAll=1" title="<?= __("okundu yapmak için tıklayınız.") ?>"><p class="text-red"><b>' + value["hata_sayisi"] + ' <?= __('Hata var') ?></b> </p>[<?= __('Bildirimleri okundu yapmak için tıklayınız.') ?>]</a>');
                                var formData = new Array();
                                formData.push({name: 'post_type', value: 2});
                                $.ajax({
                                    async: false,
                                    url: '<?php echo ASSETS_DIR; ?>/post_get_alert.php',
                                    type: 'POST',
                                    data: formData,
                                    success: function (data) {
                                        var obj = $.parseJSON(data);
                                        var error_str = "";
                                        var counter = 0;
                                        $.each(obj, function (key, value) {
                                            counter++;
                                            error_str += '<li><div class="panel box box-danger">'
                                                    + 'Hata ' + counter + ' - ' + value["zaman"]
                                                    + '<a href=".?errorlogId=' + value["id"] + '" >'
                                                    + '<br><b> <?= __("Hata Yapan Kullanıcı") ?>:</b> ' + value["kullanici_adi"]
                                                    + '<br><b> <?= __("Hata Başlığı") ?>:</b> ' + value["baslik"]
                                                    + '<br><b> <?= __("Hata Tipi") ?>:</b> ' + value["tipi"]
                                                    + '<br><b> <?= __("Hata Satırı") ?>:</b> ' + value["satir_sirasi"]
                                                    + '<br><b> <?= __("Hata Url") ?>:</b><br> ' + value["sayfa_url"]
                                                    + '<br><b> <?= __("Hata Tanımı") ?>:</b><br>' + value["aciklama"]
                                                    + '<br><b> <?= __("Hata Yapan Ip") ?>:</b> ' + value["ip"]
                                                    + '</a>'
                                                    + '</div></li > ';
                                        });
                                        $('#error_list').html(error_str);
                                    },
                                    error: function (error) {
                                        is_run_alert = false;
                                    }
                                });
                            } else {
                                $('#error_total').html('');
                                $('#error_header_title').html('<a href="."><p class="text-green"> <?= __("Yeni hata yoktur.") ?> </p></a>');
                            }
                        });
                    }
                });

                // ALERT UYARI
                var alert_total = 0;
                var alert_title = "";
                var alert_body = "";
                var formData = new Array();
                formData.push({name: 'post_type', value: 3});
                $.ajax({
                    async: false,
                    url: '<?php echo ASSETS_DIR; ?>/post_get_alert.php',
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        var obj = $.parseJSON(data);
                        $.each(obj, function (key, value) {
                            alert_total = value["uyari_sayisi"];
                            if (value["uyari_sayisi"] != 0) {
                                $('#alert_total').html(value["uyari_sayisi"]);
                                $('#alert_header_title').html('<a href=".?alert=1" class="bg-danger">[<?= __("Tüm bildirimleri okundu yapmak için tıklayınız.") ?>]</a>');
                                var formData = new Array();
                                formData.push({name: 'post_type', value: 4});
                                $.ajax({
                                    async: false,
                                    url: '<?php echo ASSETS_DIR; ?>/post_get_alert.php',
                                    type: 'POST',
                                    data: formData,
                                    success: function (data) {
                                        var obj = $.parseJSON(data);
                                        var alert_str = "";
                                        $.each(obj, function (key, value) {
                                            if (value["tipi"] == -1) {//kullanıcıdan mesaj gelmiştir.
                                                alert_str += '<li><div class="panel box box-purple">'
                                                        + '<a href="' + value["link"] + '">'
                                                        + '<div class="panel-footer">[ ' + value["zaman"] + ' ]'
                                                        + '<br><b> ' + value["baslik"] + '</b> <?= __("Tarafından Mesaj Gönderildi.") ?>'
                                                        + '<br>' + value["aciklama"] + '</div>'
                                                        + '</a>'
                                                        + '</div></li>';
                                                if (key == 0) {
                                                    $("#mymessage-box-info").remove();
                                                    $(".content-header").after('<a href="' + value["link"] + '"><div class="alert alert-info alert-dismissible" id="mymessage-box-info" style="margin-bottom: 0!important;">'
                                                            + '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-info-circle"></i> [' + value["zaman"] + '] <?= __("Kullanıcı Adı") ?>:   <b> ' + value["baslik"] + '</b> <?= __("Mesaj Gönderdi. Cevaplamak İçin Tıklayınız.") ?><div class="panel-footer" style="color:#000;">' + value["aciklama"] + '</div></div></a>');
                                                    alert_title = value["baslik"] + ' <?= __("Tarafından Mesaj Gönderildi.") ?>';
                                                    alert_body = value["aciklama"];
                                                }

                                            } else {
                                                alert_str += '<li><div class="panel box box-danger"> <?= __("Zaman") ?>: ' + value["zaman"] + ':'
                                                        + '<a href="' + value["link"] + '">'
                                                        + '<br><b> ' + value["baslik"] + '</b>'
                                                        + '<br>' + value["aciklama"]
                                                        + '</a>'
                                                        + '</div></li>';
                                            }
                                        });
                                        $('#alert_list').html(alert_str);
                                        // Daha önce kullanıcı izin verdi ise
                                        try {
                                            if (Notification.permission === "granted" && alert_total > 0) {
                                                // Bilgilendirme popup'ını çıkaralım.
                                                var notification = new Notification(alert_title, {
                                                    body: alert_body,
                                                    icon: '../locale/_logo_images/logo_img.png',
                                                    tag: 'demo',
                                                    dir: 'auto',
                                                    lang: ''
                                                });
                                            }
                                        } catch (err) {

                                        }
                                    },
                                    error: function (error) {
                                        is_run_alert = false;
                                    }
                                });
                            } else {
                                $('#alert_total').html('');
                                $('#alert_header_title').html('<a href="."><p class="text-green"> <?= __("Yeni bildirim yoktur. ") ?></p></a>');
                            }
                        });
                    }
                });
            }
        }
        $(function () {
            set_alert();
            var timer, start;
            function repeatXI(callback, interval, repeats, immediate) {
                var timer, trigger;
                trigger = function () {
                    callback();
                    --repeats || clearInterval(timer);
                };
                interval = interval <= 0 ? 1000 : interval; // default: 1000ms
                repeats = parseInt(repeats, 10) || 0; // default: repeat forever
                timer = setInterval(trigger, interval);
                if (!!immediate) { // Coerce boolean
                    trigger();
                }

                return timer;
            }
 
            window.run = function () {
                var args = Array.prototype.slice.call(arguments, 0);
                if (timer) {
                    clearInterval(timer);
                }
                start = (new Date).getTime();
                timer = repeatXI.apply(null, [set_alert].concat(args));
            }

            run(10000);
        });
    </script>
<?php } else {
    ?>
    <script>
        var is_run_alert = true;
        function set_alert()
        {
            if (is_run_alert) {
                is_run_alert = false;
                var formData = new Array();
                formData.push({name: 'post_type', value: 0});
                $.ajax({
                    async: false,
                    url: '<?php echo ASSETS_DIR; ?>/post_get_alert.php',
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        if (data == 1) {
                            is_run_alert = true;
                        }
                    }
                });
                var alert_total = 0;
                var alert_title = "";
                var alert_body = "";
                var formData = new Array();
                formData.push({name: 'post_type', value: 3});
                $.ajax({
                    async: false,
                    url: '<?php echo ASSETS_DIR; ?>/post_get_alert.php',
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        var obj = $.parseJSON(data);
                        $.each(obj, function (key, value) {
                            alert_total = value["uyari_sayisi"];
                            if (value["uyari_sayisi"] != 0) {
                                $('#alert_total').html(value["uyari_sayisi"]);
                                $('#alert_header_title').html('<a href=".?alert=1" class="bg-danger">[<?= __("Tüm bildirimleri okundu yapmak için tıklayınız.") ?>]</a>');
                                var formData = new Array();
                                formData.push({name: 'post_type', value: 4});
                                $.ajax({
                                    async: false,
                                    url: '<?php echo ASSETS_DIR; ?>/post_get_alert.php',
                                    type: 'POST',
                                    data: formData,
                                    success: function (data) {
                                        var obj = $.parseJSON(data);
                                        var alert_str = "";
                                        $.each(obj, function (key, value) {
                                            if (value["tipi"] == -1) {//kullanıcıdan mesaj gelmiştir.
                                                alert_str += '<li><div class="panel box box-purple">'
                                                        + '<a href="' + value["link"] + '">'
                                                        + '<div class="panel-footer">[ ' + value["zaman"] + ' ]'
                                                        + '<br><b> ' + value["baslik"] + '</b> <?= __("Tarafından Mesaj Gönderildi.") ?>'
                                                        + '<br>' + value["aciklama"] + '</div>'
                                                        + '</a>'
                                                        + '</div></li>';
                                                if (key == 0) {
                                                    $("#mymessage-box-info").remove();
                                                    $(".content-header").after('<a href="' + value["link"] + '"><div class="alert alert-info alert-dismissible" id="mymessage-box-info" style="margin-bottom: 0!important;">'
                                                            + '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-info-circle"></i> [' + value["zaman"] + '] <?= __("Kullanıcı Adı") ?>:   <b> ' + value["baslik"] + '</b> <?= __("Mesaj Gönderdi. Cevaplamak İçin Tıklayınız.") ?><div class="panel-footer" style="color:#000;">' + value["aciklama"] + '</div></div></a>');
                                                    alert_title = value["baslik"] + ' <?= __("Tarafından Mesaj Gönderildi.") ?>';
                                                    alert_body = value["aciklama"];
                                                }

                                            } else {
                                                alert_str += '<li><div class="panel box box-danger"> <?= __("Zaman") ?>: ' + value["zaman"] + ':'
                                                        + '<a href="' + value["link"] + '">'
                                                        + '<br><b> ' + value["baslik"] + '</b>'
                                                        + '<br>' + value["aciklama"]
                                                        + '</a>'
                                                        + '</div></li>';
                                            }
                                        });
                                        $('#alert_list').html(alert_str);
                                        // Daha önce kullanıcı izin verdi ise
                                        try {
                                            if (Notification.permission === "granted" && alert_total > 0) {
                                                // Bilgilendirme popup'ını çıkaralım.
                                                var notification = new Notification(alert_title, {
                                                    body: alert_body,
                                                    icon: '../locale/_logo_images/logo_img.png',
                                                    tag: 'demo',
                                                    dir: 'auto',
                                                    lang: ''
                                                });
                                            }
                                        } catch (err) {

                                        }
                                    }
                                });
                            } else {
                                $('#alert_total').html('');
                                $('#alert_header_title').html('<a href="."><p class="text-green"> <?= __("Yeni bildirim yoktur. ") ?></p></a>');
                            }
                        });
                    },
                    error: function (error) {
                        is_run_alert = false;
                    }
                });
            }
        }

        $(function () {
            set_alert();
            var timer, start;
            function repeatXI(callback, interval, repeats, immediate) {
                var timer, trigger;
                trigger = function () {
                    callback();
                    --repeats || clearInterval(timer);
                };
                interval = interval <= 0 ? 1000 : interval; // default: 1000ms
                repeats = parseInt(repeats, 10) || 0; // default: repeat forever
                timer = setInterval(trigger, interval);
                if (!!immediate) { // Coerce boolean
                    trigger();
                }

                return timer;
            }

            window.run = function () {
                var args = Array.prototype.slice.call(arguments, 0);
                if (timer) {
                    clearInterval(timer);
                }
                start = (new Date).getTime();
                timer = repeatXI.apply(null, [set_alert].concat(args));
            }

            run(10000);
        });
    </script>
    <?php
    if (isset($_GET["alert"]) && $_GET["alert"] == 1) {
        ?>
        <script>
            var formData = new Array();
            formData.push({name: 'post_type', value: 5});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_get_alert.php',
                type: 'POST',
                data: formData,
                success: function () {

                }
            });
        </script>
        <?php
    }
}
?>
   
