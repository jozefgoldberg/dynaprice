<?php

namespace Dpp\BuyersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dpp\CustomersBundle\Entity\Category;
use Dpp\BuyersBundle\Entity\Buyer;

/**
 * BuyerCategory
 * @ORM\Table()
 * @ORM\Table(name="dpp_buyer_category")
 * @ORM\Entity(repositoryClass="Dpp\BuyersBundle\Entity\BuyerCategoryRepository")
 */
class BuyerCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
   /**
    * @ORM\ManyToOne(targetEntity="Dpp\BuyersBundle\Entity\Buyer")
    * @ORM\JoinColumn(nullable=false)
    */
    private $buyer;

    /**
    * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Category")
    * @ORM\JoinColumn(nullable=false)
    */
    private $category;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="firstAccess", type="datetime")
     */
    private $firstAccess;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastAccess", type="datetime")
     */
    private $lastAccess;
    /**
     * @var integer
     *
     * @ORM\Column(name="totalAccess", type="integer")
     */
    private $totalAccess;


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
     * Set status
     *
     * @param integer $status
     * @return BuyerCategory
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Set firstAccess
     *
     * @param \DateTime $firstAccess
     * @return BuyerCategory
     */
    public function setFirstAccess($firstAccess)
    {
        $this->firstAccess = $firstAccess;

        return $this;
    }

    /**
     * Get firstAccess
     *
     * @return \DateTime 
     */
    public function getFirstAccess()
    {
        return $this->firstAccess;
    }

    /**
     * Set lastAccess
     *
     * @param \DateTime $lastAccess
     * @return BuyerCategory
     */
    public function setLastAccess($lastAccess)
    {
        $this->lastAccess = $lastAccess;

        return $this;
    }

    /**
     * Get lastAccess
     *
     * @return \DateTime 
     */
    public function getLastAccess()
    {
        return $this->lastAccess;
    }

    /**
     * Set totalAccess
     *
     * @param integer $totalAccess
     * @return BuyerCategory
     */
    public function setTotalAccess($totalAccess)
    {
        $this->totalAccess = $totalAccess;

        return $this;
    }

    /**
     * Get totalAccess
     *
     * @return integer 
     */
    public function getTotalAccess()
    {
        return $this->totalAccess;
    }
    
    /**
     * Set buyer
     *
     * @param Buyer $buyer
     * @return BuyerCategory
     */
    public function setBuyer(Buyer $buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @return Buyer
     */
    public function getBuyer()
    {
        return $this->buyer;
    }
    
    /**
     * Set Category
     *
     * @param Category $Category
     * @return BuyerCategory
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get Category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    /*
    * Create with default
    */
    public static function getWithDefault(Buyer $buyer, Category $category) {
        $date = new \DateTime('now');
        $bc = new BuyerCategory(); // Création de l'entité
        $bc->setBuyer($buyer);
        $bc->setCategory($category);
        $bc->setStatus(1);
        $bc->setFirstAccess($date);
        $bc->setLastAccess($date);
        $bc->setTotalAccess(1);
        $bc->setStatus(0);
        return $bc; 
    }
    
    
}
