<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class BreadcrumbsHandler extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    protected $em;
    protected $wob;
    protected $session;
    protected $requestStack;
    protected $router;
    
    const BREADCRUMB_NUMBER = 3;

    public function __construct(EntityManager $em, $wob, $session, \Symfony\Component\HttpFoundation\RequestStack $requestStack, RouterInterface $router)
    {
        $this->em = $em;
        $this->wob = $wob;
        $this->session = $session;
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function setNav($route) 
    { 
        $this->startSession();
        $nav = $this->session->get('nav');
        if (!$nav) {
            $nav = array();
        }
        
        if (reset($nav) != $route) {
            array_unshift($nav, $route);
        }

        while (count($nav) > 10) {
            array_pop($nav);
        }   

        $this->session->set('nav', $nav); 
    }
    
    public function getLastUrl() 
    {
        $this->startSession();
        $nav = $this->session->get('nav');
        if (!$nav) {
            $nav = array();
        }
        
        //Supression de la route courante dans toute la navigation
        foreach ($nav as $key => $item) {
            
            if ($this->getCurrentUrl() == $item) {
                unset($nav[$key]);
            }
        }
        $this->session->set('nav', $nav);
        
        if (sizeof($nav) == 0) {
            return $this->router->generate('homepage');
        }
        else {
            return reset($nav);
        }
    }
    
    public function getCurrentUrl() {
        $this->startSession();
        $nav = $this->session->get('nav');
        if (!$nav) {
            $nav = array();
        }
        
        return reset($nav);
    }
    

    public function setLabel($label)
    {
        $currentItem['label'] = $label;
        $currentItem['route'] = $this->requestStack->getCurrentRequest()->getRequestUri();
        
        $this->setBreadcrumbs($currentItem);
    }
    
    public function setBreadcrumbs($currentItem)
    {
        $this->startSession();
        
        //Récupération des items de breadcrumbs stockés en session
        $items = $this->session->get('breadcrumbs_items');
        if (empty($items)) {
            $items = array();
        }

        // On parcours le breadcrumb pour savoir si la page a été déjà été visitée
        foreach ($items as $key => $item) {
            // Si la route actuelle est dans le tableau
           if ($currentItem['route'] == $item['route']) {
               // Suppression de l'item dans le breadcrumbs
               unset($items[$key]);
               break;
           }
        }

        // Ajout du nouvel item à la fin du breadcrumbs
        array_push($items, $currentItem);

        // Réduction de la taille du tableau à 5 éléments maximum
        while (count($items) > self::BREADCRUMB_NUMBER) {
            array_shift($items);
        }

        // Enregistrement des items du breadcrumbs en session
        $this->session->set('breadcrumbs_items', $items);

        // Génération du breadcrumbs
        foreach ($items as $item) {
            $this->wob->addItem($item['label'], $item['route']);
        }
        
        //Gestion de la navigation
        $this->setNav($currentItem['route']);
        
        return $this->wob;
    }

    
    public function startSession() {
        //Si la session n'est pas démarrée, on la démarre
        if ($this->session->isStarted() === false) {
            $this->session->start();
        }
    }
    
    // La méthode getName() identifie votre extension Twig, elle est obligatoire
    public function getName()
    {
        return 'BreadcrumbsHandler';
    }
    
    /**
     * On définit la liste des variables globales utilisables via twig.
     */
    public function getGlobals()
    {
        return array(
            'nav' => $this->session->get('nav')
        );
    }    
    
    
}
