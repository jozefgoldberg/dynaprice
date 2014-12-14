<?php

namespace Dpp\CustomersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface as Context;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Dpp\CustomersBundle\Controller\AllTypeController;
use Dpp\AjaxServeurBundle\Entity\PromoCodeInterface;
/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Table(name="dpp_product")
 * @ORM\Entity(repositoryClass="Dpp\CustomersBundle\Entity\ProductRepository")
 * @UniqueEntity({"customer" , "urlRef"} , message="Cette reference existe pour ce client")
 */
class Product implements PromoCodeInterface
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
   * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Customer", inversedBy="products")
   * @ORM\JoinColumn(nullable=false)
   */
    private $customer;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="urlRef", type="string", length=255)
     */
    private $urlRef;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="pricingType", type="integer")
     */
    private $pricingType;

    /**
     * @var longtext
     *
     * @ORM\Column(name="promoCodes", type="text", nullable=true)
     */
    
    private $promoCodes;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer")
     */
    private $state;

    /**
   * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Category")
   * @ORM\JoinColumn(nullable=true)
   */
    private $category;    
    
    
    
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
     * Set customer
     *
     * @param Customer $customer
     * @return Product
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
     * @return Product
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
     * Set name
     *
     * @param string $name
     * @return Product
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
     * Set urlRef
     *
     * @param string $urlRef
     * @return Product
     */
    public function setUrlRef($urlRef)
    {
        $this->urlRef = $urlRef;

        return $this;
    }

    /**
     * Get urlRef
     *
     * @return string 
     */
    public function getUrlRef()
    {
        return $this->urlRef;
    }

    /**
     * Set pricingType
     *
     * @param integer $pricingType
     * @return Product
     */
    public function setPricingType($pricingType)
    {
        $this->pricingType = $pricingType;

        return $this;
    }

    /**
     * Get pricingType
     *
     * @return integer 
     */
    public function getPricingType()
    {
        return $this->pricingType;
    }
    
    /**
     * Set promoCodes
     *
     * @param longtext $promoCodes
     * @return Customer
     */
    public function setPromoCodes($promoCodes)
    {
        $this->promoCodes = $promoCodes;
        return $this;
    }

    /**
     * Get promoCodes
     *
     * @return longtext 
     */
    public function getPromoCodes()
    {
        return $this->promoCodes;
    }
    
    public function hasPromoCodes() 
    {
        return !($this->getPromoCodes() == null);
    }
    
    /*
    * get promoCode as array
    * retur array or false
    */
    public function getPromoCodesAsArray()
    {
        if ($this->getPromoCodes() == null) return FALSE;
        return json_decode($this->getPromoCodes());
    }
    
    /*
    * get promoCode as array
    * retur array or false
    * if no existe compute category code or parents or customer
    */
    public function searchPromoCodesAsArray()
    {
        if ($this->hasPromoCodes()) return $this->getPromoCodesAsArray();
        if ($this->getCategory() == null) return FALSE;
        return $this->getCategory()->searchPromoCodesAsArray();
    }
    
    
    /**
     * Set state
     *
     * @param integer $state
     * @return Product
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }
    /**
    * Get Customer domaine for unique key
    * return string
    **/
    public function getCustomerUrl() {
        if ($this->getCustomer() == null) {
            return null;
        }
        return $this->getCustomer()->getDomaine();   
    }
    /**
    * Get Customer name
    * return string
    **/
    public function getCustomerName() {
        if ($this->getCustomer() == null) {
            return null;
        }
        return $this->getCustomer()->getName();
    }
    /** 
    * get pricing as string
    * return string
    */ 
    public function getPricingAsString()
    {
        $i = $this->getPricingType();    
        if ($i > -1) {  
            return AllTypeController::getProductPricing()[$i];
        } else {
            return null;
        }   
    }
    
    /** 
    * get state as string
    * return string
    */
    public function getStateAsString()
    {
        $i = $this->getState();    
        if ($i > -1) {  
            return AllTypeController::getProductState()[$i];
        } else {
            return null;
        }   
    }
    
    public static function getWithDefault(Customer $customer, $urlRef) {
        $product = new Product(); // Création de l'entité
        $product->setCustomer($customer);
        $product->setUrlRef($urlRef);
        $product->setPricingType(0);
        // update change name for exemple prestashop 
        $pos = strpos($urlRef, '-');
        if (!$pos === FALSE) {
            $product->setName(substr($urlRef, $pos+1));
        } else {
            $product->setName($urlRef);
        }
        
        $product->setState(1);
        return $product; 
    }
    
}
