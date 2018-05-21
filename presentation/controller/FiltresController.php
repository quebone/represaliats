<?php
namespace Represaliats\Presentation\Controller;

use Represaliats\Service\FiltresService;

class FiltresController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->service = new FiltresService();
    }
    
    public function addFiltre($post): string {
        $name = $post['name'];
        $filtres = json_decode($post['filters']);
        try {
            $filtre = $this->service->addFiltre($name, $filtres);
            return json_encode([true, $filtre->toArray()]);
        } catch (\Exception $e) {
            return json_encode([false, $e->getMessage()]);
        }
    }
    
    public function deleteFiltre($post): string {
        $id = intval($post['id']);
        try {
            return json_encode([true, $this->service->deleteFiltre($id)]);
        } catch (\Exception $e) {
            return json_encode([false, $e->getMessage()]);
        }
    }
    
    public function getFiltres($post): string {
        return json_encode($this->service->getFiltres());
    }
    
    public function getFiltre($post): string {
        $id = intval($post['id']);
        try {
            return json_encode([true, $this->service->getFiltre($id)]);
        } catch (\Exception $e) {
            return json_encode([false, $e->getMessage()]);
        }
    }
}