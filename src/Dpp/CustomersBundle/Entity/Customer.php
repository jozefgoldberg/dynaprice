<?php

namespace Dpp\CustomersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Dpp\CustomersBundle\Entity\Product;
use Dpp\CustomersBundle\Controller\AllTypeController;
use Dpp\AjaxServeurBundle\Entity\PromoCodeInterface;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Customer
 *
 * @ORM\Table()
 * @ORM\Table(name="dpp_customer")
 * @ORM\Entity(repositoryClass="Dpp\CustomersBundle\Entity\CustomerRepository")
 * @UniqueEntity("domaine")
 */
 
class Customer implements PromoCodeInterface
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="domaine", type="string", length=255, unique=true)
     */
    private $domaine;

    /**
     * @var integer
     *
     * @ORM\Column(name="pricingType", type="integer", nullable=true)
     */
    private $pricingType;

    /**
     * @var integer
     *
     * @ORM\Column(name="visitTimeInterval", type="integer")
     */
    private $visitTimeInterval;
    
     /**
     * @var integer
     *
     * @ORM\Column(name="importType", type="integer")
     */
    private $importType;
    
     /**
     * @var longtext
     *
     * @ORM\Column(name="promoCodes", type="text", nullable=true)
     */
    private $promoCodes;

    /**
    * @ORM\OneToMany(targetEntity="Dpp\CustomersBundle\Entity\Product", mappedBy="customer")
    */
    private $products; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="defaultMsg", type="string", length=255)
     */
    private $defaultMsg;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="autoAcquisition", type="boolean")
     */
    private $autoAcquisition;

    /**
     * @var boolean
     *
     * @ORM\Column(name="globalPromo", type="boolean")
     */
    private $globalPromo;
    
    
    
    
    
    /**
    * Constructor
    */
    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Customer
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
     * Set domaine
     *
     * @param string $domaine
     * @return Customer
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * Get domaine
     *
     * @return string 
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * Set pricingType
     *
     * @param integer $pricingType
     * @return Customer
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
     * Set visitTimeInterval
     *
     * @param integer $visitTimeInterval
     * @return Customer
     */
    public function setVisitTimeInterval($visitTimeInterval)
    {
        $this->visitTimeInterval = $visitTimeInterval;
        return $this;
    }

    /**
     * Get visitTimeInterval
     *
     * @return integer 
     */
    public function getVisitTimeInterval()
    {
        return $this->visitTimeInterval;
    }
    
    /**
     * Set importType
     *
     * @param integer $importType
     * @return Customer
     */
    public function setImportType($importType)
    {
        $this->importType = $importType;
        return $this;
    }

    /**
     * Get importType
     *
     * @return integer 
     */
    public function getImportType()
    {
        return $this->importType;
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
     * Set defaultMsg
     *
     * @param string $defaultMsg
     * @return Customer
     */
    public function setDefaultMsg($defaultMsg)
    {
        $this->defaultMsg = $defaultMsg;

        return $this;
    }

    /**
     * Get defaultMsg
     *
     * @return string 
     */
    public function getDefaultMsg()
    {
        return $this->defaultMsg;
    }
    
    /**
     * Set autoAcquisition
     *
     * @param boolean $autoAcquisition
     * @return Customer
     */
    public function setAutoAcquisition($autoAcquisition)
    {
        $this->autoAcquisition = $autoAcquisition;

        return $this;
    }

    /**
     * Get autoAcquisition
     *
     * @return boolean 
     */
    public function getAutoAcquisition()
    {
        return $this->autoAcquisition;
    }
    public function isAutoAcquisition()
    {
        return $this->autoAcquisition;
    }
    
    /**
     * Set globalPromo
     *
     * @param boolean $globalPromo
     * @return Customer
     */
    public function setGlobalPromo($globalPromo)
    {
        $this->globalPromo = $globalPromo;

        return $this;
    }

    /**
     * Get globalPromo
     *
     * @return boolean 
     */
    public function getGlobalPromo()
    {
        return $this->globalPromo;
    }
    public function isGlobalPromo()
    {
        return $this->globalPromo;
    }
    
    
    /**
    * Add, remove, and get Product collection
    */
    
    public function addProduct(Product $product)
    {
        $this->products[] = $product;
        $product->setCustomer($this);
        return $this;
    }

    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    public function getProducts()
    {
        return $this->products;
    }
    
    /** 
    * get pricing as string
    * return string
    */
    public function getPricingAsString()
    {
        $i = $this->getPricingType();    
        if ($i > -1) {  
            return AllTypeController::getCustomerPricing()[$i];
        } else {
            return null;
        }
        
    }
     /** 
    * get pricing as string
    * return string
    */
    public function getImportAsString()
    {
        $i = $this->getImportType();    
        if ($i > -1) {  
            return AllTypeController::getCustomerImport()[$i];
        } else {
            return null;
        }
        
    }
    
    
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getPromoCodes() == null && ($this->isAutoAcquisition() || $this->isGlobalPromo())) {
            $context->addViolationAt(
                'importType','Dpp.error.customer.not_promoCode',array(), null);
        }
        if (!stripos($this->getDefaultMsg(), '[$code$]')) {
            $context->addViolationAt(
                'defaultMsg','Dpp.error.customer.noValideMsg',array(), null);
        }
    }
    
}
