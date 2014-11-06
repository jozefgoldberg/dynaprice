<?php

namespace Dpp\UsersBundle\Controller;

abstract class  RoleTypeController 
{
    protected static   $roles = array('ROLE_DPP_CUSTOMER' => 'ROLE_DPP_CUSTOMER',
                       'ROLE_ADMIN' => 'ROLE_ADMIN',
                       'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN');
    
    public static function getRoles() {
        return self::$roles;
    }
}