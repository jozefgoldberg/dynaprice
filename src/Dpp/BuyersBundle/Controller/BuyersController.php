<?php
// src\Dpp\BuyersBundle\Controller\BuyersController.php

namespace Dpp\BuyersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Validator\Constraints\DateTime;
use Dpp\BuyersBundle\Entity\Buyer;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\BuyersBundle\Entity\BuyerCustomer;



class BuyersController extends Controller
{
    
    /**
    * Create Buyer Only from Ajax
    */
    public function createAction($id)
    {   
        $msg = '<resp>';
        $response = $this->register($id);
        return $response;
    }
    
    /**
    * Copy pour les test Create Buyer from Ajax
    */
    public function createXAction($id)
    {       
        return $this->render('DppDemoBundle:Default:index.html.twig');
    }

    
    /**
    * Visit Buyer from one customer Ajax
    */    
    public function accessBruyerFromCustomerAction($domaine, $uuid) {
        $customer = $this->getCustomerByDomaine($domaine); 
        if (!$customer == null) {
            $interval = 20;
            $buyer = $this->getBuyerByUid($uuid);
            $buyerCustomer= $this->getBuyerCustomer($customer, $buyer);
            $visites = $buyerCustomer->getTotalAccess();
            $code = "" ;
            if ($visites > 2) {$code = 'HM3A';}
            if ($visites > 3) {$code = 'HM6A';}
            if ($visites > 8) {$code = 'HM9A';}
            $msg = '<resp><visite>'.$visites.'</visite><code>'.$code.'</code></resp>';
            var_dump($msg);    
        } 
        return $this->render('DppDemoBundle:Default:index.html.twig');
    }
    
    
    
    /**
    * Action d'enregistrement one buyer (acheteur)
    */
    private function register($id) {
        
        $date = new \DateTime('now');
        $entityManager = $this->getDoctrine()->getManager();
        $buyersList = $entityManager->getRepository('DppBuyersBundle:Buyer')->findBy(array('uuid' => $id));
        if ($buyersList == null) {
            $buyer = new Buyer(); 
            $buyer->setUuid($id);
            $buyer->setFirstAccess($date);
            $buyer->setLastAccess($date);
            $entityManager->persist($buyer);
        } else {
            $buyer = $buyersList[0];
            $ts = $date->getTimestamp() - $buyer->getLastAccess()->getTimestamp();
            if ($ts > 20) {
                $buyer->setTotalAccess($buyer->getTotalAccess()+1);
                $buyer->setLastAccess($date);
            }
        }
        $visites = $buyer->getTotalAccess();
        $code = "" ;
        if ($visites > 2) {$code = 'HM3A';}
        if ($visites > 3) {$code = 'HM6A';}
        if ($visites > 8) {$code = 'HM9A';}
        $entityManager->flush();
        $msg = '<resp><visite>'.$visites.'</visite><code>'.$code.'</code></resp>';
        return new Response($msg,200,array('content-type' => 'text/xml'));
    }
    /**
    * Get customer by domain
    */ 
    private function getCustomerByDomaine($domain) {
        $entityManager = $this->getDoctrine()->getManager();
        $customer = null;
        $customList = $entityManager->getRepository('DppCustomersBundle:Customer')->findBy(array('domaine' => $domain));
        if (!$customList == null) {
            $customer = $customList[0];
        }
        return $customer;
    }   
    /**
    * get Buyer by Uid if no exist create it
    */
    private function getBuyerByUid($uuid) {
        $entityManager = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');
        $buyersList = $entityManager->getRepository('DppBuyersBundle:Buyer')->findBy(array('uuid' => $uuid));
        // register if noexist
        if ($buyersList == null) {
            $buyer = new Buyer(); 
            $buyer->setUuid($id);
            $buyer->setFirstAccess($date);
            $buyer->setLastAccess($date);
            $entityManager->persist($buyer);
        } else {
            $buyer = $buyersList[0];
            $ts = $date->getTimestamp() - $buyer->getLastAccess()->getTimestamp();
            if ($ts > 60) {
                $buyer->setTotalAccess($buyer->getTotalAccess()+1);
                $buyer->setLastAccess($date);
            }
        }
        $entityManager->flush();
        return $buyer;
    }   
    /**
    * get BuyerCustomer if no exist creat it
    */
    private function getBuyerCustomer(Customer $customer, Buyer $buyer) {
        $entityManager = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');
        $bcList = $entityManager->getRepository('DppBuyersBundle:BuyerCustomer')->findBy(array('customer' => $customer,'buyer'=> $buyer ));
        if ($bcList == null) {
            $bc = new BuyerCustomer(); 
            $bc->setCustomer($customer);
            $bc->setBuyer($buyer);
            $bc->setFirstAccess($date);
            $bc->setLastAccess($date);
            $bc->setTotalAccess(1);
            $entityManager->persist($bc);
        } else {
            $bc = $bcList[0];
            $ts = $date->getTimestamp() - $bc->getLastAccess()->getTimestamp();
            if ($ts > 0) {
                $bc->setTotalAccess($bc->getTotalAccess()+1);
                $bc->setLastAccess($date);
            }
        }
        $entityManager->flush();
        return $bc;
    }   
       
    
    
    

    
}