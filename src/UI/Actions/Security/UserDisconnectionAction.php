<?php

namespace App\UI\Actions\Security;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route(name="Authentication_")
 * Class UserDisconnectionAction
 * @package App\UI\Actions\Security
 */
class UserDisconnectionAction
{
    /**
     * @Route(path="/deconnexion", name="user_disconnection")
     * @throws Exception
     */
    public function logout()
    {
        throw new Exception("This exception shouldn't be reached !");
    }
}