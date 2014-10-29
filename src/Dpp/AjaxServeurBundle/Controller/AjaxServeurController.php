<?php
// src\Dpp\AjaxServeurBundle\Controller\AjaxServeurController.php

namespace Dpp\AjaxServeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Dpp\BuyersBundle\Entity\Buyer;
use Dpp\BuyersBundle\Entity\BuyerCustomer;
use Dpp\BuyersBundle\Entity\BuyerProduct;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\CustomersBundle\Entity\Product;

class AjaxServeurController extends Controller
{
    
    
    
    /**
    * Visit Buyer from one customer Ajax
    */    
    public function accessBruyerFromCustomerAction($domaine, $uuid) {
        $customer = $this->getCustomerByDomaine($domaine); 
        if (!$customer == null) {  
            $buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
            $buyerCustomer= $this->getBuyerCustomer($customer, $buyer);// get buyerCustomer or creat it if no exist
            $visites = $buyerCustomer->getTotalAccess();
            $code = "" ;
            $msg = '<resp><visite>'.$visites.'</visite><code>'.$code.'</code></resp>';    
        } else { 
            $msg = '<resp>no exist></resp>';
        }
            return new Response($msg,200,array('content-type' => 'text/xml'));
    }
    
    /**
    * Visit product page  Ajax
    */    
    public function accessProductAction($domaine, $uuid, $prodRef) {
        $customer = $this->getCustomerByDomaine($domaine); 
        $product =  $this->getProductByReference($prodRef); 
        if ((!$customer == null) && (!$product == null) && ($product->getCustomer()->getId() == $customer->getId())) {  
            $buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
            $buyerCustomer = $this->getBuyerCustomer($customer, $buyer);// get buyerCustomer or creat it if no exist            
            $buyerProduct = $this->getBuyerProduct($buyer, $product);// get buyerProduct or creat it if no exist
            $visites = $buyerProduct->getTotalAccess();
            $code = "" ;
            if ($visites > 2) {$code = 'HM3A';}
            if ($visites > 3) {$code = 'HM6A';}
            if ($visites > 8) {$code = 'HM9A';}
            $msg = '<resp><visite>'.$visites.'</visite><code>'.$code.'</code></resp>';    
        } else { 
            $msg = '<resp>no exist></resp>';
        }
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
    * Get product by reference
    */ 
    private function getProductByReference($prodRef) {
        $entityManager = $this->getDoctrine()->getManager();
        $product = null;
        $productList = $entityManager->getRepository('DppCustomersBundle:Product')->findBy(array('urlRef' => $prodRef));
        if (!$productList == null) {
            $product = $productList[0];
        }
        return $product;
    }   
    
    /**
    * get Buyer by uuid if no exist create it
    */
    private function getBuyerByUid($uuid) {
        $entityManager = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');
        $buyersList = $entityManager->getRepository('DppBuyersBundle:Buyer')->findBy(array('uuid' => $uuid));
        // register if noexist
        if ($buyersList == null) {
            $buyer = new Buyer(); 
            $buyer->setUuid($uuid);
            $buyer->setFirstAccess($date);
            $buyer->setLastAccess($date);
            $entityManager->persist($buyer);
        } else {
            $buyer = $buyersList[0];
            $buyer->setLastAccess($date);
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
            if ($ts > $customer->getVisitTimeInterval() ) {
                $bc->setTotalAccess($bc->getTotalAccess()+1);
                $bc->setLastAccess($date);
            }
        }
        $entityManager->flush();
        return $bc;
    }   
    
    /**
    * get BuyerProduct if no exist creat it
    */
    private function getBuyerProduct(Buyer $buyer, Product $product) {
        $entityManager = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');
        $bpList = $entityManager->getRepository('DppBuyersBundle:BuyerProduct')->findBy(array('buyer'=> $buyer, 'product' => $product ));
        if ($bpList == null) {
            $bp = new BuyerProduct(); 
            $bp->setProduct($product);
            $bp->setBuyer($buyer);
            $bp->setFirstAccess($date);
            $bp->setLastAccess($date);
            $bp->setTotalAccess(1);
            $bp->setStatus(0);
            $entityManager->persist($bp);
        } else {
            $bp = $bpList[0];
            $ts = $date->getTimestamp() - $bp->getLastAccess()->getTimestamp();            
            if ($ts > $product->getCustomer()->getVisitTimeInterval() ) {
                $bp->setTotalAccess($bp->getTotalAccess()+1);
                $bp->setLastAccess($date);
            }
        }
        $entityManager->flush();
        return $bp;
    }   
       
    
    
    
    
    
    
    
    
}
