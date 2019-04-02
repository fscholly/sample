<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ConfigOption
 *
 * @ORM\Table(name="nsdh_config_option")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConfigOptionRepository")
 */
class ConfigOption
{
    const OPTION_TEXT = 1;
    const OPTION_TEXT_REQUIRED = 2;
    const OPTION_CHOICE = 3;
    const OPTION_CHOICE_REQUIRED = 4;
    const OPTION_CHECKBOX = 5;
    const OPTION_TEXTAREA = 6;
    const OPTION_TEXTAREA_REQUIRED = 7;
    const OPTION_NUMBER = 8;
    const OPTION_NUMBER_REQUIRED = 9;
    
    public function __construct()
    {
        $this->optionChoices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    public function __toString()
    {
        return $this->getOptionFamily()->__toString();
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="OptionFamily", cascade={"persist"}, inversedBy="configOptions")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    public $optionFamily;

    /**
     * @ORM\OneToMany(targetEntity="OptionChoice", mappedBy="configOption")
     */
    private $optionChoices;
    
    /**
      * Get types
      *
      * @return string
      */
    public static function getTypes()
    {
        $types = array (
            self::OPTION_TEXT => "text",
            self::OPTION_TEXT_REQUIRED => "text-required",
            self::OPTION_CHOICE => "choice",
            self::OPTION_CHOICE_REQUIRED => "choice-required",
            self::OPTION_CHECKBOX => "checkbox",
            self::OPTION_TEXTAREA => "textarea",
            self::OPTION_TEXTAREA_REQUIRED => "textarea-required",
            self::OPTION_NUMBER => "number",
            self::OPTION_NUMBER_REQUIRED => "number-required",
        );
        
        return $types;
    }
    
    /**
      * Get label type
      *
      * @return string
      */
    public function getLabelType()
    {
       $types = self::getTypes();
       
       return $types[$this->getType()];
    }
    
    
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
     * Set name.
     *
     * @param string $name
     *
     * @return CompanyConfig
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return ConfigOption
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return ConfigOption
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set optionFamily
     *
     * @param \AppBundle\Entity\OptionFamily $optionFamily
     *
     * @return ConfigOption
     */
    public function setOptionFamily(\AppBundle\Entity\OptionFamily $optionFamily)
    {
        $this->optionFamily = $optionFamily;

        return $this;
    }

    /**
     * Get optionFamily
     *
     * @return \AppBundle\Entity\OptionFamily
     */
    public function getOptionFamily()
    {
        return $this->optionFamily;
    }
    
    /**
     * Add optionChoice
     *
     * @param \AppBundle\Entity\OptionChoice $optionChoice
     *
     * @return ConfigOption
     */
    public function addOptionChoice(\AppBundle\Entity\OptionChoice $optionChoice)
    {
        $this->optionChoices[] = $optionChoice;

        return $this;
    }

    /**
     * Remove optionChoice
     *
     * @param \AppBundle\Entity\OptionChoice $optionChoice
     */
    public function removeOptionChoice(\AppBundle\Entity\OptionChoice $optionChoice)
    {
        $this->optionChoices->removeElement($optionChoice);
    }

    /**
     * Get optionChoices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptionChoices()
    {
        return $this->optionChoices;
    }
}
