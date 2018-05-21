<?php
namespace Represaliats\Service;

class Translator
{
    private $items = [];
    
    public function __construct($lang="ca") {
        $this->changeLanguage($lang);
    }
    
    public function changeLanguage($lang): void {
        $handle = fopen(LANGUAGESDIR . $lang . ".txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = str_replace("\n", "", $line);
                if (strlen($line) > 0)
                    $this->items[strtolower(explode(" => ", $line)[0])] = explode(" => ", $line)[1];
            }
            fclose($handle);
        } else {
            // error opening the file.
        }
    }
    
    /**
     * Gets the translation of a single value
     * @param string $key
     * @return string|NULL
     */
    public function get(?string $key): ?string {
        if ($key != null  && array_key_exists(strtolower($key), $this->items))
            return $this->items[strtolower($key)];
            return $key;
    }
    
    /**
     * Gets the translation of all the array's keys
     * @param array $input
     * @return array
     */
    public function getKeys(array $input): array {
        $output = [];
        foreach ($input as $key => $value) {
            $output[$this->get($key)] = $value;
        }
        return $output;
    }
    
    /**
     * Gets the translation of all the array's keys with first letter in capitals
     * @param array $input
     * @return array
     */
    public function getCaps(?string $key): ?string {
        return ucfirst($this->get($key));
    }
}