<?php
// src\Dpp\BuyersBundle\Controller\BuyersController.php

namespace Dpp\BuyersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Validator\Constraints\DateTime;
use Dpp\BuyersBundle\Entity\Buyer;



class BuyersController extends Controller
{
    
    /**
    * Create Buyer from Ajax
    */
    public function createAction($id)
    {       
            $request = $this->get('request');
            // var_dump($request);
            $date = new \DateTime('now');
            $entityManager = $this->getDoctrine()->getManager();
            $buyersList = $entityManager->getRepository('DppBuyersBundle:Buyer')->findBy(array('uuid' => $id));
            if ($buyersList == null) {
                $buyer = new Buyer(); 
                $buyer->setUuid($id);
                $buyer->setFirstAccess($date);
                $entityManager->persist($buyer);
            } else {
                $buyer = $buyersList[0];
                $buyer->setLastAccess($date);
            }
            $entityManager->flush();
            $response = new Response("",200,array('content-type' => 'text/plain'));
            return $response->send();
        //}
        //return $this->render('DppDemoBundle:Default:index.html.twig');
    }

    
}