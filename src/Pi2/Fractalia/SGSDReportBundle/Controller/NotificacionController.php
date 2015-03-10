<?php

namespace Pi2\Fractalia\SGSDReportBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion;

/**
 * Notificacion controller.
 *
 * @Route("/reports/notificacion")
 */
class NotificacionController extends Controller
{

    /**
     * Lists all Notificacion entities.
     *
     * @Route("/", name="notificacion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager('report');

        $entities = $em->getRepository('ReportBundle:Notificacion')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Notificacion entity.
     *
     * @Route("/{id}", name="notificacion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager('report');

        $entity = $em->getRepository('ReportBundle:Notificacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notificacion entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
