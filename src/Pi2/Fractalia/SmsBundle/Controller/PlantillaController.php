<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Entity\Plantilla;
use Pi2\Fractalia\SmsBundle\Form\PlantillaType;
use Pi2\Fractalia\SmsBundle\Manager\Plantill;
use Pi2\Fractalia\SmsBundle\Manager\MensajeManager;

/**
 * Plantilla controller.
 *
 * @Route("/plantilla")
 */
class PlantillaController extends Controller
{

    /**
     * Lists all Plantilla entities.
     *
     * @Route("/", name="plantilla")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $array = $this->container->getParameter('pi2_frac_sgsd_soap_server.plantillas');
        echo "<pre>";
        $tmp = array_shift($array);
        print_r($tmp);
        print_r($array);
        echo "</pre>";die;

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FractaliaSmsBundle:Plantilla')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Plantilla entity.
     *
     * @Route("/", name="plantilla_create")
     * @Method("POST")
     * @Template("FractaliaSmsBundle:Plantilla:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Plantilla();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('plantilla_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Plantilla entity.
     *
     * @param Plantilla $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Plantilla $entity)
    {
        $form = $this->createForm(new PlantillaType(), $entity, array(
            'action' => $this->generateUrl('plantilla_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Plantilla entity.
     *
     * @Route("/new", name="plantilla_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Plantilla();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Plantilla entity.
     *
     * @Route("/{id}", name="plantilla_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Plantilla')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Plantilla entity.
     *
     * @Route("/{id}/edit", name="plantilla_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Plantilla')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
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
     * Creates a form to edit a Plantilla entity.
     *
     * @param Plantilla $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Plantilla $entity)
    {
//        $form = $this->createForm(new PlantillaType(), $entity, array(
//            'action' => $this->generateUrl('plantilla_update', array('id' => $entity->getId())),
//            'method' => 'PUT',
//        ));
        $form = $this->createUpdateForm();

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Plantilla entity.
     *
     * @Route("/{id}", name="plantilla_update")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:Plantilla:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Plantilla')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid())
        {
            $em->flush();

            return $this->redirect($this->generateUrl('plantilla_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Plantilla entity.
     *
     * @Route("/{id}", name="plantilla_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Plantilla')->find($id);

            if (!$entity)
            {
                throw $this->createNotFoundException('Unable to find Plantilla entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('plantilla'));
    }

    /**
     * Creates a form to delete a Plantilla entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                ->setAction($this->generateUrl('plantilla_delete', array('id' => $id)))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'Delete'))
                ->getForm()
        ;
    }

    /**
     * Creates a form to update a Plantilla entity by Id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createUpdateForm()
    {
        $plantillaObj = new Plantill();
        $plantilla = $plantillaObj->getPlantillaResuelto();
        $id = 63;

        $em = $this->getDoctrine()->getManager();

        $incidencia = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')->find($id);
        $msj = new MensajeManager($incidencia, $plantilla);
        echo "<pre>";
        print_r($msj->fillArrayWithIncidencia());
        echo "</pre>";

        die;


        $defaultData = array('plantilla' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('id', 'text', array('label' => 'ID: '))
            ->add('cliente', 'text', array('label' => 'CLIENTE: '))
            ->add('tipo', 'text', array('label' => 'TIPO: '))
            ->add('tecnico', 'text', array('label' => 'TECNICO: '))
            ->add('tsol', 'text', array('label' => 'TSOL: '))
            ->add('fecha', 'datetime', array('label' => 'FECHA: '))
            ->add('modo', 'text', array('label' => 'MODO RECEPCION: '))
            ->add('detalle', 'text', array('label' => 'DETALLE: '))
            ->getForm();

        return $form;
    }

}
