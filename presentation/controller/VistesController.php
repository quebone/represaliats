<?php
namespace Represaliats\Presentation\Controller;

use Represaliats\Service\VistesService;

class VistesController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->service = new VistesService();
    }
    
    public function addVista($post): string {
        $name = $post['name'];
        $preset = $post['preset'];
        $fields = json_decode($post['fields']);
        try {
            $vista = $this->service->addVista($name, $preset, $fields);
            return json_encode([true, $vista->toArray()]);
        } catch (\Exception $e) {
            return json_encode([false, $e->getMessage()]);
        }
    }
    
    public function deleteVista($post): string {
        $id = intval($post['id']);
        try {
            return json_encode($this->service->deleteVista($id));
        } catch (\Exception $e) {
            return json_encode([false, $e->getMessage()]);
        }
    }
    
    public function printVista($post) {
        $id = intval($post['id']);
        try {
            return $this->service->printVista($id);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function getSpreadsheet($post) {
        $id = intval($post['id']);
        try {
            return $this->service->saveSpreadsheet($id);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}