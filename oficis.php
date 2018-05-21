<?php
use Represaliats\Presentation\View\View;
use Represaliats\Service\Entities\WebPage;

require 'sessions.php';
require_once 'init.php';

$view = new View();
$data['dades'] = $view->getEntity("Ofici");
$data['dades'] = $view->sortEntity($data['dades'], "nom");
$data['vistes'] = $view->getEntity("Vista");
$template = new \Transphporm\Builder(TPLDIR.'oficis.html', TPLDIR.'oficis.tss');
$page = new WebPage();
$page->setContents($template->output($data)->body);
$page->addUserInfo($_SESSION["username"]);
$page->show();