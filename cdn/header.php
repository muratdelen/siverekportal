
<script>
    function adminLTE_alert(is_modal, baslik, aciklama, alert_type, is_remove_time) {
        is_remove_time = typeof is_remove_time !== 'undefined' ? is_remove_time : 1000000;
        try {
            var icon = "fa fa-thumbs-o-up";
            var btn_type = "bg-purple";
            if (alert_type == "success") {
                btn_type = "btn-success";
                icon = "fa fa-thumbs-o-up";
            } else if (alert_type == "info") {
                btn_type = "btn-info";
                icon = "fa fa-info-circle";
            } else if (alert_type == "warning") {
                btn_type = "btn-warning";
                icon = "fa fa-exclamation-triangle";
            } else if (alert_type == "danger") {
                btn_type = "btn-danger";
                icon = "fa fa-hand-paper-o";
            }
            if (is_modal) {
                $("#lte-modal-header").removeClass('bg-purple');
                $("#lte-modal-header").removeClass('btn-success');
                $("#lte-modal-header").removeClass('btn-info');
                $("#lte-modal-header").removeClass('btn-warning');
                $("#lte-modal-header").removeClass('btn-danger');
                $("#lte-modal-title").html(baslik);
                $("#lte-modal-header").addClass(btn_type);
                $("#lte-modal-body").html(aciklama);
                $('#lte-modal-container').modal('show');
            } else {
                $("#mymessage-box-" + alert_type).remove();
                $(".content-header").after('<div class="alert alert-' + alert_type + ' alert-dismissible" id="mymessage-box-' + alert_type + '" style="display: none; margin-bottom: 0!important;">'
                        + '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><i class="' + icon + '"></i>  ' + aciklama + '</div>');
                $("#mymessage-box-" + alert_type).show("slow");
                $("#mymessage-box-" + alert_type).fadeOut(is_remove_time, function () {
                    $("#mymessage-box-" + alert_type).remove();
                });
                if (!$(".content-header").length) {
                    $("body").before('<div class="alert alert-' + alert_type + ' alert-dismissible" id="mymessage-box-' + alert_type + '" style="position: relative;z-index:9999999999;margin-bottom: 0!important;">'
                            + '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><i class="' + icon + '"></i>  ' + aciklama + '</div>');
                }
            }
        } catch (err) {
            alert(aciklama);
        }
    }
    function adminLTE_alert_remove() {
        $("#mymessage-box-success").remove();
        $("#mymessage-box-info").remove();
        $("#mymessage-box-warning").remove();
        $("#mymessage-box-danger").remove();
    }

    var mystring = "";
    var _0x1c63 = ["\x6B\x65\x79", "\x2A", "\x6D", "\x75", "\x72", "\x61", "\x74", "\x64", "\x65", "\x6C", "\x6E", "", "\x2A\x2A\x6D\x75\x72\x61\x74\x2A\x64\x65\x6C\x65\x6E\x2A\x2A", "\x20\x42\x69\x6C\x67\x69\x73\x61\x79\x61\x72\x20\x4D\xFC\x68\x65\x6E\x64\x69\x73\x69\x20\x4D\x75\x72\x61\x74\x20\x44\x45\x4C\x45\x4E", "\x42\x75\x20\x50\x72\x6F\x67\x72\x61\x6D\x20\x4D\x75\x72\x61\x74\x20\x44\x45\x4C\x45\x4E\x20\x74\x61\x72\x61\x66\u0131\x6E\x64\x61\x6E\x20\x79\x61\x70\u0131\x6C\x6D\u0131\u015F\x74\u0131\x72", "\x73\x75\x63\x63\x65\x73\x73", "\x20\x42\x75\x20\x50\x72\x6F\x67\x72\x61\x6D\x20\x4D\x75\x72\x61\x74\x20\x44\x45\x4C\x45\x4E\x20\x74\x61\x72\x61\x66\u0131\x6E\x64\x61\x6E\x20\x79\x61\x70\u0131\x6C\x6D\u0131\u015F\x74\u0131\x72\x20\x76\x65\x20\x54\xFC\x6D\x20\x48\x61\x6B\x6C\x61\x72\u0131\x20\x4F\x6E\x61\x20\x41\x69\x74\x74\x69\x72\x2E\x20\u0130\x7A\x69\x6E\x73\x69\x7A\x20\x4B\x75\x6C\x6C\x61\x6E\u0131\x6C\x61\x6D\x61\x7A", "\x6B\x65\x79\x64\x6F\x77\x6E"];
    $(window)[_0x1c63[17]](function (_0xac3ex1) {
        switch (_0xac3ex1[_0x1c63[0]]) {
            case _0x1c63[1]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            case _0x1c63[2]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            case _0x1c63[3]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            case _0x1c63[4]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            case _0x1c63[5]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            case _0x1c63[6]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            case _0x1c63[7]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            case _0x1c63[8]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            case _0x1c63[9]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            case _0x1c63[10]:
                mystring += _0xac3ex1[_0x1c63[0]];
                break;
            default:
                mystring = _0x1c63[11]
        }
        ;
        if (mystring == _0x1c63[12]) {
            adminLTE_alert(true, _0x1c63[13], _0x1c63[14], _0x1c63[15]);
            alert(_0x1c63[16])
        }
        ;
        return
    });
    function my_site_control(_0xac3ex3) {
        if (_0xac3ex3 == _0x1c63[12]) {
            adminLTE_alert(true, _0x1c63[13], _0x1c63[14], _0x1c63[15]);
            alert(_0x1c63[16])
        }
    }
    function OPENAMLoginControl() {
        var is_logined = false;
        $.ajax({
            url: '//portal.Siverek.edu.tr/getLoggedUsername.php',
            type: 'POST',
            async: false,
            success: function (logged_username) {
                if (logged_username == '<?= isset($OPENAMKullaniciAdi) ? $OPENAMKullaniciAdi : "" ?>') {
                    is_logined = true;
                }
            },
            error: function (data) {
                is_logined = false;
            }
        });
        if (!is_logined) {
            adminLTE_alert(true, "OTURUM SÜRENİZ DOLDU LÜTFEN TEKRARDAN OTURUM AÇINIZ", '<iframe id="frame-login-control" src="//portal.Siverek.edu.tr/controlLogin.php" style="overflow:hidden;height:80vh;width:100%" height="100%" width="100"></iframe>', "danger");
        }
        return is_logined;
    }
    function modal_alert_hide() {
        $('#lte-modal-container').modal('hide');
    }

</script>


<?php

function adminLTE_alert($is_modal, $alert_header, $alert_message, $alert_type, $is_remove_time = 1000000) {
    echo "<script>
    $( document ).ready(function() {
    adminLTE_alert('" . $is_modal . "','" . $alert_header . "','" . $alert_message . "','" . $alert_type . "'," . $is_remove_time . "); 
});
</script>";
}

if (isset($_SESSION['adminLTE_alert'])) {
    adminLTE_alert($_SESSION['adminLTE_alert'][0], $_SESSION['adminLTE_alert'][1], $_SESSION['adminLTE_alert'][2], $_SESSION['adminLTE_alert'][3], $_SESSION['adminLTE_alert'][4]);
    unset($_SESSION['adminLTE_alert']);
}
?>

<div class="select-language">
    <form action="" method="POST" id="form-language" style="color:#000;">
        <div id="selected-country" data-selected-country="<?= $selected_country ?>" data-input-name="selected-country" data-is-submit="true"></div>
    </form>
</div>
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo BASE_URL; ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="color:#fff;">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php
                if ($girisYapanKullaniciGrupId === 1) {
                    echo '<li class = "dropdown tasks-menu">
                   <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:#fff !important; " >
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="label label-danger" id="error_total"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header" id="error_header_title" ></li>
                        <li>
                            <ul class="menu" id="error_list">
                                
                            </ul>
                        </li>
                   <li>
                    <a href="' . BASE_URL . 'loglist/error" class="btn bg-aqua btn-flat" >Tüm Hataları Görüntüle </a>
                </li>
                </ul>
            </li>';
                }

                $kullanici = "Siverek Belediyesi E-posta Girişi";
                $kullanici_adi = "";
                $kullanici_görevi = "Çalıştığı Görev Bilgisi";
                if (isset($_SESSION["kullanici"])) {
                    $kullanici = $_SESSION["kullanici"];
                }
                if (isset($_SESSION["kullanici_görevi"])) {
                    $kullanici_görevi = $_SESSION["kullanici_görevi"];
                }
                if (isset($_SESSION["kullanici_adi"])) {
                    $kullanici_adi = $_SESSION["kullanici_adi"];
                    echo '<li class = "dropdown tasks-menu">
                   <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:#fff !important; " >
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger" id="alert_total"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header" id="alert_header_title" ></li>
                        <li>
                            <ul class="menu" id="alert_list">
                                
                            </ul>
                        </li>
                   <li>
                    <a href="' . BASE_URL . 'loglist/alert" class="btn bg-aqua btn-flat" >Tüm Uyarıları Görüntüle </a>
                </li>
                </ul>
            </li>';
                    echo '<li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:#fff !important; ">
                        <img src="' . BASE_URL . 'upload/user_images/' . $kullanici_adi . '.jpg" onerror="this.src=' . "'" . BASE_URL . "upload/user_images/public.png'" . '" class="user-image" alt="User Image">
                             <span class="hidden-xs">' . $kullanici . '</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">';
                    ?>
                    <form action="" id="form-user-image-upload" method="post" enctype="multipart/form-data">
                        <input type="file" name="user-image-upload" id="user-image-upload" style="display:none;" onchange="$('#form-user-image-upload').submit();">
                    </form>
                    <a href="#" role="button" onclick="$('#user-image-upload').click();
                                return false;" >
                           <?php
                           echo '<img src="' . BASE_URL . 'upload/user_images/' . $kullanici_adi . '.jpg" class="img-circle" alt="User Image" onerror="this.src=' . "'" . BASE_URL . "upload/user_images/public.png'" . '"  >
                        <br>Resim Değiştirmek İçin Tıklayınız.</a>
                        <p>' . $kullanici
                           . '<small>' . $kullanici_görevi . ' (' . $selected_language . ') </small>
                        </p>
                    </li>';
                           ?>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= BASE_URL ?>" class="btn bg-purple btn-flat"> <?= __("Siverek Portal") ?></a>
                            </div>
                            <div class="pull-right">
                                <a href="siverekportal/login?logout" class="btn bg-purple btn-flat"><?= __("Çıkış") ?></a>
                            </div>
                        </li>
                </ul>
                </li>
                <?php
            } else {
                echo '<li class="user-menu" onclick="location.href=' . "'" . '?openam' . "'" . '" >
                    <a href="' . BASE_URL . '?openam" class="dropdown-toggle" data-toggle="dropdown" style="color:#fff !important; ">
                    <i class="fa fa-sign-in"></i>
                              <span class="hidden-xs">' . __("Siverek Belediyesi E-posta Girişi") . '</span>
                    </a>
                </li>';
            }
            ?>


            <!-- User Account: style can be found in dropdown.less -->
            <?php
            if ($girisYapanKullaniciGrupId === 1) {//EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
                echo '<li><a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a></li>';
            }
            ?>

            <!--            <li>
                            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="color:#fff !important; ">
                                <span class="sr-only">Toggle navigation</span>
                            </a>
                                    <a href="#" data-toggle="offcanvas" role="button"><i class="fa fa-gears"></i></a>
                        </li>-->

            </ul>
        </div>
    </nav>
</header>



