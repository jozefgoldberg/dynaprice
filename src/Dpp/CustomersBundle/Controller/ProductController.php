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
	  $entityManager = $this->getDoctrine()->getManager();
      $products = $entityManager->getRepository('DppCustomersBundle:Product')->findAll();
      return $this->render('DppCustomersBundle:Products:productsListAll.html.twig',array('products' => $products));
    }
    
    public function listAction(Customer $customer)
    {
	  $entityManager = $this->getDoctrine()->getManager();
      $product = $entityManager->getRepository('DppCustomersBundle:Product')->findAll();
      return $this->render('DppCustomersBundle:Customer:customerList.html.twig',array('customers' => $customers));
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