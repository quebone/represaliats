<?php
use Represaliats\Presentation\View\View;
use Represaliats\Service\Entities\WebPage;

require 'sessions.php';
require_once 'init.php';

$view = new View();
$data['dades'] = $view->getEntity("Font");
$data['dades'] = $view->sortEntity($data['dades'], "nom");
$data['dades'] = $view->removeDefault($data['dades'], "nom");
$data['vistes'] = $view->getEntity("Vista");
$template = new \Transphporm\Builder(TPLDIR.'fonts.html', TPLDIR.'fonts.tss');
$page = new WebPage();
$page->setContents($template->output($data)->body);
$page->addUserInfo($_SESSION["username"]);
$page->show();