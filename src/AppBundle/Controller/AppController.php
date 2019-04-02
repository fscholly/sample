<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AppController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Accueil',
            'route' => $this->get("router")->generate("homepage")
        ));

        return $this->render('AppBundle:App:index.html.twig', array(
        ));
    }
    
    public function sidebarAction(Request $request)
    {

        return $this->render('_general_sidebar.html.twig', array(
            'route' => $request->get('route', 'homepage')
        ));
    }
    
    
    /**
     * Retour en arrière
     *
     * @Route("/return", name="return")
     * @Method("GET")
     */
    public function returnAction(Request $request)
    { 
        return $this->redirect($this->get('app.breadcrumbs_handler')->getLastUrl());
    }
    
}
