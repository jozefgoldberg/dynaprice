<?php

namespace Dpp\UsersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Dpp\UsersBundle\Controller\RoleTypeController;

class UserUpdateType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = RoleTypeController::getRoles();
        $builder
            ->add('firstName','text',array('label' => 'Dpp.user.first_name'))
            ->add('lastName','text',array('label' => 'Dpp.user.last_name'))
            ->add('email','text',array('label' => 'Dpp.user.email'))
            ->add('plainPassword','repeated',array('type' => 'password',
                                                   'invalid_message' => 'Dpp.user.password_not_equ',
                                                   'required' => false,
                                                   'first_options' => array('label' => 'Dpp.user.password','required' => false),
                                                   'second_options' => array('label' =>'Dpp.user.password_repeat','required' => false),
                                                    ))
            ->add('roles','choice',array('choices' => $roles,
                                         'expanded' => false,
                                          'multiple' => true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dpp\UsersBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'dpp_usersbundle_usertype';
    }
}
