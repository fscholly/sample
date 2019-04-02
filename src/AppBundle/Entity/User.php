<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use AppBundle\Traits\PhotoableEntity;
use BankinBundle\Traits\BankinUserableEntity;

/*
 * user is a reserved keyword in the SQL standard. 
 * If you need to use reserved words, surround them with backticks, 
 * e.g. @ORM\Table(name="`user`")
 */

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="nsdh_user")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity({"username"})
 * @UniqueEntity({"email"})
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();

        $this->enabled = true;
    }

    public function routingArray()
    {
        return array(
            'userId' => $this->id,
            );
    }

    public function __toString()
    {
        return $this->getShortname();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /*
     * Hook photoable behavior
     */
    use PhotoableEntity;
    static public function getImageTypeCodes()
    {
        return array(
            'small' => "AVATAR_SMALL",
            'medium' => "AVATAR_MEDIUM",
            'large' => "AVATAR_LARGE",
        );
    }
    
    
    /*
     * Hook bankin behavior
     */
    use BankinUserableEntity;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group")
     * @ORM\JoinTable(name="nsdh_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    
    /**
     * @Assert\Callback
     */
    public function validatePassword(ExecutionContextInterface $context)
    {
        // Vérifier que le mot de passe n'est pas vide
        // à effectuer lors de la mise à jour --> id n'est pas null
        if ($this->id && empty($this->getPassword())) {
            $context->buildViolation('Veuiller compléter ce champ.')
                    ->atPath('password')
                    ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     */
    public function validateUser(ExecutionContextInterface $context)
    {
        if (empty($this->getEmail())) {
            $context->buildViolation('Veuiller compléter ce champ.')
                    ->atPath('email')
                    ->addViolation();
        }
        if (empty($this->getUsername())) {
            $context->buildViolation('Veuiller compléter ce champ.')
                    ->atPath('username')
                    ->addViolation();
        }
    }

    /**
     * Get Shortname.
     *
     * @return string
     */
    public function getShortname()
    {
        if (($this->firstname != null) && ($this->lastname != null)) {
            return $this->lastname.'.'.substr($this->firstname, 0, 1);
        } else {
            return $this->username;
        }
    }

    /**
     * Get full name.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->firstname.' '.$this->lastname;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
}
