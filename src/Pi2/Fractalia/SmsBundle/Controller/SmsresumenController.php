<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Entity\Smsresumen;
use Pi2\Fractalia\SmsBundle\Form\SmsresumenType;

/**
 * Smsresumen controller.
 *
 * @Route("/smsresumen")
 */
class SmsresumenController extends Controller
{

    /**
     * Lists all Smsresumen entities.
     *
     * @Route("/", name="smsresumen")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FractaliaSmsBundle:Smsresumen')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Smsresumen entity.
     *
     * @Route("/", name="smsresumen_create")
     * @Method("POST")
     * @Template("FractaliaSmsBundle:Smsresumen:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Smsresumen();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('smsresumen_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Smsresumen entity.
    *
    * @param Smsresumen $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Smsresumen $entity)
    {
        $form = $this->createForm(new SmsresumenType(), $entity, array(
            'action' => $this->generateUrl('smsresumen_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Smsresumen entity.
     *
     * @Route("/new", name="smsresumen_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Smsresumen();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Smsresumen entity.
     *
     * @Route("/{id}", name="smsresumen_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Smsresumen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Smsresumen entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Smsresumen entity.
     *
     * @Route("/{id}/edit", name="smsresumen_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Smsresumen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Smsresumen entity.');
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
    * Creates a form to edit a Smsresumen entity.
    *
    * @param Smsresumen $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Smsresumen $entity)
    {
        $form = $this->createForm(new SmsresumenType(), $entity, array(
            'action' => $this->generateUrl('smsresumen_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Smsresumen entity.
     *
     * @Route("/{id}", name="smsresumen_update")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:Smsresumen:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Smsresumen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Smsresumen entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('smsresumen_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Smsresumen entity.
     *
     * @Route("/{id}", name="smsresumen_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Smsresumen')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Smsresumen entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('smsresumen'));
    }

    /**
     * Creates a form to delete a Smsresumen entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('smsresumen_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
