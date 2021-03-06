<?php

namespace Dpp\AjaxServeurBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Dpp\CustomersBundle\Entity\Customer;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * LogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LogRepository extends EntityRepository
{

 // acces produit betwin $1 end $2
    const SELECT_PRODUCT_BETWEEN = 'Select count(*) , date(dateAccess) as jour  from dpp_log 
                                                where (customer_id = :customer_id and product_id is not null)
                                                  and (date(dateAccess) >= :periode_deb)
                                                  and (date(dateAccess) <= :periode_end)                                            
                                                group by jour
                                                order by jour  asc'; 
    // acces product for month - 1 
    const SELECT_PRODUCT_MONTH_1 = 'Select count(*), date(dateAccess) as jour  from dpp_log 
                                            where   (customer_id = :customer_id and product_id is not null)
                                              and (date(dateAccess) < curdate()-day(curdate())+1) 
                                              and (date(dateAccess) >= subdate((curdate()-day(curdate())+1),Interval 1 month)) 
                                    group by jour
                                    order by jour  asc'; 


    public function getAccessProduct(Customer $customer, $periode_deb, $periode_end) {
        $select = self::SELECT_PRODUCT_BETWEEN;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count(*)', 'count');
        $rsm->addScalarResult('jour', 'jour');
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createNativeQuery($select , $rsm)
                               ->setParameter('customer_id', $customer->getId())
                               ->setParameter('periode_deb', $periode_deb)
                               ->setParameter('periode_end', $periode_end)
                               ;
        return $query->getResult();
    }
    
    
    
    
    public function getAccessCustomerByDay(Customer $customer) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count(*)', 'count');
        $rsm->addScalarResult('jour', 'jour');
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createNativeQuery(
                                                "Select count(*), date_format(dateAccess, '%Y-%m-%d') as jour  from dpp_log 
                                                 where customer_id = :customer_id and product_id is not null
                                                 group by jour
                                                 order by jour  asc" , $rsm
                                               )
                                ->setParameter('customer_id', $customer->getId());
        return $query->getResult();
    
    }
    /* 
    * double table Access et Achat
    */
    public function getMixteCustomerByDay(Customer $customer) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('count(*)', 'count');
        $rsm->addScalarResult('type', 'type');
        $rsm->addScalarResult('jour', 'jour');
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createNativeQuery(
                                                    "(Select count(*), 'ACHAT' as type ,date_format(dateAccess, '%Y-%m-%d') as jour  from dpp_log 
                                                        where customer_id = :customer_id and purchase = 1 
                                                        group by jour)                                                 
                                                    Union
                                                    (Select count(*), 'ACC' type ,date_format(dateAccess, '%Y-%m-%d') as jour  from dpp_log 
                                                        where customer_id = :customer_id and product_id is not null
                                                        group by jour)
                                                    order by   jour, type  asc" , $rsm
                                               )
                                ->setParameter('customer_id', $customer->getId());
        return $query->getResult();
    
    }
   
    
}
