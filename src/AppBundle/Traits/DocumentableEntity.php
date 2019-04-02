<?php

namespace AppBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * 
 *
 * @author CORESONANCE
 */
trait DocumentableEntity
{
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Document", cascade={"persist","remove"})
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     * @Assert\Valid
     */
    private $document;
    
    
    /**
     * Set document
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return object
     */
    public function setDocument(\AppBundle\Entity\Document $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return \AppBundle\Entity\Document
     */
    public function getDocument()
    {
        return $this->document;
    }
    
}
