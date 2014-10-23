<?php

namespace Dpp\CustomersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Table(name="dpp_product")
 * @ORM\Entity(repositoryClass="Dpp\CustomersBundle\Entity\ProductRepository")
 * @UniqueEntity("urlref")
 */
class Product
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
     * @ORM\Column(name="urlRef", type="string", length=255, unique=true)
     */
    private $urlRef;

    /**
     * @var integer
     *
     * @ORM\Column(name="pricingType", type="integer")
     */
    private $pricingType;


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
}
