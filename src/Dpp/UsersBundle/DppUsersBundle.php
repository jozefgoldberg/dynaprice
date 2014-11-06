<?php

namespace Dpp\UsersBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DppUsersBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
