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
 * Category
 *
 * @ORM\Table()
 * @ORM\Table(name="dpp_category")
 * @ORM\Entity(repositoryClass="Dpp\CustomersBundle\Entity\CategoryRepository")
 * @UniqueEntity({"customer" , "urlRef"} , message="Cette reference existe pour ce client")
 */
class Category implements PromoCodeInterface
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
   * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Customer")
   * @ORM\JoinColumn(nullable=false)
   */
    private $customer;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * 
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;
    
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
    
    /*
    * Construct function
    */
    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set customer
     *
     * @param Customer $customer
     * @return Category
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        $this->customerUrl = $this->customer->getDomaine();

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
     * Set name
     *
     * @param string $name
     * @return Category
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
     * @return Category
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
     * @return Category
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
    
    /*
    * get promoCode as array
    * retur array or false
    */
    public function getPromoCodesAsArray()
    {
        if ($this->getPromoCodes() == null) return FALSE;
        return json_decode($this->getPromoCodes());
    }
    
    
    /**
     * Set state
     *
     * @param integer $state
     * @return Category
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
     * Set parent
     *
     * @param Dpp\CustomersBundle\Entity\Category $category
     * @return Project
     */
    public function setParent($category)
    {
        $this->parent = $category;
        return $this;
    }

    /**
     * Get parent
     *
     * @return Dpp\CustomersBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
    * Has Parent
    *
    * @return boolean true if has parent
    */
    public function hasParent()
    {
        return !($this->parent == null);
    }
    public function getParentTree() 
    {
        $parentTree = new \Doctrine\Common\Collections\ArrayCollection();
        $obj = $this;
        while ($obj->hasParent()) {
            $obj = $obj->getParent();
            $parentTree[] = $obj;
        }   
        return $parentTree;
    }
    
    /**
    * Add children
    *
    * @param Dpp\CustomersBundle\Entity\Category $category
    * @return Project
    */
    public function addChild($category)
    {
        $this->children[] = $category;
        return $this;
    }
    
    /**
    * Remove children
    *
    * @param Dpp\CustomersBundle\Entity\Category $category
    */
    public function removeChild($category)
    {
        $this->children->remove($category);
        return $this;
    }
    
    /**
    * get children
    *
    * @return collection
    */
    public function getChildren()
    {
        return $this->children;
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

    /* 
    * get new with default children
    */
    public static function getWithDefault(Customer $customer, $urlRef, Category $parent=null) {
        $category = new Category(); // Création de l'entité
        $category->setCustomer($customer);
        $category->setParent($parent);
        $category->setUrlRef($urlRef);
        $category->setPricingType(0);
        $category->setName($urlRef);
        $category->setState(1);
        return $category; 
    }
    
}
