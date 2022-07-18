<?php

/*
 * Author      : Murat DELEN
 * Date        : 01.06.2014
 * Description : açıklama..
 * 
 *              "gereklilik.
 * NOT ÇALIŞMIYORSA include ettiğiniz sayfanın sonunda ?> tagını kontrol edin
 * ve onun altındakileri silin
 * 
 */
require_once 'PHPExcel.php';

class PhpExcelReaderWriter extends PHPExcel {

    public $style_is_other_column = true;
    public $download_format = "xlsx";
    public $setCreator = "System";
    public $setLastModifiedBy = "System";
    public $setTitle = "Office 2007 XLS System Document";
    public $setSubject = "Office 2007 XLS System Document";
    public $setDescription = "System document for Office 2007 XLS.";
    public $setKeywords = "Murat DELEN";
    public $setCategory = "by Murat DELEN";
    public $headerBackgroundColor1 = "FFA500";
    public $headerBackgroundColor2 = "ffc14d";
    public $headerBorderColor = "000000";
    public $cellBackgroundColor1 = "dddddd";
    public $cellBackgroundColor2 = "ffffff";
    public $cellBorderColor = "FFA500";
    public $styleHeader1;
    public $styleHeader2;
    public $styleColumn1;
    public $styleColumn2;
    public $startRowNumberBefore = 0;
    public $startColumnNumberBefore = 0;
    public $startRowNumber = 0;
    public $startColumnNumber = 0;
    public $columnIndex = 0;
    public $fileName = "";
    public $printTitle = "Excel";
    public $printDescription = "Otomatik";
    public $selectedPage = 0;

    function __construct($file_name) {
        parent::__construct();
        $default_border = array(
            'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
            'color' => array('rgb' => $this->headerBorderColor)
        );
        $this->fileName = $file_name;
        $this->printTitle = $file_name;
        $this->styleHeader1 = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => $this->headerBackgroundColor1),
            ),
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $this->styleHeader2 = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => $this->headerBackgroundColor2),
            ),
            'font' => array(
                'bold' => true,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $default_border = array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => $this->cellBorderColor)
        );
        $this->styleColumn1 = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => $this->cellBackgroundColor1),
            ),
            'font' => array(
                'bold' => false,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $this->styleColumn2 = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => $this->cellBackgroundColor2),
            ),
            'font' => array(
                'bold' => false,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
    }

    public function getNameFromNumber($num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

    public function newPage($new_page_name) {
        $objWorkSheet = $this->createSheet($new_page_name);
        $objWorkSheet->setTitle($new_page_name);
        $this->addSheet($objWorkSheet);
        $this->selectedPage++;
        $this->setActiveSheetIndex($this->selectedPage);
        $this->startRowNumber = 0;
        $this->startColumnNumber = 0;
    }

    /*
     * SADECE HEADER KISMINI EKLEYEBİLİRSİN.
     */

    public function toExcelFromArray($columns_array_or_string, $style = 1, $sheet_title = "") {
        $this->startRowNumberBefore = $this->startRowNumber;
        $this->startColumnNumberBefore = $this->columnIndex;
        $selected_style1 = $this->styleHeader1;
        $selected_style2 = $this->styleHeader2;
        if ($style === 1 || $style != "header") {
            $selected_style1 = $this->styleColumn1;
            $selected_style2 = $this->styleColumn2;
        }
// Set document properties
        $this->getProperties()->setCreator($this->setCreator)
                ->setLastModifiedBy($this->setLastModifiedBy)
                ->setTitle($this->setTitle)
                ->setSubject($this->setSubject)
                ->setDescription($this->setDescription)
                ->setKeywords($this->setKeywords)
                ->setCategory($this->setCategory);
// Rename worksheet
        if ($sheet_title !== "") {
            $this->getActiveSheet()->setTitle($sheet_title);
        }
        $this->startRowNumber++;
        $this->columnIndex = $this->startColumnNumber;
        $is_not_sender_column = true;
        foreach ($columns_array_or_string as $column) {
            $excel_column_name = $this->getNameFromNumber($this->columnIndex) . $this->startRowNumber;
            $this->setActiveSheetIndex($this->selectedPage)->setCellValue($excel_column_name, $column);
            $this->getActiveSheet()->getColumnDimension($this->getNameFromNumber($this->columnIndex))->setAutoSize(true);
            if ($this->columnIndex % 2 === 0) {
                $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($selected_style1);
            } else {
                $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($selected_style2);
            }
            $this->columnIndex++;
            $is_not_sender_column = false;
        }
        if ($is_not_sender_column) {
            $excel_column_name = $this->getNameFromNumber($this->columnIndex) . $this->startRowNumber;
            $this->setActiveSheetIndex($this->selectedPage)->setCellValue($excel_column_name, $columns_array_or_string);
            $this->getActiveSheet()->getColumnDimension($this->getNameFromNumber($this->columnIndex))->setAutoSize(true);
            if ($this->columnIndex % 2 === 0) {
                $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($selected_style1);
            } else {
                $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($selected_style2);
            }
        }
    }

    /*
     * COLUMN İÇİN KULLANILIR.
     */

    public function toExcelFromArrayAddColumn($columns_array_or_string, $style = 1, $sheet_title = "") {
        $this->startRowNumberBefore = $this->startRowNumber;
        $this->startColumnNumberBefore = $this->columnIndex;
        $selected_style = $this->styleHeader1;
        if ($style === 1 || $style != "header") {
            $selected_style = $this->styleColumn1;
        }
// Set document properties
        $this->getProperties()->setCreator($this->setCreator)
                ->setLastModifiedBy($this->setLastModifiedBy)
                ->setTitle($this->setTitle)
                ->setSubject($this->setSubject)
                ->setDescription($this->setDescription)
                ->setKeywords($this->setKeywords)
                ->setCategory($this->setCategory);
// Rename worksheet
        if ($sheet_title !== "") {
            $this->getActiveSheet()->setTitle($sheet_title);
        }
        $is_not_sender_column = true;
//        $this->startRowNumber++;
        foreach ($columns_array_or_string as $column) {
            $excel_column_name = $this->getNameFromNumber($this->columnIndex) . $this->startRowNumber;
            $this->setActiveSheetIndex($this->selectedPage)->setCellValue($excel_column_name, $column);
            $this->getActiveSheet()->getColumnDimension($this->getNameFromNumber($this->columnIndex))->setAutoSize(true);
            if ($this->columnIndex % 2 === 0) {
                $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($selected_style);
            } else {
                $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($selected_style);
            }
            $this->columnIndex++;
            $is_not_sender_column = false;
        }
        if ($is_not_sender_column) {
            $excel_column_name = $this->getNameFromNumber($this->columnIndex) . $this->startRowNumber;
            $this->setActiveSheetIndex($this->selectedPage)->setCellValue($excel_column_name, $columns_array_or_string);
            $this->getActiveSheet()->getColumnDimension($this->getNameFromNumber($this->columnIndex))->setAutoSize(true);
            if ($this->columnIndex % 2 === 0) {
                $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($selected_style);
            } else {
                $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($selected_style);
            }
            $this->columnIndex++;
        }
    }

    public function toExcelnewLine() {
        $this->startRowNumberBefore = $this->startRowNumber;
        $this->startColumnNumberBefore = $this->columnIndex;
        $this->startRowNumber++;
        $this->startColumnNumber = 1;
        $this->columnIndex = 0;
    }

    public function toExcelBeforeRowColumn() {
        $this->startRowNumber = $this->startRowNumberBefore;
        $this->columnIndex = $this->startColumnNumberBefore;
    }

    public function toExcelFromSqlZend($sql_string, $sql_array = NULL) {
        $this->startRowNumberBefore = $this->startRowNumber;
        $this->startColumnNumberBefore = $this->columnIndex;
        try {
            if (is_null($sql_array)) {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string);
            } else {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string, $sql_array);
            }
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }

        $this->startRowNumber++;

        if (is_array($get_sql_data) || is_object($get_sql_data)) {
            foreach ($get_sql_data as $row) {
                $this->columnIndex = $this->startColumnNumber;
                foreach ($row as $column) {
                    $excel_column_name = $this->getNameFromNumber($this->columnIndex) . $this->startRowNumber;
                    $this->setActiveSheetIndex($this->selectedPage)->setCellValue($excel_column_name, $column);
                    if ($this->columnIndex % 2 === 0) {
                        $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($this->styleColumn1);
                    } else {
                        $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($this->styleColumn2);
                    }
                    $this->columnIndex++;
                }
                $this->startRowNumber++;
            }
        }
    }

    public function toExcelFromSqlZendAddRow($sql_string, $sql_array = NULL) {
        $this->startRowNumberBefore = $this->startRowNumber;
        $this->startColumnNumberBefore = $this->columnIndex;
        try {
            if (is_null($sql_array)) {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string);
            } else {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string, $sql_array);
            }
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        $this->startRowNumber++;
        if (is_array($get_sql_data) || is_object($get_sql_data)) {
            foreach ($get_sql_data as $row) {
                $this->columnIndex = $this->startColumnNumber;
                foreach ($row as $column) {
                    $excel_column_name = $this->getNameFromNumber($this->columnIndex) . $this->startRowNumber;
                    $this->setActiveSheetIndex($this->selectedPage)->setCellValue($excel_column_name, $column);
                    if ($this->columnIndex % 2 === 0) {
                        $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($this->styleColumn1);
                    } else {
                        $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($this->styleColumn2);
                    }
                    $this->startRowNumber++;
                }
                $this->columnIndex++;
            }
        }
    }

    public function toExcelFromSqlZendAddColumn($sql_string, $sql_array = NULL) {
        $this->startRowNumberBefore = $this->startRowNumber;
        $this->startColumnNumberBefore = $this->columnIndex;
        try {
            if (is_null($sql_array)) {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string);
            } else {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string, $sql_array);
            }
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        if (is_array($get_sql_data) || is_object($get_sql_data)) {
            foreach ($get_sql_data as $row) {
                foreach ($row as $column) {
                    $excel_column_name = $this->getNameFromNumber($this->columnIndex) . $this->startRowNumber;
                    $this->setActiveSheetIndex($this->selectedPage)->setCellValue($excel_column_name, $column);
                    if ($this->columnIndex % 2 === 0) {
                        $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($this->styleColumn1);
                    } else {
                        $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($this->styleColumn2);
                    }
                    $this->columnIndex++;
                }
            }
        } else {
            foreach ($get_sql_data as $column) {
                $excel_column_name = $this->getNameFromNumber($this->columnIndex) . $this->startRowNumber;
                $this->setActiveSheetIndex($this->selectedPage)->setCellValue($excel_column_name, $column);
                if ($this->columnIndex % 2 === 0) {
                    $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($this->styleColumn1);
                } else {
                    $this->getActiveSheet()->getStyle($excel_column_name)->applyFromArray($this->styleColumn2);
                }
                $this->columnIndex++;
            }
        }
    }

    public function toExcelFinish() {
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//        $this->setActiveSheetIndex($this->selectedPage);
// Redirect output to a client’s web browser (Excel5)
        if ($this->download_format === "xls") {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $this->fileName . '(' . date("d/m/Y") . ').xls"');
            header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel5');
            $objWriter->save('php://output');
        } else if ($this->download_format === "xlsx") {
// Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $this->fileName . '(' . date("d/m/Y") . ').xlsx"');
            header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel2007');
            $objWriter->save('php://output');
        }
        $this->insert_print_log();
        exit;
    }

//    private $excelColumnArray = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

    public function toExcelStart() {
        header('Content-type: application/vnd.ms-excel; charset=utf-8');
//        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
//header("Content-type:   application/x-msexcel; charset=utf-8");
        header("Content-Disposition: attachment; filename=DATA(" . date('d/m/Y') . ").xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        echo '<table border="2">';
    }

    public function toExcelTableStart() {
        echo '<table border="2">';
    }

    public function toExcelTableEnd() {
        echo '</table>';
    }

    public function toExcelTableNewLineStart() {
        $this->style_is_other_column = true;
        echo '<tr>';
    }

    public function toExcelTableNewLineEnd() {
        echo '</tr>';
    }

    public function toExcelNewColumnHeaderSimple($header_string) {
//VERİLER TEMİZLENMEKTEDİR.
        $data_excel = str_replace("/\t/", "\\t", $header_string);
        $data_excel = str_replace("/\r?\n/", "\\n", $data_excel);
        $data_excel = str_replace($data_excel . "$", "", $data_excel);
        $data_excel = preg_replace("/\r\n|\n\r|\n|\r/", " ", $data_excel);
        $data_excel = str_replace("\n", " ", $data_excel);
        $data_excel = str_replace("\r", " ", $data_excel);
        $data_excel = trim($data_excel);
        if ($this->style_is_other_column) {
            $this->style_is_other_column = false;
            echo '<th  style="text-align:center;background-color:#FFA500;width:200px;">' . strip_tags($data_excel) . '</th>';
        } else {
            $this->style_is_other_column = TRUE;
            echo '<th  style="text-align:center;background-color:#ffc14d;width:200px;">' . strip_tags($data_excel) . '</th>';
        }
    }

    public function toExcelNewColumnSimple($column_string) {
//VERİLER TEMİZLENMEKTEDİR.
        $data_excel = str_replace("/\t/", "\\t", $column_string);
        $data_excel = str_replace("/\r?\n/", "\\n", $data_excel);
        $data_excel = str_replace($data_excel . "$", "", $data_excel);
        $data_excel = preg_replace("/\r\n|\n\r|\n|\r/", " ", $data_excel);
        $data_excel = str_replace("\n", " ", $data_excel);
        $data_excel = str_replace("\r", " ", $data_excel);
        $data_excel = trim($data_excel);
        if ($this->style_is_other_column) {
            $this->style_is_other_column = false;
            echo '<td  style="text-align:center;background-color:#e6f2ff;">' . strip_tags($data_excel) . '</td>';
        } else {
            $this->style_is_other_column = TRUE;
            echo '<td  style="text-align:center;">' . strip_tags($data_excel) . '</td>';
        }
    }

    public function toExcelFromSqlHeaderMysqliSimple($sql_string, $con) {
        try {
            $get_sql_data = mysqli_query($con, $sql);
            while (mysqli_more_results($con) && mysqli_next_result($con)) {
                $dummyResult = mysqli_use_result($con);
                if ($dummyResult instanceof mysqli_result) {
                    mysqli_free_result($con);
                }
            }
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        foreach ($get_sql_data as $row) {
            $this->toExcelNewLineStart();
            foreach ($row as $column) {
                $this->toExcelNewColumnHeaderSimple($column);
            }
            $this->toExcelNewLineEnd();
        }
    }

    public function toExcelFromSqlHeaderZendSimple($sql_string, $sql_array = NULL) {
        try {
            if (count($sql_array)) {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string, $sql_array);
            } else {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string);
            }
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        foreach ($get_sql_data as $row) {
            $this->toExcelNewLineStart();
            foreach ($row as $column) {
                $this->toExcelNewColumnHeaderSimple($column);
            }
            $this->toExcelNewLineEnd();
        }
    }

    public function toExcelFromSqlMysqliSimple($sql_string, $con) {
        try {
            $get_sql_data = mysqli_query($con, $sql);
            while (mysqli_more_results($con) && mysqli_next_result($con)) {
                $dummyResult = mysqli_use_result($con);
                if ($dummyResult instanceof mysqli_result) {
                    mysqli_free_result($con);
                }
            }
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        foreach ($get_sql_data as $row) {
            $this->toExcelNewLineStart();
            foreach ($row as $column) {
                $this->toExcelNewColumnSimple($column);
            }
            $this->toExcelNewLineEnd();
        }
    }

    public function toExcelFromSqlZendSimpleSimple($sql_string, $sql_array = NULL) {
        try {
            if (is_null($sql_array)) {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string);
            } else {
                $get_sql_data = $GLOBALS['db']->fetchAll($sql_string, $sql_array);
            }
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
        foreach ($get_sql_data as $row) {
            $this->toExcelNewLineStart();
            foreach ($row as $column) {
                $this->toExcelNewColumnSimple($column);
            }
            $this->toExcelNewLineEnd();
        }
    }

    /*
     * VERİLERİ EXCELDEN OKUR VE BİR ARRAY DÖNDÜRÜR.
     * COLUMN OLARAK VERİYİ OKUR
     */

    public function toArrayFromExcelColumn($read_filename) {
        $return_read_array = array();
//  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($read_filename);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($read_filename);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($read_filename, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

//  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $column_index = 0;
        while (true) {
            $column_array = array();
            $excel_column_name = $this->getNameFromNumber($column_index);
            $column_index++;
//  Loop through each row of the worksheet in turn
            for ($row = 1; $row <= $highestRow; $row++) {
//  Read a row of data into an array
                $rowData = $sheet->rangeToArray($excel_column_name . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                array_push($column_array, $rowData[0][0]);
            }
            array_push($return_read_array, $column_array);
            if ($excel_column_name == $highestColumn) {
                break;
            }
        }
        return $return_read_array;
    }

    /*
     * VERİLERİ EXCELDEN OKUR VE BİR ARRAY DÖNDÜRÜR.
     * SATIR OLARAK VERİYİ OKUR
     */

    public function toArrayFromExcelRow($read_filename) {
        $return_read_array = array();
//  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($read_filename);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($read_filename);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($read_filename, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

//  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 1; $row <= $highestRow; $row++) {
            $column_index = 0;
            $column_array = array();
            while (true) {
                $excel_column_name = $this->getNameFromNumber($column_index);
                $column_index++;
//  Read a row of data into an array
                $rowData = $sheet->rangeToArray($excel_column_name . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                array_push($column_array, $rowData[0][0]);
                if ($excel_column_name == $highestColumn) {
                    break;
                }
            }
            array_push($return_read_array, $column_array);
        }

        return $return_read_array;
    }

    public function insert_print_log() {
        global $LoggedUserId, $PageUrlAccessedByUser, $LoggedUserIp, $LoggedUserGroupId;
//        if ($this->printTitle == "") {
//            $this->printTitle = $this->headerTitle;
//        }
//        if ($this->printDescription == "") {
//            $this->printDescription = $this->headerSubString;
//        }
        $sqlLog = "INSERT INTO log_cikti VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try {
            $GLOBALS["db"]->fetchAll($sqlLog, array(NULL, 'xls', $LoggedUserId, $PageUrlAccessedByUser, $LoggedUserGroupId, $this->printTitle, $this->printDescription, $LoggedUserIp, date('Y-m-d H:i:s'), 1));
            $last_insert_id = $GLOBALS["db"]->lastInsertId();
        } catch (Zend_Db_Exception $ex) {
            log::DB_hata_kaydi_ekle(__FILE__, $ex);
        }
    }

}

?>