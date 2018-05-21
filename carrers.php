<?php
use Represaliats\Presentation\View\View;
use Represaliats\Service\Entities\WebPage;

require 'sessions.php';
require_once 'init.php';

$view = new View();
$data['dades'] = $view->getEntity("Carrer");
$data['dades'] = $view->sortEntity($data['dades'], "nomAnterior");
$data['dades'] = $view->removeDefault($data['dades'], "nomActual");
$data['vistes'] = $view->getEntity("Vista");
$template = new \Transphporm\Builder(TPLDIR.'carrers.html', TPLDIR.'carrers.tss');
$page = new WebPage();
$page->setContents($template->output($data)->body);
$page->addUserInfo($_SESSION["username"]);
$page->show();