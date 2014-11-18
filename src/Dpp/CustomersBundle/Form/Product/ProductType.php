<?php

namespace Dpp\CustomersBundle\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customerName','text',array('label' => 'Dpp.customer.name', 'read_only' => true))
            ->add('name','text',array('label' => 'Dpp.product.name'))
            ->add('urlRef','text',array('label' => 'Dpp.product.urlRef'))
            ->add('pricingType','text',array('label' => 'Dpp.product.pricingType','required' => false))
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
