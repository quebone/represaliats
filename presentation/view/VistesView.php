<?php
namespace Represaliats\Presentation\View;

use Represaliats\Service\VistesService;
use Represaliats\Service\Entities\WebPage;

class VistesView extends View
{
	private $service;
	
	public function __construct() {
		parent::__construct();
		$this->service = new VistesService();
	}
	
	/**
	 * 
	 * @param WebPage $webPage
	 * @param int $id | Identificador de la vista
	 */
	public function setItems(WebPage $webPage, int $id): void {
	    $contents = $webPage->getContents();
	    try {
	        $vista = $this->dao->getById('Vista', $id);
	    } catch (\Exception $e) {
	        throw $e;
	    }
	    //vista name
// 	    $webPage->insert('id="nom_vista"', " value=\"" . $vista->getName() . "\"");
	    //filter preset
	    //items
	    foreach ($vista->getFields() as $field) {
	        $webPage->insert("fields=\"" . $field[1] . "\"", "checked ");
	    }
	}
}