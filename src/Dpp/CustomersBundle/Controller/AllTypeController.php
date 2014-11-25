<?php

namespace Dpp\CustomersBundle\Controller;

abstract class  AllTypeController 
{
    protected static   $customerPricing = array(0 => 'Dpp.customer.pricing.interval',
                                        1 => 'Dpp.customer.pricing.discret');
                                        
    protected static   $customerImport = array(0 => 'Dpp.customer.import.novalue',
                                       1 => 'Dpp.customer.import.manuel',
                                       2 => 'Dpp.customer.import.import',
                                       3 => 'Dpp.customer.import.auto');
                                       
    protected static   $productPricing = array(0 => 'Dpp.product.pricing.novalue',
                                        1 => 'Dpp.product.pricing.discret',
                                        2 => 'Dpp.product.pricing.interval');
                                        
    protected static   $productState = array(0 => 'Dpp.product.state_noactive',
                                        1 => 'Dpp.product.state_acquis',
                                        2 => 'Dpp.product.state_normal');
                                        
    
    public static function getCustomerPricing() {
        return self::$customerPricing;
    }
    
    public static function getCustomerImport() {
        return self::$customerImport;
    }
    
    public static function getProductPricing() {
        return self::$productPricing;
    }
    
    public static function getProductState() {
        return self::$productState;
    }
}