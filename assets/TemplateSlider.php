

<!-- Indicators -->
<?php
if (isset($_GET["pages"])) {

    $ItemsSQL = "SELECT st_pages.page_content FROM st_pages WHERE st_pages.is_active AND st_pages.page_url = ? AND st_pages.page_language = ? ";
    try {
        $selected_page_information = $GLOBALS['db']->fetchRow($ItemsSQL, array("?pages=" . $_GET["pages"], $selected_language));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    if (!$selected_page_information) {
        $ItemsSQL = "SELECT st_pages.page_content FROM st_pages WHERE st_pages.is_active AND st_pages.page_url = ? ";
        try {
            $selected_page_information = $GLOBALS['db']->fetchRow($ItemsSQL, "?pages=" . $_GET["pages"]);
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }
}
$color_array = array("#74C390", "#51BCE8", "#E46653", "#FF8C00", "#7FFFD4", "#0000FF", "#8A2BE2", "#A52A2A", "#D2691E", "#6495ED", "#FFF8DC", "#DC143C", "#E46653", "#FFD700", "#00FFFF", "#778899", "#FF1493", "#ADFF2F", "#FF69B4");

try {
    $ItemsSQL = "SELECT  st_pages.page_language, st_pages.page_country, st_pages.page_url, st_pages.slider_image_url, st_pages.slider_video_description, st_pages.slider_video_url, st_pages.slider_video_description, st_pages.slider_header, st_pages.slider_title FROM st_pages "
            . "WHERE st_pages.is_active AND st_pages.page_language = ? ";
    $sliderpageItems = $GLOBALS['db']->fetchAll($ItemsSQL, $selected_language);
    $carousel_indicators = "";
    $carousel_indicators_item = "";
    $selected_item_css_color = "";
    foreach ($sliderpageItems as $key => $pageInformation) {
        $is_active = "";
        if (isset($_GET["pages"]) && isset($selected_page_information->page_content)) {
            if ($pageInformation->page_url == "?pages=" . $_GET["pages"]) {
                $is_active = "active";
                $selected_item_css_color = $color_array[$key];
            }
        } else if ($key == 0) {
            $is_active = "active";
        }
        if (isset($_SESSION["kullanici_adi"])) {
            $pageInformation->slider_title = str_replace("SiverekEpostaKullanıcıAdı", $kullaniciAdi, $pageInformation->slider_title);
        }
        if ($pageInformation->slider_image_url != NULL && $pageInformation->slider_image_url != "") {//Image
            $carousel_indicators .= '<li data-target="#template-bootstrap-slider" data-slide-to="' . $key . '" class = "' . $is_active . '"></li>';
            $carousel_indicators_item .= '<div class="item ' . $is_active . '" onclick="location.href=' . "'" . $pageInformation->page_url . "'" . ' ">
                                                        <img src="' . $pageInformation->slider_image_url . '">
                                                            <div class="carousel-caption">
                                                                <a class="btn btn-start" href="' . $pageInformation->page_url . '">' . $pageInformation->slider_header . '</a>
                                                                <p>' . $pageInformation->slider_title . '</p>
                                                            </div>
                                                    </div>';
        } else if ($pageInformation->slider_video_url != NULL && $pageInformation->slider_video_url != "") {//Video
            $carousel_indicators .= '<li data-target="#template-bootstrap-slider" data-slide-to="' . $key . '" class = "' . $is_active . '"></li>';
            if ($pageInformation->slider_video_description == "" || $pageInformation->slider_video_description == "Youtube") {//youtube
                $carousel_indicators_item .= '<div class="item ' . $is_active . '" >
                                                        <iframe class="video start_stop_video' . $key . '-video"  src="https://www.youtube.com/embed/' . $pageInformation->slider_video_url . '?rel=0&amp;controls=0&amp;showinfo=0&enablejsapi=1&version=3&playerapiid=ytplayer" frameborder="0" allowfullscreen allowscriptaccess="always"></iframe>
                                                        <div class="carousel-caption">
                                                                <div class="start_stop_video' . $key . '-video_title">' . $pageInformation->slider_header . '</div>
                                                                <p class="start_stop_video' . $key . '-video_title">' . $pageInformation->slider_title . '</p>
                                                            <a class="btn btn-start start_stop_video" data-video="' . $key . '">' . __('Oynatmak İçin Tıklayınız') . '</a>
                                                        </div>
                                                    </div>';
            }
        } else {
            $carousel_indicators .= '<li data-target="#template-bootstrap-slider" data-slide-to="' . $key . '" class = "' . $is_active . '"></li>';
            $carousel_indicators_item .= '<div class="item ' . $is_active . '" onclick="location.href=' . "'" . $pageInformation->page_url . "'" . ' ">
                                                        <img src="' . $pageInformation->slider_image_url . '">
                                                            <div class="carousel-caption">
                                                                <a class="btn btn-start" href="' . $pageInformation->page_url . '">' . $pageInformation->slider_header . '</a>
                                                                <p>' . $pageInformation->slider_title . '</p>
                                                            </div>
                                                    </div>';
        }
    }

    if (count($sliderpageItems) != 0) {
        echo '<div id="template-bootstrap-slider" class="carousel slide" data-ride="carousel">';
        echo '<ol class="carousel-indicators">' . $carousel_indicators . '</ol>';
        echo '<div class="carousel-inner" role="listbox">' . $carousel_indicators_item . '</div>';
        echo ' <a class="btn left carousel-control" href="#template-bootstrap-slider" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="btn right carousel-control" href="#template-bootstrap-slider" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div>';
    }
} catch (Zend_Db_Exception $ex) {
    log::DB_hata_kaydi_ekle(__FILE__, $ex);
}
?>



<style>
    .transparent{

        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=70)";       /* IE 8 */
        filter: alpha(opacity=70);  /* IE 5-7 */
        -moz-opacity: 0.7;          /* Netscape */
        -khtml-opacity: 0.7;        /* Safari 1.x */
        opacity: 0.7; 
    }
    .transparent:hover{
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";       /* IE 8 */
        filter: alpha(opacity=100);  /* IE 5-7 */
        -moz-opacity: 1;          /* Netscape */
        -khtml-opacity: 1;        /* Safari 1.x */
        opacity: 1; 
    }
    /*
http://muratdelen.com/
    */
    #template-bootstrap-slider{
        width: 100%;
        height: 50vh;

    }
    .carousel-indicators{
        margin-bottom: 0;
    }
    .carousel-inner .carousel-caption{
        top: 25%;
        text-shadow: 1px 1px 10px #605ca8;
        padding: 0;
    }

    .video,
    .carousel-inner > .item > img,
    .carousel-inner > .item > a > img {
        width: 100%;
        height: 50vh;
        margin: auto;
        padding-bottom: 25px;
        /*padding-top: 50px;*/
    }
    .carousel-indicators{
        bottom: 0px;
    }
    <?php
    foreach ($color_array as $key => $color) {
        echo '.item:nth-child(' . ($key + 1) . ') {background: ' . $color . ';} ';
    }
    ?>
    .carousel-control {
        top: 50%;
        height: 51px;
        width: 51px;
        border-radius: 50%;
        border:2px solid #fff;  
        text-align: center;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -ms-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
    }

    .btn-start{
        color:#fff;
        font-weight: bold; 
        background: #605ca8;
        /*    background-image: -webkit-linear-gradient(top, #75c6ff, #206c85);
            background-image: -moz-linear-gradient(top, #75c6ff, #206c85);
            background-image: -ms-linear-gradient(top, #75c6ff, #206c85);
            background-image: -o-linear-gradient(top, #75c6ff, #206c85);
            background-image: linear-gradient(to bottom, #75c6ff, #206c85);*/
        -webkit-box-shadow: 0 0 2px 1px rgba(0,0,0,0.2);
        -moz-box-shadow: 0 0 2px 1px rgba(0,0,0,0.2);
        box-shadow: 0 0 2px 1px rgba(0,0,0,0.2);
        -webkit-border-radius: 20;
        -moz-border-radius: 20;
        border-radius: 20;
    }
</style>

<script>


    //VİDEO KONTROL
    $(".start_stop_video").click(function () {
        var this_button = this;
        var this_button_number = $(this).attr("data-video");
        $(".start_stop_video" + this_button_number + "-video_title").slideToggle(function () {
            if ($(this).is(":visible")) {
                $('.carousel').carousel('cycle');
                $(".start_stop_video" + this_button_number + "-video")[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
                $(this_button).html("<?= __('Oynatmak İçin Tıklayınız') ?>");
                $(this_button).removeClass('transparent');
                $("#template-bootstrap-slider").css("height", "50vh");
                $(".video").css("height", "50vh");
                $(".carousel-inner > .item > img").css("height", "50vh");
                $(".carousel-inner > .item > a > img ").css("height", "50vh");
            } else {
                $('.carousel').carousel('pause');
                $(".start_stop_video" + this_button_number + "-video")[0].contentWindow.postMessage('{"event":"command","func":"' + 'unMute' + '","args":""}', '*');
                $("#slider-voice-control").slider("value", 50);
                $(".start_stop_video" + this_button_number + "-video")[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
                $(this_button).html("<?= __('Durdurmak İçin Tıklayınız') ?>");
                $(this_button).addClass('transparent');
                $("#template-bootstrap-slider").css("height", "90vh");
                $(".video").css("height", "90vh");
                $(".video").css("height", "90vh");
                $(".carousel-inner > .item > img").css("height", "90vh");
                $(".carousel-inner > .item > a > img ").css("height", "90vh");

            }
        });
    });
     $(".carousel").on("touchstart", function (event) {
        var xClick = event.originalEvent.touches[0].pageX;
        $(this).one("touchmove", function (event) {
            var xMove = event.originalEvent.touches[0].pageX;
            if (Math.floor(xClick - xMove) > 5) {
                $(".carousel").carousel('next');
            } else if (Math.floor(xClick - xMove) < -5) {
                $(".carousel").carousel('prev');
            }
        });
        $(".carousel").on("touchend", function () {
            $(this).off("touchmove");
        });
    });
</script>

<?php
// seçilen sayfası alta gösterir.
if (isset($_GET["pages"])) {
    if (isset($selected_page_information->page_content)) {
        if (isset($_SESSION["kullanici_adi"])) {
            $selected_page_information->page_content = str_replace("SiverekEpostaKullanıcıAdı", $kullaniciAdi, $selected_page_information->page_content);
        }
        echo '<div class="row" style="background: ' . $selected_item_css_color . ';">
            <div class="col-md-12"><div class="box box-purple">' . $selected_page_information->page_content . '</div></div>
        </div>';
    } else {
        echo '<div class="row" style="background: #f00;">
            <div class="col-md-12"><div class="box box-danger"></div></div>
        </div>';
    }
}