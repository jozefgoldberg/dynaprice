<?php

namespace Dpp\BuyersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
    * Constructeur
    * la first date = la date du jour
    *
    */
    public function __construct()
    {
        $this->firstAccess = new \DateTime();
    }
}
