<?php

use Represaliats\Presentation\Controller\FitxaController;

require_once 'init.php';
require_once 'presentation/controller/Controller.php';

define('DEBUG', true);

function toArgs($input): array {
    $data = [];
    $fields = explode("&", $input);
    foreach ($fields as $field) {
        $arr = explode("=", $field);
        $data[$arr[0]] = $arr[1];
    }
    return $data;
}

function test01() {
    $_POST = toArgs('entityName=Informe&id=71&property=registre&value=Tampoc se sap&function=changeSimple&caller=Fitxa');
    require_once 'AjaxController.php';
}

function test02() {
    $_GET['id'] = 23;
    $controller = new FitxaController();
    $controller->printPdf($_GET);
}

test01();
