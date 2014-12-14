<?php

namespace Dpp\CustomersBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\CustomersBundle\Entity\Categorie;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    private $entityManager = null;
    
    
    public function populateChildren(Category $category) {
        if ($this->entityManager == null) {
            $this->entityManager = $this->getEntityManager();
        }
        $query = $this->entityManager->createQuery(
                                                'Select u from Dpp\CustomersBundle\Entity\Category u where u.parent = :parent order by u.name')
                                ->setParameter('parent', $category);
        $children =  $query->getResult();
        return $children;
    }
    
    
    public function getAllHierarchyCustomer(Customer $customer)
    {
        if ($this->entityManager == null) {
            $this->entityManager = $this->getEntityManager();
        }
        $categoryTree = new \Doctrine\Common\Collections\ArrayCollection();
        $query = $this->entityManager->createQuery(
                                                'Select u from Dpp\CustomersBundle\Entity\Category u where u.customer = :customer
                                                                                                       and u.parent is null
                                                                                                     order by u.name' )
                                ->setParameter('customer', $customer);
        $newCollect = $query->getResult();
        if (!$newCollect == null) {
            foreach($newCollect as $category) {
                $this->recurcivePopulateTree($categoryTree, $category);
            }
        }
        return $categoryTree;
    }
    
    private function recurcivePopulateTree($categoryTree, $category) {       
        $categoryTree[] = $category;
        $newCollect = $this->populateChildren($category);
        if (!$newCollect == null) {
            foreach($newCollect as $category) {
                $this->recurcivePopulateTree($categoryTree, $category);
            }
        }
        return;
    }
    
    public function getCountForCustomer(Customer $customer)
    {
        if ($this->entityManager == null) {
            $this->entityManager = $this->getEntityManager();
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count(*)', 'count');
        $query = $this->entityManager->createNativeQuery(
                                                'Select count(*) from dpp_category where customer_id = :customer_id',
                                               $rsm)
                                ->setParameter('customer_id', $customer->getId());
        return $query->getResult();
    }
   
}
