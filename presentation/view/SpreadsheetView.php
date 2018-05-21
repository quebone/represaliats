<?php
namespace Represaliats\Presentation\View;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpreadsheetView extends View
{
    protected $spreadsheet;
    
    public function __construct() {
        parent::__construct();
        $this->spreadsheet = new Spreadsheet();
    }
    
    public function test() {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        
        $writer = new Xlsx($this->spreadsheet);
        $writer->save('hello world.xlsx');
    }
}