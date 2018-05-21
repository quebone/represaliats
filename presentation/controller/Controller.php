<?php
namespace Represaliats\Presentation\Controller;

use Represaliats\DAO;
use Represaliats\Service\Service;
use Represaliats\Presentation\View\FinderView;

class Controller
{
	protected $dao;
	protected $service;
	
	public function __construct() {
		$this->dao = DAO::getInstance();
		$this->service = new Service();
	}

	protected function parsePost($post)
	{
		$data = array();
		foreach ($post as $key => $value) {
			$arr = $this->parseName($key);
			$arr["value"] = $value;
			array_push($data, $arr);
		}
		return $data;
	}
	
	protected function parseName($data)
	{
		$tmp = explode("-", $data);
		return array("key" => $tmp[0], "id" => $tmp[1]);
	}
	
	/**
	 * Canvia el valor d'una propietat de tipus string/int/bool
	 * @param array $post
	 * @return string
	 */
	public function changeValue(array $post) {
		$entity = $post["entity"];
		$id = $post["id"];
		$propertyName = $post["propertyName"];
		$propertyEntity = isset($post["propertyEntity"]) ? $post["propertyEntity"] : null;
		$value = $post["value"];
		if (!strcmp($value, "")) $value = null;
		if (!strcmp($value, "false")) $value = false;
		if (!strcmp($value, "true")) $value = true;
		try {
		    return json_encode([true, $this->service->changeValue(ucfirst($entity), $id, $propertyName, $value, $propertyEntity)]);
		} catch (\Exception $e) {
		    return json_encode([false, $e->getMessage()]);
		}
	}
	
	public function deleteEntity($post) {
		$entity = $post["entity"];
		$id = $post["id"];
		try {
		    return json_encode([true, $this->service->deleteEntity(ucfirst($entity), $id)]);
		} catch (\Exception $e) {
		    return json_encode([false, $e->getMessage()]);
		}
	}
	
	public function find($post) {
	    $needle = $post['needle'];
	    if (strlen($needle) > 3) {
	        $view = new FinderView();
	        $results = $view->formatSearch($needle);
	        return json_encode($results);
	    } else return false;
	}
}