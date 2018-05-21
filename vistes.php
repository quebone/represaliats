<?php
use Represaliats\Presentation\View\View;
use Represaliats\Service\Entities\WebPage;
use Represaliats\Presentation\View\VistesView;
use Represaliats\DAO;

require 'sessions.php';
require_once 'init.php';

$view = new View();
$data = [];
$data['vistes'] = $view->getEntity("Vista");
$data['filtres'] = $view->getEntity("Filtre");
$page = new WebPage();
$page->contentsFromFile(TPLDIR.'vistes.html');
if (isset($_GET) && isset($_GET['id'])) {
    $dao = DAO::getInstance();
    $data['vista'] = $dao->getById('Vista', intval($_GET['id']))->toArray();
    $vistesView = new VistesView();
    $vistesView->setItems($page, intval($_GET['id']));
}
$template = new \Transphporm\Builder($page->getContents(), TPLDIR.'vistes.tss');
$page->setContents($template->output($data)->body);
$page->addUserInfo($_SESSION["username"]);
$page->show();