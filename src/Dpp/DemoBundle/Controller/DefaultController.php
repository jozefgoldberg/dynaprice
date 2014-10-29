<?php

namespace Dpp\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dpp\CustomersBundle\Entity\Product;
use Dpp\CustomersBundle\Entity\ProductRepository;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository('DppCustomersBundle:Product')->findAll();
        return $this->render('DppDemoBundle:Default:productsFOListAll.html.twig',array('products' => $products));
    }
    
    public function viewAction($reference)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository('DppCustomersBundle:Product')->findBy(array('urlRef' => $reference));
        if (!$products == null) {
            return $this->render('DppDemoBundle:Default:productFOView.html.twig',array('product' => $products[0]));
    } else
        $this->indexAction();
    }
    
}
