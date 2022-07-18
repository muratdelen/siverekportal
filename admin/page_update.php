<?php
if (isset($_POST['update']) && $_POST['update'] != '') {
    $update_id = mdecrypt($_POST['update'], $_SESSION['key']);
    try {
        $SQL_cumlesi = 'SELECT * FROM st_pages WHERE id = ? ';
        $page_information = $GLOBALS['db']->fetchRow($SQL_cumlesi, $update_id);
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
//    htmlspecialchar_array($page_information);
//var_dump($page_information);die();
    $selected_language = $page_information->page_language;
    $selected_country = $page_information->page_country;
    $remove_string = array("<h1>", "</h1>", "<h2>", "</h2>", "<h3>", "</h3>", "<h4>", "</h4>", "<h5>", "</h5>", "<h6>", "</h6>",);
    $page_information->slider_header = str_replace($remove_string, "", $page_information->slider_header);

//TRANSLATE
//$supported_locales = array('en_US','tr_TR', 'sr_CS', 'de_CH');
    $encoding = 'UTF-8';

    $locale = $selected_language . "_" . $selected_country;
// gettext setup
    T_setlocale(LC_MESSAGES, $locale);
// Set the text domain as 'messages'
    $domain = 'messages';
    T_bindtextdomain($domain, LOCALE_DIR);
    T_bind_textdomain_codeset($domain, $encoding);
    T_textdomain($domain);

    header("Content-type: text/html; charset=$encoding");
} else {
    adminLTE_redirect("Güncelleme Yetkiniz Yoktur.", "warning", BASE_URL . "admin/index.php");
}
?>

<div class="box box-primary">
    <h4 class="box-title" style="margin-left: 5px;">Yeni Sayfa Ekleme</h4>
    <div class="box box-default"></div>
    <div class="box-header with-border">
        <form class="form-horizontal" method="post"  id="form_insert" action="postPage.php">    

            <div class="form-group form-group-sm">
                <label class="col-sm-2 control-label" for="page_name">Sayfa Adı</label>
                <div class="col-sm-10">
                    <input type="text" id="page_name" name = "page_name" value="<?= $page_information->page_name ?>" placeholder=" Giriniz" class="form-control">
                </div>  
            </div>
            <div class="form-group form-group-sm">
                <label class="col-sm-2 control-label" for="page_url">Sayfa Url</label>
                <div class="col-sm-10">
                    <input type="text" id="page_url" name = "page_url" value="<?= $page_information->page_url ?>"  placeholder=" Giriniz" class="form-control" readonly>
                </div>  
            </div>         
            <div class="form-group form-group-sm">
                <!--<label class="col-sm-2 control-label" for="page_content">Sayfa İçeriği</label>-->
                <div class="col-sm-12">
                    <textarea id="page_content" name="page_content" rows="10" cols="80"><?= htmlentities($page_information->page_content) ?></textarea>
                </div>  
            </div>      
            <div class="form-group form-group-sm btn-primary">
                <label class="col-sm-3 control-label" for="file_viewer">Dosyaları Html Olarak Ekle</label>
                <div class="col-sm-7">
                    <input type="text" id="file_viewer" placeholder="Url Tam Adresini Giriniz." class=" form-control">
                </div>     
                <div class="col-sm-2 form-group form-group-sm btn-primary">
                    <button type="button" id="file_viewer_button" class=" btn btn-info btn-flat"><span class="fa fa-fa-eye"></span> <?= __("Dosya Göster") ?></button>
                </div>
            </div>      
            <div class="form-group form-group-sm">
                <label class="col-sm-2 control-label" for="slider_header">Slider Başlık</label>
                <div class="col-sm-10">
                    <input type="text" id="slider_header" name = "slider_header"  value="<?= $page_information->slider_header ?>" placeholder=" Giriniz" class="form-control">
                </div>  
            </div>  
            <div class="form-group form-group-sm">
                <label class="col-sm-2 control-label" for="slider_title">Slider Açıklama</label>
                <div class="col-sm-10">
                    <input type="text" id="slider_title" name = "slider_title" value="<?= htmlentities($page_information->slider_title) ?>" placeholder=" Giriniz" class="form-control">
                </div>  
            </div> 
            <div class="form-group form-group-sm">
                <label class="col-sm-2 control-label" for="slider_image_url">Slider Resim Url</label>
                <div class="col-sm-9">
                    <input type="text" id="slider_image_url" name = "slider_image_url" value="<?= $page_information->slider_image_url ?>" placeholder=" Giriniz" class="elfinder_upload_url form-control">
                </div>                      
                <span class="input-group-btn col-sm-1">
                    <button type="button" class="elfinder_upload_button btn btn-info btn-flat">Uploader</button>
                </span>
            </div>
            <div class="form-group form-group-sm">
                <label class="col-sm-2 control-label" for="slider_video_url">Slider Video Url</label>
                <div class="col-sm-10">
                    <input type="text" id="slider_video_url" name = "slider_video_url" value="<?= $page_information->slider_video_url ?>" placeholder=" Giriniz" class="form-control">
                </div>  
            </div> 
            <div class="form-group form-group-sm">
                <label class="col-sm-2 control-label" for="slider_video_description">Video Çeşidi</label>
                <div class="col-sm-10">
                    <select class="form-control select2"  id="slider_video_description" name="slider_video_description" style="width: 100%;">
                        <option value="Youtube" <?= $page_information->slider_video_description == "Youtube" ? 'selected' : '' ?>>Youtube</option>
                        <option value="Dailymotion" <?= $page_information->slider_video_description == "Dailymotion" ? 'selected' : '' ?>>Dailymotion</option>
                        <option value="Metacafe" <?= $page_information->slider_video_description == "Metacafe" ? 'selected' : '' ?>>Metacafe</option>
                        <option value="Vimeo" <?= $page_information->slider_video_description == "Vimeo" ? 'selected' : '' ?>>Vimeo</option>
                        <option value="Veoh" <?= $page_information->slider_video_description == "Veoh" ? 'selected' : '' ?>>Veoh</option>
                        <option value="Blip" <?= $page_information->slider_video_description == "Blip" ? 'selected' : '' ?>>Blip</option>
                        <option value="izlesene" <?= $page_information->slider_video_description == "izlesene" ? 'selected' : '' ?>>izlesene</option>
                    </select>
                </div>  
            </div>     
            <div class="form-group form-group-sm">
                <label class="col-sm-2 control-label" for="is_active">Sayfa Yayınlansın Mı?</label>
                <div class="col-sm-10">
                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="is_active" onchange="$('.ui-tooltip').remove();"  name = "is_active">
                        <option value="0" <?= $page_information->is_active == 0 ? 'selected' : '' ?> >Yayında Değil</option>
                        <option value="1" <?= $page_information->is_active == 1 ? 'selected' : '' ?> >Yayında</option>
                    </select>
                </div>
            </div>       
            <div class="box-footer">
                <button class="btn bg-orange margin pull-right" type="cancel" onclick="window.location = 'index.php';
                        return false;">Vazgeç</button>
                <input class="btn bg-olive margin pull-right" type="submit" value="Güncelle"/> 
                <input   type="hidden" name = "update" value="<?= $_POST['update']; ?>"/>
            </div>

            <div class="form-group form-group-sm"></div>  
        </form>
    </div>
</div>


<script>
    $(document).ready(function () {
//        CKEDITOR.config.syntaxhighlight_lang = 'applescript', 'actionscript3', 'as3', 'bash', 'shell', 'sh', 'coldfusion', 'cf', 'cpp', 'c', 'c#', 'c-sharp', 'csharp', 'css', 'delphi', 'pascal', 'pas', 'diff', 'patch', 'erl', 'erlang', 'groovy', 'haxe', 'hx', 'java', 'jfx', 'javafx', 'js', 'jscript', 'javascript', 'perl', 'Perl', 'pl', 'php', 'text', 'plain', 'powershell', 'ps', 'posh', 'py', 'python', 'ruby', 'rails', 'ror', 'rb', 'sass', 'scss', 'scala', 'sql', 'tap', 'Tap', 'TAP', 'ts', 'typescript', 'vb', 'vbnet', 'xml', 'xhtml', 'xslt', 'html';      
//        CKEDITOR.config.extraAllowedContent = 'div(*)';

        $('#form_insert').validate({
            rules: {
                page_name: {required: true},
                page_url: {required: true},
                page_menu_name: {required: true}
            },
            messages: {
                page_name: {required: "Sayfa Adı Girilmelidir. "},
                page_url: {required: "Sayfa Url Girilmelidir. "},
                page_menu_name: {required: "menu Adı Girilmelidir. "}
            }
        });


        $("#page_name").change(function () {
            $("#page_url").val(mUrlEncode($("#page_name").val()));
            var formData = new Array();
            formData.push({name: 'post_type', value: 0}, {name: 'insert_page_url', value: $("#page_url").val()});
            $.ajax({
//                async: true,
                url: 'ajax.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data == 1) {
                        adminLTE_alert_remove();
                        adminLTE_alert(false, '<?= __("Sayfa Adı") ?>', '<?= __("Sayfa Adı Kullanılabilir.") ?>', "success");
                    } else {
                        adminLTE_alert_remove();
                        adminLTE_alert(false, '<?= __("Sayfa Adı") ?>', '<?= __("Bu Sayfa Url Önceden Girilmiştir. Lütfen Sayfa Adını Değiştiriniz.") ?>', "danger");
                    }
                }
            });
        });
        $("#page_url").val(mUrlEncode($("#page_name").val()));
    });
    function tr_to_en(sData) {
        var newphrase = sData;
        newphrase = newphrase.replace("Ü", "U");
        newphrase = newphrase.replace("Ş", "S");
        newphrase = newphrase.replace("Ğ", "G");
        newphrase = newphrase.replace("Ç", "C");
        newphrase = newphrase.replace("İ", "I");
        newphrase = newphrase.replace("Ö", "O");
        newphrase = newphrase.replace("ü", "u");
        newphrase = newphrase.replace("ş", "s");
        newphrase = newphrase.replace("ç", "c");
        newphrase = newphrase.replace("ı", "i");
        newphrase = newphrase.replace("ö", "o");
        newphrase = newphrase.replace("ğ", "g");
        return newphrase;
    }

    function mUrlEncode(dataString) {
        var new_encode_string = tr_to_en(dataString);
//        var entities = ['%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D','_'];
//        var replacements = ['!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]"," "];
//        $.each(replacements,function(i,replacement){
//            new_encode_string = new_encode_string.replace(replacement,entities[i]);
//        });
        new_encode_string = new_encode_string.replace(/\!/g, '%21');
        new_encode_string = new_encode_string.replace(/\*/g, '%2A');
        new_encode_string = new_encode_string.replace(/\'/g, '%27');
        new_encode_string = new_encode_string.replace(/\(/g, '%28');
        new_encode_string = new_encode_string.replace(/\)/g, '%3B');
        new_encode_string = new_encode_string.replace(/\;/g, '%3A');
        new_encode_string = new_encode_string.replace(/\:/g, '%40');
        new_encode_string = new_encode_string.replace(/\@/g, '%26');
        new_encode_string = new_encode_string.replace(/\&/g, '%3D');
        new_encode_string = new_encode_string.replace(/\=/g, '%2B');
        new_encode_string = new_encode_string.replace(/\+/g, '%24');
        new_encode_string = new_encode_string.replace(/\$/g, '%40');
        new_encode_string = new_encode_string.replace(/\,/g, '%2C');
        new_encode_string = new_encode_string.replace(/\//g, '%2D');
        new_encode_string = new_encode_string.replace(/\?/g, '%2F');
        new_encode_string = new_encode_string.replace(/\%/g, '%3F');
        new_encode_string = new_encode_string.replace(/\#/g, '%25');
        new_encode_string = new_encode_string.replace(/\[/g, '%5B');
        new_encode_string = new_encode_string.replace(/\]/g, '%5D');
        new_encode_string = new_encode_string.replace(/\ /g, '-');
        return new_encode_string;
    }


</script>