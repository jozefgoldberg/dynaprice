<?php

namespace Dpp\BuyersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\BuyersBundle\Entity\Buyer;

/**
 * BuyerCustomer
 *
 * @ORM\Table()
 * @ORM\Table(name="dpp_buyer_customer")
 * @ORM\Entity(repositoryClass="Dpp\BuyersBundle\Entity\BuyerCustomerRepository")
 */
class BuyerCustomer
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
     * @var integer
     *
     * @ORM\Column(name="totalPurchases", type="integer")
     */
    private $totalPurchases;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="totalALLAccess", type="integer")
     */
    private $totalAllAccess;
    
   

    /**
    * @ORM\ManyToOne(targetEntity="Dpp\BuyersBundle\Entity\Buyer")
    * @ORM\JoinColumn(nullable=false)
    */
    private $buyer;

    /**
    * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Customer")
    * @ORM\JoinColumn(nullable=false)
    */
    private $customer;

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
     * Set firstAccess
     *
     * @param \DateTime $firstAccess
     * @return BuyerCustomer
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
     * @return BuyerCustomer
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
     * @return BuyerCustomer
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
     * Set totalAllAccess
     *
     * @param integer $totalAllAccess
     * @return BuyerCustomer
     */
    public function setTotalAllAccess($totalAllAccess)
    {
        $this->totalAllAccess = $totalAllAccess;

        return $this;
    }

    /**
     * Get totalAllAccess
     *
     * @return integer 
     */
    public function getTotalAllAccess()
    {
        return $this->totalAllAccess;
    }
    
    /**
     * Set totalPurchases
     *
     * @param integer $totalPurchases
     * @return BuyerCustomer
     */
    public function setTotalPurchases($totalPurchases)
    {
        $this->totalPurchases = $totalPurchases;

        return $this;
    }

    /**
     * Get totalPurchases
     * @return integer 
     */
    public function getTotalPurchases()
    {
        return $this->totalPurchases;
    }
        
    /**
     * Set customer
     *
     * @param Customer $customer
     * @return BuyerCustomer
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
     * Set buyer
     *
     * @param Buyer $buyer
     * @return BuyerCustomer
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
    
    /* 
    * Create segment with default
    * @parm Buyer $buyer Customer $customer
    * @return BuyerCustomer
    */
    static function getWithDefault(Customer $customer, Buyer $buyer) {
        $date = new \DateTime('now');
        $buyerCustomer = new BuyerCustomer();
        $buyerCustomer->setCustomer($customer);
        $buyerCustomer->setBuyer($buyer);
        $buyerCustomer->setFirstAccess($date);
        $buyerCustomer->setLastAccess($date);
        $buyerCustomer->setTotalAccess(1);
        $buyerCustomer->setTotalAllAccess(0);
        $buyerCustomer->setTotalPurchases(0);
        return $buyerCustomer;
    }
    
    /* 
    * action for one purchase
    */
    public function makePurchase() {;
        $this->setTotalPurchases($this->getTotalPurchases()+1);
        $this->setTotalAllAccess($this->getTotalAllAccess()+$this->getTotalAccess());
        $this->setTotalAccess(0);
    }
    
    
    
    
    
}
