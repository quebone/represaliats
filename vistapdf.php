<?php
use Represaliats\Presentation\Controller\VistesController;

require_once 'init.php';

if (!isset($_GET) || !isset($_GET['id'])) die();

$controller = new VistesController();
$controller->printVista($_GET);