
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <?= __("Copyright &copy;2022 <b>Version</b> 1.0"); ?>
    </div>
    <?= __('<strong><a href="http://bidb.Siverek.edu.tr">Yazılım Geliştirme Birimi</a></strong> tarafından yapılmıştır.'); ?>

</footer>

<script>
    $(function () {
//   $(document).tooltip({
//       position: {
//           my: "center bottom-20",
//           at: "center top",
//           using: function(position, feedback) {
//               $(this).css(position);
//               $("<div>")
//                       .addClass("arrow")
//                       .addClass(feedback.vertical)
//                       .addClass(feedback.horizontal)
//                       .appendTo(this);
//           }
//       }
////   });
//        $("#ui-tooltip-content").mouseover(function () {
//            $(document).tooltip('disable');
//        });
//       $("#dialog_ozel_dizayn_ekrani").mouseout(function(){
//       $(document).tooltip('enable');
//   });    
    });
</script>

<?php
if (!isset($tour_step) || isset($tour_step) && $tour_step == "") {
    $tour_step = '
                    {
//                        path: "/",
                        element: ".sidebar-menu",
                        placement: "right",
                        title: "MENÜ",
                        content: "Menüleri tıklayarak istediğiniz sayfaya erişebilirsiniz.",
                        backdrop: true,
                        reflex: true,
                        keyboard: true,
                        duration: 10000,
                        onShow: function (tour) {
                        }
                    },
                    {
                        element: ".user-menu",
                        placement: "bottom",
                        title: "Siverek Belediyesi GİRİŞ",
                        content: "Burayı tıklayarak Siverek Belediyesi E-posta ile giriş yapabilirsiniz.",
                        backdrop: true,
                        reflex: true,
                        keyboard: true,
                        duration: 10000,
                        onShow: function (tour) {
                        }
                    },
                    {
                        element: ".sidebar-form",
                        placement: "bottom",
                        title: "MENU ARAMA",
                        content: "Buradan menu arayabilirsiniz.",
                        backdrop: true,
                        reflex: true,
                        keyboard: true,
                        duration: 10000,
                        onShow: function (tour) {
                        }
                    }
                    ';
}
if ($girisYapanKullaniciGrupId === 1) {//EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
    ?>
    <script>

        //ACCESS PAGE
        function erisilebilen_sayfalar_yukle() {
            $('.ui-tooltip').remove();
            var formData = new Array();
            formData.push({name: 'post_type', value: 1}, {name: 'secilen_menu_id', value: $("#erisilebilen_sayfalarin_menu_id").val()});
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
                formData.push({name: 'post_type', value: 2}, {name: 'secilen_menu_id', value: $("#erisilebilen_sayfalarin_menu_id").val()}, {name: 'sayfa_url', value: $("#sayfa_url").val()}, {name: 'sayfa_adi', value: $("#sayfa_adi").val()});
                $.ajax({
                    async: false,
                    url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        erisilebilen_sayfalar_yukle();
                        if (data == 1) {
                            adminLTE_alert(true, "Sayfa Kaydetme İşlemi", "Başarılı ile kaydedildi.", "success");
                        } else if (data == 0) {
                            adminLTE_alert(true, "Sayfa Kaydetme İşlemi", "Kaydedilmedi. <br>Sayfa Seçiniz.", "danger");
                        } else if (data == -1) {
                            adminLTE_alert(true, "Sayfa Kaydetme İşlemi", "Kaydedilmedi. <br>Veritabanı Hatası.", "danger");
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
                formData.push({name: 'post_type', value: 3}, {name: 'secilen_sayfa', value: $("#erisilebilen_sayfa_sil_select").val()});
                $.ajax({
                    async: false,
                    url: '<?php echo ASSETS_DIR; ?>/post_settings.php',
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        erisilebilen_sayfalar_yukle();
                        if (data == 1) {
                            adminLTE_alert(true, "Sayfa Silme İşlemi", "Başarılı ile silindi.", "success");
                        } else if (data == 0) {
                            adminLTE_alert(true, "Sayfa Silme İşlemi", "Silinmedi. <br>Sayfa Seçiniz.", "danger");
                        } else if (data == -1) {
                            adminLTE_alert(true, "Sayfa Silme İşlemi", "Silinmedi. <br>Veritabanı Hatası.", "danger");
                        }
                    }
                });
            }
        }

    </script>

    <aside class="control-sidebar control-sidebar-dark" >         
        <p>ERİŞİM İÇİN SAYFANIN SİSTEME EKLENMESİ/SİLİNMESİ</p>
        <!--//SAYFA BAĞLANACAK MENU SEÇİNİZ-->
        <div class="form-group">
            <label for="erisilebilen_sayfalarin_menu_id">Sayfanın Bağlanacağı Menü:</label>
            <select class="form-control select2"  id="erisilebilen_sayfalarin_menu_id" onchange="erisilebilen_sayfalar_yukle();" style="width: 100%;">
                <option value="">Ana Menu</option>
                <?php echo get_menu_items_option_html(); ?>
            </select>
        </div><!-- /.form-group -->
        <div class="form-group">
            <label for="sayfa_adi">Sayfa Adı:</label>
            <input type="text" id="sayfa_adi" />
        </div><!-- /.form-group -->        

        <div class="form-group">
            <label for="sayfa_url">Sayfa Url:</label>
            <input type="text" id="sayfa_url" value="<?php echo $pageUrl; ?>"/>
        </div><!-- /.form-group -->
        <!--///SAYFA URL-->
        <h4><button class="btn btn-default btn-block" onclick="erisebilen_sayfa_ekle();"><i class="fa fa-download"></i>Sayfayı Sisteme Ekle</button></h4>

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
        <h4><button class="btn btn-default btn-block" onclick="erisilebilen_sayfa_sil();"><i class="fa fa-times"></i>Sayfayı Sistemden Sil</button></h4>


    </aside>
    <script>
        $(function ($) {
            $(".help_for_use_website").click(function () {
                tour.init();
                tour.restart();
                return false;
            });

            var tour = new Tour({
                basePath: '',
                template: "<div class = 'popover tour'>"
                        + "<div class = 'arrow'></div><h3 class = 'popover-title alert-warning'></h3>"
                        + "<div class = 'popover-content change-color'></div>"
                        + "<div class = 'popover-navigation  text-center'>"
                        + "<button class = 'btn btn-primary' data-role = 'prev'><i class = 'fa fa-step-backward'></i> <?= ("Önceki") ?></button><span data-role = 'separator'> | </span><button class = 'btn btn-primary' data-role = 'next'><?= ("Sonraki") ?> <i class = 'fa fa-step-forward'></i></button>"
                        + "<button class = 'btn btn-block btn-default' data-role = 'end'><?= ("Tanıtımı Sonlandır") ?></button></div></nav></div>",
                onStart: function () {
                },
                onEnd: function () {
                },
                steps: [<?= $tour_step ?>]
            });
            // init tour
            tour.init();
        });
    </script>
    <style>
        .popover-navigation{
            border: 2px solid #c1c1c1;
            text-align: center;
        }
        .tour-step-background {
            background: transparent;
            border: 4px solid #f6a828;
        }
        .tour-backdrop {
            opacity:0.5;
        }
        .popover{
            max-width: 100%; /* Max Width of the popover (depending on the container!) */
        }
        .popover-title{
            text-align: center;
        }
    </style>
    <?php
}
?>