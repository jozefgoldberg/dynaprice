<?php

namespace Dpp\CustomersBundle\Form\Customer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array('label' => 'Dpp.customer.name'))
            ->add('domaine','text',array('label' => 'Dpp.domaine'))
            ->add('princingType','text',array('label' => 'Dpp.pricingType','required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dpp\CustomersBundle\Entity\Customer'
        ));
    }
    public function getName()
    {
        return 'dpp_customersbundle_customer_customertype';
    }

}
