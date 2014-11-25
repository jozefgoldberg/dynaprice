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

    public function getResponse($msg) {
        $response = new Response($msg,200,array('content-type' => 'text/xml'));
        $response->headers->set('Access-Control-Allow-Origin','*');
        return $response;       
    }
    
    
    /**
    * Visit Buyer from one customer Ajax
    */    
    public function accessBruyerFromCustomerAction($domaine, $uuid) {
        $customer = $this->getCustomerByDomaine($domaine); 
        if (!$customer == null) {  
            $buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
            $buyerCustomer= $this->getBuyerCustomer($customer, $buyer);// get buyerCustomer or creat it if no exist
            $visites = $buyerCustomer->getTotalAccess();
            $tabPromo = $customer->getPromoCodesAsArray();
            $msg = null;
            if ($customer->isGlobalPromo() && (!$tabPromo === FALSE)) { 
                $msg = $this->getPromoMessage($customer, $tabPromo, $customer->getPricingType(), $visites);
            }
        }
        if ($msg == null) { 
            $msg = '<resp></resp>';
        }
        return $this->getResponse($msg);   
    }
    
    /**
    * Visit product page  Ajax
    */    
    public function accessProductAction($domaine, $uuid, $prodRef) {
        $customer = $this->getCustomerByDomaine($domaine); 
        if (!$customer == null) {       
            $product =  $this->getProductByReference($customer, $prodRef); 
            $msg = null;
            if (!$product == null && $product->getState() > 0) {  
                $buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
                $buyerCustomer = $this->getBuyerCustomer($customer, $buyer);// get buyerCustomer or creat it if no exist            
                $buyerProduct = $this->getBuyerProduct($buyer, $product);// get buyerProduct or creat it if no exist
                $visites = $buyerProduct->getTotalAccess();
                $pricingType = 0;
                if ($product->getPricingType() == 0) {
                    $pricingType = $customer->getPricingType();
                }
                $tabPromo = $product->getPromoCodesAsArray();
                if ($tabPromo === FALSE) {
                    $tabPromo = $customer->getPromoCodesAsArray();
                }
                $msg = $this->getPromoMessage($customer, $tabPromo, $pricingType, $visites);
            }
        }
        if ($msg == null) { 
            $msg = '<resp></resp>';
        }
        return $this->getResponse($msg);   
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
    * if not existe and customer is autoAcquisition create is
    */ 
    private function getProductByReference(Customer $customer,  $prodRef) {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository('DppCustomersBundle:Product')->findOneBy(array('customer'=>$customer, 'urlRef' => $prodRef));
        if ($product == null) {
            if ($customer->isAutoAcquisition()) {       
                $product = Product::create($customer,  $prodRef);
                $entityManager->persist($product);
                $entityManager->flush();
            } 
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
            $ts = $ts / 3600; // en heures            
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
            $ts = $ts / 3600; // en heures
            if ($ts > $product->getCustomer()->getVisitTimeInterval() ) {
                $bp->setTotalAccess($bp->getTotalAccess()+1);
                $bp->setLastAccess($date);
            }
        }
        $entityManager->flush();
        return $bp;
    }   
       
    public function getPromoMessage($customer, $tabPromo, $pricingType, $visites) {
        $msg = null;
        $codePromo = null;
        foreach(array_reverse($tabPromo) as $ligneCode) {
            if ($pricingType == 1) {
                if ($ligneCode[0] == $visites) {
                    $codePromo = $ligneCode[1];
                    $msg = $ligneCode[2];
                    break;
                }
            } else {
                if ($ligneCode[0] <= $visites) {
                    $codePromo = $ligneCode[1];
                    $msg = $ligneCode[2];
                    break;
                }
            }
        }
        if (!$codePromo == null) {
            if ($msg == null) {
                $msg = $customer->getDefaultMsg();
            }                
            $pos = strpos($msg,'[$visite$]');
            if (!$pos===FALSE) {
                $repstr = strval($visites);
                if ($visites > 1){
                    $repstr = $repstr+'éme ';
                }else { 
                    $repstr = $repstr+'ére ';
                }
                $msg = str_replace('[$visite$]',$repstr,$msg);
            }
            $msg = str_replace('[$code$]',$codePromo,$msg);
            $msg = '<resp><msg>'.$msg.'</msg></resp>';                     
        }
        return $msg;
    }
    
    
    
    
    
    
    
}
