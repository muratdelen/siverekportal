<?php

require_once '../../../../../lib/config.php';
require_once '../../../../../lib/input_filter.php';
require_once '../../../../../lib/functions.php';

include_once '../../../../../assets/TemplateHeader.php';
include_once '../../../../../assets/TemplateMenu.php';
if ($page_information->is_show_slider) {
    include_once '../../../../../assets/TemplateSlider.php';
} 
echo '<div style="padding-top:100px;height:50px;"><div>';
echo '<div class="container-fluid">';

include_once '../../../../../assets/TemplateSearch.php';

?>

<script>

// Prevent from DOM clobbering.
if ( typeof window.opener._cke_htmlToLoad == 'string' ) {
        var preview_html_string = window.opener._cke_htmlToLoad;
        var body = preview_html_string.match(/<body[^>]*>[\s\S]*<\/body>/gi);
	document.write(body );
//$("#change-preview-text").html(window.opener._cke_htmlToLoad);
//document.write(window.opener.CKEDITOR.instances.editor.getData());

//	delete window.opener._cke_htmlToLoad;
}

</script>

<?php

configuration::getMyTemplatePageHeaderAfter();
configuration::getPageHeaderAfter("jQueryFlag");

configuration::getPageHeaderAfter("FastClick");
configuration::getPageHeaderAfter("Select2");

configuration::getPageHeaderAfter("jQueryUI");
configuration::getPageHeaderAfter("InputMask");

configuration::getPageHeaderAfter("jQuery-Mask");
configuration::getPageHeaderAfter("DataTables");
configuration::getPageHeaderAfter("DataTablesColumnFilter");
configuration::getPageHeaderAfter("Jquery Validation");
configuration::getPageHeaderAfter("ckeditor");
configuration::getPageHeaderAfter("Color Picker");

if ($page_information->is_show_send_email) {
    require_once '../../../../../lib/google_captcha/autoload.php';
    include_once '../../../../../assets/TemplateContact.php';
}

if ($page_information->is_show_maps) {
    include_once '../../../../../assets/TemplateMaps.php';
}
echo '</div>';
include_once '../../../../../assets/TemplateFooter.php';
echo '</body> </html>';
?>
