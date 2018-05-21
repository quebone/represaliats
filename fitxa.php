<?php
use Represaliats\Presentation\Controller\PersonaController;
use Represaliats\Service\Entities\WebPage;
use Represaliats\Presentation\View\View;

require 'sessions.php';
if (!isset($_GET) || !isset($_GET["id"])) {
	die();
}
require_once 'init.php';

$controller = new PersonaController();
try {
	$data = $controller->getPersona(intval($_GET["id"]));
	$view = new View();
	$data['vistes'] = $view->getEntity("Vista");
	$template = new \Transphporm\Builder(TPLDIR.'fitxa.html', TPLDIR.'fitxa.tss');
	$page = new WebPage();
	$page->setContents($template->output($data)->body);
	$page->addUserInfo($_SESSION["username"]);
	$page->show();
} catch (Exception $e) {
	die($e->getMessage());
}
