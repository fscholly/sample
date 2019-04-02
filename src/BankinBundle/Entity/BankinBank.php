<?php

namespace BankinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * BankinBank
 *
 * @ORM\Table(name="bankin_bank")
 * @ORM\Entity(repositoryClass="BankinBundle\Repository\BankinBankRepository")
 * @UniqueEntity({"bankId"})
 */
class BankinBank
{
    
    public function __construct()
    {
    }

    public function routingArray()
    {
        return array(
            'bankinBankId' => $this->id,
        );
    }

    public function __toString()
    {
        return "bankinBank #" . $this->id;
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
     * @var int
     *
     * @ORM\Column(name="bankId", type="integer")
     * @Assert\NotBlank()
     */
    private $bankId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="countryCode", type="string", length=255, nullable=true)
     */
    private $countryCode;

    
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
     * Set bankId
     *
     * @param integer $bankId
     *
     * @return BankinBank
     */
    public function setBankId($bankId)
    {
        $this->bankId = $bankId;

        return $this;
    }

    /**
     * Get bankId
     *
     * @return integer
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return BankinBank
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return BankinBank
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
}
