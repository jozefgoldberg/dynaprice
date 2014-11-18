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
            ->add('domaine','text',array('label' => 'Dpp.customer.domaine'))
            ->add('princingType','text',array('label' => 'Dpp.customer.pricingType','required' => false))
            ->add('visitTimeInterval','integer',array('label' => 'Dpp.customer.visitTimeInterval'))
            ->add('promoCodes','hidden',array('required' => false,'read_only' => false))
            ->add('importType','choice',array('label' => 'Dpp.customer.importType','choices' => array (
                                                                                                    0 => 'Pas de saisie',
                                                                                                    1 => 'Saisie manuelle',
                                                                                                    2 => 'Import manuel',
                                                                                                    3 =>  'Import automatique')
                                                                                                ))
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
        return 'dpp_customersbundle_customers_customertype';
    }

}
