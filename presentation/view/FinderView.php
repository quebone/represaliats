<?php
namespace Represaliats\Presentation\View;

use Represaliats\Service\FinderService;
use Represaliats\Service\PersonaService;
use Represaliats\Presentation\Model\FitxaModel;
use Represaliats\Service\Entities\Persona;

class FinderView extends View
{
    private $max = 100;
    private $pre = 50;
    private $startMark = "<span class='highlight'>";
    private $endMark = "</span>";
    private $separator = "<span class='separator'>... </span>";
    
    public function __construct() {
        parent::__construct();
    }
    
    public function formatSearch($needle): string {
        $output = "";
        $founds = $this->getAllPersonesFounds($needle);
        foreach ($founds as $found) {
            $output .= "<p class='name'><a href=fitxa.php?id=" . $found['id'] . ">" . $found['name'] . "</a></p><p class='data'>" . $found['data'] . "</p>\n";
        }
        return $output;
    }
    
    public function getAllPersonesFounds(string $needle): array {
        $data = [];
        $service = new PersonaService();
        foreach ($service->getAllPersones() as $persona) {
            $name = $persona->getNom() . " " . $persona->getCognoms();
            $input = $this->getData($persona);
            $founds = $this->getFounds($input, $needle);
            if ($founds != "") $data[] = ['name' => $name, 'id' => $persona->getId(), 'data' => $founds];
        }
        return $data;
    }
    
    public function getFounds(string $input, string $needle): string {
        $output = "";
        $service = new FinderService();
        $data = $service->getFounds($input, $needle, $this->max, $this->pre);
        $data = $service->merge($data);
        foreach ($data as $key => $value) {
            if ($value[0] > 0) $output = $this->separator;
            for ($i = 0; $i < count($value) - 2; $i++) {
                $output .= substr($input, $value[$i], $value[$i+1] - $value[$i]);
                $i++;
                $output .= $this->startMark . $needle . $this->endMark;
            }
            $output .= substr($input, $value[$i], $value[$i+1] - $value[$i]);
            if ($key < count($data) - 1) $output .= $this->separator;
        }
        return $output;
    }
    
    private function getData(Persona $persona): string {
        $finder = new FinderView();
        $model = new FitxaModel();
        $seccions = $model->getSeccions();
        $name = $persona->getNom() . " " . $persona->getCognoms();
        $info = ["Nom: $name"];
        foreach ($seccions as $nomSeccio=>$seccio) {
            if (strcmp($nomSeccio, "Sumari") || (!strcmp($nomSeccio, "Sumari") && $persona->getHasSumari())) {
                foreach ($seccio as $label => $attributes) {
                    $result = $model->getValuesFromAttributes($persona, $attributes);
                    $labels = $result['labels'];
                    $output = $result['output'];
                    $txt = $model->getTextFromValuesArray($output, $labels);
                    if ($txt != "") $info[] = "$label: $txt";
                }
            }
        }
        return implode(". ", $info);
    }
}