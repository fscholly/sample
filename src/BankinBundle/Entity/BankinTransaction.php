<?php

namespace BankinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * BankinTransaction
 *
 * @ORM\Table(name="bankin_transaction")
 * @ORM\Entity(repositoryClass="BankinBundle\Repository\BankinTransactionRepository")
 * @UniqueEntity({"transactionId"})
 */
class BankinTransaction
{
    
    public function __construct()
    {
    }

    public function routingArray()
    {
        return array(
            'bankinTransactionId' => $this->id,
        );
    }

    public function __toString()
    {
        return "bankinTransaction #" . $this->id;
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
     * @ORM\ManyToOne(targetEntity="BankinAccount",cascade={"persist"}, inversedBy="bankinTransactions")
     * @ORM\JoinColumn(name="bankin_account_id", nullable=true, onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private $bankinAccount;
    
    /**
     * @ORM\ManyToOne(targetEntity="BankinCategory",cascade={"persist"})
     * @ORM\JoinColumn(name="bankin_category_id", nullable=true, onDelete="SET NULL")
     */
    private $bankinCategory;
    
    /**
     * @var int
     *
     * @ORM\Column(name="transactionId", type="bigint", options={"unsigned":true})
     * @Assert\NotBlank()
     */
    private $transactionId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="rawDescription", type="string", length=255, nullable=true)
     */
    private $rawDescription;
    
    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=true)
     */
    private $amount;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updateDate", type="datetime", nullable=true)
     */
    private $updateDate;
    

    
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
     * Set transactionId
     *
     * @param integer $transactionId
     *
     * @return BankinTransaction
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return integer
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }


    /**
     * Set description
     *
     * @param string $description
     *
     * @return BankinTransaction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set rawDescription
     *
     * @param string $rawDescription
     *
     * @return BankinTransaction
     */
    public function setRawDescription($rawDescription)
    {
        $this->rawDescription = $rawDescription;

        return $this;
    }

    /**
     * Get rawDescription
     *
     * @return string
     */
    public function getRawDescription()
    {
        return $this->rawDescription;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return BankinTransaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return BankinTransaction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return BankinTransaction
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set bankinCategory
     *
     * @param \BankinBundle\Entity\BankinCategory $bankinCategory
     *
     * @return BankinTransaction
     */
    public function setBankinCategory(\BankinBundle\Entity\BankinCategory $bankinCategory = null)
    {
        $this->bankinCategory = $bankinCategory;

        return $this;
    }

    /**
     * Get bankinCategory
     *
     * @return \BankinBundle\Entity\BankinCategory
     */
    public function getBankinCategory()
    {
        return $this->bankinCategory;
    }

    /**
     * Set bankinAccount
     *
     * @param \BankinBundle\Entity\BankinAccount $bankinAccount
     *
     * @return BankinTransaction
     */
    public function setBankinAccount(\BankinBundle\Entity\BankinAccount $bankinAccount = null)
    {
        $this->bankinAccount = $bankinAccount;

        return $this;
    }

    /**
     * Get bankinAccount
     *
     * @return \BankinBundle\Entity\BankinAccount
     */
    public function getBankinAccount()
    {
        return $this->bankinAccount;
    }
}
