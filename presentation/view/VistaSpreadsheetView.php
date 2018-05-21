<?php
namespace Represaliats\Presentation\View;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Represaliats\Presentation\Model\VistaModel;
use Represaliats\Service\Entities\Vista;

class VistaSpreadsheetView extends SpreadsheetView implements ISpreadsheetView
{
    private $model;
    private $pdf;
    private $instances;
    private $vista;
    private $sheet;
    private $row;
    private $col;
    private $cellsWidth;
    private $tableHeader;
    private $tableFields;
    
    public function __construct(array $instances, Vista $vista) {
        parent::__construct();
        $this->instances = $instances;
        $this->vista = $vista;
        $this->model = new VistaModel();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->row = 1;
        $this->col = 'A';
        $this->tableHeader = [];
        $this->tableFields = [];
    }
    
    private function getTableField($value): array {
        return [$value, strlen($value)];
    }
    
    private function setTableHeader() {
        $labels = $this->vista->getLabels();
        foreach ($labels as $label) {
            $this->tableHeader[] = [$label, strlen($label)];
        }
    }
    
    private function createTableHeader() {
        foreach ($this->tableHeader as $i => $value) {
            $this->spreadsheet->getActiveSheet()->getColumnDimension($this->col)->setWidth($this->cellsWidth[$i]);
            $this->sheet->setCellValue($this->col++ . 1, $value[0]);
        }
        $cellRange = "A1:" . $this->col . "1";
        $this->spreadsheet->getActiveSheet()->getStyle($cellRange)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $this->row ++;
    }
    
    private function createTableFields() {
        foreach ($this->tableFields as $instance) {
            $this->col = 'A';
            foreach ($instance as $i => $value) {
                $this->sheet->setCellValue($this->col++ . $this->row, $value[0]);
            }
            if ($this->row % 2 == 1) {
                $end = 'A';
                while ($i--) $end++;
                $cellRange = 'A' . $this->row . ":" . $end . $this->row;
            }
            $this->row ++;
        }
    }
    
    private function getCellsWidth() {
        $this->cellsWidth = [];
        foreach ($this->tableHeader as $i=>$col) {
            $this->cellsWidth[$i] = $this->tableHeader[$i][1];
            foreach ($this->tableFields as $field) {
                if ($field[$i][1] > $this->cellsWidth[$i]) {
                    $this->cellsWidth[$i] = $field[$i][1];
                }
            }
        }
    }
    
    public function getSpreadsheet(): string {
        $this->setTableHeader();
        $index = 0;
        foreach ($this->instances as $instance) {
            $this->col = 'A';
            foreach ($this->vista->getFieldsContent() as $attributes) {
                $result = $this->model->getValuesFromAttributes($instance, $attributes);
                $labels = $result['labels'];
                $output = $result['output'];
                $txt = $this->model->getTextFromValuesArray($output, $labels);
                $this->tableFields[$index][] = $this->getTableField($txt);
            }
            $index++;
        }
        $this->getCellsWidth();
        $this->createTableHeader();
        $this->createTableFields();
        $writer = new Xlsx($this->spreadsheet);
        $filename = FILESDIR. str_replace(" ", "-", $this->vista->getName()) . '.xlsx';
        $writer->save($filename);
        return $filename;
    }
}