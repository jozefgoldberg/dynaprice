<?php

namespace Dpp\CustomersBundle\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Dpp\CustomersBundle\Controller\AllTypeController;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pricing = AllTypeController::getProductPricing();
        $state = AllTypeController::getProductState();
        $builder
            ->add('customerName','text',array('label' => 'Dpp.customer.name', 'read_only' => true))
            ->add('name','text',array('label' => 'Dpp.product.name'))
            ->add('urlRef','text',array('label' => 'Dpp.product.urlRef'))
            ->add('pricingType','choice',array('label' => 'Dpp.customer.pricingType',
                                                'required' => true,
                                                'choices' => $pricing,
                                                'expanded' => false,
                                                'multiple' => false))
            ->add('state','choice',array('label' => 'Dpp.product.state',
                                                'required' => true,
                                                'choices' => $state,
                                                'expanded' => false,
                                                'multiple' => false))                                    
            ->add('promoCodes','hidden',array('required' => false))
            
                                                                                        
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dpp\CustomersBundle\Entity\Product'
        ));
    }
    public function getName()
    {
        return 'dpp_customersbundle_product_producttype';
    }

}
