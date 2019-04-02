<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImageType
 *
 * @ORM\Table(name="nsdh_image_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageTypeRepository")
 */
class ImageType
{
    public function __toString()
    {
        return "[".$this->code."]".$this->name;
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;
    

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="max_width", type="integer", nullable=true)
     */
    private $maxWidth;
    
    /**
     * @var int
     *
     * @ORM\Column(name="max_height", type="integer", nullable=true)
     */
    private $maxHeight;


    /**
     * @var string
     *
     * @ORM\Column(name="default_filepath", type="string", length=255)
     */
    private $defaultFilepath;


    
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
     * Set code
     *
     * @param string $code
     *
     * @return ImageType
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
     * Set name
     *
     * @param string $name
     *
     * @return ImageType
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
     * Set maxWidth
     *
     * @param integer $maxWidth
     *
     * @return ImageType
     */
    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;

        return $this;
    }

    /**
     * Get maxWidth
     *
     * @return integer
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * Set maxHeight
     *
     * @param integer $maxHeight
     *
     * @return ImageType
     */
    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    /**
     * Get maxHeight
     *
     * @return integer
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * Set defaultFilepath
     *
     * @param string $defaultFilepath
     *
     * @return ImageType
     */
    public function setDefaultFilepath($defaultFilepath)
    {
        $this->defaultFilepath = $defaultFilepath;

        return $this;
    }

    /**
     * Get defaultFilepath
     *
     * @return string
     */
    public function getDefaultFilepath()
    {
        return $this->defaultFilepath;
    }
}
