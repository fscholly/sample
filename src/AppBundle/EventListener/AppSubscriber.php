<?php

namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use AppBundle\Entity\Event;

class AppSubscriber implements EventSubscriber
{
    ///////////////////////////////////////////////////////////////
    //                      Historique                      
    ///////////////////////////////////////////////////////////////
    //
    // [create] Cas de la création
    // * ajout des évènements directement à la base de données
    // 
    // [update] Cas de la mise à jour
    // * analyse des éléments qui ont changés (dans le preUpdate)
    // * création d'un évènement et ajout à la pile des évènements à créer(dans le preUpdate)
    // * mise à jour de la base de données (dans le postFlush)
    //
    // [remove] Cas de la suppression
    // 
    // 
    // 
    // 
    // pour récupérer l'utilisateur qui a effectué l'action :
    private $token_storage;
    // 
    // (update) pour savoir si on doit mettre à jour la base de données  :
    private $traceUpdate = false;
    // (update) contient les évènements à ajouter à la base de données :
    private $events = array();
    //
    private $separator = "<i class='fa fa-long-arrow-right'></i>";
    //
    ////////////////////////////////////////////////////////////////
    
    public function __construct(TokenStorageInterface $token_storage)
    {
        $this->token_storage = $token_storage;
    }
    
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postUpdate',
            'postPersist',
            'preRemove',
            'postRemove',
            'postFlush'
        );
    }

   
    
    public function prePersist(LifecycleEventArgs $args)
    {
        
    }
    
    public function preUpdate(PreUpdateEventArgs $args )
    {
        // Rappel : 
        //  * impossible d'effectuer un persist/flush dans un preUpdate
        //  * c'est le seul endroit où l'on peut comparer les modifications sur l'entité avant qu'elles ne soit éxecutées
        //  * dans le postFlush on mettra à jour la base de données (= création d'évènements dans l'historique)
        
        // Préparer les évenements à ajouter à l'historique
        $this->preTraceUpdate($args);
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
    }

    public function postPersist(LifecycleEventArgs $args)
    {
            $this->traceCreate($args);
    }
    
    public function postFlush(PostFlushEventArgs $args)
    {
        
        // S'il y a des évènements à ajouter à l'historique
        if($this->traceUpdate == true){
            // Mettre à jour l'historique des évènements
            $this->traceUpdate($args);
        }
        $this->events = [];
    }
    
    public function postRemove(LifecycleEventArgs $args)
    {
        
    }
    
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->traceRemove($args);
    }
    
    public function traceCreate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $token = $this->token_storage->getToken();
        $user = $token ? $token->getUser() : null;
        $em = $args->getEntityManager();
        
//        if ($entity instanceof \AppBundle\Entity\Design) {
//            $event = new Event();
//            $title = "Création du modèle " . $entity->getFullName();
//            $type = Event::EVENT_DESIGN;
//
//            $event->setEntityId($entity->getId());
//            $event->setUser($user);
//            $event->setType($type);
//            $event->setTitle($title);
//
//            $em->persist($event);
//
//            $em->flush();
//        }

    }
    
    // Préparer les évenements à ajouter à l'historique
    public function preTraceUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        $token = $this->token_storage->getToken();
        $user = $token ? $token->getUser() : null;
        $em = $args->getEntityManager();
      
        $event = new Event();
        $entityId = $type = $title = $content = null;
        
        // Modèle
//        if ($entity instanceof \AppBundle\Entity\Design) {
//            $entityId = $entity->getId();
//            $title = "Modification du modèle ".$entity->getFullName();
//            $type = Event::EVENT_DESIGN;
//
//            $fields = array(
//                'name' => array('text','Intitulé'),
//                'code' => array('text','Code'),
//                'officialName' => array('text','Nom officiel'),
//                'designType' => array('entity','Type de modèle'),
//                'feature' => array('entity','Matériau'),
//                'universe' => array('entity','Univers'),
//                'paHt' => array('float','Prix d\'achat HT'),
//                'paTtc' => array('float','Prix d\'achat TTC'),
//                'pvHt' => array('float','Prix de vente HT'),
//                'pvTtc' => array('float','Prix de vente TTC'),
//            );
//            $content = $this->analyzeChange($args, $fields, $content);
//        }
//
        // Ajout d'un nouvel évènement
        if(!empty($content)){
            $event->setEntityId($entityId);
            $event->setUser($user);
            $event->setType($type);
            $event->setTitle($title);
            $event->setContent($content);
            array_push($this->events, $event);
            
            $this->traceUpdate = true;
        }
    }
    
    // Fabriquer la chaîne de caractère qui contient tous les changements effectués
    public function analyzeChange($args, $fields, $content)
    {
        $vide = "NULL";
        foreach($fields as $field => $params){
            $type = $params[0];
            $name = $params[1];
            if ($args->hasChangedField($field)) {
                $oldValue = $args->getOldValue($field);
                $newValue = $args->getNewValue($field);
                if($type == 'bool'){
                    $oldValue = $oldValue ? 'Oui' : 'Non';
                    $newValue = $newValue ? 'Oui' : 'Non';
                }
                if(empty($oldValue)){
                    $oldValue = $vide;
                }
                elseif($type == 'date'){
                    $oldValue = $oldValue->format('j/m/Y');
                }
                if(empty($newValue)){
                    $newValue = $vide;
                }
                elseif($type == 'date'){
                    $newValue = $newValue->format('j/m/Y');
                }
                $content .= "<li>". $name ." : " . $oldValue . " $this->separator <b>" . $newValue . "</b></li>"; 
            }
        }
        return $content;
    }
    
    // Mettre à jour l'historique des évènements (ajouter les nouveaux évènements à la base de données)
    public function traceUpdate(PostFlushEventArgs $args)
    {
        $this->traceUpdate = false;
        $em = $args->getEntityManager();
        foreach($this->events as $event){
            $em->persist($event);
        }
        if(count($this->events)){
            $em->flush();
        }
    }

    // Tracer la suppression
    public function traceRemove( LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $token = $this->token_storage->getToken();
        $user = $token ? $token->getUser() : null;
        $em = $args->getEntityManager();
//
//        if ($entity instanceof \AppBundle\Entity\Design) {
//            $event = new Event();
//            $title = "Suppression du modèle " . $entity->getFullName();
//            $type = Event::EVENT_DESIGN;
//
//            $event->setEntityId($entity->getId());
//            $event->setUser($user);
//            $event->setType($type);
//            $event->setTitle($title);
//
//            $em->persist($event);
//            $em->flush();
//        }
    }
}