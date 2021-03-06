<?php

namespace BankinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * BankinCategory
 *
 * @ORM\Table(name="bankin_category")
 * @ORM\Entity(repositoryClass="BankinBundle\Repository\BankinCategoryRepository")
 * @UniqueEntity({"categoryId"})
 */
class BankinCategory
{
    
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function routingArray()
    {
        return array(
            'bankinCategoryId' => $this->id,
        );
    }

    public function __toString()
    {
        return "bankinCategory #" . $this->id;
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
     * @ORM\Column(name="categoryId", type="integer")
     * @Assert\NotBlank()
     */
    private $categoryId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    
    /**
     * @var int
     *
     * @ORM\Column(name="parentId", type="integer", nullable=true)
     */
    private $parentId;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="BankinBundle\Entity\BankinCategory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", nullable=true, onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="BankinBundle\Entity\BankinCategory", mappedBy="parent")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $children;
    
    
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
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return BankinCategory
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return BankinCategory
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
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return BankinCategory
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set parent
     *
     * @param \BankinBundle\Entity\BankinCategory $parent
     *
     * @return BankinCategory
     */
    public function setParent(\BankinBundle\Entity\BankinCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \BankinBundle\Entity\BankinCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \BankinBundle\Entity\BankinCategory $child
     *
     * @return BankinCategory
     */
    public function addChild(\BankinBundle\Entity\BankinCategory $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \BankinBundle\Entity\BankinCategory $child
     */
    public function removeChild(\BankinBundle\Entity\BankinCategory $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
}
