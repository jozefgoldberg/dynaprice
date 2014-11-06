<?php

namespace Dpp\CustomersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Dpp\CustomersBundle\Entity\Product;

/**
 * Customer
 *
 * @ORM\Table()
 * @ORM\Table(name="dpp_customer")
 * @ORM\Entity(repositoryClass="Dpp\CustomersBundle\Entity\CustomerRepository")
 * @UniqueEntity("domaine")
 */
 
class Customer
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
     * @ORM\Column(name="princingType", type="integer", nullable=true)
     */
    private $princingType;

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
    * @ORM\OneToMany(targetEntity="Dpp\CustomersBundle\Entity\Product", mappedBy="customer")
    */
    private $products; 

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
     * Set princingType
     *
     * @param integer $princingType
     * @return Customer
     */
    public function setPrincingType($princingType)
    {
        $this->princingType = $princingType;
        return $this;
    }

    /**
     * Get princingType
     *
     * @return integer 
     */
    public function getPrincingType()
    {
        return $this->princingType;
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
    
    
}
