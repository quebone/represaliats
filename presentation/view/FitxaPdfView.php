<?php
namespace Represaliats\Presentation\View;

use Represaliats\Service\Entities\Persona;
use Represaliats\Service\Utils;
use Represaliats\Presentation\Model\FitxaModel;

class FitxaPdfView extends PdfView implements IPdfView
{
    private $lh;
    private $sh;
    private $seccions;
    private $model;
    public $persona;

    public function __construct(Persona $persona) {
        parent::__construct();
        $this->model = new FitxaModel();
        $this->seccions = $this->model->getSeccions();
        $this->persona = $persona;
        $this->setConstants();
    }
    
    private function setConstants() {
        $this->lh = 5;
        $this->sh = 8;
    }

    private function writeSeccio($value) {
        if (strlen($value) > 0) {
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->Write($this->lh, "\n");
            $this->pdf->Write($this->sh, Utils::decode($value) . "\n");
        }
    }
    
    private function writeInfo($label, $value) {
        if (strlen($value) > 0) {
            if (is_bool($value)) $value = "Sí";
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->Write($this->lh, Utils::decode($label) . ": ");
            $this->pdf->SetFont('Arial', '', 10);
            $this->pdf->Write($this->lh, Utils::decode($value) . "\n");
        }
    }
    
    public function getPdf() {
        $this->pdf->setHeader($this->persona->getNom() . ' ' . $this->persona->getCognoms());
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
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
        $this->pdf->Output();
    }
}
