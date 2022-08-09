<?php

function SetClickedMenu($menu_id) {
    global $ClickedMenuId;
    $subMenuItem = $GLOBALS["db"]->fetchRow("SELECT mainmenu.id FROM yt_menu submenu, yt_menu mainmenu WHERE mainmenu.aktif_mi AND submenu.aktif_mi AND mainmenu.id = submenu.ana_menu_id AND submenu.id = ?", $menu_id);

    if (isset($subMenuItem->id)) {//Üsstünde menü varsa eklenir ve devam eder 
        array_push($ClickedMenuId, $subMenuItem->id);
    }
}

$page_url = $pageUrl;
$ClickedMenuId = array();
if (isset($_GET["frame"])) {
    $page_url = $page_url . "?frame=" . $_GET["frame"];
}
//if($page_url == "/hesap/index.php"){
//   $page_url =  "/hesap/sifre-guncelle";
//}
$subMenuItems = $GLOBALS["db"]->fetchAll("SELECT id FROM yt_menu WHERE aktif_mi AND yt_menu.language = ? AND link = ? ", array($selected_language, $page_url));

foreach ($subMenuItems as $key => $subMenuItem) {
    array_push($ClickedMenuId, $subMenuItem->id);
    SetClickedMenu($subMenuItem->id);
}
?>
<style>
    .have-children > a::after{
        content: " \f0d9";
        font-family: FontAwesome;
    }
    .have-children.active > a::after{
        content: " \f0d7";
        font-family: FontAwesome;
    }
</style>
<section class="sidebar">
    <!-- Sidebar user panel -->
    <!--          <div class="user-panel">
                <div class="pull-left image">
                  <img src="<?php // echo ASSETS_DIR                                     ?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                  <p>Alexander Pierce</p>
                  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
              </div>-->
    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
            <input type="text" name="q" class="form-control" autocomplete="off" id="menu-search" placeholder="<?= __("Menu Arama"); ?>" onkeyup="menu_search();">
                <span class="input-group-btn">
                    <button type="button" name="search" id="search-btn" onclick="menu_search();" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
        </div>
    </form>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <!-- MENU SECTION -->
    <ul class="sidebar-menu">
        <?php
        $menuItems = get_menu_items();
        $menu_html = "";
        foreach ($menuItems as $menuItem) {
            if (isset($_SESSION["kullanici_adi"])) {
                if ($menuItem->disariya_acik_mi == 2) {
                    
                } else {
                    $ana_menu_class = $menuItem->menu_class;
                    if (in_array($menuItem->id, $ClickedMenuId)) {
                        $ana_menu_class = ' class="active treeview" ';
                    }
                    $menu_url = $menuItem->link;
                    if ($menu_url[0] == "/") {
                        $menu_url = BASE_URL_MENU . $menu_url;
                    }
                    $menu_html .= '<li ' . $ana_menu_class . '>';
                    $menu_html .= '<a href="' . BASE_URL_MENU . $menuItem->link . '" ' . $menuItem->target . '>' . $menuItem->sol_icon . '<span>' . $menuItem->adi . '</span>' . $menuItem->sag_icon . '</a>';
                    $menu_html .= get_submenu_items($menuItem->id);
                    $menu_html .= '</li>';
                }
            } else if ($menuItem->disariya_acik_mi == 1 || $menuItem->disariya_acik_mi == 2) {
//        $page = str_replace('..', SYSTEM_URL, $menuItem->link) . '/' . basename($_SERVER['PHP_SELF']);
                $ana_menu_class = $menuItem->menu_class;
                if (in_array($menuItem->id, $ClickedMenuId)) {
                    $ana_menu_class = ' class="active treeview" ';
                }
                $menu_url = $menuItem->link;
                if ($menu_url[0] == "/") {
                    $menu_url = BASE_URL_MENU . $menu_url;
                }
                $menu_html .= '<li ' . $ana_menu_class . '>';
                $menu_html .= '<a href="' . BASE_URL_MENU . $menuItem->link . '" ' . $menuItem->target . '>' . $menuItem->sol_icon . '<span>' . $menuItem->adi . '</span>' . $menuItem->sag_icon . '</a>';
                $menu_html .= get_submenu_items($menuItem->id);
                $menu_html .= '</li>';
            }
        }
       
//        $menu_html .= '<li class="treeview help_for_use_website"><a href="/"><small class="label pull-left bg-purple"><i class="fa fa-question-circle"></i></small><span>' . __("Kullanımı") . '</span></a></li>';
        echo $menu_html;
        ?>


    </ul>
    <!--END MENU SECTION -->
</section>






<?php
/*
 * HER BİR SAYFANIN İD KISMI MENU ID OLARAK TANIMLANIR
 * BU MENU İD KARŞILIK BU SAYFANIN YAPTIĞI İŞLEMLERİ ÇEKEBİLİRSİNİZ
 */

function get_menu_items() {
    global $kullaniciId, $selected_language;
    //EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
    $menuItemsSQL = 'CALL ana_menuleri_getir( ?, ? )';
//    $menuItemsSQL = 'SELECT * FROM yt_menu WHERE ISNULL(yt_menu.ana_menu_id)';
    try {
        $menuItems = $GLOBALS['db']->fetchAll($menuItemsSQL, array($kullaniciId, $selected_language));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }

    return $menuItems;
}

function get_submenu_items($menu_id) {
    global $kullaniciId, $pageUrl, $selected_language, $ClickedMenuId;
    $page_url = $pageUrl;
    if (isset($_GET["frame"])) {
        $page_url = $page_url . "?frame=" . $_GET["frame"];
    }
    //EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
    $subMenuItemsSQL = 'CALL alt_menuleri_getir(?, ?, ? )';
//    $subMenuItemsSQL = 'SELECT * FROM yt_menu WHERE yt_menu.ana_menu_id = ? ';
    try {
        $subMenuItems = $GLOBALS['db']->fetchAll($subMenuItemsSQL, array($kullaniciId, $menu_id, $selected_language));
    } catch (Zend_Db_Exception $ex) {
        log::DB_hata_kaydi_ekle(__FILE__, $ex);
    }
    $menu_html = "";
    foreach ($subMenuItems as $subMenuItem) {
        if (isset($_SESSION["kullanici_adi"]) && $subMenuItem->disariya_acik_mi == 2) {
            
        } else {
            $alt_menu_class = $subMenuItem->menu_class;
            $menu_url = $subMenuItem->link;
            if ($menu_url[0] == "/") {
                $menu_url = BASE_URL_MENU . $menu_url;
            }
            if (in_array($subMenuItem->id, $ClickedMenuId)) {
                $alt_menu_class = ' active';
            }
            $submenu_html = get_submenu_items($subMenuItem->id);
            if ($submenu_html != "") {
                $alt_menu_class .= " have-children";
            }
            $menu_html .= '<li class="' . $alt_menu_class . '">';
            $menu_html .= '<a href="' . $menu_url . '" ' . $subMenuItem->target . '>' . $subMenuItem->sol_icon . '<span>' . $subMenuItem->adi . '</span>' . $subMenuItem->sag_icon . '</a>';
            $menu_html .= $submenu_html;
            $menu_html .= '</li>';
        }
    }
    if ($menu_html != "") {
        $menu_html = '<ul class="treeview-menu ' . $alt_menu_class . '">' . $menu_html . '</ul>';
    }
    return $menu_html;
}

//function is_submenu_click($menu_id) {
//    global $pageUrl;
//    $page_url = $pageUrl;
//    if (isset($_GET["frame"])) {
//        $page_url = $page_url . "?frame=" . $_GET["frame"];
//    }
//
//    //EĞER DEVELOPER İSE TÜM YETKİLER AÇIK HALE GELİR.
//    $subMenuItemsSQL = 'SELECT id FROM yt_menu WHERE aktif_mi AND link = ? AND ana_menu_id = ? ';
//    try {
//        $subMenuItems = $GLOBALS['db']->fetchAll($subMenuItemsSQL, array($page_url, $menu_id));
//    } catch (Zend_Db_Exception $ex) {
//        log::DB_hata_kaydi_ekle(__FILE__, $ex);
//    }
//    if (count($subMenuItems) === 0) {
//        $subMenuItemsSQL = 'SELECT ana_menu.id FROM yt_menu ana_menu, yt_menu alt_menu WHERE ana_menu.aktif_mi AND alt_menu.aktif_mi AND ana_menu.id = alt_menu.ana_menu_id AND alt_menu.link = ? AND ana_menu.ana_menu_id = ?';
//        try {
//            $subMenuItems = $GLOBALS['db']->fetchAll($subMenuItemsSQL, array($page_url, $menu_id));
//        } catch (Zend_Db_Exception $ex) {
//            log::DB_hata_kaydi_ekle(__FILE__, $ex);
//        }
//        if (count($subMenuItems) === 0) {
//            return FALSE;
//        } else {
//            return TRUE;
//        }
//    } else {
//        return TRUE;
//    }
//}
?>

<script>

    function menu_search() {
        var search_txt = $('#menu-search').val();
        if (search_txt == "") {
            $(".sidebar-menu li").removeClass("active");
            $(".sidebar-menu li").show();
        } else {
            $(".sidebar-menu li").each(function (index) {
                var verinin_tamamami = $(this).find("span").html();
                if (verinin_tamamami.toLowerCase().indexOf(search_txt.toLowerCase()) == -1) {
                    $(this).removeClass("active");
                    $(this).hide();
                } else {
                    $(this).addClass("active");
                    $(this).parents().show();
                    $(this).show();

                }
            });
        }
    }



</script>

<!--    <li class="header">MAIN NAVIGATION</li>
    <li class="active treeview">
        <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Layout Options</span>
            <span class="label label-primary pull-right">4</span>
        </a>
        <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
            <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
            <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
        </ul>
    </li>
    <li>
        <a href="pages/widgets.html">
            <i class="fa fa-th"></i> <span>Widgets</span> <small class="label pull-right bg-green">new</small>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Charts</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
            <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
            <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
            <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-laptop"></i>
            <span>UI Elements</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
            <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
            <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
            <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
            <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
            <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-edit"></i> <span>Forms</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
            <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
            <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-table"></i> <span>Tables</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
            <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
        </ul>
    </li>
    <li>
        <a href="pages/calendar.html">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <small class="label pull-right bg-red">3</small>
        </a>
    </li>
    <li>
        <a href="pages/mailbox/mailbox.html">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <small class="label pull-right bg-yellow">12</small>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-folder"></i> <span>Examples</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
            <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
            <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
            <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
            <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
            <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
            <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
            <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
            <li>
                <a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
        </ul>
    </li>
    <li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
    <li class="header">LABELS</li>
    <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
    <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
    <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>-->

<!--END MENU SECTION -->

