<?php

namespace Dpp\BuyersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BuyerProduct
 * @ORM\Table()
 * @ORM\Table(name="dpp_buyer_product")
 * @ORM\Entity(repositoryClass="Dpp\BuyersBundle\Entity\BuyerProductRepository")
 */
class BuyerProduct
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
    * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Product")
    * @ORM\JoinColumn(nullable=false)
    */
    private $product;
    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

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
     * @return BuyerProduct
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
     * Set totalAccess
     *
     * @param integer $totalAccess
     * @return BuyerProduct
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
     * @return BuyerProduct
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
     * Set product
     *
     * @param Product $product
     * @return BuyerProduct
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }
    
    
}
