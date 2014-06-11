<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Entity\Resumen;
use Pi2\Fractalia\SmsBundle\Form\ResumenType;

/**
 * Resumen controller.
 *
 * @Route("/resumen")
 */
class ResumenController extends Controller
{

    /**
     * Lists all Resumen entities.
     *
     * @Route("/", name="resumen")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FractaliaSmsBundle:Resumen')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Resumen entity.
     *
     * @Route("/", name="resumen_create")
     * @Method("POST")
     * @Template("FractaliaSmsBundle:Resumen:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Resumen();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('resumen_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Resumen entity.
    *
    * @param Resumen $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Resumen $entity)
    {
        $form = $this->createForm(new ResumenType(), $entity, array(
            'action' => $this->generateUrl('resumen_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Resumen entity.
     *
     * @Route("/new", name="resumen_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Resumen();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Resumen entity.
     *
     * @Route("/{id}", name="resumen_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Resumen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Resumen entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Resumen entity.
     *
     * @Route("/{id}/edit", name="resumen_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Resumen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Resumen entity.');
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
    * Creates a form to edit a Resumen entity.
    *
    * @param Resumen $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Resumen $entity)
    {
        $form = $this->createForm(new ResumenType(), $entity, array(
            'action' => $this->generateUrl('resumen_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Resumen entity.
     *
     * @Route("/{id}", name="resumen_update")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:Resumen:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Resumen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Resumen entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('resumen_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Resumen entity.
     *
     * @Route("/{id}", name="resumen_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Resumen')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Resumen entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('resumen'));
    }

    /**
     * Creates a form to delete a Resumen entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('resumen_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
