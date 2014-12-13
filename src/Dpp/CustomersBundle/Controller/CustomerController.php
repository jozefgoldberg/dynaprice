<?php

// src\Dpp\CustomersBundle\Controller\CustomerController.php

namespace Dpp\CustomersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\CustomersBundle\Form\Customer\CustomerType;
use Dpp\CustomersBundle\Form\Customer\CustomerNewType;



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
		$customer =  Customer::getWithDefault();  // Création de l'entité
        $form = $this->createForm(new CustomerNewType, $customer);    
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { // aprés la validation user
            $form->bind($request); // on remplis le $form
            if ($form->isValid()) {     
                $em = $this->getDoctrine()->getManager();
                $em->persist($customer);
                $em->flush();
                return $this->redirect( $this->generateUrl('dpp_customers_update', array('customerRef' => $customer->getDomaine())));
            } 
        }
        $returnUrl = $this->generateUrl('dpp_customers_list');
        return $this->render('DppCustomersBundle:Customers:customerCreate.html.twig', array('form' => $form->createView(),
                                                                                    'returnUrl' => $returnUrl,
                                                                                    ));
    }
    
    public function updateAction($customerRef)
    {
		if ($customerRef == null) {
            return $this->createAction();
        }
        $entityManager = $this->getDoctrine()->getManager();
        $customer = $entityManager->getRepository('DppCustomersBundle:Customer')->findOneBy(array('domaine' => $customerRef));
        if ($customer == null) {
            return $this->createAction();
        }
        $tabPromoCodes = json_decode($customer->getPromoCodes());
        if (!$tabPromoCodes == null) {
            $customer->setPromoCodes( substr($customer->getPromoCodes(),1,strlen($customer->getPromoCodes())-2));
        }
        $form = $this->createForm(new CustomerType, $customer);        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { // aprés la validation user
            $form->bind($request); // on remplis le $form
            if ($form->isValid()) {     
                var_dump($customer->getPromoCodes());
                if (!$customer->getPromoCodes() == null) {
                    $tabPromoCodes = json_decode($customer->getPromoCodes());
                    var_dump($tabPromoCodes);
                    usort($tabPromoCodes, array($this, 'UcompCode'));
                    $customer->setPromoCodes(json_encode($tabPromoCodes));
                }
                $entityManager->persist($customer);
                $entityManager->flush();
                return $this->redirect( $this->generateUrl('dpp_customers_list') );
            } 
        }
        $returnUrl = $this->generateUrl('dpp_customers_list');
        $listCategorysUrl = $this->generateUrl('dpp_categorys_customer', array('customerRef' => $customer->getDomaine()));
        $listProductsUrl = $this->generateUrl('dpp_products_customer', array('customerRef' => $customer->getDomaine()));
        return $this->render('DppCustomersBundle:Customers:customerUpdate.html.twig', array('form' => $form->createView(),
                                                                                    'returnUrl' => $returnUrl,
                                                                                    'listProductsUrl' => $listProductsUrl,
                                                                                    'listCategorysUrl' => $listCategorysUrl,
                                                                                    'tabPromoCodes' => $tabPromoCodes));
    }
    
    public function deleteAction($customerRef) {
        $entityManager = $this->getDoctrine()->getManager();
        $customer = $entityManager->getRepository('DppCustomersBundle:Customer')->findOneBy(array('domaine' => $customerRef));
        if (!$customer == null) {
            $entityManager->remove($customer);
            $entityManager->flush();
        }
        return $this->redirect( $this->generateUrl('dpp_customers_list') );
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