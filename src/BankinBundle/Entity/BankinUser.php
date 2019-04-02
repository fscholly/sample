<?php

namespace BankinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="BankinBundle\Repository\BankinUserRepository")
 * @ORM\Table(name="bankin_user")
 * @UniqueEntity({"uuid"})
 * @UniqueEntity({"email"})
 */
class BankinUser
{
    public function __construct()
    {
        $this->bankinAccounts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function routingArray()
    {
        return array(
            'BankinUserId' => $this->id,
        );
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="uuid", type="string", length=255, nullable=true)
     */
    private $uuid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=255, nullable=true)
     */
    private $accessToken;
    
    
    /**
     * @ORM\OneToMany(targetEntity="BankinAccount", mappedBy="bankinUser", cascade={"remove"})
     */
    private $bankinAccounts;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     *
     * @return User
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     *
     * @return User
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Add bankinAccount
     *
     * @param BankinAccount $bankinAccount
     *
     * @return BankinUser
     */
    public function addBankinAccount(BankinAccount $bankinAccount)
    {
        $this->bankinAccounts[] = $bankinAccount;

        return $this;
    }

    /**
     * Remove bankinAccount
     *
     * @param BankinAccount $bankinAccount
     */
    public function removeBankinAccount(BankinAccount $bankinAccount)
    {
        $this->bankinAccounts->removeElement($bankinAccount);
    }

    /**
     * Get bankinAccounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBankinAccounts()
    {
        return $this->bankinAccounts;
    }
}
