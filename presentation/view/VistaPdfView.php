<?php
namespace Represaliats\Presentation\View;

use Represaliats\Service\Entities\Vista;
use Represaliats\Service\Utils;
use Represaliats\Presentation\Model\VistaModel;

class VistaPdfView extends PdfView implements IPdfView
{
    private $model;
    private $instances;
    private $vista;
    private $tableHeader;
    private $tableFields;
    private $lh;
    private $sh;
    private $margins;
    private $cellsWidth;
    private $maxCellWidth;
    private $fontSize;
    private $headerCellFormat;
    private $infoCellFormat;
    private $cellHeight;
    
    public function __construct(array $instances, Vista $vista) {
        parent::__construct();
        $this->instances = $instances;
        $this->vista = $vista;
        $this->model = new VistaModel();
        $this->tableHeader = [];
        $this->tableFields = [];
        $this->setConstants();
    }
    
    private function setConstants() {
        $this->lh = 5;
        $this->sh = 8;
        $this->margins = ['left'=>10, 'top'=>10, 'right'=>10, 'bottom'=>10];
        $this->fontSize = 8;
        $this->headerCellFormat = [
            'font' => 'Arial',
            'style' => 'B',
            'size' => 8,
            'color' => ['r'=>0x00, 'g'=>0x00, 'b'=>0x00],
            'bkColor' => ['r'=>0xF0, 'g'=>0xF0, 'b'=>0xF0],
        ];
        $this->infoCellFormat = [
            'font' => 'Arial',
            'style' => '',
            'size' => 8,
            'color' => ['r'=>0x00, 'g'=>0x00, 'b'=>0x00],
            'bkColor' => ['r'=>0xF6, 'g'=>0xF6, 'b'=>0xF6],
        ];
        $this->maxCellWidth = 40;
        $this->cellHeight = 6;
    }
    
    private function formatPage() {
        $tableWidth = array_sum($this->cellsWidth);
        $orientation = ($this->margins['left'] + $this->margins['right'] + $tableWidth < 210) ? 'P' : 'L';
        $size = ($this->margins['left'] + $this->margins['right'] + $tableWidth < 297) ? 'A4' : 'A3';
        $this->pdf->AddPage($orientation, $size);
        $this->pdf->SetMargins($this->margins['left'], $this->margins['top'], $this->margins['right']);
    }
    
    private function setTableHeader() {
        $this->pdf->SetFont($this->headerCellFormat['font'], $this->headerCellFormat['style'], $this->headerCellFormat['size']);
        $labels = $this->vista->getLabels();
        foreach ($labels as $label) {
            $label = Utils::decode($label);
            $cellWidth = $this->pdf->GetStringWidth($label) + 2;
            $this->tableHeader[] = [$label, $cellWidth];
        }
    }
    
    private function getTableField($value): array {
        if (is_bool($value)) $value = "Sí";
        $this->pdf->SetFont($this->infoCellFormat['font'], $this->infoCellFormat['style'], $this->infoCellFormat['size']);
        $value = Utils::decode($value);
        $cellWidth = $this->pdf->GetStringWidth($value);
        if ($cellWidth > $this->maxCellWidth) $cellWidth = $this->maxCellWidth;
        return [$value, $cellWidth + 2];
    }
    
    private function createTableHeader() {
        $this->pdf->SetFont($this->headerCellFormat['font'], $this->headerCellFormat['style'], $this->headerCellFormat['size']);
        $this->pdf->SetFillColor(
            $this->headerCellFormat['bkColor']['r'],
            $this->headerCellFormat['bkColor']['g'],
            $this->headerCellFormat['bkColor']['b']);
        $this->pdf->SetLineWidth(.1);
        foreach ($this->tableHeader as $i=>$value) {
            $this->pdf->Cell($this->cellsWidth[$i], $this->cellHeight, $value[0], 'TB', 0, 'L', true);
        }
        $this->pdf->Ln();
    }
    
    private function createTableFields() {
        $y = $this->pdf->GetY();
        $width = 0;
        foreach ($this->cellsWidth as $cellWidth) $width += $cellWidth;
        foreach ($this->tableFields as $index => $instance) {
            $x = $this->margins['left'];
            $height = $this->getRowHeight($this->cellsWidth, $instance, $this->infoCellFormat, $this->cellHeight);
            if ($this->pageOverflown($this->pdf, $this->margins, $x, $y + $height)[1]) {
                $this->formatPage();
                $this->createTableHeader();
                $y = $this->pdf->GetY();
            }
            $this->pdf->SetFont($this->infoCellFormat['font'], $this->infoCellFormat['style'], $this->infoCellFormat['size']);
            $this->pdf->SetFillColor(
                $this->infoCellFormat['bkColor']['r'],
                $this->infoCellFormat['bkColor']['g'],
                $this->infoCellFormat['bkColor']['b']);
            $this->pdf->SetXY($x, $y);
            $this->pdf->Cell($width, $height, "", 0, 0, 'L', true * ($index % 2));
            foreach ($instance as $i=>$value) {
                $this->pdf->SetXY($x, $y);
                $x += $this->cellsWidth[$i];
                $this->pdf->MultiCell($this->cellsWidth[$i], $this->cellHeight, $value[0], 0, 'L');
            }
            $y += $height;
        }
    }
    
    private function getCellsWidth() {
        $this->cellsWidth = [];
        foreach ($this->tableHeader as $i=>$col) {
            $maxCellWidth = $this->maxCellWidth;
            $this->cellsWidth[$i] = $this->tableHeader[$i][1];
            if ($this->tableHeader[$i][1] > $maxCellWidth) $maxCellWidth = $this->tableHeader[$i][1];
            foreach ($this->tableFields as $field) {
                if ($field[$i][1] > $this->cellsWidth[$i]) {
                    if ($field[$i][1] > $maxCellWidth) {
                        $this->cellsWidth[$i] = $maxCellWidth;
                    } else {
                        $this->cellsWidth[$i] = $field[$i][1];
                    }
                }
            }
        }
    }
    
    public function getPdf() {
        $this->pdf->setHeader($this->vista->getName());
        $this->pdf->SetAutoPageBreak(false);
        $this->pdf->AliasNbPages();
        $this->setTableHeader();
        $index = 0;
        foreach ($this->instances as $instance) {
            $this->tableFields[$index] = [];
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
        $this->formatPage();
        $this->createTableHeader();
        $this->createTableFields();
        $this->pdf->Output();
    }
}