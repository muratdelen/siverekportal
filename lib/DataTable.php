<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataTable
 *
 * @author taha
 */
class DataTable {

    //put your code here
    private $tableId;
    private $tableHeaders;
    private $orderColumn = 0;
    private $orderType = 'asc';
    private $tableData;
    private $url;
    private $tableFooters;
    private $filters;
    private $buttons;
    private $processButtons;
    private $ajaxButtonUrls;
    private $hasButton;
    private $buttonUrls;
    private $buttonConditions;

    public function __construct($options) {

        //id girilmemişse tablo idsi dataTable olacak
        $this->tableId = (isset($options['id']) ? $options['id'] : 'dataTable');

        // tablo başlıkları girilmelidir
        if (isset($options['tableHeaders']) && count($options['tableHeaders']) > 0) {
            $this->tableHeaders = $options['tableHeaders'];
        } else {
            throw new Exception('Tablo Başlıkları Girilmelidir.');
        }

        //Kolon Sıralama
        if (isset($options['order'])) {
            if (is_array($options['order']) && is_numeric($options['order'][0]) && in_array($options['order'][1], array('asc', 'desc'))) {
                $this->orderColumn = $options['order'][0];
                $this->orderType = $options['order'][1];
            } else {
                throw new Exception('Order verisi girilmelidir.');
            }
        }


        //post için kullanılan buttonlar
        if (isset($options['processButtons']['buttonUrls'])) {
            $this->buttonUrls = $options['processButtons']['buttonUrls'];
        }

        //ajax için kullanılan butonlar
//        if (isset($options['processButtons']['buttonConditions'])) {
//            $this->buttonConditions = $options['processButtons']['buttonConditions'];
//        }
        //ajax için kullanılan butonlar
        if (isset($options['processButtons']['ajaxButtonUrls'])) {
            $this->ajaxButtonUrls = $options['processButtons']['ajaxButtonUrls'];
        }


        //tablo verisi varsa kontrol et , tablo verisi varsa url olmamalı
        if (isset($options['tableData'])) {

//                if (count($options['tableData']) <= 0) {
//                    throw new Exception('Tablo verisi boş olamaz.');
//                }

            if (isset($options['url'])) {
                throw new Exception('Hem tablo verisi hem url girilemez.');
            }

            // işlem buttonları
            if (isset($options['processButtons'])) {
                if (!isset($options['processButtons']['buttonUrls'])) {
                    if (!isset($options['processButtons']['ajaxButtonUrls'])) {
                        throw new Exception('processButtons ajaxButtonUrls parametresi geçerli bir url olmalıdır.');
                    } elseif (isset($options['processButtons']['buttons'])) {
                        if (is_array($options['processButtons']['buttons'])) {
                            $this->processButtons = $options['processButtons'];
                        } else {
                            throw new Exception('processButtons buttons parametresi dizi şeklinde olmalıdır.');
                        }
                    }
                }

                $this->processButtons = $options['processButtons'];
                $this->tableHeaders[] = 'İşlem';
                $this->hasButton = true;
            }


            $this->tableData = $options['tableData'];
        } elseif (!isset($options['url']) || !file_exists($options['url'])) {

            throw new Exception('Geçerli bir Url girilmelidir.');
        } else {

            $this->url = $options['url'];
        }




        // tablo footerları
        if (isset($options['tableFooters'])) {
            if (is_array($options['tableFooters']) && count($options['tableFooters']) <= count($options['tableHeaders'])) {
                $this->tableFooters = $options['tableFooters'];
            } else {
                throw new Exception('tableFooters parametreleri dizi şeklinde olmalıdır ve sütun sayısını geçmemelidir.');
            }
        }

        //tablodaki filtreler
        if (isset($options['filters'])) {
            if (is_array($options['filters']) && count($options['filters']) <= count($options['tableHeaders'])) {
                $this->filters = $options['filters'];
            } else {
                throw new Exception('Filtre parametreleri dizi şeklinde olmalıdır ve sütun sayısını geçmemelidir.');
            }
        }

        // tablodaki buttonlar
        if (isset($options['buttons'])) {
            if (is_array($options['buttons'])) {
                $this->buttons = $options['buttons'];
            } else {
                throw new Exception('buttons parametresi dizi olmalıdır.');
            }
        }
    }

    public function get_data_table_selected() {
        global $sayfaIslemleriId;
        $dataTable = '<table id="' . $this->tableId . '" class="table table-striped table-bordered dt-responsive nowrap  dataTable" style="width:100%">';

        // Tablo başlıkları
        $dataTable .='<thead><tr> ';
        $dataTable .= '<th>Seçiniz</th> ';
        foreach ($this->tableHeaders as $header) {
            $dataTable .= '<th>' . $header . '</th> ';
        }
        $dataTable .='</tr></thead>';

        //Tablo süzgeçleri
        $dataTable .='<tfoot><tr> ';
        if (isset($this->tableFooters)) {
            $dataTable .= '<th>Seçiniz</th> ';
            foreach ($this->tableFooters as $footer) {
                $dataTable .= '<th>' . $footer . '</th> ';
            }
        }
        $dataTable .='</tr></tfoot>';

        //varsa tablo verisi
        if (isset($this->tableData)) {
            $dataTable .='<tbody>';

            foreach ($this->tableData as $data) {

                $dataTable .= '<tr>';
                $dataTable .= '<td><button class="btn btn-warning" onclick="get_selected_value(this)" value="' . mcrypt($data->id, $_SESSION['key']) . '">Seç</button></td>';
                foreach ($data as $key => $dataVal) {
                    if ($key != 'id') {
                        if (startsWith($key, 'condition')) {
                            $anahtar = explode('_', $key)[1];
                            $this->buttonConditions[$anahtar] = $dataVal;
                        } else {
                            $dataTable .= '<td><h5 class="table-data">' . $dataVal . '</h5></td>';
                        }
                    }
                }
                $dataTable .= '</tr>';
            }
            $dataTable .='</tbody>';
        } else {
            $dataTable .='<tbody> </tbody>';
        }
        $dataTable .= '</table>';
        return ' <div style="overflow: auto; ">' . $dataTable . ' </div>';
    }

    function get_datatable_script_selected() {
        global $selected_language;
        if (isset($this->filters)) {
            $aoColumns = "var aoColumns = [";
            $aoColumns .= "{ type:'text'},";
            foreach ($this->filters as $filter) {
                $aoColumns .= '{ type:';
                if (is_array($filter)) {
                    $aoColumns .="'select', values: [";
                    foreach ($filter as $val) {
                        $aoColumns .= "'$val' , ";
                    }
                    $aoColumns = rtrim($aoColumns, ',');
                    $aoColumns .= ']';
                } else {
                    $aoColumns .= "'$filter'";
                }
                $aoColumns .= "},";
            }
            $aoColumns = rtrim($aoColumns, ',');

            $aoColumns .= "];";
            $columnFilters = ".columnFilter({aoColumns: aoColumns})";
        } else {
            $aoColumns = '';
            $columnFilters = '';
        }


        if (isset($this->url)) {
            $serverSideConfig = " 
            'processing': true,
            'bServerSide': true,
            'sAjaxSource': '$this->url',";
        } else {
            $serverSideConfig = '';
        }



        $script = "<script>
            var row_index;
            var datatable;
            $( document ).ready(function() {
            $aoColumns 
         datatable = $('#$this->tableId').dataTable({
        $serverSideConfig 
        'autoWidth': false,
        'language': {
            'url': '/assets/plugins/datatables/i18n/" . $selected_language . ".json'
        },
//        'stateSave': true,";


        // ajax buttonları varsa buttonlara işlev kazandır
        if (isset($this->ajaxButtonUrls)) {

            $script .="
                   'aoColumnDefs': [{
                'aTargets': [" . (count($this->tableHeaders) - 1) . "],
                'fnCreatedCell': function (nTd, sData, oData, iRow, iCol){

                    $(nTd).attr('id', 'process');
                    
                    jQuery('a', nTd).click(function () {
                         

                        var process = jQuery(this).attr('id'); ";

            $script .= "url ='" . json_encode($this->ajaxButtonUrls) . "'
                            purl = $.parseJSON(url)[''+process+'']; ";


            $script .= " $.ajax({
                            url: ''+purl+'',
                            type: 'POST',
                            data: new Array({name: 'islem', value: process},{name: 'id', value: $(this).closest('div').find('input').val()}),
                            success: function (data){  

                                

                                if(jQuery.isPlainObject(data)){
                                    data = jQuery.parseJSON(data);
                                  
                                   $('#detailsModal').on('hidden.bs.modal', function () {

                                        datatable.fnSettings().bSort = false;
                                        datatable.fnPageChange(pageId, true);

                                    if(data['updated_data']){
                                        datatable.fnUpdate( jQuery.parseJSON(data['updated_data'])['aaData'][0] , row_index , null , false  ); 
                                     }
                               
                            });

                                }else{

                                    $('#'+process+'-dialog .modal-body').html(data);
                                    $('#'+process+'-dialog').modal('show');

                                }

                            }, error: function (jqXHR, textStatus, errorThrown)
                            {

                            }
                            
                        });
                    });
                }
            }],
                        ";
        }

        $script .= " 
                'fnDrawCallback': function(){

           pageId = this.fnPagingInfo().iPage;

        },
        'fnInitComplete': function() {";



//            if (isset($this->Buttons)) {
        $script .= "    
        $('#" . $this->tableId . "_pdf_button').appendTo('#" . $this->tableId . "_filter.dataTables_filter');
        $('#" . $this->tableId . "_excel_button').appendTo('#" . $this->tableId . "_filter.dataTables_filter'); ";
//            }

        $script .= " $('#$this->tableId tbody').on( 'click', 'td', function () {
         var aPos = datatable.fnGetPosition(this);
         row_index = aPos[0];
         col_index = aPos[1];
         
            if ($('#$this->tableId  tbody > tr').eq(row_index).hasClass('selected') == false){
                    $('#$this->tableId .selected').removeClass('selected');
//                  $('#$this->tableId  tbody > tr').eq(row_index).addClass('selected');
                    var rowIndex = ( $(this).closest('tr'));
                    rowIndex.addClass('selected') ;
                }

});
                $('#$this->tableId').show();
         },
         'order': [[$this->orderColumn, '$this->orderType' ]],
    })$columnFilters;
    

});
             function details(id , url){
                         $.ajax({
                            url: url,
                            type: 'POST',
                            data: new Array({name: 'detailId', value:id }),
                            success: function (data){  
                            
                            $('#details-dialog .modal-body').html(data);
                            $('#details-dialog').modal('show');
                               

                            }, error: function (jqXHR, textStatus, errorThrown)
                            {

                            }
                            
                        });
                    };


           function confirmRemove(form){
                $('#remove-dialog').modal('show');
                  
                    $('#btn-remove').click(function(){
                   form.submit();
                }); 
                return false;
                }
 
    </script>";

        return $script;
    }

    public function get_data_table() {
        global $sayfaIslemleriId;
        if (isset($this->tableData)) {
            if (count($this->tableData) <= 0) {

//                    throw new Exception('Tablo verisi boş olamaz.');
//                    echo 'Tablo verisi boş olamaz.';
                echo '<div class="alert alert-warning alert-dismissable col-md-6">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                   <h4><i class="icon fa fa-warning"></i> Uyarı!</h4>
                   Aradığınız kriterde kayıt bulunamadı.
                 </div>';
                return;
            }
        }


        $dataTable = '<table id="' . $this->tableId . '" class="table table-striped table-bordered dt-responsive nowrap  dataTable" style="width:100%">';

        // Tablo başlıkları
        $dataTable .='<thead><tr> ';
        foreach ($this->tableHeaders as $header) {

            $dataTable .= '<th>' . $header . '</th> ';
        }
        $dataTable .='</tr></thead>';


        //Tablo süzgeçleri
        $dataTable .='<tfoot><tr> ';
        if (isset($this->tableFooters)) {
            foreach ($this->tableFooters as $footer) {
                $dataTable .= '<th>' . $footer . '</th> ';
            }
        }
        $dataTable .='</tr></tfoot>';

        //varsa tablo verisi
        if (isset($this->tableData)) {
            $dataTable .='<tbody>';

            foreach ($this->tableData as $data) {

                $dataTable .= '<tr>';

                foreach ($data as $key => $dataVal) {
//                    if (!startsWith($key, 'id')) {
                    if ($key != 'id') {
                        if (startsWith($key, 'condition')) {

                            $anahtar = explode('_', $key)[1];
//                            var_dump($anahtar);
                            $this->buttonConditions[$anahtar] = $dataVal;
                        } else {
                            $dataTable .= '<td><h5 class="table-data">' . $dataVal . '</h5></td>';
                        }
                    }
                }


                if ($this->hasButton === true) {
                    $button = '
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">İşlem <span class="fa fa-caret-down"></span></button>
                                <ul class="dropdown-menu">
                                ' . $this->get_process_buttons(mcrypt($data->id, $_SESSION['key']), $this->buttonConditions) . '
                            </ul>';
                    if (!isset($this->buttonUrls)) {

                        $button .='<input type="hidden"  value="' . mcrypt($data->id, $_SESSION['key']) . '"/>';
                    }
                    $button .='</div>';
                    $dataTable .= '<td>' . $button . '</td>';
                }

//                for ($i = 0; $i < sizeof($data); $i++) {
//                    $dataTable .= '<td>' . $data[$i] . '</td>';
//                }
                $dataTable .= '</tr>';
            }
            $dataTable .='</tbody>';
        } else {
            $dataTable .='<tbody> </tbody>';
        }

        if (isset($this->buttons)) {
            $input = null;
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $input = "<input type='hidden' name='params' value='" . serialize($_POST) . "'/>";
            } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $input = "<input type='hidden' name='params' value='" . serialize($_GET) . "'/>";
            }
            if (in_array('pdf', $this->buttons)) {
                if (in_array(YT_PDF, $sayfaIslemleriId)) {
                    echo '
                        <form method="post" action="pdf.php" id="' . $this->tableId . '_pdf_button" class="table_button">    
                            ' . $input . '
                            <button class="btn btn-danger table_button" type="submit" 
                         data-container="body" data-toggle="tooltip" title="PDFe Dök">
                        PDF</button>
                        </form>';
                }
            }

            if (in_array('excel', $this->buttons)) {
                if (in_array(YT_EXCEL, $sayfaIslemleriId)) {
                    echo '<form method="post" action="excel.php" id="' . $this->tableId . '_excel_button" class="table_button">
                                 ' . $input . '
                                <button class="btn btn-success  table_button" id="btn-excel"
                             data-container="body" data-toggle="tooltip" title="Excele Dök">
                            EXCEL</button></form>';
                }
            }
        }

        $dataTable .= '</table>';

        $dataTable .='<div class="modal fade in" id="remove-dialog" tabindex="-1"  aria-labelledby="detailsModalLabel">
            <div class="modal-dialog" >
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="detailsModalLabel"><i class="icon-zoom-in"></i>  Sil </h4>
                    </div>
                    <div class="modal-body">
                        Silmek istediğinize emin misiniz?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="btn-remove">SİL</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">VAZGEÇ</button>
                    </div>
                </div>
            </div>
        </div>';

        $dataTable .='<div class="modal fade in" id="details-dialog" tabindex="-1"  aria-labelledby="detailsModalLabel">
            <div class="modal-dialog" >
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="detailsModalLabel"><i class="icon-zoom-in"></i>  Detay </h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">KAPAT</button>
                    </div>
                </div>
            </div>
        </div>';

        return ' <div style="overflow: auto; ">' . $dataTable . ' </div>';
    }

    function get_datatable_script() {
        global $selected_language;
        if (isset($this->filters)) {
            $aoColumns = "var aoColumns = [";
            foreach ($this->filters as $filter) {
                $aoColumns .= '{ type:';
                if (is_array($filter)) {
                    $aoColumns .="'select', values: [";
                    foreach ($filter as $val) {
                        $aoColumns .= "'$val' , ";
                    }
                    $aoColumns = rtrim($aoColumns, ',');
                    $aoColumns .= ']';
                } else {
                    $aoColumns .= "'$filter'";
                }
                $aoColumns .= "},";
            }
            $aoColumns = rtrim($aoColumns, ',');

            $aoColumns .= "];";
            $columnFilters = ".columnFilter({aoColumns: aoColumns})";
        } else {
            $aoColumns = '';
            $columnFilters = '';
        }


        if (isset($this->url)) {
            $serverSideConfig = " 
            'processing': true,
            'bServerSide': true,
            'sAjaxSource': '$this->url',";
        } else {
            $serverSideConfig = '';
        }



        $script = "<script>
            var row_index;
            var datatable;
            $( document ).ready(function() {
            $aoColumns 
         datatable = $('#$this->tableId').dataTable({
        $serverSideConfig 
        'autoWidth': false,
         'lengthMenu': [
            [50, 100, 200,  -1],
            [50, 100, 200, 'All'],
        ],
        'language': {
            'url': '/assets/plugins/datatables/i18n/" . $selected_language . ".json'
        },
//        'stateSave': true,";


        // ajax buttonları varsa buttonlara işlev kazandır
        if (isset($this->ajaxButtonUrls)) {

            $script .="
                   'aoColumnDefs': [{
                'aTargets': [" . (count($this->tableHeaders) - 1) . "],
                'fnCreatedCell': function (nTd, sData, oData, iRow, iCol){

                    $(nTd).attr('id', 'process');
                    
                    jQuery('a', nTd).click(function () {
                         

                        var process = jQuery(this).attr('id'); ";

            $script .= "url ='" . json_encode($this->ajaxButtonUrls) . "'
                            purl = $.parseJSON(url)[''+process+'']; ";


            $script .= " $.ajax({
                            url: ''+purl+'',
                            type: 'POST',
                            data: new Array({name: 'islem', value: process},{name: 'id', value: $(this).closest('div').find('input').val()}),
                            success: function (data){  

                                

                                if(jQuery.isPlainObject(data)){
                                    data = jQuery.parseJSON(data);
                                  
                                   $('#detailsModal').on('hidden.bs.modal', function () {

                                        datatable.fnSettings().bSort = false;
                                        datatable.fnPageChange(pageId, true);

                                    if(data['updated_data']){
                                        datatable.fnUpdate( jQuery.parseJSON(data['updated_data'])['aaData'][0] , row_index , null , false  ); 
                                     }
                               
                            });

                                }else{

                                    $('#'+process+'-dialog .modal-body').html(data);
                                    $('#'+process+'-dialog').modal('show');

                                }

                            }, error: function (jqXHR, textStatus, errorThrown)
                            {

                            }
                            
                        });
                    });
                }
            }],
                        ";
        }

        $script .= " 
                'fnDrawCallback': function(){

           pageId = this.fnPagingInfo().iPage;

        },
        'fnInitComplete': function() {";



//            if (isset($this->Buttons)) {
        $script .= "    
        $('#" . $this->tableId . "_pdf_button').appendTo('#" . $this->tableId . "_filter.dataTables_filter');
        $('#" . $this->tableId . "_excel_button').appendTo('#" . $this->tableId . "_filter.dataTables_filter'); ";
//            }

        $script .= " $('#$this->tableId tbody').on( 'click', 'td', function () {
         var aPos = datatable.fnGetPosition(this);
         row_index = aPos[0];
         col_index = aPos[1];
         
            if ($('#$this->tableId  tbody > tr').eq(row_index).hasClass('selected') == false){
                    $('#$this->tableId .selected').removeClass('selected');
//                  $('#$this->tableId  tbody > tr').eq(row_index).addClass('selected');
                    var rowIndex = ( $(this).closest('tr'));
                    rowIndex.addClass('selected') ;
                }

});
                $('#$this->tableId').show();
         },
         'order': [[$this->orderColumn, '$this->orderType' ]],
    })$columnFilters;
    

});
             function details(id , url){
                         $.ajax({
                            url: url,
                            type: 'POST',
                            data: new Array({name: 'detailId', value:id }),
                            success: function (data){  
                            
                            $('#details-dialog .modal-body').html(data);
                            $('#details-dialog').modal('show');
                               

                            }, error: function (jqXHR, textStatus, errorThrown)
                            {

                            }
                            
                        });
                    };


           function confirmRemove(form){
                $('#remove-dialog').modal('show');
                  
                    $('#btn-remove').click(function(){
                   form.submit();
                }); 
                return false;
                }
 
    </script>";

        return $script;
    }

    static function process_table_content($aColumns, $sIndexColumn, $sTable, $sWhere, $columnsName = "") {

        $db = $GLOBALS['db'];
        if ($columnsName == "") {
            $columnsName = $aColumns;
        }
        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
        }


        /*
         * Ordering
         */
        $sOrder = NULL;
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $columnsName[intval($_GET['iSortCol_' . $i])] . "
				 	" . $_GET['sSortDir_' . $i] . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
            if ($sOrder != "") {
                $sOrder .= ", $sIndexColumn desc";
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and mysqli's regex functionality is very limited
         */



        if ($_GET['sSearch'] != "") {
            if (isset($sWhere)) {
                $sWhere .= " AND (";
            } else {
                $sWhere = "WHERE (";
            }

            for ($i = 0; $i < count($columnsName); $i++) {
                $sWhere .= $columnsName[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ') ';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($columnsName); $i++) {
            if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }

                if ($_GET['sSearch_' . $i] == "~") {
                    $_GET['sSearch_' . $i] = "";
                }

                $sWhere .= $columnsName[$i] . " LIKE '%" . $_GET['sSearch_' . $i] . "%' ";
            }
        }

//            var_dump($sWhere);die();
        /*
         * SQL queries
         * Get data to display
         */

        $sQuery = "
		SELECT SQL_CALC_FOUND_ROWS  " . $sIndexColumn . " , " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
		FROM   $sTable
		$sWhere
		$sOrder
                
	";
        $sQuery .= $sLimit;
//        var_dump($sQuery);
        $rResult = $db->query($sQuery);
        $aRow = $rResult->fetchAll();
        /* Data set length after filtering */
        $sQuery = "
		SELECT FOUND_ROWS() as fndrow 
	";

        $rResultFilterTotal = $db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->fetchAll();
        $iFilteredTotal = $aResultFilterTotal[0]->fndrow;

        /* Total data set length */
        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ") as cnt
		FROM   $sTable 
	";

        $rResultTotal = $db->query($sQuery);
        $aResultTotal = $rResultTotal->fetchAll();
        $iTotal = $aResultTotal[0]->cnt;

        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );


        foreach ($aRow as $aVal) {
            $row = array();
//            for ($i = 0; $i < count($columnsName); $i++) {
//                array_push($row, $aVal->$columnsName[$i]);
//            }
            $button = '
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">İşlem <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu">
                        <li><a href="#"  id="add">Ekle</a></li>
                        <li><a href="#" id="remove" >Sil</a></li>
                        <li><a href="#" id="update" >Güncelle</a></li>
                      </ul>
                      <input type="hidden"  value="' . $aVal->id . '"/>

                    </div>';
            unset($aVal->id);
            foreach ($aVal as $key => $value) {
                array_push($row, $value);
            }
            array_push($row, $button);
            array_push($output['aaData'], $row);
        }
        return json_encode($output);
    }

    private function get_process_buttons($id, $buttonConditions) {
        Global $sayfaIslemleriId;
        $buttonList = '';
        foreach ($this->processButtons['buttons'] as $key => $value) {
            $condition = TRUE;
            if (isset($buttonConditions[$key])) {
                $condition = $buttonConditions[$key];
            }
            $button = '';
            if ($condition) {
                if (isset($this->buttonUrls)) {
                    switch ($key) {
                        case 'insert':
                            if (in_array(YT_INSERT, $sayfaIslemleriId)) {
                                $button = '<li>
                             <form method="post" action="' . (isset($this->buttonUrls[$key]) ? $this->buttonUrls[$key] : null) . '">
                              <input type="hidden"  name = "' . $key . '" value="' . $id . '"/>
                                <button  id = "' . $key . '" class="btn btn-warning" type="submit"><i class="fa fa-plus"></i> ' . $value . '</button>
                            </form>
                        </li>';
                            }
                            break;

                        case 'update':
                            if (in_array(YT_UPDATE, $sayfaIslemleriId)) {
                                $button = '<li>
                             <form method="post" action="' . (isset($this->buttonUrls[$key]) ? $this->buttonUrls[$key] : null) . '">
                              <input type="hidden"  name = "' . $key . '" value="' . $id . '"/>
                                <button id = "' . $key . '" class="btn btn-info" type="submit"><i class="fa fa-refresh"></i> ' . $value . '</button>
                            </form>
                        </li>';
                            }
                            break;
                        case 'update_get':
                            if (in_array(YT_UPDATE, $sayfaIslemleriId)) {
                                $button = '<li>
                             <form method="get" action="' . (isset($this->buttonUrls[$key]) ? $this->buttonUrls[$key] : null) . '">
                              <input type="hidden"  name = "update" value="' . $id . '"/>
                                <button id = "' . $key . '" class="btn btn-info" type="submit"><i class="fa fa-refresh"></i> ' . $value . '</button>
                            </form>
                        </li>';
                            }
                            break;

                        case 'remove':
                            if (in_array(YT_DELETE, $sayfaIslemleriId)) {
                                $button = '<li>
                             <form method="post" action="' . (isset($this->buttonUrls[$key]) ? $this->buttonUrls[$key] : null) . '" onSubmit="return confirmRemove(this);">
                              <input type="hidden"  name = "' . $key . '" value= "' . $id . '" />
                                <button id = "' . $key . '" class="btn btn-danger" type="submit" ><i class="fa fa-remove"></i> ' . $value . '</button>
                            </form>
                        </li>';
                            }
                            break;

                        case 'details':
                            if (in_array(YT_VIEW, $sayfaIslemleriId)) {
                                $detailId = "'$id'";
                                $url = "'" . $this->buttonUrls[$key] . "'";
                                $button = '<li><button  class="btn btn-warning" id="details" onClick="return details( ' . $detailId . ',' . $url . ');"><i class="fa fa-bars"></i> ' . $value . '</button></li>';
                            }
                            break;
                        case 'print1':
                            if (in_array(YT_PRINT, $sayfaIslemleriId)) {

                                $button = '<li>
                             <form method="post" action="' . (isset($this->buttonUrls[$key]) ? $this->buttonUrls[$key] : null) . '">
                              <input type="hidden"  name = "' . $key . '" value="' . $id . '"/>
                                <button  id = "' . $key . '" class="btn bg-purple" type="submit"><i class="fa fa-print"></i> ' . $value . '</button>                                    
                            </form>
                        </li>';
                            }
                            break;
                        case 'print2':
                            if (in_array(YT_PRINT, $sayfaIslemleriId)) {

                                $button = '<li>
                             <form method="post" action="' . (isset($this->buttonUrls[$key]) ? $this->buttonUrls[$key] : null) . '">
                              <input type="hidden"  name = "' . $key . '" value="' . $id . '"/>
                                <button  id = "' . $key . '" class="btn bg-purple" type="submit"><i class="fa fa-print"></i> ' . $value . '</button>                                    
                            </form>
                        </li>';
                            }
                            break;
                        case 'print3':
                            if (in_array(YT_PRINT, $sayfaIslemleriId)) {

                                $button = '<li>
                             <form method="post" action="' . (isset($this->buttonUrls[$key]) ? $this->buttonUrls[$key] : null) . '">
                              <input type="hidden"  name = "' . $key . '" value="' . $id . '"/>
                                <button  id = "' . $key . '" class="btn bg-purple" type="submit"><i class="fa fa-print"></i> ' . $value . '</button>                                    
                            </form>
                        </li>';
                            }
                            break;

                        default :
                            $button = '<li>
                             <form method="get" action="' . (isset($this->buttonUrls[$key]) ? $this->buttonUrls[$key] : null) . '">
                              <input type="hidden"  name = "' . $key . '" value="' . $id . '"/>
                                <button id = "' . $key . '" class="btn bg-success" type="submit">' . $value . '</button>
                            </form>
                        </li>';
                            break;
                    }
                } elseif (isset($this->ajaxButtonUrls)) {


                    $button = '<li><a href="#" onClick=" return false;" id="' . $key . '">' . $value . '</a></li>';
                }
            }

            $buttonList .= $button;
        }
        return $buttonList;
    }

    function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

}
