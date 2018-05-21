<?php
namespace Represaliats\Presentation\View;

use Represaliats\Service\Entities\Persona;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Represaliats\Presentation\Model\FitxaModel;

class FitxaSpreadsheetView extends SpreadsheetView implements ISpreadsheetView
{
    private $model;
    public $persona;
    private $seccions;
    private $sheet;
    private $row;
    private $col;
    
    public function __construct(Persona $persona) {
        parent::__construct();
        $this->persona = $persona;
        $this->model = new FitxaModel();
        $this->seccions = $this->model->getSeccions();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->row = 1;
        $this->col = 'A';
    }
    
    private function writeHeader(string $txt) {
        $this->sheet->setCellValue('A' . $this->row, $txt);
        $this->row ++;
    }
    
    private function writeSeccio(string $txt) {
        $this->row += 2;
        $this->sheet->setCellValue('A' . $this->row, $txt);
        $this->row += 2;
    }
    
    private function writeInfo(string $label, string $txt) {
        $this->sheet->setCellValue('A' . $this->row, $label);
        $this->sheet->setCellValue('B' . $this->row, $txt);
        $this->row++;
    }
    
    public function getSpreadsheet(): string {
        $this->writeHeader($this->persona->getNom() . " " . $this->persona->getCognoms());
        foreach ($this->seccions as $nomSeccio=>$seccio) {
            if (strcmp($nomSeccio, "Sumari") || (!strcmp($nomSeccio, "Sumari") && $this->persona->getHasSumari())) {
                $this->writeSeccio($nomSeccio);
                foreach ($seccio as $label=>$attributes) {
                    $result = $this->model->getValuesFromAttributes($this->persona, $attributes);
                    $labels = $result['labels'];
                    $output = $result['output'];
                    $txt = $this->model->getTextFromValuesArray($output, $labels);
                    if ($txt != "") $this->writeInfo($label, $txt);
                }
            }
        }
        $writer = new Xlsx($this->spreadsheet);
        $filename = FILESDIR. str_replace(" ", "-", $this->persona->getCognoms() . " " . $this->persona->getNom()) . '.xlsx';
        $writer->save($filename);
        return $filename;
    }
}