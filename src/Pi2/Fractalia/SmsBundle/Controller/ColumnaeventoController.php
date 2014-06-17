<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Entity\Columnaevento;
use Pi2\Fractalia\SmsBundle\Form\ColumnaeventoType;

/**
 * Columnaevento controller.
 *
 * @Route("/columnaevento")
 */
class ColumnaeventoController extends Controller
{
    private $plantillas;

    /**
     * Lists all Columnaevento entities.
     *
     * @Route("/", name="columnaevento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FractaliaSmsBundle:Columnaevento')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Columnaevento entity.
     *
     * @Route("/", name="columnaevento_create")
     * @Method("POST")
     * @Template("FractaliaSmsBundle:Columnaevento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Columnaevento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('columnaevento_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Columnaevento entity.
     *
     * @param Columnaevento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Columnaevento $entity)
    {
        $form = $this->createForm(new ColumnaeventoType(), $entity, array(
            'action' => $this->generateUrl('columnaevento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Columnaevento entity.
     *
     * @Route("/new", name="columnaevento_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Columnaevento();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Columnaevento entity.
     *
     * @Route("/{id}", name="columnaevento_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Columnaevento')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Columnaevento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and displays a Columnaevento entity.
     *
     * @Route("/{id}/text", name="columnaevento_text")
     * @Method("GET")
     * @Template()
     */
    public function textAction($id, $em = null)
    {
        if (null === $em)
        {
            $em = $this->getDoctrine()->getManager();
        }
//       
        $entity = $em->getRepository('FractaliaSmsBundle:Columnaevento')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Columnaevento entity.');
        }

        return $this->render('FractaliaSmsBundle:Columnaevento:text.txt.twig', array(
                'label' => $this->getLabelsFromConfigByEvento('RESUELTO'),
                'entity' => $entity
                )
        );
    }

    /**
     * Displays a form to edit an existing Columnaevento entity.
     *
     * @Route("/{id}/edit", name="columnaevento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Columnaevento')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Columnaevento entity.');
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
     * Creates a form to edit a Columnaevento entity.
     *
     * @param Columnaevento $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Columnaevento $entity)
    {
        $form = $this->createForm(new ColumnaeventoType(), $entity, array(
            'action' => $this->generateUrl('columnaevento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Columnaevento entity.
     *
     * @Route("/{id}", name="columnaevento_update")
     * @Method("PUT")
     * @Template("FractaliaSmsBundle:Columnaevento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FractaliaSmsBundle:Columnaevento')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Columnaevento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid())
        {
            $em->flush();

            return $this->redirect($this->generateUrl('columnaevento_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Columnaevento entity.
     *
     * @Route("/{id}", name="columnaevento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FractaliaSmsBundle:Columnaevento')->find($id);

            if (!$entity)
            {
                throw $this->createNotFoundException('Unable to find Columnaevento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('columnaevento'));
    }

    /**
     * Creates a form to delete a Columnaevento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                ->setAction($this->generateUrl('columnaevento_delete', array('id' => $id)))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'Delete'))
                ->getForm()
        ;
    }

}
