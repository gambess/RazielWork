<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Entity\Sms;
use Pi2\Fractalia\SmsBundle\Form\SmseventoType;
use Pi2\Fractalia\SmsBundle\Form\SmsresumenType;
use Doctrine\ORM\PersistentCollection;
use Pi2\Fractalia\SmsBundle\Entity\Columnaevento;

/**
 * Mensajes controller.
 *
 * @Route("/mensajes")
 */
class DefaultController extends Controller {

    /**
     * Lista Todos los mensajes.
     *
     * @Route("/", name="mensajes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        
        //TODO: Quitar las peticiones de data access de aqui
        $entities = $em->getRepository('FractaliaSmsBundle:Sms')
                    ->findBy(
                            array('estadoEnvio'=> 
                                array(
                                    'ERROR', 'POR_ENVIAR', 'ERROR_BUILD', 'ENVIADO'
                                    )
                                ),
                            array(
                                'fechaCreacion'=>'DESC',
                                )
                            );
        
        $tsol = $em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
        if(is_null($tsol)){
            $configuraciones = $this->container->get('fractalia_sms.configuracion_manager');
            $configuraciones->saveTsol();
            $tsol = $em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
        }
        
        $nombres = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->findAll();
        if(!is_array($nombres) or count($nombres) == 0){
            $configuraciones = $this->container->get('fractalia_sms.configuracion_manager');
            $configuraciones->saveNombreCorto();
            $nombres = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->findAll();
        }

        return array(
            'entities' => $entities,
            'tsol' => $tsol,
            'nombres' => $nombres,
        );
    }

    /**
     * Finds and displays a Sms entity.
     *
     * @Route("/{id}", name="mensajes_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Sms')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sms entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Sms entity.
     *
     * @Route("/{id}/edit", name="mensajes_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Sms')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sms entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Sms entity.
     *
     * @param Sms $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Sms $entity) {
        if (null != $entity->getMensaje()->getColumnaEvento()) {
            $form = $this->createForm(new SmseventoType(), $entity, array(
                'action' => $this->generateUrl('mensajes_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
        }
        if (count($entity->getMensaje()->getColumnaResumen()) > 0) {
            $form = $this->createForm(new SmsresumenType(), $entity, array(
                'action' => $this->generateUrl('mensajes_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
        }

        $form->add('submit', 'submit', array('label' => 'Actualizar Sms'));

        return $form;
    }

    /**
     * Edits an existing Sms entity.
     *
     * @Route("/{id}", name="mensajes_update")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:Sms:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Sms')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sms entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            //Esta logica deberia encapsularse en el manager de mensajes
            $now = new \DateTime("NOW");
            $msj = $entity->getMensaje();
            $resumen = $msj->getColumnaresumen();
            $evento = $msj->getColumnaevento();
            $entity->setFechaActualizacion($now);
            $msj->setFechaActualizacion($now);
            $msj->setEstado("CORRECT");
            $entity->setEstadoEnvio("POR_ENVIAR");
            if (null != $evento) {
                $msj->setTexto($this->getText($evento, $msj->getNombrePlantilla()));
            }
            if (count($resumen) > 0){
                $msj->setTexto($this->getText($resumen));
            }
            $em->persist($msj);
            $em->flush();

            return $this->redirect($this->generateUrl('mensajes_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Sms entity.
     *
     * @Route("/{id}", name="mensajes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Sms')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sms entity.');
            }
            $now = new \DateTime("NOW");
            $entity->setFechaActualizacion($now);
            $state = $entity->getEstadoEnvio();
            $state.= "_Y_CANCEL";
            $entity->setEstadoEnvio($state);
            $em->persist($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mensajes'));
    }

    /**
     * Creates a form to delete a Sms entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('mensajes_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Remover del Listado'))
                        ->getForm()
        ;
    }

    /*
     * Se renderiza la plantilla con el texto en txt
     * que se copia como cuerpo del mensaje
     */

    protected function getText($entity, $plantilla = null) {
        if ($entity instanceof Columnaevento) {
            return $this->renderView('FractaliaSmsBundle:Default:evento.txt.twig', array(
                'label' => $this->getLabelsFromConfigByEvento($plantilla),
                'entity' => $entity
            ));
        }
        if (($entity instanceof PersistentCollection) and (count($entity) > 0)) {
            return $this->renderView('FractaliaSmsBundle:Columnaresumen:text.txt.twig', array(
                'label' => $this->getLabelFromConfigByResumen('titulo'),
                'entities' => $entity
            ));
        }
    }

    /*
     * Setear las etiquetas de los textos con datos de la configuracion
     */

    protected function getLabelsFromConfigByEvento($name) {
        return $this->container->getParameter('fractalia_sms.plantillas')[$name];
    }

    protected function getLabelFromConfigByResumen($name) {
        return $this->container->getParameter('fractalia_sms.resumenes.resumen')[$name];
    }

}
