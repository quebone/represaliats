<?php
namespace Represaliats\Service;

use Represaliats\Service\Entities\Vista;
use Represaliats\Presentation\View\VistaPdfView;
use Represaliats\Presentation\View\VistaSpreadsheetView;

/**
 * Gestiona les vistes de dades
 * @author carles
 */

class VistesService extends Service
{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Crea una vista. Si el nom ja existeix, n'actualitza els paràmetres
     * @param string $name : Nom de la vista
     * @param string $preset : Nom del filtre
     * @param string $fields : Camps a mostrar
     * @throws \Exception
     * @return Vista
     */
    public function addVista(string $name, string $preset, array $fields): Vista {
        $result = $this->dao->getByFilter('Vista', ['name'=>$name], [], true);
        if (count($result) > 0) {
            $vista = $result[0];
        } else {
            $vista = new Vista($name);
            $this->dao->persist($vista);
        }
        $filtre = $this->dao->getById('Filtre', $preset);
        $vista->setFields($fields);
        $vista->setFiltre($filtre);
        try {
            $this->dao->flush();
            return $vista;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Elimina una vista
     * @param string $name | Nom de la vista
     * @throws \Exception
     * @return bool
     */
    public function deleteVista(int $id): bool {
        try {
            $vista = $this->dao->getById('Vista', $id);
            $this->dao->remove($vista);
            $this->dao->flush();
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Crea el pdf d'una vista
     * @param int $id
     * @throws \Exception
     * @return string
     */
    public function printVista(int $id) {
        try {
            $vista = $this->dao->getById('Vista', $id);
            $filtre = $vista->getFiltre();
            $fields = $filtre->getFieldsClean();
            $filterService = new FilterService('Persona', $fields);
            $persones = $filterService->applyFilters();
            $persones = $this->order($persones, 'cognoms');
            $vistaPdfView = new VistaPdfView($persones, $vista);
            return $vistaPdfView->getPdf();
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Crea un full de càlcul d'una vista
     * @param int $id
     * @throws \Exception
     * @return string
     */
    public function saveSpreadsheet(int $id) {
        try {
            $vista = $this->dao->getById('Vista', $id);
            $filtre = $vista->getFiltre();
            $fields = $filtre->getFieldsClean();
            $filterService = new FilterService('Persona', $fields);
            $persones = $filterService->applyFilters();
            $persones = $this->order($persones, 'cognoms');
            $ssView = new VistaSpreadsheetView($persones, $vista);
            return $ssView->getSpreadsheet();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}