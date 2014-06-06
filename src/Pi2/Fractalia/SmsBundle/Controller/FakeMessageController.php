<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Entity\FakeMessage;
use Pi2\Fractalia\SmsBundle\Form\FakeMessageType;

/**
 * FakeMessage controller.
 *
 * @Route("/mensajes")
 */
class FakeMessageController extends Controller
{

    /**
     * Lists all FakeMessage entities.
     *
     * @Route("/", name="mensajes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FractaliaSmsBundle:FakeMessage')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new FakeMessage entity.
     *
     * @Route("/", name="mensajes_create")
     * @Method("POST")
     * @Template("FractaliaSmsBundle:FakeMessage:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new FakeMessage();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mensajes_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a FakeMessage entity.
    *
    * @param FakeMessage $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(FakeMessage $entity)
    {
        $form = $this->createForm(new FakeMessageType(), $entity, array(
            'action' => $this->generateUrl('mensajes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new FakeMessage entity.
     *
     * @Route("/new", name="mensajes_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FakeMessage();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FakeMessage entity.
     *
     * @Route("/{id}", name="mensajes_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:FakeMessage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FakeMessage entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FakeMessage entity.
     *
     * @Route("/{id}/edit", name="mensajes_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:FakeMessage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FakeMessage entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a FakeMessage entity.
    *
    * @param FakeMessage $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(FakeMessage $entity)
    {
        $form = $this->createForm(new FakeMessageType(), $entity, array(
            'action' => $this->generateUrl('mensajes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing FakeMessage entity.
     *
     * @Route("/{id}", name="mensajes_update")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:FakeMessage:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:FakeMessage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FakeMessage entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('mensajes_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FakeMessage entity.
     *
     * @Route("/{id}", name="mensajes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:FakeMessage')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FakeMessage entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mensajes'));
    }

    /**
     * Creates a form to delete a FakeMessage entity by id.
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
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
