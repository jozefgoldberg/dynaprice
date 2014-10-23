<?php

namespace Dpp\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DppDemoBundle:Default:index.html.twig');
    }
    
    public function viewAction($reference)
    {
        return $this->render('DppDemoBundle:Default:productView.html.twig',array('reference' => $reference));
    }
}
