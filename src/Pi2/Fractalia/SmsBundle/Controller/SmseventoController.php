<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Entity\Smsevento;
use Pi2\Fractalia\SmsBundle\Form\SmseventoType;

/**
 * Smsevento controller.
 *
 * @Route("/smsevento")
 */
class SmseventoController extends Controller
{

    /**
     * Lists all Smsevento entities.
     *
     * @Route("/", name="smsevento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FractaliaSmsBundle:Smsevento')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Smsevento entity.
     *
     * @Route("/", name="smsevento_create")
     * @Method("POST")
     * @Template("FractaliaSmsBundle:Smsevento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Smsevento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('smsevento_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Smsevento entity.
    *
    * @param Smsevento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Smsevento $entity)
    {
        $form = $this->createForm(new SmseventoType(), $entity, array(
            'action' => $this->generateUrl('smsevento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Smsevento entity.
     *
     * @Route("/new", name="smsevento_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Smsevento();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Smsevento entity.
     *
     * @Route("/{id}", name="smsevento_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Smsevento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Smsevento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Smsevento entity.
     *
     * @Route("/{id}/edit", name="smsevento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Smsevento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Smsevento entity.');
        }
        

        $editForm = $this->createEditForm($entity);
        echo "aqui";die;
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Smsevento entity.
    *
    * @param Smsevento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Smsevento $entity)
    {
        $form = $this->createForm(new SmseventoType(), $entity, array(
            'action' => $this->generateUrl('smsevento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'label_attr' => $this->getLabelsFromConfigByEvento('RESUELTO')
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Smsevento entity.
     *
     * @Route("/{id}", name="smsevento_update")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:Smsevento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Smsevento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Smsevento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('smsevento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Smsevento entity.
     *
     * @Route("/{id}", name="smsevento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Smsevento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Smsevento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('smsevento'));
    }

    /**
     * Creates a form to delete a Smsevento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('smsevento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    protected function getLabelsFromConfigByEvento($name)
    {
        return $this->container->getParameter('pi2_frac_sgsd_soap_server.plantillas')[$name];
    }
}
