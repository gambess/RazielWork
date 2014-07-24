<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Entity\Nombrecorto;
use Pi2\Fractalia\SmsBundle\Form\NombrecortoType;

/**
 * Nombrecorto controller.
 *
 * @Route("/nombrecorto")
 */
class NombrecortoController extends Controller
{

    /**
     * Lists all Nombrecorto entities.
     *
     * @Route("/", name="nombrecorto")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Show one Entity.
     *
     * @Route("/{id}/show", name="nombrecorto_mostrar")
     * @Method("GET")
     * @Template()
     */
    public function mostrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $nombre = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->find($id);
        return array(
            'nombre' => $nombre,
        );
    }
    /**
     * Lists all Nombretsol entities.
     *
     * @Route("/show", name="nombrecorto_todos")
     * @Method("GET")
     * @Template()
     */
    public function todosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nombres = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->FindAll();
        return array(
            'nombres' => $nombres,
        );
    }

    /**
     * Creates a new Nombrecorto entity.
     *
     * @Route("/", name="nombrecorto_create")
     * @Method("POST")
     * @Template("FractaliaSmsBundle:Nombrecorto:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Nombrecorto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('nombrecorto_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Nombrecorto entity.
     *
     * @param Nombrecorto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Nombrecorto $entity)
    {
        $form = $this->createForm(new NombrecortoType(), $entity, array(
            'action' => $this->generateUrl('nombrecorto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Nombrecorto entity.
     *
     * @Route("/new", name="nombrecorto_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Nombrecorto();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Nombrecorto entity.
     *
     * @Route("/nuevo", name="nombrecorto_nuevo")
     * @Method("GET")
     * @Template()
     */
    public function crearAction()
    {
        $entity = new Nombrecorto();
//        $form   = $this->createCreateForm($entity);
        $form = $this->createForm(new NombrecortoType(), $entity, array(
            'action' => $this->generateUrl('nombrecorto_create'),
            'method' => 'POST',
        ));
        $form->add('nombre', 'text', array('label' => false));
        $form->add('', 'submit', array('label' => false));

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Nombrecorto entity.
     *
     * @Route("/{id}", name="nombrecorto_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombrecorto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Nombrecorto entity.
     *
     * @Route("/{id}/edit", name="nombrecorto_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombrecorto entity.');
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
     * Displays a form to edit an existing Nombrecorto entity.
     *
     * @Route("/{id}/editar", name="nombrecorto_editar")
     * @Method("GET")
     * @Template()
     */
    public function editarAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombrecorto entity.');
        }

        $form = $this->createForm(new NombrecortoType(), $entity, array(
            'action' => $this->generateUrl('nombrecorto_actualizar', array('id' => $entity->getId())),
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
     * Edits an existing Nombrecorto entity.
     *
     * @Route("/{id}", name="nombrecorto_actualizar")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:Nombrecorto:mostrar.html.twig")
     */
    public function actualizarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombrecorto entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid())
        {
            $entity->setFechaModificacion((new \DateTime('NOW')));
            $em->persist($entity);
            $em->flush();

            return array(
                'nombres' => $em->getRepository('FractaliaSmsBundle:Nombrecorto')->findAll(),
            );
        }
        return $this->redirect($this->generateUrl('tsol_editar', array('id' => $entity->getId())));
    }

    /**
     * Creates a form to edit a Nombrecorto entity.
     *
     * @param Nombrecorto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Nombrecorto $entity)
    {
        $form = $this->createForm(new NombrecortoType(), $entity, array(
            'action' => $this->generateUrl('nombrecorto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Nombrecorto entity.
     *
     * @Route("/{id}", name="nombrecorto_update")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:Nombrecorto:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombrecorto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid())
        {
            $em->flush();

            return $this->redirect($this->generateUrl('nombrecorto_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Nombrecorto entity.
     *
     * @Route("/{id}", name="nombrecorto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->find($id);

            if (!$entity)
            {
                throw $this->createNotFoundException('Unable to find Nombrecorto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('nombrecorto'));
    }

    /**
     * Creates a form to delete a Nombrecorto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                ->setAction($this->generateUrl('nombrecorto_delete', array('id' => $id)))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'Delete'))
                ->getForm()
        ;
    }

    /**
     * Deletes a Nombrecorto entity.
     *
     * @Route("/{id}/borrar", name="nombrecorto_borrar")
     * @Method("GET")
     * @Template("FractaliaSmsBundle:Nombrecorto:mostrar.html.twig")
     */
    public function borrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Nombrecorto entity.');
        }
        if ($entity)
        {
            $em->remove($entity);
            $em->flush();

            return array(
                'nombres' => $em->getRepository('FractaliaSmsBundle:Nombrecorto')->findAll(),
            );
        }

        return $this->redirect($this->generateUrl('nombrecorto'));
    }

}
