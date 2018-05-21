<?php
use Represaliats\Presentation\View\MainView;
use Represaliats\Service\Entities\WebPage;

require 'sessions.php';
require_once 'init.php';

$view = new MainView();
$data = [];
// $data["persones"] = $view->getResumFitxes();
// $data["persones"] = $view->sortEntity($data["persones"], "cognoms");
$data["tipusSituacio"] = $view->sortEntity($view->getEntity("TipusSituacio"), "nom");
$data["oficis"] = $view->sortEntity($view->getEntity("Ofici"), "nom");
$data["afiliacioPolitica"] = $view->sortEntity($view->getEntity("Partit"), "nom");
$data["afiliacioSindical"] = $view->sortEntity($view->getEntity("Sindicat"), "nom");
$data["execucions"] = $view->sortEntity($view->getEntity("Execucio"), "tipus");
$data["filtres"] = $view->sortEntity($view->getEntity("Filtre"), "id");
$data['vistes'] = $view->getEntity("Vista");
$page = new WebPage();
$page->contentsFromFile(TPLDIR.'fitxes.html');
$page->addRequired();
$template = new \Transphporm\Builder($page->getContents(), TPLDIR.'fitxes.tss');
$page->setContents($template->output($data)->body);
$page->addUserInfo($_SESSION["username"]);
$page->show();
