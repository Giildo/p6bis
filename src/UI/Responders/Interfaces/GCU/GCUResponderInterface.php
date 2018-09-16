<?php

namespace App\UI\Responders\Interfaces\GCU;

use Symfony\Component\HttpFoundation\Response;

interface GCUResponderInterface
{
    /**
     * @return Response
     */
    public function response(): Response;
}
