<?php

namespace Dpp\CustomersBundle\Form\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Dpp\CustomersBundle\Controller\AllTypeController;
use Dpp\CustomersBundle\Entity\CategoryRepository;

class CategoryType extends AbstractType
{
     public function __construct($id, $customer) 
    {
        $this->id = $id;
        $this->customer = $customer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = $this->id;
        $customer = $this->customer;
        $pricing = AllTypeController::getProductPricing();
        $state = AllTypeController::getProductState();
        $builder
            ->add('customerName','text',array('label' => 'Dpp.customer.name', 'read_only' => true))
            ->add('name','text',array('label' => 'Dpp.global.name'))
            ->add('urlRef','text',array('label' => 'Dpp.global.urlRef'))
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
            ->add('parent','entity',array('required' => false,
                                            'label' => 'Dpp.category.parent',
                                            'class'   => 'DppCustomersBundle:Category',
                                            'property'=> 'name',                                           
                                            'multiple'=> false))
                                                                                       
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dpp\CustomersBundle\Entity\Category'
        ));
    }
    public function getName()
    {
        return 'dpp_customersbundle_category_categorytype';
    }

}
