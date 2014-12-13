<?php

// src\Dpp\CustomersBundle\Controller\CategoryController.php

namespace Dpp\CustomersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Dpp\CustomersBundle\Entity\Customer;
use Dpp\CustomersBundle\Entity\Category;
use Dpp\CustomersBundle\Form\Category\CategoryType;



class CategoryController extends Controller
{    

    public function listByUserCustomerAction() {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect( $this->generateUrl('dpp_customers_list') );
        }
        $user = $this->container->get('security.context')->getToken()->getUser();
        $customer = $user->getCustomer();
        if (!$customer == null) {
            return $this->listByCustomer($customer);
        }
        throw new AccessDeniedException("Vous n'étez pas autorisé.");       
    }


    public function listByCustomerAction($customerRef)
    {   
        $entityManager = $this->getDoctrine()->getManager();
        $customer = $entityManager->getRepository('DppCustomersBundle:Customer')->findOneBy(array('domaine' => $customerRef));
        if (!$customer == null) {
            $categorys = $entityManager->getRepository('DppCustomersBundle:Category')->getAllHierarchyCustomer($customer);
            $returnUrl = $this->generateUrl('dpp_customers_update', array('customerRef' => $customer->getDomaine() ));
            return $this->render('DppCustomersBundle:Categorys:categorysCustomerList.html.twig',array('categorys' => $categorys, 
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
            $categorys = $entityManager->getRepository('DppCustomersBundle:Category')->findby(array('customer' => $customer));
            return $this->render('DppCustomersBundle:Categorys:categorysCustomerList.html.twig',array('categorys' => $categorys,
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
        $category = new Category(); // Création de l'entité
        $category->setCustomer($customer);
        $form = $this->createForm(new CategoryType($category->getId(), $customer), $category);        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { // aprés la validation user
            $form->bind($request); // on remplis le $form
            if ($form->isValid()) {                       
                $entityManager->persist($category);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('info', 'Category enregistré');
                return $this->redirect( $this->generateUrl('dpp_category_add', array('customerRef'=>$customerRef)));
            } 
        }
        $returnUrl = $this->generateUrl('dpp_categorys_customer', array('customerRef' => $customer->getDomaine() ));
        return $this->render('DppCustomersBundle:Categorys:categoryCreate.html.twig', array('form' => $form->createView(),
                                                                                        'returnUrl' => $returnUrl,
                                                                                        'tabPromoCodes' => null));
    }
    
    public function updateAction($id)
    {
		$entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository('DppCustomersBundle:Category')->findOneBy(array('id' => $id));
        if ($category == null) {
            return listAllAction();
        }
        $tabPromoCodes = json_decode($category->getPromoCodes());
        if (!$tabPromoCodes == null) {
            $category->setPromoCodes( substr($category->getPromoCodes(),1,strlen($category->getPromoCodes())-2));
        }
        $form = $this->createForm(new CategoryType($category->getId(), $category->getCustomer()), $category);        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { // aprés la validation user
            $form->bind($request); // on remplis le $form
            if ($form->isValid()) {   
                if (!$category->getPromoCodes() == null) {
                    $tabPromoCodes = json_decode($category->getPromoCodes());
                    usort($tabPromoCodes, array($this, 'UcompCode'));
                    $category->setPromoCodes(json_encode($tabPromoCodes));
                }                  
                $entityManager->persist($category);
                $entityManager->flush();
                return $this->listByCustomer($category->getCustomer());
            } 
        }
        $returnUrl = $this->generateUrl('dpp_categorys_customer', array('customerRef' => $category->getCustomer()->getDomaine() ));
        return $this->render('DppCustomersBundle:Categorys:categoryUpdate.html.twig', array('form' => $form->createView(),
                                                                                        'returnUrl' => $returnUrl,
                                                                                        'tabPromoCodes' => $tabPromoCodes));
    }
    
    public function deleteAction($id)
    {
		$entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository('DppCustomersBundle:Category')->findOneBy(array('id' => $id));
        if ($category == null) {
            return $this->redirect( $this->generateUrl('dpp_customers_list') );
        }
        $customer = $category->getCustomer();       
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->listByCustomer($customer);
    }
    /* 
    * sort promo table 
    */
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