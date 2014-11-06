<?php
namespace Dpp\UsersBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // Ajoutez vos champs ici, revoilà nos champs 
        $builder->remove('username');
        $builder->add('lastName');
        $builder->add('firstName');
    }

    public function getName()
    {
        return 'Dpp_user_registration';
    }
}