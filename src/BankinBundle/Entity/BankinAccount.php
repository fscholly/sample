<?php

namespace BankinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * BankinAccount
 *
 * @ORM\Table(name="bankin_account")
 * @ORM\Entity(repositoryClass="BankinBundle\Repository\BankinAccountRepository")
 * @UniqueEntity({"accountId"})
 */
class BankinAccount
{
    const TYPE_CHECKING = 'checking';
    const TYPE_SAVINGS = 'savings';
    const TYPE_SECURITIES = 'securities';
    const TYPE_CARD = 'card';
    const TYPE_LOAN = 'loan';
    const TYPE_SHARE_SAVINGS_PLAN = 'share_savings_plan';
    const TYPE_PENDING = 'pending';
    const TYPE_LIFE_INSURANCE = 'life_insurance';
    const TYPE_SPECIAL = 'special';
    const TYPE_UNKNOWN = 'unknown';
    
    public function __construct()
    {
        $this->bankinTransactions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function routingArray()
    {
        return array(
            'bankinAccountId' => $this->id,
        );
    }

    public function __toString()
    {
        return "bankinAccount #" . $this->id;
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
     * @ORM\ManyToOne(targetEntity="BankinUser",cascade={"persist"}, inversedBy="bankinAccounts")
     * @ORM\JoinColumn(name="bankin_user_id", nullable=false, onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private $bankinUser;
    
    /**
     * @var int
     *
     * @ORM\Column(name="accountId", type="bigint", options={"unsigned":true})
     * @Assert\NotBlank()
     */
    private $accountId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    
    /**
     * @var float
     *
     * @ORM\Column(name="balance", type="float", nullable=true)
     */
    private $balance;
    
    /**
     * @var string
     *
     * @ORM\Column(name="currencyCode", type="string", length=255, nullable=true)
     */
    private $currencyCode;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastRefreshDate", type="datetime", nullable=true)
     */
    private $lastRefreshDate;
    
    /**
     * @var int
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    private $type;
    
    /**
     * @ORM\ManyToOne(targetEntity="BankinBank",cascade={"persist"})
     * @ORM\JoinColumn(name="bankin_bank_id", nullable=true, onDelete="SET NULL")
     */
    private $bankinBank;

    
    /**
     * @var bool
     *
     * @ORM\Column(name="sync", type="boolean", nullable=true)
     */
    private $sync;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastSyncDate", type="datetime", nullable=true)
     */
    private $lastSyncDate;

    /**
     * @ORM\OneToMany(targetEntity="BankinTransaction", mappedBy="bankinAccount", cascade={"remove"})
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $bankinTransactions;
    
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
     * Set accountId
     *
     * @param integer $accountId
     *
     * @return BankinAccount
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get accountId
     *
     * @return integer
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return BankinAccount
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
     * Set balance
     *
     * @param float $balance
     *
     * @return BankinAccount
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     *
     * @return BankinAccount
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * Get currencyCode
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Set lastRefreshDate
     *
     * @param \DateTime $lastRefreshDate
     *
     * @return BankinAccount
     */
    public function setLastRefreshDate($lastRefreshDate)
    {
        $this->lastRefreshDate = $lastRefreshDate;

        return $this;
    }

    /**
     * Get lastRefreshDate
     *
     * @return \DateTime
     */
    public function getLastRefreshDate()
    {
        return $this->lastRefreshDate;
    }

    /**
     * Set bankinUser
     *
     * @param \BankinBundle\Entity\BankinUser $bankinUser
     *
     * @return BankinAccount
     */
    public function setBankinUser(\BankinBundle\Entity\BankinUser $bankinUser)
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

    /**
     * Set bankinBank
     *
     * @param \BankinBundle\Entity\BankinBank $bankinBank
     *
     * @return BankinAccount
     */
    public function setBankinBank(\BankinBundle\Entity\BankinBank $bankinBank = null)
    {
        $this->bankinBank = $bankinBank;

        return $this;
    }

    /**
     * Get bankinBank
     *
     * @return \BankinBundle\Entity\BankinBank
     */
    public function getBankinBank()
    {
        return $this->bankinBank;
    }

    /**
     * Set sync
     *
     * @param boolean $sync
     *
     * @return BankinAccount
     */
    public function setSync($sync)
    {
        $this->sync = $sync;

        return $this;
    }

    /**
     * Get sync
     *
     * @return boolean
     */
    public function getSync()
    {
        return $this->sync;
    }

    /**
     * Set lastSyncDate
     *
     * @param \DateTime $lastSyncDate
     *
     * @return BankinAccount
     */
    public function setLastSyncDate($lastSyncDate)
    {
        $this->lastSyncDate = $lastSyncDate;

        return $this;
    }

    /**
     * Get lastSyncDate
     *
     * @return \DateTime
     */
    public function getLastSyncDate()
    {
        return $this->lastSyncDate;
    }


    /**
     * Set type
     *
     * @param string $type
     *
     * @return BankinAccount
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add bankinTransaction
     *
     * @param \BankinBundle\Entity\BankinTransaction $bankinTransaction
     *
     * @return BankinAccount
     */
    public function addBankinTransaction(\BankinBundle\Entity\BankinTransaction $bankinTransaction)
    {
        $this->bankinTransactions[] = $bankinTransaction;

        return $this;
    }

    /**
     * Remove bankinTransaction
     *
     * @param \BankinBundle\Entity\BankinTransaction $bankinTransaction
     */
    public function removeBankinTransaction(\BankinBundle\Entity\BankinTransaction $bankinTransaction)
    {
        $this->bankinTransactions->removeElement($bankinTransaction);
    }

    /**
     * Get bankinTransactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBankinTransactions()
    {
        return $this->bankinTransactions;
    }
}
