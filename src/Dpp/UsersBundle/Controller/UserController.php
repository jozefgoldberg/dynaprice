<?php

// src\Dpp\UsersBundle\Controller

namespace Dpp\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Dpp\UsersBundle\Entity\User;
use Dpp\UsersBundle\Form\Type\UserType;
use Dpp\UsersBundle\Form\Type\UserUpdateType;

class UserController extends Controller
{
    /**
     * User manager
     *
     */
    private $userManager;

    /**
    *
    */
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository('DppUsersBundle:User')->findAllByDescription();
        return $this->render('DppUsersBundle:User:userList.html.twig',array('users' => $users));
    }
    /**
    *
    */
	public function voirAction($id)
    {

	}
    
     //   /**
     //   * @Secure(roles="ROLE_ADMIN")
      //  */
	public function createAction()
	{
        $userManager = $this->get('fos_user.user_manager');        
		$user = $userManager->createUser(); //Création de l'entité
        $form = $this->createForm(new UserType, $user);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request); // on remplis le $form           
                if ($form->isValid()) {   // verification de la validité
                    $user->setEnabled(true);
                    //$user->addRole('ROLE_USER');
                    $userManager->UpdateUser($user);
                    //$this->get('session')->getFlashBag()->add('info', 'User bien enregistré');
                    return $this->redirect( $this->generateUrl('Dpp_user_accueil'));
                }
            }
        $returnUrl = $this->generateUrl('Dpp_user_accueil');
        return $this->render('DppUsersBundle:User:useradd.html.twig', array('form' => $form->createView(), 'returnUrl' => $returnUrl));
	}
    
	public function updateAction($email)
    {
        $userManager = $this->get('fos_user.user_manager');        
		$user = $userManager->findUserByEmail($email);
        $form = $this->createForm(new UserUpdateType, $user);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { //après button valid
            $form->bind($request);
            if ($form->isValid()) {
                $userManager->UpdateUser($user);
                return $this->redirect( $this->generateUrl('Dpp_user_accueil'));
            }
		}
        $returnUrl = $this->generateUrl('Dpp_user_accueil');
		return $this->render('DppUsersBundle:User:userupdate.html.twig', array('form' => $form->createView(),
                                                                               'returnUrl' => $returnUrl));
	}
    
    public function deleteAction($email)
    {
        $userManager = $this->get('fos_user.user_manager');        
		$user = $userManager->findUserByEmail($email);
        if ($this->getUser() == $user) {
            $this->get('session')->getFlashBag()->add('info', 'Dpp.message.connot_delete_imself');
            return $this->redirect($this->generateUrl('Dpp_user_accueil')); 
         }
        $userManager->deleteUser($user);
        return $this->redirect( $this->generateUrl('Dpp_user_accueil')); 
	}
 
}