<?php

// src\Dpp\CustomersBundle\Controller\ProductController.php

namespace Dpp\CustomersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\CustomersBundle\Entity\Product;
use Dpp\CustomersBundle\Form\Product\ProductType;



class ProductController extends Controller
{    
    public function listByCustomerAction($customerRef)
    {   
        $entityManager = $this->getDoctrine()->getManager();
        $customer = $entityManager->getRepository('DppCustomersBundle:Customer')->findOneBy(array('domaine' => $customerRef));
        if (!$customer == null) {
            $products = $entityManager->getRepository('DppCustomersBundle:Product')->findby(array('customer' => $customer));
            $returnUrl = $this->generateUrl('dpp_customers_update', array('customerRef' => $customer->getDomaine() ));
            return $this->render('DppCustomersBundle:Products:productsCustomerList.html.twig',array('products' => $products, 
                                                                                                    'customer'=> $customer,
                                                                                                    'returnUrl'=>$returnUrl));
        }
        return $this->redirect( $this->generateUrl('dpp_customers_list') );
    
    }
    
    private function listByCustomer(Customer $customer)
    {   
        $entityManager = $this->getDoctrine()->getManager();
        if (!$customer == null) {
            $returnUrl = $this->generateUrl('dpp_customers_update', array('customerRef' => $customer->getDomaine() ));
            $products = $entityManager->getRepository('DppCustomersBundle:Product')->findby(array('customer' => $customer));
            return $this->render('DppCustomersBundle:Products:productsCustomerList.html.twig',array('products' => $products,
                                                                                                    'customer'=> $customer,
                                                                                                    'returnUrl'=>$returnUrl));
        }
        return $this->redirect( $this->generateUrl('dpp_customers_list') );
    
    }
    
    
    
    public function createAction($customerRef)
    {
		$entityManager = $this->getDoctrine()->getManager();
        $customer = $entityManager->getRepository('DppCustomersBundle:Customer')->findOneBy(array('domaine' => $customerRef));
        if ($customer == null) {
            return listAllAction();
        }
        $product = new Product(); // Cr�ation de l'entit�
        $product->setCustomer($customer);
        $form = $this->createForm(new ProductType, $product);        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { // apr�s la validation user
            $form->bind($request); // on remplis le $form
            if ($form->isValid()) {                       
                $entityManager->persist($product);
                $entityManager->flush();
                return $this->redirect( $this->generateUrl('dpp_product_edit', array('id'=>$product->getId())));
            } 
        }
        $returnUrl = $this->generateUrl('dpp_products_customer', array('customerRef' => $customer->getDomaine() ));
        return $this->render('DppCustomersBundle:Products:productCreate.html.twig', array('form' => $form->createView(),
                                                                                        'returnUrl' => $returnUrl));
    }
    
    public function updateAction($id)
    {
		$entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository('DppCustomersBundle:Product')->findOneBy(array('id' => $id));
        if ($product == null) {
            return listAllAction();
        }
        $tabPromoCodes = json_decode($product->getPromoCodes());
        if (!$tabPromoCodes == null) {
            $product->setPromoCodes( substr($product->getPromoCodes(),1,strlen($product->getPromoCodes())-2));
        }
        $form = $this->createForm(new ProductType, $product);        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { // apr�s la validation user
            $form->bind($request); // on remplis le $form
            if ($form->isValid()) {   
                if (!$product->getPromoCodes() == null) {
                    $tabPromoCodes = json_decode($product->getPromoCodes());
                    usort($tabPromoCodes, array($this, 'UcompCode'));
                    $product->setPromoCodes(json_encode($tabPromoCodes));
                }                  
                $entityManager->persist($product);
                $entityManager->flush();
                return $this->listByCustomer($customer);
            } 
        }
        $returnUrl = $this->generateUrl('dpp_products_customer', array('customerRef' => $product->getCustomer()->getDomaine() ));
        return $this->render('DppCustomersBundle:Products:productUpdate.html.twig', array('form' => $form->createView(),
                                                                                        'returnUrl' => $returnUrl,
                                                                                        'tabPromoCodes' => $tabPromoCodes));
    }
    
    public function deleteAction($id)
    {
		$entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository('DppCustomersBundle:Product')->findOneBy(array('id' => $id));
        if ($product == null) {
            return $this->redirect( $this->generateUrl('dpp_customers_list') );
        }
        $customer = $product->getCustomer();       
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->listByCustomer($customer);
    }
    
    private function UcompCode(Array $l1, Array $l2) {
        if ($l1[0] < $l2[0]) {
            return -1;
        } elseif ($l1[0] == $l2[0]) {
            return 0;
        } else {
            return 1;
        }
    }
}