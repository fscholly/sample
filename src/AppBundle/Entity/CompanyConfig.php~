<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * CompanyConfig.
 *
 * @ORM\Table(name="nsdh_config")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyConfigRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CompanyConfig
{
    const TVA_RATE = 0.2;
    
    public function __construct()
    {
        $this->date = new \Datetime();
        $this->optionValues = new \Doctrine\Common\Collections\ArrayCollection();
        
        $this->appLogo = new \AppBundle\Entity\Image();
        $this->quotationLogo = new \AppBundle\Entity\Image();
    }

    public function __toString()
    {
        return 'config';
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     * @Assert\Valid
     */
    private $appLogo;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     * @Assert\Valid
     */
    private $quotationLogo;
    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="nsdh_config_administrator")
     */
    private $administrators;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OptionValue", mappedBy="companyConfig")
     */
    public $optionValues;

    
    public function getOptionValueByCode($code)
    {
        foreach($this->getOptionValues() as $optionValue) {
            if($optionValue->getConfigOption()->getCode() == $code) {
                return $optionValue;
            }
        }
        return null;
    }
    
    
    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return CompanyConfig
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return CompanyConfig
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }


    /**
     * Add administrator.
     *
     * @param \AppBundle\Entity\User $administrator
     *
     * @return CompanyConfig
     */
    public function addAdministrator(\AppBundle\Entity\User $administrator)
    {
        $this->administrators[] = $administrator;

        return $this;
    }

    /**
     * Remove administrator.
     *
     * @param \AppBundle\Entity\User $administrator
     */
    public function removeAdministrator(\AppBundle\Entity\User $administrator)
    {
        $this->administrators->removeElement($administrator);
    }

    /**
     * Get administrators.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdministrators()
    {
        return $this->administrators;
    }

    /**
     * Add optionValue
     *
     * @param \AppBundle\Entity\OptionValue $optionValue
     *
     * @return CompanyConfig
     */
    public function addOptionValue(\AppBundle\Entity\OptionValue $optionValue)
    {
        $this->optionValues[] = $optionValue;

        return $this;
    }

    /**
     * Remove optionValue
     *
     * @param \AppBundle\Entity\OptionValue $optionValue
     */
    public function removeOptionValue(\AppBundle\Entity\OptionValue $optionValue)
    {
        $this->optionValues->removeElement($optionValue);
    }

    /**
     * Get optionValues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptionValues()
    {
        return $this->optionValues;
    }
    
   /**
     * Set appLogo
     *
     * @param \AppBundle\Entity\Image $appLogo
     *
     * @return CompanyConfig
     */
    public function setAppLogo(\AppBundle\Entity\Image $appLogo = null)
    {
        $this->appLogo = $appLogo;

        return $this;
    }

    /**
     * Get appLogo
     *
     * @return \AppBundle\Entity\Image
     */
    public function getAppLogo()
    {
        return $this->appLogo;
    }
    
    /**
     * Set quotationLogo
     *
     * @param \AppBundle\Entity\Image $quotationLogo
     *
     * @return CompanyConfig
     */
    public function setQuotationLogo(\AppBundle\Entity\Image $quotationLogo = null)
    {
        $this->quotationLogo = $quotationLogo;

        return $this;
    }

    /**
     * Get quotationLogo
     *
     * @return \AppBundle\Entity\Image
     */
    public function getQuotationLogo()
    {
        return $this->quotationLogo;
    }
}
