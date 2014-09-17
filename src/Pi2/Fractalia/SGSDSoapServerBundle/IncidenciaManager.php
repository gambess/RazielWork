<?php
namespace Pi2\Fractalia\SGSDSoapServerBundle;

use Doctrine\ORM\EntityManager;
use Pi2\Fractalia\Entity\SGSD\Incidencia;

class IncidenciaManager {
    private $em;   

    public function __construct(EntityManager $em) {
        $this->em = $em;       
    }
    
    public function updateOldFromNew(Incidencia $incidenciaOld, Incidencia $incidenciaNew) {
        $clase = new \ReflectionClass('Pi2\Fractalia\Entity\SGSD\Incidencia');
        $propiedades = $clase->getProperties(\ReflectionProperty::IS_PRIVATE);

        foreach ($propiedades as $propiedad) {
            if ($propiedad->name == 'id' ||
                    $propiedad->name == 'acciones' ||
                    $propiedad->name == 'descripciones' ||
                    $propiedad->name == 'infoAdjuntos' ||
                    $propiedad->name == 'resoluciones') {
                continue;
            }

            $valorProp = $incidenciaNew->{"get" . ucfirst($propiedad->getName())}();
            if (!is_null($valorProp)) {
                $incidenciaOld->{"set" . ucfirst($propiedad->getName())}($valorProp);
            }            
        }
        
        $incidenciaOld->addAcciones($incidenciaNew->getAcciones());
        $incidenciaOld->addDescripciones($incidenciaNew->getDescripciones());
        $incidenciaOld->addResoluciones($incidenciaNew->getResoluciones());
        $incidenciaOld->addInfoAdjuntos($incidenciaNew->getInfoAdjuntos());
        
    }
}
