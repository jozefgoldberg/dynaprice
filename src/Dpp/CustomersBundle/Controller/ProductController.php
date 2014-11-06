<?php

// src\Dpp\CustomersBundle\Controller\ProductController.php

namespace Dpp\CustomersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\CustomersBundle\Entity\Product;
use Dpp\CustomersBundle\Form\Product\CustomerType;



class ProductController extends Controller
{
    public function listAllAction()
    {
        $security = $this->get('security.context');
        if (!$security->isGranted("ROLE_ADMIN")) {
            $user = $security->getToken()->getUser();
            $customerRef = $user->getCustomer()->getDomaine();
            return $this->listByCustomerAction($customerRef);
        }
        
        
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository('DppCustomersBundle:Product')->findAll();
        return $this->render('DppCustomersBundle:Products:productsListAll.html.twig',array('products' => $products));
    }
    
    public function listByCustomerAction($customerRef)
    {   
        $entityManager = $this->getDoctrine()->getManager();
        $customersList = $entityManager->getRepository('DppCustomersBundle:Customer')->findBy(array('domaine' => $customerRef));
        if (!$customersList == null) {
            $customer = $customersList[0];
            $products = $entityManager->getRepository('DppCustomersBundle:Product')->findby(array('customer' => $customer));
            return $this->render('DppCustomersBundle:Products:productsCustomerList.html.twig',array('products' => $products, 'customer'=> $customer));
        }
        return $this->render('DppCustomersBundle:Products:productsListAll.html.twig',array('products' => null));
    
    }
    
    
    
    public function createAction(Customer $customer)
    {
		$customer = new Customer(); // Création de l'entité
        $form = $this->createForm(new CustomerType, $customer);        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { // après la validation user
            $form->bind($request); // on remplis le $form
            if ($form->isValid()) {     
                $em = $this->getDoctrine()->getManager();
                $em->persist($customer);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', 'Client enregistré');
                return $this->redirect( $this->generateUrl('dpp_customers_list') );
            } 
        }
        return $this->render('DppCustomersBundle:Customer:customeradd.html.twig', array('form' => $form->createView()));
    }
}