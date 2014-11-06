<?php

// src\Dpp\CustomersBundle\Controller\CustomerController.php

namespace Dpp\CustomersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\CustomersBundle\Form\Customer\CustomerType;



class CustomerController extends Controller
{
    public function listAction()
    {
	  $entityManager = $this->getDoctrine()->getManager();
      $customers = $entityManager->getRepository('DppCustomersBundle:Customer')->findAll();
      return $this->render('DppCustomersBundle:Customers:customersList.html.twig',array('customers' => $customers));
    }
    public function createAction()
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
                return $this->redirect( $this->generateUrl('dpp_customers_list') );
            } 
        }
        return $this->render('DppCustomersBundle:Customers:customeradd.html.twig', array('form' => $form->createView()));
    }
}