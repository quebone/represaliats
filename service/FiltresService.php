<?php
namespace Represaliats\Service;

use Represaliats\Service\Entities\Filtre;

/**
 * Gestiona el CRUD dels filtres
 * @author carles
 */

class FiltresService extends Service
{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Crea un filtre nou. Si el nom ja existeix, actualitza els filtres
     * @param string $name
     * @param array $filtres
     * @return Filtre
     */
    public function addFiltre(string $name, array $filtres): Filtre {
        $result = $this->dao->getByFilter('Filtre', ['name'=>$name], [], true);
        if (count($result) > 0) {
            $filtre = $result[0];
        }
        else {
            $filtre = new Filtre($name);
            $this->dao->persist($filtre);
        }
        $filtre->setFields(json_encode($filtres));
        try {
            $this->dao->flush();
            return $filtre;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Elimina el filtre amb el nom donat
     * @param string $name
     * @throws \Exception (el filtre no existeix)
     * @return bool
     */
    public function deleteFiltre(int $id): bool {
        $filtre = $this->dao->getById('Filtre', $id);
        if ($filtre != null) {
            $this->dao->remove($filtre);
            try {
                $this->dao->flush();
                return true;
            } catch (\Exception $e) {
                throw $e;
            }
        } else {
            throw new \Exception('El filtre ' . $id . ' no existeix');
        }
    }
    
    /**
     * Retorna tots els filtres existents en format array
     * @return array
     */
    public function getFiltres(): array {
        $data = [];
        $filtres = $this->dao->getByFilter('Filtre');
        foreach ($filtres as $filtre) {
            $data[] = $filtre->toArray();
        }
        return $data;
    }
    
    /**
     * Retorna el filtre corresponent a l'identificador en format array
     * @param int $id
     * @return array
     */
    public function getFiltre(int $id): array {
        return $this->dao->getById('Filtre', $id)->toArray();
    }
}