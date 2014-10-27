<?php

namespace Dpp\BuyersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Buyer
 *
 * @ORM\Table()
 * @ORM\Table(name="dpp_buyer")
 * @ORM\Entity(repositoryClass="Dpp\BuyersBundle\Entity\BuyerRepository")
 * @UniqueEntity("uuid")
 */
class Buyer
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
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=36, unique=true)
     */
    private $uuid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_Access", type="datetime")
     */
    private $firstAccess;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_Access", type="datetime", nullable=true )
     */
    private $lastAccess;

    /**
     * @var string
     *
     * @ORM\Column(name="permCode", type="string", length=32, nullable=true )
     */
    private $permCode;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="totalAccess", type="integer",  nullable=true )
     */
    private $totalAccess;
    
    /**
    * @ORM\OneToMany(targetEntity="Dpp\BuyersBundle\Entity\BuyerProduct", mappedBy="product")
    */
    private $buyerProducts;


     /**
    * Constructor
    */
    public function __construct()
    {
        $this->firstAccess = new \DateTime();
        $this->totalAccess = 1;
        $this->buyerProducts = new ArrayCollection();
    }

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
     * Set uuid
     *
     * @param string $uuid
     * @return Buyer
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
     * Set firstAccess
     *
     * @param \DateTime $firstAccess
     * @return Buyer
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
     * @return Buyer
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
     * Set permCode
     *
     * @param string $permCode
     * @return Buyer
     */
    public function setPermCode($permCode)
    {
        $this->permCode = $permCode;

        return $this;
    }

    /**
     * Get permCode
     *
     * @return string 
     */
    public function getPermCode()
    {
        return $this->permCode;
    }
    
    /**
     * Set totalAccess
     *
     * @param integer $totalAccess
     * @return Buyer
     */
    public function setTotalAccess($totalAccess)
    {
        $this->totalAccess = $totalAccess;

        return $this;
    }

    /**
     * Get totalAccess
     *
     * @return string 
     */
    public function getTotalAccess()
    {
        return $this->totalAccess;
    }
       
    /**
    * Add, remove, and get BuyersProduct collection
    */
    
    public function addBuyerProduct(BuyerProduct $buyerProduct)
    {
        $this->buyerProducts[] = $buyerProduct;
        return $this;
    }

    public function removeBuyerProduct(buyerProduct $buyerProduct)
    {
        $this->buyerProducts->removeElement($buyerProduct);
    }

    public function getBuyerProducts()
    {
        return $this->buyerProducts;
    }
    
  
}
