<?php

namespace Dpp\BuyersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DppBuyersBundle:Default:index.html.twig', array('name' => $name));
    }
}
