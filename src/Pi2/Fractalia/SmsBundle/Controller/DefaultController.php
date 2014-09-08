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
 * @Route("/log_sms")
 */
class DefaultController extends Controller
{

    /**
     * Lista Todos los mensajes.
     *
     * @Route("/", name="mensajes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //TODO: Quitar las peticiones de data access de aqui
        $entities = $em->getRepository('FractaliaSmsBundle:Sms')
            ->findBy(
            array('estadoEnvio' =>
            array(
                'ERROR', 'POR_ENVIAR', 'ERROR_BUILD', 'ENVIADO'
            )
            ), array(
            'fechaCreacion' => 'DESC',
            )
        );

        $tsol = $em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
        if (is_null($tsol))
        {
            $configuraciones = $this->container->get('fractalia_sms.configuracion_manager');
            $configuraciones->saveTsol();
            $tsol = $em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
        }

        $nombres = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->findAll();
        if (!is_array($nombres) or count($nombres) == 0)
        {
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
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Sms')->find($id);

        if (!$entity)
        {
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
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Sms')->find($id);

        if (!$entity)
        {
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
    private function createEditForm(Sms $entity)
    {
        if (null != $entity->getMensaje()->getColumnaEvento())
        {
            $form = $this->createForm(new SmseventoType(), $entity, array(
                'action' => $this->generateUrl('mensajes_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
        }
        if (count($entity->getMensaje()->getColumnaResumen()) > 0)
        {
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
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Sms')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Sms entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid())
        {
            //Esta logica deberia encapsularse en el manager de mensajes
            $now = new \DateTime("NOW");
            $msj = $entity->getMensaje();
            $resumen = $msj->getColumnaresumen();
            $evento = $msj->getColumnaevento();
            $entity->setFechaActualizacion($now);
            $msj->setFechaActualizacion($now);
            $msj->setEstado("CORRECT");
            $entity->setEstadoEnvio("POR_ENVIAR");
            if (null != $evento)
            {
                $texto_evento = $this->getText($evento, $msj->getNombrePlantilla());
                if (strlen($texto_evento) >= 434)
                {
                    $texto_evento = substr($texto_evento, 0, 434);
                }

                $msj->setTexto(rtrim($this->translateChars($texto_evento)));
            }
            if (count($resumen) > 0)
            {
                $msj->setTexto(rtrim($this->translateChars($this->getText($resumen))));
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
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Sms')->find($id);

            if (!$entity)
            {
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
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                ->setAction($this->generateUrl('mensajes_delete', array('id' => $id)))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'Remover del Listado'))
                ->getForm()
        ;
    }
    
    /**
     * Creates a form to edit a Sms entity.
     *
     * @param Sms $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm(Sms $entity)
    {
        if (null != $entity->getMensaje()->getColumnaEvento())
        {
            $form = $this->createForm(new SmseventoType(), $entity, array(
                'action' => $this->generateUrl('mensajes_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
        }
        if (count($entity->getMensaje()->getColumnaResumen()) > 0)
        {
            $form = $this->createForm(new SmsresumenType(), $entity, array(
                'action' => $this->generateUrl('mensajes_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
        }

        $form->add('submit', 'submit', array('label' => 'Actualizar Sms'));

        return $form;
    }

    /*
     * Se renderiza la plantilla con el texto en txt
     * que se copia como cuerpo del mensaje
     */

    protected function getText($entity, $plantilla = null)
    {
        if ($entity instanceof Columnaevento)
        {
            return $this->renderView('FractaliaSmsBundle:Default:evento.txt.twig', array(
                    'label' => $this->getLabelsFromConfigByEvento($plantilla),
                    'entity' => $entity
            ));
        }
        if (($entity instanceof PersistentCollection) and ( count($entity) > 0))
        {
            return $this->renderView('FractaliaSmsBundle:Columnaresumen:text.txt.twig', array(
                    'label' => $this->getLabelFromConfigByResumen('titulo'),
                    'entities' => $entity
            ));
        }
    }

    /*
     * Setear las etiquetas de los textos con datos de la configuracion
     */

    protected function getLabelsFromConfigByEvento($name)
    {
        return $this->container->getParameter('fractalia_sms.plantillas')[$name];
    }

    protected function getLabelFromConfigByResumen($name)
    {
        return $this->container->getParameter('fractalia_sms.resumenes.resumen')[$name];
    }

    /**
     * Replace language-specific characters by ASCII-equivalents.
     * @param string $s
     * @return string
     */
    protected function translateChars($s)
    {
        $replace = array(
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae', 'Å' => 'A', 'Æ' => 'A', 'Ă' => 'A', 'Ą' => 'A', 'ą' => 'a',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'ae', 'å' => 'a', 'ă' => 'a', 'æ' => 'ae',
            'þ' => 'b', 'Þ' => 'B',
            'Ç' => 'C', 'ç' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ę' => 'E', 'ę' => 'e',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'Ğ' => 'G', 'ğ' => 'g',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'İ' => 'I', 'ı' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'Ł' => 'L', 'ł' => 'l',
            'Ñ' => 'N', 'Ń' => 'N', 'ń' => 'n',
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'Oe', 'Ø' => 'O', 'ö' => 'oe', 'ø' => 'o',
            'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'Š' => 'S', 'š' => 's', 'Ş' => 'S', 'ș' => 's', 'Ș' => 'S', 'ş' => 's', 'ß' => 'ss', 'Ś' => 'S', 'ś' => 's',
            'ț' => 't', 'Ț' => 'T',
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'ue',
            'Ý' => 'Y',
            'ý' => 'y', 'ý' => 'y', 'ÿ' => 'y',
            'Ž' => 'Z', 'ž' => 'z', 'Ż' => 'Z', 'ż' => 'z', 'Ź' => 'Z', 'ź' => 'z',
            //extra aparte de letras otros casos
//        '_' => ' ', '^' => '`',
            '{' => '(', '}' => ')', '|' => '/', '[' => '(', ']' => ')',
            '€' => 'E',
        );
        return strtr($s, $replace);
    }

    /**
     * Displays a list of existing Sms filters by string.
     *
     * @Route("/{string}/search", name="mensajes_search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction($string)
    {
        {
            $em = $this->getDoctrine()->getManager();

            //TODO: Quitar las peticiones de data access de aqui
            if(!is_null($string)){
                $string = strtoupper($string);
                $string = $this->translateState($string);
            }
            $entities = $em->getRepository('FractaliaSmsBundle:Sms')->findByString($string);
                

            $tsol = $em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
            if (is_null($tsol))
            {
                $configuraciones = $this->container->get('fractalia_sms.configuracion_manager');
                $configuraciones->saveTsol();
                $tsol = $em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
            }

            $nombres = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->findAll();
            if (!is_array($nombres) or count($nombres) == 0)
            {
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
    }
    
    /*
     * Returns a translate string for state
     * @param string $string, a string to search in sms
     */
    protected function translateState($string)
    {
        if (!is_null($string))
        {
            switch ($string)
            {
                case 'CORRECTO':
                    return "ENVIADO";
                case 'FALLO_TEXTO':
                    return "ERROR_BUILD";
                case 'FALLO_ENVIO':
                    return "FAIL";
                case 'POR_ENVIAR':
                    return 'POR_ENVIAR';
                default:
                    return $string;
            }
        }
    }

}
