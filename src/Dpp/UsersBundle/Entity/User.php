<?php
// src/Dpp/UsersBundle/Entity/User.php

namespace Dpp\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Dpp\CustomersBundle\Entity\Customer;

/**
 * user;
 *
 * @ORM\Table(name="dpp_user")
 * @ORM\Entity(repositoryClass="Dpp\UsersBundle\Entity\UserRepository")
 * @UniqueEntity("email")
 */
class User extends BaseUser 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
   /**
   * @ORM\ManyToOne(targetEntity="Dpp\CustomersBundle\Entity\Customer")
   * @ORM\JoinColumn(nullable=true)
   */
    private $customer;
    
    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     */
    private $firstName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=false)
     */
    private $lastName;
    
    /**
     * Set firstName
     *
     * @param string $firstName
     * @return user
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return user
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    
    /** 
    *
    * override for email in username
    */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->username = $email;
        return $this;
    }
    public function setUsername($username)
    {
        $this->username = $this->getEmail();
        return $this;
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
     * get description pseudo Attribute
     *
     * @param string $string
     * @return user
     */
    public function getDescription()
    {
        if ($this->getCustomer() == null) {
            return $this->getFirstName().".".$this->getLastName();
        } else {
            return $this->getCustomer()->getName()." / ".$this->getFirstName().".".$this->getLastName();
        
        }
    }
    
    

}
