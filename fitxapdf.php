<?php
use Represaliats\Presentation\Controller\FitxaController;

require_once 'init.php';
// $_GET['id'] = 23;

if (!isset($_GET) || !isset($_GET['id'])) die();

$controller = new FitxaController();
$controller->printPdf($_GET);