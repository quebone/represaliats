<?php
namespace Represaliats\Service;

class Utils
{
    /**
     * Converteix els caràcters UTF8 a CP1252
     */
    public static function decode($inputText)
    {
        setlocale(LC_ALL, 'es_CA');
        return $str = iconv('UTF-8', 'cp1252', $inputText);
    }
    
    public static function removeAccents(string $input): string {
        return str_replace(
            array('à','è','é','í','ò','ó','ú','À','È','É','Í','Ò','Ó','Ú'),
            array('a','e','e','i','o','o','u','A','E','E','I','O','O','U'),
            $input);
    }
    
    /**
     * Converteix una cadena de tipus "[a,$mark $mark,b]" en un array [0=>a, 1=>$mark $mark, 2=>b]
     * @param string $input
     * @return array | string
     */
    public static function strToArray(string $input, $mark=null) {
        $input = str_replace("[", "", $input);
        $input = str_replace("]", "", $input);
        //cerquem les cadenes de tipus $mark
        $output = [];
        if ($mark != null && substr_count($input, $mark) % 2 == 0) {
            $pos = strpos($input, $mark);
            if ($pos !== false) {
                if ($pos == 0) {
                    $end = strpos($input, $mark, 1) + 1;
                    $strTmp = substr($input, $pos, $end);
                    $output[] = $strTmp;
                    $strTmp = substr($input, $end + 1);
                    $output = array_merge($output, Utils::strToArray($strTmp, $mark));
                } else {
                    $strTmp = substr($input, 0, $pos - 1);
                    $output[] = $strTmp;
                    $strTmp = substr($input, $pos);
                    $output = array_merge($output, Utils::strToArray($strTmp, $mark));
                }
            } else {
                $strTmp = $input;
                $output = array_merge($output, explode(",", $strTmp));
            }
        } else {
            $output = explode(",", $input);
        }
        return $output;
    }
    
    /**
     * Comprova si tots els valors d'un array són nuls o corresponen al valor per defecte
     * @param mixed $input
     * @return boolean
     */
    public static function isEmpty($input) {
        $empty = true;
        foreach ($input as $value) {
            if (is_array($value)) {
                $empty = $empty && Utils::isEmpty($value);
            }
            else {
                if ($value != null && is_string($value) && strlen($value) > 0 && strcmp($value, DEFAULTVALUE) != 0) return false;
            }
        }
        return $empty;
    }
    
    /**
     * Canvia les files i les columnes d'un array
     * @param array $input
     * @return array
     */
    public static function switchArray(array $input): array {
        $output = [];
        for ($i = 0; $i < count($input); $i++) {
            for ($j = 0; $j < count($input[$i]); $j++) {
                $output[$j][$i] = $input[$i][$j];
            }
        }
        return $output;
    }
    
    /**
     * Calcula la profunditat d'un array
     * @param array $array
     * @return number
     */
    public static function array_depth(array $array) {
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = Utils::array_depth($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }
    
    /**
     * Mira si un atribut està relacionat amb un altre
     * @param string $key
     * @return string|boolean
     */
    public static function isRelated(string $key) {
        $related = [
            'parelles' => 'myParelles',
        ];
        if (array_key_exists($key, $related)) return $related[$key];
        return false;
    }
}