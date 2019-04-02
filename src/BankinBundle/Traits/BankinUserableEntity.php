<?php

namespace BankinBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * 
 *
 * @author CORESONANCE
 */
trait BankinUserableEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="BankinBundle\Entity\BankinUser", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="bankin_user_id", onDelete="SET NULL", nullable=true)
     * @Assert\Valid
     */
    private $bankinUser;
    
    
    /**
     * Set bankinUser
     *
     * @param \BankinBundle\Entity\BankinUser $bankinUser
     *
     * @return object
     */
    public function setBankinUser(\BankinBundle\Entity\BankinUser $bankinUser = null)
    {
        $this->bankinUser = $bankinUser;

        return $this;
    }

    /**
     * Get bankinUser
     *
     * @return \BankinBundle\Entity\BankinUser
     */
    public function getBankinUser()
    {
        return $this->bankinUser;
    }
    
}
