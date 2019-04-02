<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="nsdh_event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
{
    // Gestion des produits & Services
    const EVENT_DESIGN = 1;
    const EVENT_PRODUCT = 2;
    const EVENT_SERVICE = 3;
    const EVENT_DESIGN_TYPE = 4;
    const EVENT_PRODUCT_RANGE = 5;
    const EVENT_SERVICE_RANGE = 6;
    const EVENT_FEATURE = 7;
    const EVENT_FEATURE_TYPE = 8;
    
    // Gestion des licences
    const EVENT_UNIVERSE = 9;
    const EVENT_COPYRIGHT = 10;
    const EVENT_LICENCE = 11;
    
    // Gestion activité commerciale
    const EVENT_QUOTATION = 12;
    const EVENT_INVOICE = 13;
    const EVENT_PAYMENT = 14;
    const EVENT_CONTACT = 15;
    
    public function __construct() {
        $this->postDate = new \Datetime();
    }
    
    public function __toString() {
        if ($this->content != null)
            return $this->content;
        else return 'Evènement '. $this->id;
    }
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="post_date", type="datetime")
     */
    private $postDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * Author of the event
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User" )
     * @ORM\JoinColumn(nullable=true , onDelete="SET NULL")
     */
    protected $user;
    
    /**
     * type of this event
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    protected $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=true)
     */
    private $entityId;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set postDate
     *
     * @param \DateTime $postDate
     * @return Event
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;
    
        return $this;
    }

    /**
     * Get postDate
     *
     * @return \DateTime 
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Event
     */
    public function setContent($content)
    {
        $this->content = str_replace('"', "'", $content);
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Set title
     *
     * @param string $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Set type
     *
     * @param integer $type
     * @return Event
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
      * Get label type
      *
      * @return string
      */
    public function getLabelType()
    {
       $types = $this->getTypes();
       return $types[$this->getType()];
    }
    
    /**
      * Get types
      *
      * @return string
      */
    public function getTypes()
    {
        $types = array (
            self::EVENT_DESIGN => 'Modèle',
            self::EVENT_PRODUCT => 'Produit',
            self::EVENT_SERVICE => 'Service',
            self::EVENT_DESIGN_TYPE => 'Type de modèle',
            self::EVENT_PRODUCT_RANGE => 'Gamme de produit',
            self::EVENT_SERVICE_RANGE => 'Gamme de service',
            self::EVENT_FEATURE => 'Caractéristique',
            self::EVENT_FEATURE_TYPE => 'Type de caractéristique',
            self::EVENT_UNIVERSE => 'Univers',
            self::EVENT_COPYRIGHT => 'Ayant droit',
            self::EVENT_LICENCE => 'Licence',
            self::EVENT_QUOTATION => 'Devis',
            self::EVENT_INVOICE => 'Facture',
            self::EVENT_PAYMENT => 'Paiement',
            self::EVENT_CONTACT => 'Contact',
        );
        
        return $types;
    }
    
    
    /**
     * Set entityId
     *
     * @param integer $entityId
     * @return Event
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    
        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer 
     */
    public function getEntityId()
    {
        return $this->entityId;
    }
}
