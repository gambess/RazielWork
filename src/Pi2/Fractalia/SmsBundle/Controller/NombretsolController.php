<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Entity\Nombretsol;
use Pi2\Fractalia\SmsBundle\Form\NombretsolType;

/**
 * Nombretsol controller.
 *
 * @Route("/tsol")
 */
class NombretsolController extends Controller
{

    /**
     * Lists all Nombretsol entities.
     *
     * @Route("/", name="tsol")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('FractaliaSmsBundle:Nombretsol')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all Nombretsol entities.
     *
     * @Route("/show", name="tsol_mostrar")
     * @Method("GET")
     * @Template()
     */
    public function mostrarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Creates a new Nombretsol entity.
     *
     * @Route("/", name="tsol_create")
     * @Method("POST")
     * @Template("FractaliaSmsBundle:Nombretsol:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Nombretsol();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tsol_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Nombretsol entity.
     *
     * @param Nombretsol $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Nombretsol $entity)
    {
        $form = $this->createForm(new NombretsolType(), $entity, array(
            'action' => $this->generateUrl('tsol_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Nombretsol entity.
     *
     * @Route("/new", name="tsol_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Nombretsol();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Nombretsol entity.
     *
     * @Route("/{id}", name="tsol_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombretsol')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombretsol entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Nombretsol entity.
     *
     * @Route("/{id}/edit", name="tsol_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombretsol')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombretsol entity.');
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
     * Displays a form to edit an existing Nombretsol entity.
     *
     * @Route("/{id}/editar", name="tsol_editar")
     * @Method("GET")
     * @Template()
     */
    public function editarAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombretsol')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombretsol entity.');
        }

        $form = $this->createForm(new NombretsolType(), $entity, array(
            'action' => $this->generateUrl('tsol_actualizar', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('nombre', 'text', array('label' => false));
        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return array(
            'entity' => $entity,
            'edit_form' => $form->createView(),
        );
    }

    /**
     * Edits an existing Nombretsol entity.
     *
     * @Route("/{id}", name="tsol_actualizar")
     * @Method("PUT")
     * @Template()
     */
    public function actualizarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombretsol')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombretsol entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid())
        {
            $entity->setFechaModificacion((new \DateTime('NOW')));
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mensajes'));
        }
        return $this->redirect($this->generateUrl('tsol_editar', array('id' => $entity->getId())));
    }

    /**
     * Creates a form to edit a Nombretsol entity.
     *
     * @param Nombretsol $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Nombretsol $entity)
    {
        $form = $this->createForm(new NombretsolType(), $entity, array(
            'action' => $this->generateUrl('tsol_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Nombretsol entity.
     *
     * @Route("/{id}", name="tsol_update")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:Nombretsol:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombretsol')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombretsol entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid())
        {
            $em->flush();

            return $this->redirect($this->generateUrl('tsol_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Nombretsol entity.
     *
     * @Route("/{id}", name="tsol_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Nombretsol')->find($id);

            if (!$entity)
            {
                throw $this->createNotFoundException('Unable to find Nombretsol entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tsol'));
    }

    /**
     * Creates a form to delete a Nombretsol entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                ->setAction($this->generateUrl('tsol_delete', array('id' => $id)))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'Delete'))
                ->getForm()
        ;
    }

}
