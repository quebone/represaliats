<?php
use Represaliats\Presentation\Controller\FitxaController;

require_once 'init.php';

if (!isset($_GET) || !isset($_GET['id'])) die();

$controller = new FitxaController();
$controller->printPdf($_GET);