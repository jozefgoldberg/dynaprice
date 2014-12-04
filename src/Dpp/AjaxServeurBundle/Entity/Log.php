<?php

namespace Dpp\AjaxServeurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\CustomersBundle\Entity\Category;
use Dpp\CustomersBundle\Entity\Product;
use Dpp\BuyersBundle\Entity\Buyer;

/**
 * Log
 *
 * @ORM\Table()
  * @ORM\Table(name="dpp_log")
 * @ORM\Entity(repositoryClass="Dpp\AjaxServeurBundle\Entity\LogRepository")
 */
class Log
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateAccess", type="datetime")
     */
    private $dateAccess;

    /**
    * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Customer")
    * @ORM\JoinColumn(nullable=false)
    */
    private $customer;

    /**
    * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Category")
    * @ORM\JoinColumn(nullable=true)
    */
    private $category;    

    /**
    * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Product")
    * @ORM\JoinColumn(nullable=true)
    */
    private $product;
    
    /**
    * @ORM\ManyToOne(targetEntity="Dpp\BuyersBundle\Entity\Buyer")
    * @ORM\JoinColumn(nullable=false)
    */
    private $buyer;
    
    /* ***********************
    * setter's and geter's
    ************************ */
    
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
     * Set dateAccess
     *
     * @param \DateTime $dateAccess
     * @return Log
     */
    public function setDateAccess($dateAccess)
    {
        $this->dateAccess = $dateAccess;

        return $this;
    }

    /**
     * Get dateAccess
     *
     * @return \DateTime 
     */
    public function getDateAccess()
    {
        return $this->dateAccess;
    }
    
    /**
     * Set customer
     *
     * @param Customer $customer
     * @return Log
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     * Set category
     *
     * @param Category $category
     * @return Log
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Set product
     *
     * @param Product $product
     * @return Log
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
       /**
     * Set buyer
     *
     * @param Buyer $buyer
     * @return Log
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
    
}
