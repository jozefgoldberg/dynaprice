<?php  
// src\Dpp\AjaxServeurBundle\Controller\AjaxServeurController.php

namespace Dpp\AjaxServeurBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Dpp\BuyersBundle\Entity\Buyer;
use Dpp\BuyersBundle\Entity\BuyerCustomer;
use Dpp\BuyersBundle\Entity\BuyerProduct;
use Dpp\BuyersBundle\Entity\BuyerCategory;
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
    protected $purchase = FALSE;
    protected $message = null;
    
    /**
    * todfo Constructor make to get entity manager
    */

    public function getResponse() {
        $response = new Response($this->message,200,array('content-type' => 'text/xml'));
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
            $log->setPurchase($this->purchase);
            $this->entityManager->persist($log);
        }
    }
    
    
    /**
    * Visit Buyer from all page in customer site 
    */    
    public function accessBruyerFromCustomerAction($domaine, $uuid) {
        $this->customer = $this->getCustomerByDomaine($domaine); 
        if (!$this->customer == null) {  
            $this->buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
            $buyerCustomer= $this->getBuyerCustomer();// get buyerCustomer or creat it if no exist
            $visites = $buyerCustomer->getTotalAccess();
            $tabPromo = $this->customer->getPromoCodesAsArray();
            if ($this->customer->isGlobalPromo() && (!$tabPromo === FALSE)) { 
                $this->message = $this->getPromoMessage( $tabPromo, $this->customer->getPricingType(), $visites);
            }
        }
        if ($this->message == null) { 
            $this->message = '<resp></resp>';
        }
        return $this->getResponse($this->message);   
    }
    /**
    * Visit Category page  
    */    
    public function accessCategoryAction($domaine, $uuid, $urlRef) {
        $this->customer = $this->getCustomerByDomaine($domaine); 
        $categoryRef = null;
        if (!$this->customer == null) {    
            $pos = strrpos($urlRef, "+");
            if (!$pos === FALSE) {
                $categoryRef = substr($urlRef,$pos+1); 
            } 
            if (!$categoryRef == null) {
                $this->category = $this->getCategoryByReference($categoryRef);
            }      
            if (!$this->category == null && $this->category->getState() > 0) {  
                $this->buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
                $buyerCustomer = $this->getBuyerCustomer($this->customer, $this->buyer);// get buyerCustomer or creat it if no exist            
                $buyerCategory = $this->getBuyerCategory($this->buyer, $this->category);// get buyerCategory or creat it if no exist
                $visites = $buyerCategory->getTotalAccess();
                $pricingType = 0;
                if (!$this->customer->isGlobalPromo()) { // pas de message category si promo général
                    if ($this->category->getPricingType() == 0) {
                        $pricingType = $this->customer->getPricingType();
                    }
                    $tabPromo = $this->category->getPromoCodesAsArray();
                    if ($tabPromo === FALSE) {
                        $tabPromo = $this->customer->getPromoCodesAsArray();
                    }
                    if (!$tabPromo === FALSE) {
                        $this->message = $this->getPromoMessage( $tabPromo, $pricingType, $visites);
                    }
                }
            }
        }
        if ($this->message == null) { 
            $this->message = '<resp></resp>';
        }
        return $this->getResponse($this->message);   
    }
    
    /**
    * Visit product page  
    */    
    public function accessProductAction($domaine, $uuid, $urlRef) {
        $this->customer = $this->getCustomerByDomaine($domaine); 
        $prodRef = null;
        $categoryName= null;
        if (!$this->customer == null) {    
            if ($this->customer->hasCategoryPromo()) {
                $pos = strrpos($urlRef, "+");
                if (!$pos === FALSE) {
                    $prodRef = substr($urlRef,$pos+1); 
                    $pos1 = strrpos(substr($urlRef,0,-1*(strlen($prodRef)+1)),"+");
                    if (!$pos1 === FALSE) {
                        $categoryName = substr($urlRef,$pos1+1,$pos-$pos1-1);
                    }
                }
                
            } else {
                $pos = strrpos($urlRef, "+");
                if (!$pos === FALSE) {
                    $prodRef = substr($urlRef,-1,strlen($urlRef)-$pos-1); 
                } 
            }
            if (!$prodRef == null) {
                if (!$categoryName == null) {
                    $this->category = $this->getCategoryByName($categoryName);
                }      
                $this->product =  $this->getProductByReference($prodRef, $this->category); 
            }
            $this->message = null;
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
                        $this->message = $this->getPromoMessage( $tabPromo, $pricingType, $visites);
                    }
                }
            }
        }
        if ($this->message == null) { 
            $this->message = '<resp></resp>';
        }
        return $this->getResponse($this->message);   
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
        $this->message = '<resp></resp>';
        $this->customer = $this->getCustomerByDomaine($domaine); 
        if (!$this->customer == null) {   
            $this->buyer = $this->getBuyerByUid($uuid);  // get buyer or creat it if no exist
            $buyerCustomer= $this->getBuyerCustomer($this->customer, $this->buyer);// get buyerCustomer or creat it if no exist
            //
            $buyerCustomer->makePurchase();
            $this->entityManager->getRepository('DppBuyersBundle:BuyerProduct')->removeForBuyerCustomer($buyerCustomer);
            $this->purchase = True;
            $this->logAction();
            $this->entityManager->flush();
        }
        return $this->getResponse($this->message);   
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
    private function getProductByReference($prodRef, Category $category=null) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $this->category = $category;
        $product = $this->entityManager->getRepository('DppCustomersBundle:Product')->findOneBy(array('customer'=>$this->customer, 'urlRef' => $prodRef));
        if ($product == null) {
            if ($this->customer->isAutoAcquisition()) {       
                $product = Product::getWithDefault($this->customer,  $prodRef);
                if (!$this->category==null) {
                    $product->setCategory($this->category);
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
    * if not existe  verify by name if existe update autherwhise  create it
    */ 
    private function getCategoryByReference($categoryRef) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $category = $this->entityManager->getRepository('DppCustomersBundle:Category')->findOneBy(array('customer'=>$this->customer, 'urlRef' => $categoryRef));
        if ($category == null) {
        // verify by name 
            $pos = strpos($categoryRef, '-');
            if (!$pos === FALSE) {
                $name = substr($categoryRef, $pos+1);
                $category = $this->getCategoryByName($name);
                if (!$category == null) {
                    $category->setUrlRef($categoryRef);
                    $this->entityManager->persist($category);
                    $this->entityManager->flush();
                }
            } else {      
                $category = Category::getWithDefault($this->customer,  $categoryRef);
                $this->entityManager->persist($category);
                $this->entityManager->flush();
            }
        }
        return $category;
    }   
    /**
    * Get category by name (acces from product )
    * if not existe and customer is autoAcquisition create is
    */ 
    private function getCategoryByName($name) {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $category = $this->entityManager->getRepository('DppCustomersBundle:Category')->findOneBy(array('customer'=>$this->customer, 'name' => $name));
        if ($category == null) {      
            $category = Category::getWithDefault($this->customer,  $name);
            $this->entityManager->persist($category);
            $this->entityManager->flush();
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
    private function getBuyerCustomer() {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $date = new \DateTime('now');
        $buyerCustomer = $this->entityManager->getRepository('DppBuyersBundle:BuyerCustomer')->findOneBy(array('customer' => $this->customer,'buyer'=> $this->buyer ));
        if ($buyerCustomer == null) {
            $buyerCustomer = BuyerCustomer::getWithDefault($this->customer, $this->buyer); 
            $this->entityManager->persist($buyerCustomer);
            $this->logAction();
        } else {
            $ts = $date->getTimestamp() - $buyerCustomer->getLastAccess()->getTimestamp();  
            $ts = $ts / 3600; // en heures            
            if ($ts > $this->customer->getVisitTimeInterval() ) {
                $buyerCustomer->setTotalAccess($buyerCustomer->getTotalAccess()+1);
                $buyerCustomer->setLastAccess($date);
                $this->logAction();
            }
        }
        $this->entityManager->flush();
        return $buyerCustomer;
    }   
    
    /**
    * get BuyerCategory if no exist creat it
    */
    private function getBuyerCategory() {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $date = new \DateTime('now');
        $buyerCategory = $this->entityManager->getRepository('DppBuyersBundle:BuyerCategory')->findOneBy(array('buyer'=> $this->buyer, 'category' => $this->category ));
        if ($buyerCategory == null) {
            $buyerCategory = BuyerCategory::getWithDefault($this->buyer, $this->category); 
            $this->entityManager->persist($buyerCategory);
            $this->logAction();
        } else {
            $ts = $date->getTimestamp() - $buyerCategory->getLastAccess()->getTimestamp();     
            $ts = $ts / 3600; // en heures
            if ($ts > $this->customer->getVisitTimeInterval() ) {
                $buyerCategory->setTotalAccess($buyerCategory->getTotalAccess()+1);
                $buyerCategory->setLastAccess($date); 
                $this->logAction();                
            }
        }        
        $this->entityManager->flush();
        return $buyerCategory;
    }   
    /**
    * get BuyerProduct if no exist creat it
    */
    private function getBuyerProduct() {
        if ($this->entityManager == null) { 
            $this->entityManager = $this->getDoctrine()->getManager();
        }
        $date = new \DateTime('now');
        $buyerProduct = $this->entityManager->getRepository('DppBuyersBundle:BuyerProduct')->findOneBy(array('buyer'=> $this->buyer, 'product' => $this->product ));
        if ($buyerProduct == null) {
            $buyerProduct = BuyerProduct::getWithDefault($this->buyer, $this->product); 
            $this->entityManager->persist($buyerProduct);
            $this->logAction();
        } else {
            $ts = $date->getTimestamp() - $buyerProduct->getLastAccess()->getTimestamp();     
            $ts = $ts / 3600; // en heures
            if ($ts > $this->customer->getVisitTimeInterval() ) {
                $buyerProduct->setTotalAccess($bp->getTotalAccess()+1);
                $buyerProduct->setLastAccess($date);
                $this->logAction();
            }
        }
        $this->entityManager->flush();
        return $buyerProduct;
    }   
       
    public function getPromoMessage($tabPromo, $pricingType, $visites) {
        $this->message = null;
        $codePromo = null;
        foreach(array_reverse($tabPromo) as $ligneCode) {
            if ($pricingType == 1) {
                if ($ligneCode[0] == $visites) {
                    $codePromo = $ligneCode[1];
                    $this->message = $ligneCode[2];
                    break;
                }
            } else {
                if ($ligneCode[0] <= $visites) {
                    $codePromo = $ligneCode[1];
                    $this->message = $ligneCode[2];
                    break;
                }
            }
        }
        if (!$codePromo == null) {
            if ($this->message == null) {
                $this->message = $this->customer->getDefaultMsg();
            }                
            $pos = strpos($this->message,'[$visite$]');
            if (!$pos===FALSE) {
                $repstr = strval($visites);
                if ($visites > 1){
                    $repstr = $repstr+'éme ';
                }else { 
                    $repstr = $repstr+'ére ';
                }
                $this->message = str_replace('[$visite$]',$repstr,$this->message);
            }
            $this->message = str_replace('[$code$]',$codePromo,$this->message);
            $this->message = '<resp><msg>'.$this->message.'</msg></resp>';                     
        }
        return $this->message;
    }
    
    
    
    
    
    
    
}
