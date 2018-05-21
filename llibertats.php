<?php
use Represaliats\Presentation\View\View;
use Represaliats\Service\Entities\WebPage;

require 'sessions.php';
require_once 'init.php';

$view = new View();
$data['dades'] = $view->getEntity("Llibertat");
$data['dades'] = $view->sortEntity($data['dades'], "tipus");
$data['dades'] = $view->removeDefault($data['dades'], "tipus");
$data['vistes'] = $view->getEntity("Vista");
$template = new \Transphporm\Builder(TPLDIR.'llibertats.html', TPLDIR.'llibertats.tss');
$page = new WebPage();
$page->setContents($template->output($data)->body);
$page->addUserInfo($_SESSION["username"]);
$page->show();