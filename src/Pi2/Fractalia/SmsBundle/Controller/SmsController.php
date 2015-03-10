<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Pi2\Fractalia\SmsBundle\Entity\Sms;
use Pi2\Fractalia\SmsBundle\Form\SmsType;

/**
 * Sms controller.
 *
 * @Route("/construccion_sms")
 */
class SmsController extends Controller
{

    /**
     * Lists all Sms entities.
     *
     * @Route("/", name="sms")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FractaliaSmsBundle:Sms')->findBY(array(), array('fechaCreacion' => 'DESC'));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Sms entity.
     *
     * @Route("/", name="sms_create")
     * @Method("POST")
     * @Template("FractaliaSmsBundle:Sms:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Sms();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sms_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Sms entity.
     *
     * @param Sms $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Sms $entity)
    {
        $form = $this->createForm(new SmsType(), $entity, array(
            'action' => $this->generateUrl('sms_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Sms entity.
     *
     * @Route("/new", name="sms_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Sms();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Sms entity.
     *
     * @Route("/{id}", name="sms_show")
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
     * @Route("/{id}/edit", name="sms_edit")
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
        $form = $this->createForm(new SmsType(), $entity, array(
            'action' => $this->generateUrl('sms_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Sms entity.
     *
     * @Route("/{id}", name="sms_update")
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
            $em->flush();

            return $this->redirect($this->generateUrl('sms_edit', array('id' => $id)));
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
     * @Route("/{id}", name="sms_delete")
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

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sms'));
    }

    /**
     * Checks if a sms came to mobile phone Contact GROUP
     * 
     * @Route("/CheckSms", name="sms_check")
     * @Method("POST") 
     */
    public function checkSms(Request $request)
    {

        $log = $this->get('monolog.logger.listener');
        $log->info("Nueva peticion de Asentimiento", array('from' => $request->getClientIp()));
        $params = array();
        $content = $this->get("request")->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true);
            if (empty($params['body']) or $params['body'] == "")
            {
                $log->info("Datos Incorrectos", array('Parametro obligatorio body' => "Sin Datos"));
                return new Response("-1", 200);
            }
            $smsMan = $this->get('fractalia_sms.sms_manager');

            $log->info("Datos recibidos", array('body' => $params['body']));

            $checkReady = $smsMan->findAndMarckSmsAsentido($params['body']);
            if ($checkReady === 2)
            {
                $log->info("No se realizo operacion", array('Asentido' => "El sms ya esta ASENTIDO"));
                return new Response("-1", 200);
            }
            elseif ($checkReady === 4)
            {
                $log->info("No se encontro el mensaje", array('Fallo la busqueda' => "No se encontro el mensaje con el texto enviado"));
                return new Response("-1", 200);
            }
            elseif ($checkReady === 3)
            {
                $log->info("No se pudo determinar el mensaje", array('Mas de un Sms' => "Existe mas de un mensaje con el texto enviado"));
                return new Response("-1", 200);
            }
            elseif ($checkReady === 0)
            {
                $log->info("Existe algun problema", array('Se debe revisar el log' => "Este error es inesperado"));
                return new Response("-1", 200);
            }
            elseif ($checkReady === 1)
            {
                $log->info("Asentido el mensaje", array('Exito' => "Se asintio el mensaje"));
                return new Response("0", 200);
            }
        }
        else
        {
            return new Response("-1", 200);
        }
    }

    /**
     * Checks if a sms came to mobile phone Contact GROUP
     * 
     * @Route("/asentir", name="sms_asentir")
     * @Method("POST") 
     */
    public function checkSmsById(Request $request)
    {

        $log = $this->get('monolog.logger.listener');
        $log->info("Nueva peticion de Asentimiento via log_sms", array('from' => $request->getClientIp()));
        $params = array();
        $content = $this->get("request")->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true);
            if (empty($params['id']) or $params['id'] == "")
            {
                $log->info("Datos Incorrectos", array('Parametro obligatorio id de mensaje' => "Sin Datos"));
                return new Response("-2", 200);
            }
            $log->info("Datos recibidos", array('id' => $params['id']));
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Sms')->find($params['id']);
            if (!$entity)
            {
                $log->info("No se encontro el sms", array('Fallo en la busqueda' => "No se encontro el sms con el id enviado: " . $params['id']));
                return new Response("-1", 200);
            }
            $entity->setEstadoEnvio("ASENTIDO");
            $entity->setFechaActualizacion(new \DateTime('NOW'));
            $em->persist($entity);
            $em->flush();
            $log->info("Asentido el sms ", array('id' => $params['id']));
            return new Response("0", 200);
        }
        else
        {
            $log->info("Datos Incorrectos");
            return new Response("-3", 200);
        }
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
                ->setAction($this->generateUrl('sms_delete', array('id' => $id)))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'Delete'))
                ->getForm()
        ;
    }

    /**
     * WorkAround for Replace ¿ for @ on the fly.
     * @param string $string
     * @return string
     */
    protected function translateToArroba($string)
    {
        $replace = array(
            '¿' => '@'
        );
        return strtr($string, $replace);
    }

    /**
     * Find ¿ in string.
     * @param array $string
     * @param string $char
     * @return string
     */
    protected function stringHasChar($string, $char)
    {
        return strpos($string, $char);
    }

}
