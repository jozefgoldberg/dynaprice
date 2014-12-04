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
use Dpp\CustomersBundle\Entity\Category;
use Dpp\AjaxServeurBundle\Entity\Log;

class AjaxServeurController extends Controller
{
    protected $entityManager = null;
    protected $customer = null;
    protected $product = null;
    protected $category = null;
    protected $buyer = null;
    
    /**
    * todfo Constructor make to get entity manager
    */

    public function getResponse($msg) {
        $response = new Response($msg,200,array('content-type' => 'text/xml'));
        $response->headers->set('Access-Control-Allow-Origin','*');
        return $response;       
    }
    /* 
    * log all action
    */
    private function logAction() {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        if ((!$this->customer == null) && (!$this->buyer == null)) {
            $log = new Log();
            $log->setDateAccess(new \DateTime('now'));
            $log->setCustomer($this->customer);
            $log->setBuyer($this->buyer);
            if (!$this->product == null) {$log->setProduct($this->product);}
            if (!$this->category == null) {$log->setCategory($this->category);}
            $this->entityManager->persist($log);
        }
    }
    
    
    /**
    * Visit Buyer from one customer Ajax
    */    
    public function accessBruyerFromCustomerAction($domaine, $uuid) {
        $this->customer = $this->getCustomerByDomaine($domaine); 
        if (!$this->customer == null) {  
            $this->buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
            $buyerCustomer= $this->getBuyerCustomer($this->customer, $this->buyer);// get buyerCustomer or creat it if no exist
            $visites = $buyerCustomer->getTotalAccess();
            $tabPromo = $this->customer->getPromoCodesAsArray();
            $msg = null;
            if ($this->customer->isGlobalPromo() && (!$tabPromo === FALSE)) { 
                $msg = $this->getPromoMessage($this->customer, $tabPromo, $this->customer->getPricingType(), $visites);
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
    public function accessProductAction($domaine, $uuid, $urlRef) {
        $this->customer = $this->getCustomerByDomaine($domaine); 
        $prodRef = null;
        $categoryRef = null;
        if (!$this->customer == null) {    
            if ($this->customer->hasCategoryPromo()) {
                $pos = strrpos($urlRef, "+");
                if (!$pos === FALSE) {
                    $prodRef = substr($urlRef,$pos+1); 
                    $pos1 = strrpos(substr($urlRef,0,-1*(strlen($prodRef)+1)),"+");
                    if (!$pos1 === FALSE) {
                        $categoryRef = substr($urlRef,$pos1+1,$pos-$pos1-1);
                    }
                }
                
            } else {
                $pos = strrpos($urlRef, "+");
                if (!$pos === FALSE) {
                    $prodRef = substr($urlRef,-1,strlen($urlRef)-$pos-1); 
                } 
            }
            if (!$prodRef == null) {
                if (!$categoryRef == null) {
                    $this->category = $this->getCategoryByReference($this->customer, $categoryRef);
                }      
                $this->product =  $this->getProductByReference($this->customer, $prodRef, $this->category); 
            }
            $msg = null;
            if (!$this->product == null && $this->product->getState() > 0) {  
                $this->buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
                $buyerCustomer = $this->getBuyerCustomer($this->customer, $this->buyer);// get buyerCustomer or creat it if no exist            
                $buyerProduct = $this->getBuyerProduct($this->buyer, $this->product);// get buyerProduct or creat it if no exist
                $visites = $buyerProduct->getTotalAccess();
                $pricingType = 0;
                if (!$this->customer->isGlobalPromo()) { // pas de message produit si promo général
                    if ($this->product->getPricingType() == 0) {
                        $pricingType = $this->customer->getPricingType();
                    }
                    $tabPromo = $this->product->getPromoCodesAsArray();
                    if ($tabPromo === FALSE) {
                        $tabPromo = $this->customer->getPromoCodesAsArray();
                    }
                    if (!$tabPromo === FALSE) {
                        $msg = $this->getPromoMessage($this->customer, $tabPromo, $pricingType, $visites);
                    }
                }
            }
        }
        if ($msg == null) { 
            $msg = '<resp></resp>';
        }
        return $this->getResponse($msg);   
    }
    
    /**
    *  Buyer as buyed  from one customer Ajax
    *  achat++
    *  total product for this customers is archived and deleteed ?
    */    
    public function achatBruyerFromCustomerAction($domaine, $uuid) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $msg = '<resp></resp>';
        $this->customer = $this->getCustomerByDomaine($domaine); 
        if (!$this->customer == null) {   
            $this->buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
            $buyerCustomer= $this->getBuyerCustomer($this->customer, $this->buyer);// get buyerCustomer or creat it if no exist
            //
            $buyerCustomer->makePurchase();
            $this->entityManager->getRepository('DppBuyersBundle:BuyerProduct')->removeForBuyerCustomer($buyerCustomer);
            $this->entityManager->flush();
        }
        return $this->getResponse($msg);   
    }
    
    /**
    * Get customer by domain
    */ 
    private function getCustomerByDomaine($domain) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $customer = null;
        $customList = $this->entityManager->getRepository('DppCustomersBundle:Customer')->findBy(array('domaine' => $domain));
        if (!$customList == null) {
            $customer = $customList[0];
        }
        return $customer;
    }   
    
    /**
    * Get product by reference
    * if not existe and customer is autoAcquisition create is
    */ 
    private function getProductByReference(Customer $customer,  $prodRef, Category $category=null) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $this->category = $category;
        $product = $this->entityManager->getRepository('DppCustomersBundle:Product')->findOneBy(array('customer'=>$customer, 'urlRef' => $prodRef));
        if ($product == null) {
            if ($customer->isAutoAcquisition()) {       
                $product = Product::getWithDefault($this->customer,  $prodRef);
                if (!$this->category==null) {
                    $this->product->setCategory($this->category);
                }
                $this->entityManager->persist($product);
                $this->entityManager->flush();
            } 
        } else {
            if (!($category == null) && ($product->getCategory() == null)) {
                $product->setCategory($category);
                $this->entityManager->persist($product);
                $this->entityManager->flush();
            }
        }
        return $product;
    }   
    
    /**
    * Get category by reference
    * if not existe and customer is autoAcquisition create is
    */ 
    private function getCategoryByReference(Customer $customer,  $categoryRef) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $category = $this->entityManager->getRepository('DppCustomersBundle:Category')->findOneBy(array('customer'=>$customer, 'urlRef' => $categoryRef));
        if ($category == null) {
            if ($customer->isAutoAcquisition()) {       
                $category = Category::getWithDefault($customer,  $categoryRef);
                $this->entityManager->persist($category);
                $this->entityManager->flush();
            } 
        }
        return $category;
    }   
    
    /**
    * get Buyer by uuid if no exist create it
    */
    private function getBuyerByUid($uuid) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $date = new \DateTime('now');
        $buyer = $this->entityManager->getRepository('DppBuyersBundle:Buyer')->findOneBy(array('uuid' => $uuid));
        // register if noexist
        if ($buyer == null) {
            $buyer = new Buyer(); 
            $buyer->setUuid($uuid);
            $buyer->setFirstAccess($date);
            $buyer->setLastAccess($date);            
        } else {
            $buyer->setLastAccess($date);
        }
        $this->entityManager->persist($buyer);
        $this->entityManager->flush();
        return $buyer;
    }   
    
    /**
    * get BuyerCustomer if no exist creat it
    */
    private function getBuyerCustomer(Customer $customer, Buyer $buyer) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $date = new \DateTime('now');
        $bcList = $this->entityManager->getRepository('DppBuyersBundle:BuyerCustomer')->findBy(array('customer' => $customer,'buyer'=> $buyer ));
        if ($bcList == null) {
            $bc = BuyerCustomer::getWithDefault($customer, $buyer); 
            $this->entityManager->persist($bc);
            $this->logAction();
        } else {
            $bc = $bcList[0];
            $ts = $date->getTimestamp() - $bc->getLastAccess()->getTimestamp();  
            $ts = $ts / 3600; // en heures            
            if ($ts > $customer->getVisitTimeInterval() ) {
                $bc->setTotalAccess($bc->getTotalAccess()+1);
                $bc->setLastAccess($date);
                $this->logAction();
            }
        }
        $this->entityManager->flush();
        return $bc;
    }   
    
    /**
    * get BuyerProduct if no exist creat it
    */
    private function getBuyerProduct(Buyer $buyer, Product $product) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $date = new \DateTime('now');
        $bp = $this->entityManager->getRepository('DppBuyersBundle:BuyerProduct')->findOneBy(array('buyer'=> $buyer, 'product' => $product ));
        if ($bp == null) {
            $bp = new BuyerProduct(); 
            $bp->setProduct($product);
            $bp->setBuyer($buyer);
            $bp->setFirstAccess($date);
            $bp->setLastAccess($date);
            $bp->setTotalAccess(1);
            $bp->setStatus(0);
            $this->entityManager->persist($bp);
            $this->logAction();
        } else {
            $ts = $date->getTimestamp() - $bp->getLastAccess()->getTimestamp();     
            $ts = $ts / 3600; // en heures
            if ($ts > $product->getCustomer()->getVisitTimeInterval() ) {
                $bp->setTotalAccess($bp->getTotalAccess()+1);
                $bp->setLastAccess($date);
            }
        }
        $this->entityManager->flush();
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
