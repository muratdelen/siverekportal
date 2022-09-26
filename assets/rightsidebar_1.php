<?php

function get_menu_items_option_html() {
    //EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
    $menuItemsSQL = 'SELECT * FROM yt_menu WHERE ISNULL(yt_menu.ana_menu_id) ';
    try {
        $menuItems = $GLOBALS['db']->fetchAll($menuItemsSQL);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    $menu_order = 0;
    foreach ($menuItems as $MenuItem) {
        $menu_order++;
        $id_sifreli = mcrypt($MenuItem->id, $_SESSION['key']);
        $menu_html .= '<option value="' . $id_sifreli . '" title="' . $MenuItem->link . '">[' . $menu_order . '] ' . $MenuItem->adi . '</option>' . get_submenu_itemsoption_html($MenuItem->id);
    }

    return $menu_html;
}

function get_submenu_itemsoption_html($menu_id, $subicon = "- ") {
    //EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
    $subMenuItemsSQL = 'SELECT * FROM yt_menu WHERE yt_menu.ana_menu_id = ? ';
    try {
        $subMenuItems = $GLOBALS['db']->fetchAll($subMenuItemsSQL, $menu_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    $menu_order = 0;
    foreach ($subMenuItems as $subMenuItem) {
        $menu_order++;
        $id_sifreli = mcrypt($subMenuItem->id, $_SESSION['key']);
        $menu_html .= '<option value="' . $id_sifreli . '" title="' . $subMenuItem->link . '">' . $menu_order . $subicon . $subMenuItem->adi . '</option>' . get_submenu_itemsoption_html($subMenuItem->id, "> ");
    }
    return $menu_html;
}

function get_page_items_option_html($menu_id) {
    $ItemsSQL = 'SELECT * FROM yt_erisilebilecek_sayfalar WHERE yt_erisilebilecek_sayfalar.yt_menu_id = ? ';
    try {
        $Items = $GLOBALS['db']->fetchAll($ItemsSQL, $menu_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    foreach ($Items as $Item) {
        $id_sifreli = mcrypt($Item->id, $_SESSION['key']);
        $menu_html .= '<option value="' . $id_sifreli . '" >' . $Item->adi . '</option>';
    }
    return $menu_html;
}
?>

<script>
    //for COOKİE
    //    $(document).ready(function () {
    //        $("#thema_fixed_layout").change(function () {
    //            setCookie("thema_fixed_layout", this);
    //        });
    //    });
    //
    //    function setCookie(cname, cvalueID) {
    //        var d = new Date();
    //        var exdays = 99999;
    //        var cvalue = $(cvalueID).is(":checked");
    //        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    //        var expires = "expires=" + d.toUTCString();
    //        document.cookie = cname + "=" + cvalue + "; " + expires;
    //    }
    //
    //    function getCookie(cname) {
    //        var name = cname + "=";
    //        var ca = document.cookie.split(';');
    //        for (var i = 0; i < ca.length; i++) {
    //            var c = ca[i];
    //            while (c.charAt(0) == ' ')
    //                c = c.substring(1);
    //            if (c.indexOf(name) == 0)
    //                return c.substring(name.length, c.length);
    //        }
    //        return "";
    //    }
    // FOR MENU AND GROUP
    //    var formData = new Array();
    //    formData.push({name: 'post_type', value: 1}, {name: 'is_post', value: 1}, {name: 'page_post_title', value: 'system_post'}, {name: 'is_post', value: 1}, {name: 'is_post', value: 1}, {name: 'is_post', value: 1}, {name: 'is_post', value: 1}, {name: 'is_post', value: 1}, {name: 'is_post', value: 1}, {name: 'is_post', value: 1});
    //    $.ajax({
    //        async: false,
    //        url: '<?php // echo ASSETS_DIR;                           ?>/post_get_st_uyari.php',
    //        type: 'POST',
    //        data: formData,
    //        success: function () {
    //
    //        }
    //    });
</script>
<?php
//if (isset($_COOKIE["thema_fixed_layout"])) {
//    echo '<script>
//    $( document ).ready(function() {
//    //$("#thema_fixed_layout").prop("checked", ' . $_COOKIE["thema_fixed_layout"] . ');
//});
//</script>';
//}
?>
<script>
    function admin_proccess_st_uyari(aciklama, st_uyari_type) {
        if (st_uyari_type == "success") {
            //                            $("#admin_mymessage-box_success").remove();
            $(".tab-content").before('<div class="st_uyari alert-success alert-dismissible" id="admin_mymessage-box_success" style="margin-bottom: 0!important;">'
                    + '<button aria-hidden="true" data-dismiss="st_uyari" class="close" type="button">×</button><i class="fa fa-thumbs-o-up"></i>  ' + aciklama + '</div>');
            setTimeout(function () {
                $("#admin_mymessage-box_success").remove();
            }, 5000);
        } else if (st_uyari_type == "info") {
            //                            $("#admin_mymessage-box_info").remove();
            $(".tab-content").before('<div class="st_uyari alert-info alert-dismissible" id="admin_mymessage-box_info" style="margin-bottom: 0!important;">'
                    + '<button aria-hidden="true" data-dismiss="st_uyari" class="close" type="button">×</button><i class="fa fa-info-circle"></i>  ' + aciklama + '</div>');
            setTimeout(function () {
                $("#admin_mymessage-box_danger").remove();
            }, 5000);
        } else if (st_uyari_type == "warning") {
            //                            $("#admin_mymessage-box_warning").remove();
            $(".tab-content").before('<div class="st_uyari alert-warning alert-dismissible" id="admin_mymessage-box_warning" style="margin-bottom: 0!important;">'
                    + '<button aria-hidden="true" data-dismiss="st_uyari" class="close" type="button">×</button><i class="fa fa-exclamation-triangle"></i>  ' + aciklama + '</div>');
            setTimeout(function () {
                $("#admin_mymessage-box_danger").remove();
            }, 5000);
        } else if (st_uyari_type == "danger") {
            //                            $("#admin_mymessage-box_danger").remove();
            $(".tab-content").before('<div class="st_uyari alert-danger alert-dismissible" id="admin_mymessage-box_danger" style="margin-bottom: 0!important;">'
                    + '<button aria-hidden="true" data-dismiss="st_uyari" class="close" type="button">×</button><i class="fa fa-hand-paper-o"></i>  ' + aciklama + '</div>');
            setTimeout(function () {
                $("#admin_mymessage-box_danger").remove();
            }, 5000);
        } else {
            //            $("#mymessage-box_success").remove();
            //            $("#mymessage-box_info").remove();
            //            $("#mymessage-box_warning").remove();
            //            $("#mymessage-box_danger").remove();
        }
    }


    function baglanacak_menu_degismesi() {
        $('.ui-tooltip').remove();
        var formData = new Array();
        formData.push({name: 'post_type', value: 0}, {name: 'baglanacak_menu_id', value: $("#baglanacak_ana_menu").val()});
        $.ajax({
            async: false,
            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var obj = $.parseJSON(data);
                var str_menu_option = '<option value="">Baştaki Menu</option>' + obj;
                $("#sonrasinda_gelecek_menu").html(str_menu_option);
            }
        });
    }
    function menu_ekle() {
        $('.ui-tooltip').remove();
        var formData = new Array();
        formData.push({name: 'post_type', value: 1}, {name: 'ana_menu_id', value: $("#ana_menu").val()}, {name: 'onceki_menu_id', value: $("#sonrasinda_gelecek_menu").val()}, {name: 'menu_kaydedilen_sayfa_url', value: '<?php echo $pageUrl; ?>'}, {name: 'menu_link', value: $("#menu_link").val()}, {name: 'menu_adi', value: $("#menu_adi").val()}, {name: 'menu_sol_icon', value: $("#menu_sol_icon").val()}, {name: 'menu_sag_icon', value: $("#menu_sag_icon").val()});
        $.ajax({
            async: false,
            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var obj = $.parseJSON(data);
                var str_menu_option = '<option value="">Ana Menu</option>' + obj;
                $("#ana_menu_id").html(str_menu_option);
                $("#before_menu").html(str_menu_option);
                $("#silinecek_menu").html(str_menu_option);
                admin_proccess_st_uyari("Yeni Menü Eklendi.", "success");
            }
        });
    }
    function menu_sil() {

        var formData = new Array();
        formData.push({name: 'post_type', value: 2}, {name: 'silinecek_menu', value: $("#silinecek_menu").val()});
        $.ajax({
            async: false,
            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var obj = $.parseJSON(data);
                var str_menu_option = '<option value="">Ana Menu</option>' + obj;
                $("#ana_menu").html(str_menu_option);
                $("#before_menu_id").html(str_menu_option);
                $("#silinecek_menu").html(str_menu_option);
                admin_proccess_st_uyari("Menü Silindi.", "success");
            }
        });
    }

    //ACCESS PAGE
    function erisilebilen_sayfalar_yukle() {
        $('.ui-tooltip').remove();
        var formData = new Array();
        formData.push({name: 'post_type', value: 3}, {name: 'secilen_menu_id', value: $("#erisilebilen_sayfalarin_menu_id").val()});
        $.ajax({
            async: false,
            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var obj = $.parseJSON(data);
                var str_menu_option = '<option value="">Seçiniz</option>';
                $.each(obj, function (key, value) {
                    str_menu_option += '<option value="' + value["id"] + '">' + value["adi"] + '[' + value["sayfa_url"] + ']</option>';
                });
                $("#erisilebilen_sayfa_sil_select").html(str_menu_option);
            }
        });
    }

    function erisebilen_sayfa_ekle() {
        if ($("#erisilebilen_sayfalarin_menu_id").val() == "") {
            admin_proccess_st_uyari("Eklenecek Menu Seçiniz.", "danger");
        } else {
            $('.ui-tooltip').remove();
            var formData = new Array();
            formData.push({name: 'post_type', value: 4}, {name: 'secilen_menu_id', value: $("#erisilebilen_sayfalarin_menu_id").val()}, {name: 'sayfa_url', value: $("#sayfa_url").val()}, {name: 'sayfa_adi', value: $("#sayfa_adi").val()});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    erisilebilen_sayfalar_yukle();
                    if (data == 1) {
                        admin_proccess_st_uyari("Başarılı ile kaydedildi.", "success");
                    } else if (data == 0) {
                        admin_proccess_st_uyari("Kaydedilmedi. <br>Sayfa Seçiniz.", "danger");
                    } else if (data == -1) {
                        admin_proccess_st_uyari("Kaydedilmedi. <br>Veritabanı Hatası.", "danger");
                    }
                }
            });
        }
    }
    function erisilebilen_sayfa_sil() {
        if ($("#erisilebilen_sayfa_sil_select").val() == "") {
            admin_proccess_st_uyari("Silinecek Sayfa Seçiniz.", "danger");
        } else {
            var formData = new Array();
            formData.push({name: 'post_type', value: 5}, {name: 'secilen_sayfa', value: $("#erisilebilen_sayfa_sil_select").val()});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    erisilebilen_sayfalar_yukle();
                    if (data == 1) {
                        admin_proccess_st_uyari("Başarılı ile silindi.", "success");
                    } else if (data == 0) {
                        admin_proccess_st_uyari("Silinmedi. <br>Sayfa Seçiniz.", "danger");
                    } else if (data == -1) {
                        admin_proccess_st_uyari("Silinmedi. <br>Veritabanı Hatası.", "danger");
                    }
                }
            });
        }
    }
    //ACCESS POST PAGE
    function load_post_page_access() {
        $('.ui-tooltip').remove();
        var formData = new Array();
        formData.push({name: 'post_type', value: 4}, {name: 'secilen_menu_id', value: $("#post_erisilebilen_sayfalarin_menu_id").val()});
        $.ajax({
            async: false,
            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var obj = $.parseJSON(data);
                var str_menu_option = '<option value="-1">Seçiniz</option>';
                $.each(obj, function (key, value) {
                    str_menu_option += '<option value="' + value["id"] + '">' + value["title"] + '[' + value["page_url"] + ']</option>';
                });
                $("#delete_post_page_access_select").html(str_menu_option);
            }
        });
    }

    function insert_post_access_page() {

        $('.ui-tooltip').remove();
        var formData = new Array();
        formData.push({name: 'post_type', value: 6}, {name: 'post_erisilebilen_sayfalarin_menu_id', value: $("#post_erisilebilen_sayfalarin_menu_id").val()}, {name: 'post_sayfa_url', value: $("#post_sayfa_url").val()}, {name: 'post_sayfa_adi', value: $("#post_sayfa_adi").val()});
        $.ajax({
            async: false,
            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                load_post_page_access();
                if (data == 1) {
                    admin_proccess_st_uyari("Başarılı ile kaydedildi.", "success");
                } else if (data == 0) {
                    admin_proccess_st_uyari("Kaydedilmedi. <br>Sayfa Seçiniz.", "danger");
                } else if (data == -1) {
                    admin_proccess_st_uyari("Kaydedilmedi. <br>Veritabanı Hatası.", "danger");
                }
            }
        });
    }
    function delete_post_page_access() {

        var formData = new Array();
        formData.push({name: 'post_type', value: 8}, {name: 'selected_page_id', value: $("#delete_post_page_access_select").val()});
        $.ajax({
            async: false,
            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                load_post_page_access();
                if (data == 1) {
                    admin_proccess_st_uyari("Başarılı ile kaydedildi.", "success");
                } else if (data == 0) {
                    admin_proccess_st_uyari("Kaydedilmedi. <br>Sayfa Seçiniz.", "danger");
                } else if (data == -1) {
                    admin_proccess_st_uyari("Kaydedilmedi. <br>Veritabanı Hatası.", "danger");
                }
            }
        });
    }

    function change_access_page(my_id) {
        $('.ui-tooltip').remove();
        var menu_id = $(my_id).val();
        if ($("#is_post_page").prop("checked")) {
            var formData = new Array();
            formData.push({name: 'post_type', value: 4}, {name: 'secilen_menu_id', value: menu_id});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    var obj = $.parseJSON(data);
                    var str_menu_option = '<option value="-1">Seçiniz</option>';
                    $.each(obj, function (key, value) {
                        str_menu_option += '<option value="' + value["id"] + '">' + value["title"] + '[' + value["page_url"] + ']</option>';
                    });

                    $("#page_access_for_roles_proccess_select").html(str_menu_option);
                    $("#page_access_users_for_proccess_select").html(str_menu_option);
                }
            });
        } else {
            var formData = new Array();
            formData.push({name: 'post_type', value: 3}, {name: 'secilen_menu_id', value: menu_id});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    var obj = $.parseJSON(data);
                    var str_menu_option = '<option value="-1">Seçiniz</option>';
                    $.each(obj, function (key, value) {
                        str_menu_option += '<option value="' + value["id"] + '">' + value["title"] + '[' + value["page_url"] + ']</option>';
                    });
                    $("#page_access_for_roles_proccess_select").html(str_menu_option);
                    $("#page_access_users_for_proccess_select").html(str_menu_option);

                }
            });
        }
    }
    function change_access_page_role() {
        $('.ui-tooltip').remove();
        var page_id = $("#page_access_for_roles_proccess_select").val();
        var role_id = $("#role_select").val();
        if ($("#is_post_page").prop("checked")) {
            var formData = new Array();
            formData.push({name: 'post_type', value: 13}, {name: 'is_post_page', value: 1}, {name: 'selected_page_id', value: page_id}, {name: 'selected_role_id', value: role_id});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    var str_menu_option = $.parseJSON(data);
                    $("#roles_page_proccess").html(str_menu_option);
                }
            });
        } else {
            var formData = new Array();
            formData.push({name: 'post_type', value: 13}, {name: 'is_post_page', value: 0}, {name: 'selected_page_id', value: page_id}, {name: 'selected_role_id', value: role_id});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    var str_menu_option = $.parseJSON(data);
                    $("#roles_page_proccess").html(str_menu_option);
                }
            });
        }
    }
    function insert_roles_for_page_access_and_post_access() {

        var selected_proccess_id = new Array();
        $('#roles_page_proccess option:selected').each(function (i, selected) {
            selected_proccess_id.push($(selected).val());
        });
        //       var selected_proccess_id = $("#roles_page_proccess option:selected").map(function(){ return this.value }).get();
        if ($("#is_post_page").prop("checked")) {
            var formData = new Array();
            formData.push({name: 'post_type', value: 9}, {name: 'page_id', value: $("#page_access_for_roles_proccess_select").val()}, {name: 'role_id', value: $("#role_select").val()}, {name: 'page_proccess_id', value: selected_proccess_id}, {name: 'is_post_page', value: 1});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data == 1) {
                        admin_proccess_st_uyari("Başarılı ile kaydedildi.", "success");
                    } else if (data == 0) {
                        admin_proccess_st_uyari("Kaydedilmedi. <br>Sayfa Seçiniz.", "danger");
                    } else if (data == -1) {
                        admin_proccess_st_uyari("Kaydedilmedi. <br>Veritabanı Hatası.", "danger");
                    }
                }
            });
        } else {
            var formData = new Array();
            formData.push({name: 'post_type', value: 9}, {name: 'page_id', value: $("#page_access_for_roles_proccess_select").val()}, {name: 'role_id', value: $("#role_select").val()}, {name: 'page_proccess_id', value: selected_proccess_id}, {name: 'is_post_page', value: 0});

            //                 formData.push({name: 'post_type', value: 9}, {name: 'group_id', value: $("#roles_for_proccess_select").val()}, {name: 'page_id', value: $("#page_access_for_roles_proccess_select").val()},{name: 'page_proccess_add', value: $("#users_page_proccess_add").val()},{name: 'page_proccess_except', value: $("#users_page_proccess_except").val()},{name: 'is_post_page', value:0});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data == 1) {
                        admin_proccess_st_uyari("Başarılı ile kaydedildi.", "success");
                    } else if (data == 0) {
                        admin_proccess_st_uyari("Kaydedilmedi. <br>Sayfa Seçiniz.", "danger");
                    } else if (data == -1) {
                        admin_proccess_st_uyari("Kaydedilmedi. <br>Veritabanı Hatası.", "danger");
                    }

                }
            });
        }
    }
    function insert_users_for_page_access_and_post_access() {

        var selected_proccess_id_add = new Array();
        var selected_proccess_id_except = new Array();
        $('#users_page_proccess_add option:selected').each(function (i, selected) {
            selected_proccess_id_add.push($(selected).val());
        });
        $('#users_page_proccess_except option:selected').each(function (i, selected) {
            selected_proccess_id_except.push($(selected).val());
        });
        //       var selected_proccess_id = $("#roles_page_proccess option:selected").map(function(){ return this.value }).get();
        if ($("#is_post_page").prop("checked")) {
            var formData = new Array();
            formData.push({name: 'post_type', value: 10}, {name: 'kullanici_adi', value: $("#users_for_proccess_select").val()}, {name: 'user_roles_title', value: $("#user_roles_title").val()}, {name: 'user_roles_aciklama', value: $("#user_roles_aciklama").val()}, {name: 'page_id', value: $("#page_access_users_for_proccess_select").val()}, {name: 'page_proccess_id_add', value: selected_proccess_id_add}, {name: 'page_proccess_except', value: selected_proccess_id_except}, {name: 'is_post_page', value: 1});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    admin_proccess_st_uyari("deneme", "success");
                }
            });
        } else {
            var formData = new Array();
            formData.push({name: 'post_type', value: 10}, {name: 'kullanici_adi', value: $("#users_for_proccess_select").val()}, {name: 'user_roles_title', value: $("#user_roles_title").val()}, {name: 'user_roles_aciklama', value: $("#user_roles_aciklama").val()}, {name: 'page_id', value: $("#page_access_users_for_proccess_select").val()}, {name: 'page_proccess_id_add', value: selected_proccess_id_add}, {name: 'page_proccess_except', value: selected_proccess_id_except}, {name: 'is_post_page', value: 0});

            //                 formData.push({name: 'post_type', value: 9}, {name: 'group_id', value: $("#roles_for_proccess_select").val()}, {name: 'page_id', value: $("#page_access_for_roles_proccess_select").val()},{name: 'page_proccess_add', value: $("#users_page_proccess_add").val()},{name: 'page_proccess_except', value: $("#users_page_proccess_except").val()},{name: 'is_post_page', value:0});
            $.ajax({
                async: false,
                url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    admin_proccess_st_uyari("deneme", "success");

                }
            });
        }
    }
    //SELECT ROL
    function insert_role_for_pages() {

        $('.ui-tooltip').remove();
        var formData = new Array();
        formData.push({name: 'post_type', value: 11}, {name: 'role_title', value: $("#role_title").val()}, {name: 'role_aciklama', value: $("#role_aciklama").val()});
        $.ajax({
            async: false,
            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var str_menu_option = '<option value="-1">Seçiniz</option>' + $.parseJSON(data);
                $("#role_select").html(str_menu_option);
                $("#role_select_for_delete").html(str_menu_option);
                admin_proccess_st_uyari("Yeni Rol Eklendi.", "success");
            }
        });
    }
    function delete_role_for_pages() {

        $('.ui-tooltip').remove();
        var formData = new Array();
        formData.push({name: 'post_type', value: 12}, {name: 'role_id', value: $("#role_select_for_delete").val()});
        $.ajax({
            async: false,
            url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var str_menu_option = '<option value="-1">Seçiniz</option>' + $.parseJSON(data);
                $("#role_select").html(str_menu_option);
                $("#role_select_for_delete").html(str_menu_option);
                admin_proccess_st_uyari("Rol Silindi.", "success");
            }
        });
    }

</script>
<?php
if ($girisYapanKullaniciGrupId === 1) {//EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
    ?>
    <aside class="control-sidebar control-sidebar-dark" >
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab" title="Menü Ekleme"><i class="fa fa-caret-square-o-down"></i></a></li>
            <li><a href="#control-pageAccess-settings-tab" data-toggle="tab" title="Erişilecek Sayfa Ekleme"><i class="fa fa-file"></i></a></li> 
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">
                <p>MENU EKLEME & SİLME</p>
                <h4><button class="btn btn-default btn-block"onclick="menu_ekle();" ><i class="fa fa-download"></i>Menu Ekle</button></h4
                <!--//ÜST MENU-->
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bağlanacak Ana Menü Seçiniz.</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <select class="form-control select2"  id="ana_menu" onchange="baglanacak_menu_degismesi();" style="width: 100%;">
                                <option value="-1">Ana Menü</option>
                                <?php echo get_menu_items_option_html(); ?>
                            </select>
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>
                <!--//ÖNCEKİ MENU-->
                <div class="box box-default box-solid"  style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Hangi Menüden Sonra Geleceğini Seçiniz.</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <select class="form-control select2"  id="sonrasinda_gelecek_menu" style="width: 100%;">
                                <option value="-1">Baştaki Menu</option>
                                <?php echo get_menu_items_option_html(); ?>
                            </select>
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>
                <!--///MENU LİNK-->
                <div class="box box-default box-solid"  style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Menü tıklandığında gidecek link giriniz.</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <input type="text" id="menu_link" value="<?php echo $pageUrl; ?>"/>
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>
                <!--///MENU ADI-->
                <div class="box box-default box-solid" style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Menu adı giriniz.</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <input type="text" id="menu_adi"/>
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>
                <!--///İCON-->
                <div class="box box-default box-solid"  style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Menu icon seçiniz.</h3>
                        <p><a href="http://tema.Siverek.edu.tr/Original/pages/UI/icons.html" target="_blank">iconlar</a></p>
                        <p><a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">icon kodları</a></p>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <input type="text"id="menu_sol_icon"/>
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>
                <!--///İCON-->
                <div class="box box-default box-solid"  style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Menu sağ icon seçiniz.</h3>
                        <p><a href="http://tema.Siverek.edu.tr/Original/pages/UI/icons.html" target="_blank">iconlar</a></p>
                        <p><a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">icon kodları</a></p>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <input type="text" id="menu_sag_icon"/>
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>

                <h4><button class="btn btn-default btn-block" onclick="menu_sil();"><i class="fa fa-download"></i>Menü Sil</button></h4>
                <!--//ÜST MENU-->
                <div class="box box-default box-solid"  style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Silinecek Menü Seçiniz.</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">

                        <div class="form-group">
                            <select class="form-control select2"  id="silinecek_menu" style="width: 100%;">
                                <option value="">Ana Menu</option>
                                <?php echo get_menu_items_option_html(); ?>
                            </select>
                        </div><!-- /.form-group -->

                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- /.tab-pane -->


            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->


            <!-- Settings ERİŞİLECEK SAYFALAR content -->
            <div class="tab-pane" id="control-pageAccess-settings-tab">
                <p>ERİŞİM İÇİN SAYFANIN SİSTEME EKLENMESİ/SİLİNMESİ</p>
                <h4><button class="btn btn-default btn-block" onclick="erisebilen_sayfa_ekle();"><i class="fa fa-download"></i>Sayfayı Sisteme Ekle</button></h4>
                <!--//SAYFA BAĞLANACAK MENU SEÇİNİZ-->
                <div class="box box-default box-solid" style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sayfanın Bağlanacağı Menü Seçiniz</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <select class="form-control select2"  id="erisilebilen_sayfalarin_menu_id" onchange="erisilebilen_sayfalar_yukle();" style="width: 100%;">
                                <option value="">Ana Menu</option>
                                <?php echo get_menu_items_option_html(); ?>
                            </select>
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>
                <!--///SAYFA ADI-->
                <div class="box box-default box-solid" style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sayfa Adını Giriniz.</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <input type="text" id="sayfa_adi" />
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>
                <!--///SAYFA ADI-->
                <!--///SAYFA URL-->
                <div class="box box-default box-solid" style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sayfa Url Giriniz</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <input type="text" id="sayfa_url" value="<?php echo $pageUrl; ?>"/>
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>
                <!--///SAYFA URL-->

                <h4><button class="btn btn-default btn-block" onclick="erisilebilen_sayfa_sil();"><i class="fa fa-download"></i>Sayfayı Sistemden Sil</button></h4>
                <!--//ÜST MENU-->
                <div class="box box-default box-solid" style="color:black;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Silinecek Sayfa Seçiniz.</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        <div class="form-group">
                            <select class="form-control select2"  id="erisilebilen_sayfa_sil_select"  style="width: 100%;">
                                <option value="">Seçiniz</option>
                            </select>
                        </div><!-- /.form-group -->
                    </div><!-- /.box-body -->
                </div>
            </div><!-- /.tab-pane -->


        </div><!-- /.tab-pane -->

    </aside>

    <?php
}
?>