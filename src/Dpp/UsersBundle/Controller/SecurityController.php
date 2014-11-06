<?php

// src\Dpp\UsersBundle\Controller\SecurityController.php;

namespace Dpp\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction()
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        $sContext = $this->get('security.context');
        if ($sContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if ($sContext->isGranted('ROLE_ADMIN')) {
                return $this->redirect($this->generateUrl('dpp_customers_homepage'));
            } else {
                $user = $sContext->getToken()->getUser();               
                return $this->redirect($this->generateUrl('dpp_customers_customer_products',array('customRef'=>$user->getCustomer()->getDomaine())));
            }
        }
        $request = $this->getRequest();
        $session = $request->getSession();
 
        // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('DppUsersBundle:Security:login.html.twig', array(
                                                // Valeur du précédent nom d'utilisateur entré par l'internaute
                                                                   'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                                                                   'error'         => $error
                                                                   )
                            );
  }
}