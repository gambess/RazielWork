<?php

namespace Pi2\Fractalia\SGSDReportBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SGSDReportBundle\Entity\Rechazada;

/**
 * Rechazada controller.
 *
 * @Route("/reports/rechazada")
 */
class RechazadaController extends Controller
{

    /**
     * Lists all Rechazada entities.
     *
     * @Route("/", name="rechazada")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager('report');

        $entities = $em->getRepository('ReportBundle:Rechazada')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Rechazada entity.
     *
     * @Route("/{id}", name="rechazada_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager('report');

        $entity = $em->getRepository('ReportBundle:Rechazada')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rechazada entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
