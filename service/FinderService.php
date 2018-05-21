<?php
namespace Represaliats\Service;

/**
 * Busca subcadenes en una cadena
 * @author carles
 *
 */
class FinderService extends Service
{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Retorna un array de mida igual al número de cops que s'ha trobat la subcadena en la cadena
     * Cada ítem de l'array és un array amb els caràcters anteriors, els posteriors i la posició de la subcadena
     * @param string $input
     * @param string $needle
     * @param int $max
     * @param int $pre
     * @param int $post
     * @return array
     */
    public function getFounds(string $input, string $needle, int $max, int $pre, int $post=-1): array {
        $result = [];
        $input = strtolower(($input));
        $needle = strtolower(($needle));
        if ($post < 0) $post = $pre;
        $pos = 0;
        $data = [];
        while (($pos = strpos($input, $needle, $pos)) !== false) {
            $data[0] = $pos - $pre > 0 ? $pos - $pre : 0;
            $data[1] = $pos;
            $data[2] = $pos + strlen($needle);
            $data[3] = $data[2] + $post < strlen($input) ? $data[2] + $post : strlen($input);
            $result[] = $data;
            $pos++;
        }
        return $result;
    }
    
    public function merge(array $input): array {
        if (array_key_exists(1, $input)) {
            $i = 0;
            $pos1 = $input[$i][count($input[$i]) - 1];
            $pos2 = $input[$i + 1][0];
            if ($pos1 >= $pos2) {
                unset($input[$i][count($input[$i]) - 1]);
                unset($input[$i + 1][0]);
                $input[$i] = array_merge($input[$i], $input[$i + 1]);
                unset($input[$i + 1]);
                $data = [];
                foreach ($input as $values) $data[] = $values;
                $input = $this->merge($data);
            }
        }
        return $input;
    }
}