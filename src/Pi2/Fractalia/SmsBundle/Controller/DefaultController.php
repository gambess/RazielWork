<?php

namespace Pi2\Fractalia\SmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pi2\Fractalia\SmsBundle\Manager\MensajeManager;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        $em = $this->getDoctrine()->getManager();
//        $mensajeRepository = $em->getRepository('Pi2\Fractalia\SmsBundle\Entity\Mensaje');
//        
//        $mensaje = $mensajeRepository->findOneBy(array('id'=>'79'));
//        
//        echo get_class($resumenes);
//        echo sizeof($resumenes);die;

        $servicios = $this->container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.servicio');
        $datosResumenes = $this->container->getParameter('pi2_frac_sgsd_soap_server.resumenes.resumen');
        $array = $em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia')->getResumen($datosResumenes['estados'],$servicios);
//        echo "<pre>";
//        print_r($array);
//        echo "</pre>";die;

        $msj_manager = new MensajeManager();
        echo $msj_manager->createMensaje($array, $em);die;
        
        $response = $this->render('FractaliaSmsBundle:Columnaresumen:text.txt.twig', array(
//            'label' => $this->getLabelsFromConfigByEvento('RESUELTO'),
//            'label' => 'PENDIENTES',
            'label' => $datosResumenes['titulo'],
            'entities' => $array
        ));
        return $response;
    }
}
