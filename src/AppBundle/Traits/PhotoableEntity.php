<?php

namespace AppBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * 
 *
 * @author CORESONANCE
 */
trait PhotoableEntity
{
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Photo", cascade={"persist","remove"})
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=true)
     * @Assert\Valid
     */
    private $photo;
    
    /**
     * 
     * Préparer les différentes images associées à une photo
     *
     * @ORM\PrePersist()
     * 
     */
    public function createPhoto(\Doctrine\ORM\Event\LifecycleEventArgs $event)
    {
        if(empty($this->photo)){
            $this->photo = new \AppBundle\Entity\Photo();
        }
        
        $em = $event->getEntityManager();
        $imageTypesCodes = self::getImageTypeCodes();
        
        if(empty($this->photo->getImageSmall()->getImageType())) {
            $imageSmallType = $em->getRepository('AppBundle:ImageType')->findOneByCode($imageTypesCodes['small']);
            $this->photo->getImageSmall()->setImageType($imageSmallType);
        }
        if(empty($this->photo->getImageMedium()->getImageType())) {
            $imageMediumType = $em->getRepository('AppBundle:ImageType')->findOneByCode($imageTypesCodes['medium']);
            $this->photo->getImageMedium()->setImageType($imageMediumType);
        }
        if(empty($this->photo->getImageLarge()->getImageType())) {
            $imageLargeType = $em->getRepository('AppBundle:ImageType')->findOneByCode($imageTypesCodes['large']);
            $this->photo->getImageLarge()->setImageType($imageLargeType);
        }
    }
    
    /**
     * Set photo
     *
     * @param \AppBundle\Entity\Photo $photo
     *
     * @return object
     */
    public function setPhoto(\AppBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \AppBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }
    
}
