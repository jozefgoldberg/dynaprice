<?php

namespace Dpp\AjaxServeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DppAjaxServeurBundle:Default:index.html.twig', array('name' => $name));
    }
}
