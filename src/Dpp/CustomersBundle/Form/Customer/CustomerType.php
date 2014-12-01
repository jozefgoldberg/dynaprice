<?php

namespace Dpp\CustomersBundle\Form\Customer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Dpp\CustomersBundle\Controller\AllTypeController;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pricing = AllTypeController::getCustomerPricing();
        $import = AllTypeController::getCustomerImport();
        $builder
            ->add('name','text',array('label' => 'Dpp.customer.name'))
            ->add('domaine','text',array('label' => 'Dpp.customer.domaine'))
            ->add('defaultMsg','text',array('label' => 'Dpp.customer.defaultMsg','required' => true))
            ->add('autoAcquisition','checkbox',array('label' => 'Dpp.customer.autoAcquisition','required' => false))
            ->add('globalPromo','checkbox',array('label' => 'Dpp.customer.globalPromo','required' => false))
            ->add('categoryPromo','checkbox',array('label' => 'Dpp.customer.categoryPromo','required' => false))
            ->add('pricingType','choice',array('label' => 'Dpp.customer.pricingType',
                                                'required' => true,
                                                'choices' => $pricing,
                                                'expanded' => false,
                                                'multiple' => false))
            ->add('visitTimeInterval','integer',array('label' => 'Dpp.customer.visitTimeInterval','required' => true))
            ->add('promoCodes','hidden',array('required' => false,'read_only' => false))
            ->add('importType','choice',array('label' => 'Dpp.customer.importType',
                                              'required' => true,
                                              'choices' => $import,
                                              'expanded' => false,
                                              'multiple' => false))
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
