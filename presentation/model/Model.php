<?php
namespace Represaliats\Presentation\Model;

use Represaliats\Service\Entities\IEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Represaliats\Service\Utils;

class Model
{
    public function __construct() {
        
    }

    public function getValuesFromAttributes(IEntity $instance, $attributes): array {
        $labelIndex = 0;
        $labels = [];
        $output = [];
        if (!is_array($attributes)) $attributes = [$attributes];
        foreach ($attributes as $attribute) {
            if (($result = $this->getLabel($attribute)) != null) {
                if (count($output) > 0) $labels[$labelIndex] = $result;
            } else {
                $fields = $this->parseFields($attribute);
                $output[] = $this->processFields($instance, $fields, 0, []);
            }
            $labelIndex++;
        }
        return ['output' => $output, 'labels' => $labels];
    }
    
    private function isCollection($attribute): bool {
        return $attribute instanceof ArrayCollection || $attribute instanceof PersistentCollection;
    }
    
    private function parseFields(string $fields): array {
        return explode(".", $fields);
    }
    
    private function getLabel(string $field): ?string {
        if ($field[0] == '%' && $field[count($field) - 1] == '%') {
            return str_replace("%", "", $field);
        }
        return null;
    }
    
    private function processFields($entity, array $fields, int $index, $output) {
        $value = $entity->{"get" . ucfirst($fields[$index])}();
        if ($this->isCollection($value)) {
            if ($related = Utils::isRelated($fields[$index])) {
                $value = $value->toArray();
                $value = array_merge($value, $entity->{"get" . ucfirst($related)}()->toArray());
            }
            $index++;
            for ($i = 0; $i < count($value); $i++) {
                $output[] = $this->processFields($value[$i], $fields, $index, []);
            }
        } else {
            if (++$index < count($fields)) {
                $output[] = $this->processFields($value, $fields, $index, $output);
            } else {
                //                 if (strcmp($value, DEFAULTVALUE))
                $output[] = (is_bool($value) && $value == true) ? "Sí" : $value;
            }
        }
        return $this->cleanArray($output);
    }
    
    private function cleanArray($input) {
        if (is_array($input) && count($input) == 1) return $input[0];
        return $input;
    }
    
    public function getTextFromValuesArray(array $output, array $labels): string {
        $txt = "";
        if (!Utils::isEmpty($output)) {
            if (Utils::array_depth($output) == 1) {
                if (count($output) == 1) {
                    $txt = $output[0];
                } else {
                    for ($i = 0, $j = 0; ($i + $j) < (count($output) + count($labels)); $i++) {
                        if (array_key_exists($j, $output)) {
                            $txt .= $output[$j];
                            if (array_key_exists($i + $j + 1, $labels)) {
                                $txt .= $labels[$i + $j + 1];
                                $j++;
                            }
                        }
                    }
                }
            } else {
                $output = Utils::switchArray($output);
                for ($i=0; $i<count($output); $i++) {
                    for($j=0, $k=0; ($j + $k) < (count($output[$i]) + count($labels)); $j++) {
                        $txt .= $output[$i][$k];
                        if (array_key_exists($j + $k + 1, $labels)) {
                            $txt .= $labels[$j + $k + 1];
                            $k++;
                        }
                    }
                    if ($i < count($output) - 1) $txt .= ", ";
                }
            }
        }
        return $txt;
    }
}