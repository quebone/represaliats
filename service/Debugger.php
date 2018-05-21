<?php
namespace Represaliats\Service;

class Debugger
{
    public static function debug($output)
    {
        if (defined('DEBUG')) {
            if (is_scalar($output)) {
                echo $output . "\n";
            } else {
                var_dump($output);
            }
        }
    }
}